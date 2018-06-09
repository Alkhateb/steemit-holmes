<?php

require dirname(__FILE__).'/src/libs/header.php';

$tablerDir = '/vendor/tabler/tabler/dist/';
$page      = false;
$user      = null;

if (isset($_REQUEST['user'])) {
    $page = 'user';

    if (!empty($_REQUEST['user'])) {
        $user = filter_input(INPUT_GET, 'user');
        $user = urldecode($_REQUEST['user']);
        $user = str_replace('@', '', $user);
        $user = trim($user);
    }
}

if (isset($_REQUEST['tags'])) {
    $page = 'tags';
}

if (isset($_REQUEST['about'])) {
    $page = 'about';
}

?>
<!doctype html>
<html lang="en" dir="ltr">
<head>
    <title>STEEMIT Holmes - Your S2DB Explorer</title>

    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0"/>

    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <meta http-equiv="Content-Language" content="en"/>

    <meta name="msapplication-TileColor" content="#2d89ef"/>
    <meta name="theme-color" content="#4188c9"/>
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent"/>
    <meta name="apple-mobile-web-app-capable" content="yes"/>
    <meta name="mobile-web-app-capable" content="yes"/>
    <meta name="HandheldFriendly" content="true"/>
    <meta name="MobileOptimized" content="320"/>

    <link rel="icon" href="/favicon.ico" type="image/x-icon"/>
    <link rel="shortcut icon" type="image/x-icon" href="/favicon.ico"/>

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css"
          integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp"
          crossorigin="anonymous"
    />

    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,300i,400,400i,500,500i,600,600i,700,700i&amp;subset=latin-ext"
    >

    <link href="/bin/css/style.css" rel="stylesheet"/>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.min.js"></script>

    <script src="<?php echo $tablerDir; ?>/assets/js/require.min.js"></script>
    <script>
        requirejs.config({
            baseUrl: '<?php echo $tablerDir;?>'
        });
    </script>

    <!-- Dashboard Core -->
    <link href="<?php echo $tablerDir; ?>/assets/css/dashboard.css" rel="stylesheet"/>
    <script src="<?php echo $tablerDir; ?>/assets/js/dashboard.js"></script>

    <!-- Input Mask Plugin -->
    <script src="<?php echo $tablerDir; ?>/assets/plugins/input-mask/plugin.js"></script>
</head>
<body>

<div class="page">
    <div class="page-main">
        <div class="header py-4">
            <div class="container">
                <div class="d-flex">
                    <a class="header-brand" href="/">
                        <img src="/bin/images/jediholmshenwatson-logo.png" class="header-brand-img" alt="tabler logo">
                    </a>

                    <a href="#" class="header-toggler d-lg-noneorder-lg-2 ml-auto collapsed"
                       data-toggle="collapse"
                       data-target="#headerMenuCollapse"
                       aria-expanded="false"
                    >
                        <span class="header-toggler-icon"></span>
                    </a>
                </div>
            </div>
        </div>

        <?php require dirname(__FILE__).'/src/menu.php'; ?>

        <main>
            <?php

            switch ($page) {
                case 'user':
                    require dirname(__FILE__).'/src/user.php';
                    break;

                case 'about':
                    require dirname(__FILE__).'/src/about.php';
                    break;

                default:
                    require dirname(__FILE__).'/src/dashboard.php';
            }

            ?>
        </main>
    </div>

    <footer class="footer">
        <div class="container">
            <div class="row align-items-center flex-row-reverse">
                <div class="col-auto ml-lg-auto">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <ul class="list-inline list-inline-dots mb-0">
                                <li class="list-inline-item">
                                    <a href="/?about" target="_blank">
                                        About
                                    </a>
                                </li>
                                <li class="list-inline-item">
                                    <a href="https://github.com/pcsg/steemit-holmes/wiki" target="_blank">
                                        Documentation
                                    </a>
                                </li>
                                <li class="list-inline-item">
                                    <a href="https://github.com/pcsg/steemit-holmes/wiki" target="_blank">
                                        FAQ
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="col-auto">
                            <a href="https://github.com/pcsg/steemit-holmes"
                               class="btn btn-outline-primary btn-sm"
                               target="_blank"
                            >
                                Source code
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-auto mt-3 mt-lg-0 text-center">
                    Copyright © <?php echo date('Y'); ?> <a href=".">PCSG OHG</a>. All rights reserved.
                </div>
            </div>
        </div>
    </footer>
</div>

</body>
</html>