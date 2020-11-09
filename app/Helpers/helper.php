<?php

use Laravel\Lumen\Routing\UrlGenerator;

if (!function_exists('resp')) {
    function resp($code = 200, $msg = '', $data = [])
    {
        return response()->json([
            'code' => $code,
            'msg' => $msg,
            'data' => $data,
        ]);
    }
}

if (!function_exists('urlGenerator')) {
    /**
     * @return UrlGenerator
     */
    function urlGenerator()
    {
        return new UrlGenerator(app());
    }
}

if (!function_exists('asset')) {
    /**
     * @param $path
     * @param bool $secured
     *
     * @return string
     */
    function asset($path, $secured = false)
    {
        return urlGenerator()->asset($path, $secured);
    }
}
