<?php

use Triad\SymbolGenerator\SymbolGenerator;

require 'vendor/autoload.php';

$generator = new SymbolGenerator();

if (isset($_GET['name'])) {
    $name = $_GET['name'];
} else {
    $name = '';
    $level = mt_rand(1, 5);
    for ($i = 0; $i < $level; $i++) {
        $name .= mt_rand(1, 3);
    }
}

$size = 400;
$generator->generate($name, $size, $size);