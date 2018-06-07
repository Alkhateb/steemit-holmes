<?php


$years = array_combine(range(date("Y"), 2016), range(date("Y"), 2016));
$years = array_reverse($years);

$currentYear  = (int)date('Y');
$currentMonth = (int)date('m');

$regs      = [];
$creations = [];
$users     = [];

// preparing
$query = '
    SELECT COUNT(*) AS count
    FROM 
        s2db.sbds_tx_account_create_with_delegations
    WHERE 
        `timestamp` >= :from AND
        `timestamp` < :to        
';

$queryCreations = '
    SELECT COUNT(*) AS count
    FROM 
        s2db.sbds_tx_account_creates
    WHERE 
        `timestamp` >= :from AND
        `timestamp` < :to        
';


$userCount = 0;

foreach ($years as $year) {
    for ($i = 1; $i <= 12; $i++) {
        if ($currentYear === $year && $i > $currentMonth) {
            continue;
        }

        $dateStart = date('Y-m-d', mktime(0, 0, 0, $i, 1, $year)).' 00:00:00';
        $dateTo    = date("Y-m-t", strtotime($dateStart)).' 23:59:59';

        $cache       = md5('cache-registrations-wd-'.$year.'-'.$i); // with delegation
        $cacheCreate = md5('cache-registrations-c-'.$year.'-'.$i);  // without delegation

        /* @var $Item \Stash\Interfaces\ItemInterface */
        /* @var $ItemCreation \Stash\Interfaces\ItemInterface */
        $Item         = $Cache->getItem($cache);
        $ItemCreation = $Cache->getItem($cacheCreate);

        // creations
        if (!$ItemCreation->isMiss()) {
            $userCount            = $userCount + $ItemCreation->get();
            $creations[$year][$i] = $ItemCreation->get();
        } else {
            $Statement = $Database->prepare($queryCreations);
            $Statement->bindParam(':from', $dateStart);
            $Statement->bindParam(':to', $dateTo);
            $Statement->execute();

            $result = $Statement->fetchAll(PDO::FETCH_ASSOC);

            if ($result) {
                $userCount = $userCount + $result[0]['count'];

                $creations[$year][$i] = $result[0]['count'];

                $ItemCreation->set($result[0]['count']);
                $ItemCreation->save();
            }
        }


        if (!$Item->isMiss()) {
            $userCount = $userCount + $Item->get();

            $regs[$year][$i] = $Item->get();
            $users[]         = $userCount;
            continue;
        }

        $Statement = $Database->prepare($query);
        $Statement->bindParam(':from', $dateStart);
        $Statement->bindParam(':to', $dateTo);
        $Statement->execute();

        $result = $Statement->fetchAll(PDO::FETCH_ASSOC);

        if (!$result) {
            continue;
        }

        if (isset($result[0]['count'])) {
            $userCount = $userCount + $result[0]['count'];

            $regs[$year][$i] = $result[0]['count'];
            $users[]         = $userCount;

            $Item->set($result[0]['count']);
            $Item->save();
        }
    }
}

$data    = [];
$created = [];
$labels  = [];

foreach ($regs as $year => $values) {
    foreach ($values as $day => $amount) {
        $data[]   = $amount;
        $labels[] = $year.'-'.$day;
    }
}

foreach ($creations as $year => $values) {
    foreach ($values as $day => $amount) {
        $created[] = $amount;
    }
}

?>
<div class="col-lg-6">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Registration Activity</h3>
        </div>

        <canvas id="registrations" height="360"></canvas>

        <script>
            new window.Chart(document.getElementById('registrations'), {
                type   : 'line',
                data   : {
                    labels  : <?php echo json_encode($labels); ?>,
                    datasets: [{
                        label      : 'Registrations',
                        borderColor: 'rgb(153, 102, 255)',
                        data       : <?php echo json_encode($data); ?>
                    }, {
                        label      : 'Created',
                        borderColor: 'rgb(54, 162, 235)',
                        data       : <?php echo json_encode($created); ?>
                    }, {
                        label      : 'User count',
                        borderColor: 'rgb(255, 99, 132)',
                        data       : <?php echo json_encode($users); ?>
                    }]
                },
                options: {
                    scales: {
                        xAxes: [{
                            display   : true,
                            scaleLabel: {
                                display    : true,
                                labelString: 'Month'
                            }
                        }],
                        yAxes: [{
                            display   : true,
                            scaleLabel: {
                                display    : true,
                                labelString: 'Users'
                            }
                        }]
                    }
                }
            });
        </script>
    </div>
</div>
