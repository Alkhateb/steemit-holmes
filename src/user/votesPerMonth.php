<?php

$years = array_combine(range(date("Y"), 2016), range(date("Y"), 2016));
$years = array_reverse($years);

$currentYear  = (int)date('Y');
$currentMonth = (int)date('m');

$votes = [];

$query = '
    SELECT COUNT(*) as count 
    FROM s2db.sbds_tx_votes
    WHERE 
        `voter` = :username AND
        `timestamp` >= :from AND
        `timestamp` < :to;
';

foreach ($years as $year) {
    for ($i = 1; $i <= 12; $i++) {
        if ($currentYear === $year && $i > $currentMonth) {
            continue;
        }

        $dateStart = date('Y-m-d', mktime(0, 0, 0, $i, 1, $year)).' 00:00:00';
        $dateTo    = date("Y-m-t", strtotime($dateStart)).' 23:59:59';
        $cache     = md5('cache-user-votes-per-month-'.$user);

        /* @var $Item \Stash\Interfaces\ItemInterface */
        $Item = $Cache->getItem($cache);

        if (!$Item->isMiss() && false) {
            $votes[$year][$i] = $Item->get();
            continue;
        }

        $Statement = $Database->prepare($query);
        $Statement->bindParam(':username', $user);
        $Statement->bindParam(':from', $dateStart);
        $Statement->bindParam(':to', $dateTo);
        $Statement->execute();

        $result = $Statement->fetchAll(PDO::FETCH_ASSOC);

        if (!$result) {
            continue;
        }

        if (isset($result[0]['count'])) {
            $votes[$year][$i] = (int)$result[0]['count'];

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

<div class="card">
    <div class="card-header">
        Votes per month
    </div>

    <canvas id="user-votes-per-month" height="360"></canvas>

    <script>
        new window.Chart(document.getElementById('user-votes-per-month'), {
            type: 'bar',
            data: {
                labels  : <?php echo json_encode($labels); ?>,
                datasets: [{
                    label          : 'Votes',
                    backgroundColor: 'rgb(255, 205, 86)',
                    borderColor    : 'rgb(255, 205, 86)',
                    data           : <?php echo json_encode($data); ?>
                }]
            }
        });
    </script>
</div>
