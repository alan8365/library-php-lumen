<?php


namespace App\Exceptions;


class Msg
{   
    // TODO traslate to chinese
    const Success = 'Success';
    const Unauthorized = 'Unauthorized';
    const Failed = 'Failed';

    const CreateUserSuccess = 'CreateUserSuccess';
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

}

