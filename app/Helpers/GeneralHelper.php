<?php


if (!function_exists('greetings')) {
    function greetings()
    {
        $hour = (int) date('H');
        if ($hour >= 5 && $hour < 11) {
            return "Selamat Pagi";
        } else if ($hour >= 11 && $hour < 15) { 
            return "Selamat Siang";
        } else if ($hour >= 15 && $hour < 19) {
            return "Selamat Sore";
        } else {
            return "Selamat Malam";
        }
    }
}
