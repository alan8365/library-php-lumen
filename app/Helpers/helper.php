<?php

use Illuminate\Http\Response;
use Laravel\Lumen\Routing\UrlGenerator;

if (!function_exists('resp')) {
    /**
     * @param int $code
     * @param string $msg
     * @param array $data
     * @return Response
     */
    function resp($code = 200, $msg = '', $data = [])
    {
        return response([
            'code' => $code,
            'msg' => $msg,
            'data' => $data,
        ], $code);
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
