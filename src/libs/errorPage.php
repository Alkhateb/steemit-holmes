<?php

$vendorUrlDir = '/vendor/tabler/tabler/dist/';

if (!isset($ERROR_CODE)) {
    $ERROR_CODE = 503;
}

if (!isset($ERROR_MESSAGE)) {
    $ERROR_MESSAGE = '
        <h1 class="h2 mb-3">Oops.. You just found an error page..</h1>
        <p class="h4 text-muted font-weight-normal mb-7">
            We are sorry but our service is currently not available..
        </p>
    ';
}

?>
<!doctype html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-Language" content="en"/>
    <meta name="msapplication-TileColor" content="#2d89ef">
    <meta name="theme-color" content="#4188c9">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent"/>
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="HandheldFriendly" content="True">
    <meta name="MobileOptimized" content="320">
    <link rel="icon" href="/favicon.ico" type="image/x-icon"/>
    <link rel="shortcut icon" type="image/x-icon" href="/favicon.ico"/>

    <title>ERROR - STEEMIT Holmes</title>

    <!-- Dashboard Core -->
    <link href="<?php echo $vendorUrlDir; ?>assets/css/dashboard.css" rel="stylesheet"/>
    <script src="<?php echo $vendorUrlDir; ?>assets/js/dashboard.js"></script>
</head>
<body class="">
<div class="page">
    <div class="page-content">
        <div class="container text-center">
            <div class="display-1 text-muted mb-5">
                <i class="si si-exclamation"></i>
                <?php echo $ERROR_CODE; ?>
            </div>
            <?php echo $ERROR_MESSAGE; ?>
        </div>
    </div>
</div>
</body>
</html>
