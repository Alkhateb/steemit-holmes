<?php

$dir = dirname(dirname(__FILE__));

?>
<div class="my-3 my-md-5">
    <div class="container">
        <div class="page-header">
            <h1 class="page-title">
                Bot usage for User @<?php echo $user; ?>
            </h1>
        </div>

        <div class="row row-cards">
            <div class="col-lg-6">
                <?php require $dir."/usedBotsThisMonth.php"; ?>
            </div>
        </div>
    </div>
</div>