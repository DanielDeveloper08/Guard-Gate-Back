<?php
if (!function_exists('getFechaActual')) {
    function getFechaActual()
    {
        return now()->format('Y-m-d H:i:s');
    }
}

if (!function_exists('getExpiredToken')) {
    function getExpiredToken()
    {
        return config('sanctum.expiration');
    }
}
