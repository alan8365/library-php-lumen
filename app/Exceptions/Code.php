<?php


namespace App\Exceptions;


class Code
{
    const   Success = 200;
    const   Failed = 400;
    const   Unauthorized = 401;
    const   NotFound = 404;


    const   CreateUserSuccess = 10001;
    const   CreateUserFailed = 10002;

    const   LoginSuccess = 20001;
    const   LoginFailed = 20002;
    const   LogOutSuccess = 20003;
    const   UserIsMe = 20004;

    const   CreatePostsSuccess = 30001;
    const   CreatePostsFailed = 30002;
    const   PostsListSuccess = 30003;
    const   PostsListFailed = 30004;
}
