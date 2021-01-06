<?php


namespace App\Exceptions;


class Msg
{
    // TODO traslate to chinese
    const Success = '成功';
    const Unauthorized = '未登入';
    const Failed = '失敗';

    const CreateUserSuccess = '註冊成功';
    const CreateUserFailed = 'CreateUserFailed';
    const LoginSuccess = 'LoginSuccess';
    const LoginFailed = 'LoginFailed';
    const LogOutSuccess = 'LogOutSuccess';
    const UserIsMe = 'UserIsMe';

    const CreatePostsSuccess = 'CreatePostsSuccess';
    const CreatePostsFailed = 'CreatePostsFailed';
    const PostsListSuccess = 'PostsListSuccess';
    const PostsListFailed = 'PostsListFailed';

    const SetFavoriteSuccess = 'SetFavoriteSuccess';
    const UnsetFavoriteSuccess = 'UnsetFavoriteSuccess';

    const BookNotFound = 'BookNotFound';
}

