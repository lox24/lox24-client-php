<?php

use DG\BypassFinals;

require dirname(__DIR__).'/vendor/autoload.php';

BypassFinals::enable();
$cacheDir = dirname(__DIR__).'.phpunit.bypass.cache';
if (!file_exists($cacheDir) && !mkdir($cacheDir, 0777, true) && !is_dir($cacheDir)) {
    throw new \RuntimeException(sprintf('Directory "%s" was not created', $cacheDir));
}
BypassFinals::setCacheDirectory($cacheDir);

