<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\Code;
use App\Exceptions\Msg;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;

class UserController extends Controller
{

    /**
     * UserController constructor.
     * Auth middleware, exclude login and register.
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'store', 'find']]);
    }

    /**
     * @param Request $request
     * @return Response
     * Register user
     */
    public function store(Request $request)
    {
        $message = [
            'email.required' => 'Please input email',
            'email.email' => 'email format incorrect',
            'email.unique' => 'email exist',
            'name.required' => 'Please input name',
            'password.required' => 'Please input password',
            'name.min' => 'name at least :min characters',
            'password.min' => 'password at least :min characters',
        ];

        $validator = Validator::make($request->input(), [
            'name' => 'required|min:6',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
        ], $message);

        if ($validator->fails()) {
            foreach ($validator->errors()->getMessages() as $error) {
                return resp(Code::Failed, $error[0]);
            }
        }

        $name = $request->get('name');
        $email = $request->get('email');
        $password = $request->get('password');

        $attributes = [
            'email' => $email,
            'name' => $name,
            'password' => $password
        ];

        $user = User::create($attributes);

        return resp(Code::Success, Msg::CreateUserSuccess);
    }

    /**
     * @return Response
     * Get all user
     */
    public function index()
    {
        $users = User::paginate(env('PAGINATE'));
        return resp(Code::Success, Msg::Success, $users);
    }

    /**
     * @param string $email
     * @return Response
     * Get target user
     */
    public function find(string $email)
    {
        $user = User::find($email);
        return resp(Code::Success, Msg::Success, $user);
    }

    // TODO Let exp longer

    /**
     * @param Request $request
     * @return Response
     * User login
     */
    public function login(Request $request)
    {
        $message = [
            'email.required' => "Please input email",
            'password.required' => "Please input password",
        ];
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required'
        ], $message);

        if ($validator->fails()) {
            foreach ($validator->errors()->getMessages() as $error) {
                return resp(Code::NotFound, $error[0]);
            }
        }

        $credentials = request(['email', 'password']);
        if (!$token = auth()->attempt($credentials)) {
            return resp(Code::NotFound, Msg::LoginFailed);
        }
        return resp(Code::Success, Msg::LoginSuccess, $this->responseWithToken($token));
    }

    /**
     * @return Response
     * Get current user info
     */
    public function whoAmI()
    {
        return resp(Code::Success, Msg::UserIsMe, auth()->user());
    }

    /**
     * @return Response
     * User logout
     */
    public function logout()
    {
        auth()->logout();
        return resp(Code::Success, Msg::LogOutSuccess);
    }

    /**
     * @return array
     * Refresh token
     */
    public function refresh()
    {
        return $this->responseWithToken(auth()->refresh());
    }

    /**
     * @param $token
     * @return array
     * Return token message
     */
    protected function responseWithToken($token)
    {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * env('JWT_TTL')
        ];
    }
}
