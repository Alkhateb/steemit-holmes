<?php

/**
 * Show an error page
 *
 * @param string $title
 * @param string $message
 * @param integer $code
 */
function show_error($title, $message, $code)
{
    $ERROR_CODE = $code;

    $ERROR_MESSAGE = '
    <h1 class="h2 mb-3">'.$title.'</h1>
        <p class="h4 text-muted font-weight-normal mb-7">
            '.$message.'
        </p>
    ';

    include 'errorPage.php';
    exit;
}
