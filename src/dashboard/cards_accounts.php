<?php


// latest block
$query = '
    SELECT * 
    FROM s2db.sbds_core_blocks
    ORDER BY timestamp DESC
    LIMIT 1;
';

// accounts
$accounts                       = 0;
$accountsCreation               = 0;
$accountsCreationWithDelegation = 0;

$Statement = $Database->prepare($query);
$Statement->execute();

$result      = $Statement->fetchAll(PDO::FETCH_ASSOC);
$latestBlock = $result[0]['block_num'];

// accounts
$query = '
    SELECT COUNT(*) AS count
    FROM 
        s2db.sbds_tx_account_creates
';

$cache = md5('cache-general-account_creates');

/* @var $Item \Stash\Interfaces\ItemInterface */
$Item = $Cache->getItem($cache);

if (!$Item->isMiss()) {
    $accountsCreation = $Item->get();
} else {
    $Statement = $Database->prepare($query);
    $Statement->execute();

    $result = $Statement->fetchAll(PDO::FETCH_ASSOC);

    $accountsCreation = (int)$result[0]['count'];

    $Item->set($result[0]['count']);
    $Item->save();
}

// accounts with delegation
$query = '
    SELECT COUNT(*) AS count
    FROM 
        s2db.sbds_tx_account_create_with_delegations;
';

$cache = md5('cache-general-account_create_with_delegations');

/* @var $Item \Stash\Interfaces\ItemInterface */
$Item = $Cache->getItem($cache);

if (!$Item->isMiss()) {
    $accountsCreationWithDelegation = $Item->get();
} else {
    $Statement = $Database->prepare($query);
    $Statement->execute();

    $result = $Statement->fetchAll(PDO::FETCH_ASSOC);

    $accountsCreationWithDelegation = (int)$result[0]['count'];

    $Item->set($result[0]['count']);
    $Item->save();
}

$accounts = $accountsCreation + $accountsCreationWithDelegation;

?>
<div class="row row-cards">
    <div class="col-6 col-sm-4 col-lg-2">
        <div class="card">
            <div class="card-body p-3 text-center">
                <div class="h1 m-0">
                    <?php echo number_format($accounts); ?>
                </div>
                <div class="text-muted mb-4">
                    Accounts
                    <br/><br/>
                </div>
            </div>
        </div>
    </div>

    <div class="col-6 col-sm-4 col-lg-2">
        <div class="card">
            <div class="card-body p-3 text-center">
                <div class="h1 m-0">
                    <?php echo number_format($accountsCreationWithDelegation); ?>
                </div>
                <div class="text-muted mb-4">
                    Account creation<br/>
                    <span class="card--description">(with delegations)</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-6 col-sm-4 col-lg-2">
        <div class="card">
            <div class="card-body p-3 text-center">
                <div class="h1 m-0">
                    <?php echo number_format($accountsCreation); ?>
                </div>
                <div class="text-muted mb-4">
                    Account creation<br/>
                    <span class="card--description">(without delegations)</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-6 col-sm-4 col-lg-2">
        <div class="card">
            <div class="card-body p-3 text-center">
                <div class="h1 m-0">
                    <?php echo number_format($latestBlock); ?>
                </div>
                <div class="text-muted mb-4">
                    Latest Block
                    <br/><br/>
                </div>
            </div>
        </div>
    </div>
</div>
