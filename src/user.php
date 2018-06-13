<?php

$user = '';

if (!empty($_REQUEST['user'])) {
    $user = filter_input(INPUT_GET, 'user');
    $user = urldecode($_REQUEST['user']);
    $user = str_replace('@', '', $user);
    $user = trim($user);
}

if (empty($user)) { ?>
    <div class="my-3 my-md-5">
        <div class="container">
            <div class="col-lg-6" style="margin: 0 auto">
                <div class="alert alert-primary">
                    Please insert an username
                </div>

                <form class="input-icon">
                    <input type="search"
                           class="form-control header-search"
                           placeholder="Search @steemit-username..."
                           tabindex="1"
                           name="user"
                    >
                    <div class="input-icon-addon">
                        <i class="fe fe-search"></i>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php } else { ?>
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

                <div class="col-lg-6">
                    <?php require "user/usedBotsThisMonth.php"; ?>
                </div>

            </div>
        </div>
    </div>
<?php } ?>