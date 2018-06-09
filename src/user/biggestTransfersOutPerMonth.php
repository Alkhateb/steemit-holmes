<?php

/**
 * Shows out transfers for every month, for the top 3 accounts
 */

$years = array_combine(range(date("Y"), 2016), range(date("Y"), 2016));
$years = array_reverse($years);

$currentYear  = (int)date('Y');
$currentMonth = (int)date('m');

$query = '
    SELECT *
    FROM s2db.sbds_tx_transfers
    WHERE
        `from` = :username AND
        `timestamp` >= :from AND
        `timestamp` < :to
    ;
';

$data = [];

$getSummary = function ($transfers) {
    $data = [];

    foreach ($transfers as $transfer) {
        $to     = $transfer['to'];
        $symbol = $transfer['amount_symbol'];

        if (!isset($data[$to])) {
            $data[$to.'###SBD']   = 0;
            $data[$to.'###STEEM'] = 0;
        }

        $data[$to.'###'.$symbol] = $data[$to.'###'.$symbol] + floatval($transfer['amount']);
    }

    asort($data);

    return array_slice($data, -1);
};

foreach ($years as $year) {
    for ($i = 1; $i <= 12; $i++) {
        if ($currentYear === $year && $i > $currentMonth) {
            continue;
        }

        $dateStart = date('Y-m-d', mktime(0, 0, 0, $i, 1, $year)).' 00:00:00';
        $dateTo    = date("Y-m-t", strtotime($dateStart)).' 23:59:59';

        $cache = md5('cache-transfer-out-'.$year.'-'.$i.'-'.$user);

        /* @var $Item \Stash\Interfaces\ItemInterface */
        $Item = $Cache->getItem($cache);

        if (!$Item->isMiss()) {
            $data[$year][$i] = $Item->get();
            continue;
        }


        $Statement = $Database->prepare($query);
        $Statement->bindParam(':username', $user);
        $Statement->bindParam(':from', $dateStart);
        $Statement->bindParam(':to', $dateTo);
        $Statement->execute();

        $result = $Statement->fetchAll(PDO::FETCH_ASSOC);

        $data[$year][$i] = $getSummary($result);
    }
}


$labels  = [];
$dataSet = [];
$results = [];

foreach ($data as $year => $entries) {
    foreach ($entries as $month => $values) {
        if (empty($values)) {
            continue;
        }

        foreach ($values as $userData => $amount) {
            $labels[]  = $year.'-'.$month.' @'.str_replace('###', ': ', $userData).' '.$amount;
            $dataSet[] = $amount;

            $results[] = [
                'username' => ' @'.trim(explode('###', $userData)[0]),
                'currency' => trim(explode('###', $userData)[1]),
                'amount'   => $amount,
                'year'     => $year,
                'month'    => $month
            ];
        }
    }
}

?>

<div class="card">
    <div class="card-header">
        Biggest transfers summary per month
    </div>
    <canvas id="biggestTransfersOutPerMonth" height="360" style="max-height: 360px; margin-top: 25px;"></canvas>

    <table class="table card-table table-striped table-vcenter">
        <thead>
        <tr>
            <th>Time</th>
            <th>Account</th>
            <th>Amount</th>
            <th>Currency</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($results as $data) { ?>
            <tr>
                <td><?php echo $data['year'].'-'.sprintf('%02d', (int)$data['month']); ?></td>
                <td><?php echo $data['username']; ?></td>
                <td><?php echo number_format($data['amount']); ?></td>
                <td><?php echo $data['currency']; ?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>

<script>
    new window.Chart(document.getElementById('biggestTransfersOutPerMonth'), {
        type   : 'horizontalBar',
        data   : {
            labels  : <?php echo json_encode($labels); ?>,
            datasets: [{
                backgroundColor: 'rgb(255, 99, 132)',
                data           : <?php echo json_encode($dataSet); ?>
            }]
        },
        options: {
            events      : false,
            showTooltips: false,
            legend      : {
                display: false
            },
            scales      : {
                yAxes: [{
                    display: false
                }]
            },
            animation   : {
                onComplete: function () {
                    var ctx          = this.chart.ctx;
                    ctx.font         = Chart.helpers.fontString(Chart.defaults.global.defaultFontFamily, 'normal', Chart.defaults.global.defaultFontFamily);
                    ctx.textAlign    = 'left';
                    ctx.textBaseline = 'bottom';

                    this.data.datasets.forEach(function (dataset) {
                        for (var i = 0; i < dataset.data.length; i++) {
                            var model     = dataset._meta[Object.keys(dataset._meta)[0]].data[i]._model,
                                left      = dataset._meta[Object.keys(dataset._meta)[0]].data[i]._xScale.left;
                            ctx.fillStyle = '#444'; // label color
                            var label     = model.label;
                            ctx.fillText(label, left + 15, model.y + 8);
                        }
                    });
                }
            }
        }
    });
</script>
