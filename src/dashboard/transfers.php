<?php

$years = array_combine(range(date("Y"), 2016), range(date("Y"), 2016));
$years = array_reverse($years);

$currentYear  = (int)date('Y');
$currentMonth = (int)date('m');

$votes = [];

// preparing
$query = '
    SELECT COUNT(*) AS count
    FROM 
        s2db.sbds_tx_transfers 
    WHERE 
        `timestamp` >= :from AND
        `timestamp` < :to        
';

foreach ($years as $year) {
    for ($i = 1; $i <= 12; $i++) {
        if ($currentYear === $year && $i > $currentMonth) {
            continue;
        }

        $dateStart = date('Y-m-d', mktime(0, 0, 0, $i, 1, $year)).' 00:00:00';
        $dateTo    = date("Y-m-t", strtotime($dateStart)).' 23:59:59';

        $cache = md5('cache-transfers-'.$year.'-'.$i);

        /* @var $Item \Stash\Interfaces\ItemInterface */
        $Item = $Cache->getItem($cache);

        if (!$Item->isMiss()) {
            $votes[$year][$i] = $Item->get();
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
            $votes[$year][$i] = $result[0]['count'];

            $Item->set($result[0]['count']);
            $Item->save();
        }
    }
}

$data   = [];
$labels = [];

foreach ($votes as $year => $values) {
    foreach ($values as $day => $amount) {
        $data[]   = $amount;
        $labels[] = $year.'-'.$day;
    }
}

?>
<div class="col-lg-6">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Transfer Activity</h3>
        </div>

        <canvas id="transfers" height="360"></canvas>

        <script>
            new window.Chart(document.getElementById('transfers'), {
                type   : 'line',
                data   : {
                    labels  : <?php echo json_encode($labels); ?>,
                    datasets: [{
                        label          : 'Transfers',
                        backgroundColor: 'rgb(75, 192, 192)',
                        borderColor    : 'rgb(75, 192, 192)',
                        data           : <?php echo json_encode($data); ?>
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
                                labelString: 'Transfers'
                            }
                        }]
                    }
                }
            });
        </script>
    </div>
</div>
