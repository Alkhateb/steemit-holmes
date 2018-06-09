<?php

$user = '';

if (!empty($_REQUEST['user'])) {
    $user = filter_input(INPUT_GET, 'user');
    $user = urldecode($_REQUEST['user']);
    $user = str_replace('@', '', $user);
    $user = trim($user);
}

if (empty($user)) {
    echo '<p>Please insert an username</p>';
} else {
    ?>
    <div class="my-3 my-md-5">
        <div class="container">
            <div class="page-header">
                <h1 class="page-title">
                    User Search for @<?php echo $user; ?>
                </h1>
            </div>

            <div class="row row-cards">
                <div class="col-lg-6">
                    <?php require "user/biggestTransfersOutPerMonth.php"; ?>
                </div>

                <div class="col-md-6">
                    <?php require "user/votesPerMonth.php"; ?>
                    <?php require "user/voteAverage.php"; ?>
                </div>

                <div class="col-lg-6">
                    <?php require "user/firstTransfers.php"; ?>
                </div>

                <div class="col-md-6">
                    <?php require "user/lastTransfers.php"; ?>
                </div>

                <div class="col-lg-6">
                    <?php require "user/firstTransfersReceived.php"; ?>
                </div>

                <div class="col-md-6">
                    <?php require "user/lastTransfersReceived.php"; ?>
                </div>

            </div>
        </div>
    </div>
<?php } ?>