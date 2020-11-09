<?php

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
