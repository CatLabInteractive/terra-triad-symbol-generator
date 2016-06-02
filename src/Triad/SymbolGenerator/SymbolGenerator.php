<?php

namespace Triad\SymbolGenerator;

/**
 * Class SymbolGenerator
 * @package Triad\SymbolGenerator
 */
class SymbolGenerator
{
    public function __construct()
    {

    }

    private function hex2rgb($hex) {
        $hex = str_replace("#", "", $hex);

        if(strlen($hex) == 3) {
            $r = hexdec(substr($hex,0,1).substr($hex,0,1));
            $g = hexdec(substr($hex,1,1).substr($hex,1,1));
            $b = hexdec(substr($hex,2,1).substr($hex,2,1));
        } else {
            $r = hexdec(substr($hex,0,2));
            $g = hexdec(substr($hex,2,2));
            $b = hexdec(substr($hex,4,2));
        }
        $rgb = array($r, $g, $b);
        //return implode(",", $rgb); // returns the rgb values separated by commas
        return $rgb; // returns an array with the rgb values
    }

    public function generate($name, $width, $height)
    {
        header('Content-Type: image/png');

        $im = imagecreatetruecolor($width, $height);

        // White background and blue text
        $bg = imagecolorallocate($im, 255, 255, 255);
        imagefill($im, 0, 0, $bg);

        $chars = str_split($name, 1);
        $radius = $width * 0.8;

        $center = [
            'x' => $width / 2,
            'y' => $height / 2
        ];

        $colorsHex = [
            '#ff3155',
            '#2daefd',
            '#49f770',
            '#ffed5e',
            '#663399'
        ];

        $colors = [];
        foreach ($colorsHex as $v) {
            $colors[] = $this->hex2rgb($v);
        }

        $colorIndex = 0;
        switch (substr($name, 0, 1)) {
            case 1:
                break;

            case 2:
                $colorIndex += 1;
                break;

            case 3:
                $colorIndex += 2;
                break;
        }

        for ($i = 0; $i < count($chars); $i ++) {
            $color = $colors[$colorIndex % count($colors)];
            $colorIndex += 1;

            switch($chars[$i]) {
                case 1:
                    $radius = $this->circle($im, $radius, $center, $color);
                    //$colorIndex += 1;
                    break;

                case 2:
                    $radius = $this->square($im, $radius, $center, $color);
                    //$colorIndex += 2;
                    break;

                case 3:
                    $radius = $this->triangle($im, $radius, $center, $color);
                    //$colorIndex += 3;
                    break;
            }
        }

        // Output the image
        header('Content-type: image/png');

        imagepng($im);
        imagedestroy($im);
    }

    private function getThikness($radius)
    {
        return 1 + ceil($radius / 20);
    }

    private function circle($im, $radius, $center, $color = [ 0, 0, 255 ])
    {
        $thikness = $this->getThikness($radius);

        // Write the string at the top left
        $textcolor = imagecolorallocate($im, $color[0], $color[1], $color[2]);

        imagesetthickness($im, $thikness);
        imagearc($im, $center['x'], $center['y'], $radius, $radius, 0, 359.99, $textcolor);

        $radius *= 0.8;

        return $radius;
    }

    private function square($im, $radius, $center, $color = [ 0, 0, 255 ])
    {
        //$this->circle($im, $radius, $center, [ 0, 255, 0 ]);

        $thikness = $this->getThikness($radius);

        // Write the string at the top left
        $color = imagecolorallocate($im, $color[0], $color[1], $color[2]);

        $width = $radius * 0.68;

        imagesetthickness($im, $thikness);
        imagerectangle(
            $im,
            $center['x'] - ($width / 2), $center['y'] - ($width / 2),
            $center['x'] + ($width / 2), $center['y'] + ($width / 2),
            $color
        );

        $radius *= 0.60;

        return $radius;
    }

    private function triangle($im, $radius, $center, $color = [ 0, 0, 255 ])
    {
        $thikness = $this->getThikness($radius);

        //$this->circle($im, $radius, $center, [ 0, 255, 0 ]);

        // Write the string at the top left
        $color = imagecolorallocate($im, $color[0], $color[1], $color[2]);

        //$width = $radius * 0.82;
        $width = $radius * 0.5;

        $topx = floor(sin(deg2rad(180)) * $width);
        $topy = floor(cos(deg2rad(180)) * $width);

        $leftx = floor(sin(deg2rad(240)) * $width);
        $lefty = floor(-cos(deg2rad(240)) * $width);

        $rightx = floor(sin(deg2rad(120)) * $width);
        $righty = floor(-cos(deg2rad(120)) * $width);

        $points = [
            $center['x'] + $topx, $center['y'] + $topy,
            $center['x'] + $leftx, $center['y'] + $lefty,
            $center['x'] + $rightx, $center['y'] + $righty,
        ];

        imagesetthickness($im, $thikness);
        imagepolygon(
            $im,
            $points,
            count($points) / 2,
            $color
        );

        $radius *= 0.45;

        return $radius;
    }
}