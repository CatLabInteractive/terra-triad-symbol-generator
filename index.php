<?php

require 'vendor/autoload.php';

function getCombinations($length, $prefix = null)
{
    $out = [];

    foreach ([ 1, 2, 3] as $value) {
        if ($length > 0) {
            $out = array_merge($out, getCombinations($length - 1, $prefix . $value));
        } else {
            $out[] = $prefix . $value;
        }
    }

    return $out;
}

for ($length = 0; $length < 5; $length ++) {

    $names = getCombinations($length);
    foreach ($names as $v) {
        echo '<img src="image.php?name=' . $v . '" />';
    }

}