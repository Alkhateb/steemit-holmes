<?php

$botList = require dirname(dirname(__FILE__)).'/libs/botList.php';
$botList = array_flip($botList);

$query = '
    SELECT * 
    FROM s2db.sbds_tx_transfers
    WHERE 
        `timestamp` >= :from AND
        `timestamp` < :to AND
        (`from` = :username OR `to` = :username);
';

$dateStart = date('Y-m-d', mktime(0, 0, 0, date('m'), 1, date('Y'))).' 00:00:00';
$dateTo    = date("Y-m-t", strtotime($dateStart)).' 23:59:59';

$Statement = $Database->prepare($query);

$Statement->bindParam(':username', $user);
$Statement->bindParam(':from', $dateStart);
$Statement->bindParam(':to', $dateTo);
$Statement->execute();

$transfers = $Statement->fetchAll(PDO::FETCH_ASSOC);
$bots      = [];

$sbdTotal   = 0;
$steemTotal = 0;

foreach ($transfers as $transfer) {
    $to     = $transfer['to'];
    $from   = $transfer['from'];
    $symbol = $transfer['amount_symbol'];

    if (isset($botList[$from])) {
        // returns
        if (!isset($bots[$from])) {
            $bots[$from]['STEEM'] = 0;
            $bots[$from]['SBD']   = 0;
        }

        $bots[$from][$symbol] = $bots[$from][$symbol] - floatval($transfer['amount']);

        switch ($symbol) {
            case 'STEEM':
                $steemTotal = $steemTotal - floatval($transfer['amount']);
                break;

            case 'SBD':
                $sbdTotal = $sbdTotal - floatval($transfer['amount']);
                break;
        }
        continue;
    }

    if (!isset($botList[$to])) {
        continue;
    }

    if (!isset($bots[$to])) {
        $bots[$to]['STEEM'] = 0;
        $bots[$to]['SBD']   = 0;
    }

    $bots[$to][$symbol] = $bots[$to][$symbol] + floatval($transfer['amount']);

    switch ($symbol) {
        case 'STEEM':
            $steemTotal = $steemTotal + floatval($transfer['amount']);
            break;

        case 'SBD':
            $sbdTotal = $sbdTotal + floatval($transfer['amount']);
            break;
    }
}

?>

<div class="card">
    <div class="card-header">
        Used Vote Bots
    </div>

    <div class="table-responsive">
        <table class="table card-table table-striped table-vcenter table-transfer">
            <thead>
            <tr>
                <th></th>
                <th>To</th>
                <th style="text-align: right">STEEM</th>
                <th style="text-align: right">SBD</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($bots as $bot => $data) { ?>
                <?php if (!empty($data['STEEM']) || !empty($data['SBD'])) { ?>
                    <tr>
                        <td>
                            <div class="avatar d-block"
                                 style="background-image: url('https://img.busy.org/@<?php echo $bot; ?>')"
                            ></div>
                        </td>
                        <td><?php echo $bot; ?></td>
                        <td style="text-align: right">
                            <?php echo number_format($data['STEEM']); ?>
                        </td>
                        <td style="text-align: right">
                            <?php echo number_format($data['SBD']); ?>
                        </td>
                    </tr>
                <?php } ?>
            <?php } ?>
            </tbody>
            <tfoot>
            <tr>
                <td>
                    Total
                </td>
                <td>&nbsp;</td>
                <td style="text-align: right"><?php echo number_format($steemTotal); ?> STEEM</td>
                <td style="text-align: right"><?php echo number_format($sbdTotal); ?> SBD</td>
            </tr>
            </tfoot>
        </table>
    </div>
</div>
