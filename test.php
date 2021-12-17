<?php
declare(strict_types=1);

$dir = new DirectoryIterator(dirname(__FILE__));
foreach ($dir as $fileinfo) {
    if (!$fileinfo->isDot()) {
        echo $fileinfo->getFilename() . PHP_EOL;
    }
}