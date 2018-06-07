<?php

$years = array_combine(range(date("Y"), 2016), range(date("Y"), 2016));
$years = array_reverse($years);

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


$userCreated                 = 0;
$userCreatedWidthDelegations = 0;

foreach ($years as $year) {
    for ($i = 1; $i <= 12; $i++) {
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
            $userCount   = $userCount + $ItemCreation->get();
            $userCreated = $userCreated + $ItemCreation->get();
        } else {
            $Statement = $Database->prepare($queryCreations);
            $Statement->bindParam(':from', $dateStart);
            $Statement->bindParam(':to', $dateTo);
            $Statement->execute();

            $result = $Statement->fetchAll(PDO::FETCH_ASSOC);

            if ($result) {
                $userCount   = $userCount + $result[0]['count'];
                $userCreated = $userCreated + $result[0]['count'];

                $ItemCreation->set($result[0]['count']);
                $ItemCreation->save();
            }
        }


        if (!$Item->isMiss()) {
            $userCount                   = $userCount + $Item->get();
            $userCreatedWidthDelegations = $userCreatedWidthDelegations + $Item->get();
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
            $userCount                   = $userCount + $result[0]['count'];
            $userCreatedWidthDelegations = $userCreatedWidthDelegations + $result[0]['count'];

            $Item->set($result[0]['count']);
            $Item->save();
        }
    }
}

$labels  = ['Created', 'Created with Delegation'];
$dataset = [$userCreated, $userCreatedWidthDelegations];

?>
<div class="col-lg-6">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Account creation</h3>
        </div>

        <canvas id="registrations-pie" height="300" style="margin-bottom: 30px; margin-top: 30px;"></canvas>

        <script>
            new window.Chart(document.getElementById('registrations-pie'), {
                type: 'pie',
                data: {
                    labels  : <?php echo json_encode($labels); ?>,
                    datasets: [{
                        data           : <?php echo json_encode($dataset); ?>,
                        backgroundColor: ['rgb(54, 162, 235)', 'rgb(153, 102, 255)']
                    }]
                }
            });
        </script>
    </div>
</div>
