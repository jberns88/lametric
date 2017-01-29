<?php

foreach ([
__DIR__ . '/../vendor/autoload.php',
 __DIR__ . '/../../../autoload.php'
] as $autoloadPath) {
    if (file_exists($autoloadPath)) {
        return require ($autoloadPath);
    }
}

throw new RuntimeException('Failed to find autoloader, have you run composer up/install?');
