<?php
namespace App\Helpers;

class StringHelper
{
    public static function clean($string)
    {
        $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
        $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.

        return array_filter(explode('-', $string));
    }

    public static function replaceWords($string, $removeList = [])
    {
        foreach ($removeList as $key => $item) {
            $string = str_replace($key, $item, $string); // Replaces all spaces with hyphens.
        }

        return $string;
    }

    public static function replaceWordsFromTemplate($string, $removeList = [])
    {
        foreach ($removeList as $key => $item) {
            $string = str_replace('[:' . $key . ']', $item, $string); // Replaces all spaces with hyphens.
        }

        return $string;
    }
}
