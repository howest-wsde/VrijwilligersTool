<?php

namespace AppBundle\UrlEncoder;

class UrlEncoder
{
    public static function encode($string)
    {
        $url = str_replace(" ", "-", $string);
        return $url;
    }

    public static function decode($url)
    {
        $string = str_replace("-", " ", $url);
        return $string;
    }
}
