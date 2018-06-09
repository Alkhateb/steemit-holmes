<?php

$years = array_combine(range(date("Y"), 2016), range(date("Y"), 2016));
$years = array_reverse($years);

$currentYear  = (int)date('Y');
$currentMonth = (int)date('m');

$votes = [];
$cache = md5('cache-user-vote-average-'.$user);

$query = '
    SELECT * 
    FROM s2db.sbds_tx_votes
    WHERE 
        `voter` = :username AND
        `timestamp` >= :from AND
        `timestamp` < :to;
';

/* @var $Item \Stash\Interfaces\ItemInterface */
$Item = $Cache->getItem($cache);

if (!$Item->isMiss()) {
    $votes = $Item->get();
} else {
    foreach ($years as $year) {
        for ($i = 1; $i <= 12; $i++) {
            if ($currentYear === $year && $i > $currentMonth) {
                continue;
            }

            $dateStart = date('Y-m-d', mktime(0, 0, 0, $i, 1, $year)).' 00:00:00';
            $dateTo    = date("Y-m-t", strtotime($dateStart)).' 23:59:59';

            $Statement = $Database->prepare($query);
            $Statement->bindParam(':username', $user);
            $Statement->bindParam(':from', $dateStart);
            $Statement->bindParam(':to', $dateTo);
            $Statement->execute();

            $result = $Statement->fetchAll(PDO::FETCH_ASSOC);

            if (!$result || empty($result)) {
                continue;
            }

            foreach ($result as $vote) {
                $weight = $vote['weight'];
                $weight = (int)($weight / 100);

                if (!isset($votes[$weight])) {
                    $votes[$weight] = 0;
                }

                $votes[$weight]++;
            }
        }
    }

    $Item->set($votes);
    $Item->save();
}

$labels = [];
$data   = [];
$bg     = [];

ksort($votes);

foreach ($votes as $weight => $amount) {
    $labels[] = $weight.'%';
    $data[]   = $amount;

    if ($amount < 15) {
        $bg[] = 'rgb(153, 102, 255)'; // purple
        continue;
    }

    if ($amount < 30) {
        $bg[] = 'rgb(54, 162, 235)'; // blue
        continue;
    }

    if ($amount < 45) {
        $bg[] = 'rgb(75, 192, 192)'; // green
        continue;
    }

    if ($amount < 60) {
        $bg[] = 'rgb(255, 205, 86)'; // yellow
        continue;
    }

    if ($amount < 75) {
        $bg[] = 'rgb(255, 159, 64)'; // orange
        continue;
    }

    $bg[] = 'rgb(255, 99, 132)'; // red
}

?>
<div class="card">
    <div class="card-header">
        Vote average
    </div>

    <canvas id="user-vote-average" height="360" style="height: 360px; margin-top: 20px; margin-bottom: 20px;"></canvas>


    <script>
        new window.Chart(document.getElementById('user-vote-average'), {
            type   : 'doughnut',
            data   : {
                labels  : <?php echo json_encode($labels); ?>,
                datasets: [{
                    label          : 'Votes',
                    data           : <?php echo json_encode($data); ?>,
                    backgroundColor:<?php echo json_encode($bg); ?>
                }]
            },
            options: {
                legend: {
                    display: false
                }
            }
        });

        document.getElementById('user-vote-average').style.height = '360px';
    </script>
</div>
