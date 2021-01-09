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
     *
     * @OA\Post(
     *     path="/auth/store",
     *     summary="Register user.",
     *     tags={"Auth"},
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="email",
     *         in="query",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="password",
     *         in="query",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Register success"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Field invalid"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $message = [
            'name.required' => 'Please input name',
            'name.min' => 'name at least :min characters',
            'email.required' => 'Please input email',
            'email.email' => 'email format incorrect',
            'email.unique' => 'email exist',
            'password.required' => 'Please input password',
            'password.min' => 'password at least :min characters',
        ];

        $validator = Validator::make($request->all(), [
            'name' => 'required|min:6',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
        ], $message);

        if ($validator->fails()) {
            foreach ($validator->errors()->getMessages() as $error) {
                return resp(Code::Failed, $error[0]);
            }
        }

        $user = User::create($request->all());

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

    /**
     * @param Request $request
     * @return Response
     * User login
     *
     * @OA\Post(
     *     path="/auth/login",
     *     summary="Get token in website.",
     *     tags={"Auth"},
     *     @OA\Parameter(
     *         name="email",
     *         in="query",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="password",
     *         in="query",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List success"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Field invalid"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found"
     *     )
     * )
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
     *
     * @OA\Get(
     *     path="/auth/whoAmI",
     *     summary="Get current user info.",
     *     tags={"Auth"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Get user info success."
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized."
     *     )
     * )
     */
    public function whoAmI()
    {
        return resp(Code::Success, Msg::UserIsMe, auth()->user());
    }

    /**
     * @return Response
     * User logout
     *
     * @OA\Post(
     *     path="/auth/logout",
     *     summary="Cancel token.",
     *     tags={"Auth"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Logout success."
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized."
     *     )
     * )
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
