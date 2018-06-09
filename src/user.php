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
                    User Search
                </h1>
            </div>

            <div class="row row-cards">
                <?php require "user/biggestTransfersOutPerMonth.php"; ?>
            </div>
        </div>
    </div>
<?php } ?>