<?php

$query = '
    SELECT * 
    FROM s2db.sbds_tx_transfers
    WHERE `to` = :username
    ORDER BY timestamp ASC
    LIMIT 7;
';

$cache = md5('cache-first-transfers-receive-'.$user);

/* @var $Item \Stash\Interfaces\ItemInterface */
$Item = $Cache->getItem($cache);

if (!$Item->isMiss()) {
    $transfers = $Item->get();
} else {
    $Statement = $Database->prepare($query);
    $Statement->bindParam(':username', $user);
    $Statement->execute();

    $transfers = $Statement->fetchAll(PDO::FETCH_ASSOC);

    $Item->set($transfers);
    $Item->save();
}

?>
<div class="card">
    <div class="card-header">
        First transfers received from others
    </div>

    <table class="table card-table table-striped table-vcenter">
        <thead>
        <tr>
            <th></th>
            <th>From</th>
            <th>Block</th>
            <!--            <th>From</th>-->
            <th>Date</th>
            <th>Amount</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($transfers as $transfer) { ?>
            <tr>
                <td>
                    <div class="avatar d-block"
                         style="background-image: url('https://img.busy.org/@<?php echo $transfer['from']; ?>')"
                    ></div>
                </td>
                <td><?php echo $transfer['from']; ?></td>
                <td><?php echo number_format($transfer['block_num']); ?></td>
                <!--                <td>--><?php //echo $transfer['from']; ?><!--</td>-->

                <td><?php echo $transfer['timestamp']; ?></td>
                <td style="text-align: right">
                    <?php echo number_format($transfer['amount']).' '.$transfer['amount_symbol']; ?>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>
