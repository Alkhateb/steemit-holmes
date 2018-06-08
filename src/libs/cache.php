<?php

$cachePath = dirname(dirname(dirname(__FILE__))).'/var/cache';

$Stash = new Stash\Pool(
    new Stash\Driver\FileSystem([
        'path' => $cachePath
    ])
);

return $Stash;
