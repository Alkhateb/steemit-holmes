<?php

$dir = dirname(dirname(__FILE__));

?>
<div class="my-3 my-md-5">
    <div class="container">
        <div class="page-header">
            <h1 class="page-title">
                User @<?php echo $user; ?>
            </h1>
        </div>

        <div class="row row-cards">
            <div class="col-lg-6">
                <?php require $dir."/firstTransfers.php"; ?>
            </div>

            <div class="col-md-6">
                <?php require $dir."/lastTransfers.php"; ?>
            </div>

            <div class="col-lg-6">
                <?php require $dir."/firstTransfersReceived.php"; ?>
            </div>

            <div class="col-md-6">
                <?php require $dir."/lastTransfersReceived.php"; ?>
            </div>
        </div>
    </div>
</div>