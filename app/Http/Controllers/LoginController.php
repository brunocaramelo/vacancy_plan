<?php

namespace App\Http\Controllers;

use App\Services\LoginService;
use Illuminate\Http\Request;

use App\Http\Requests\LoginFormRequest;

class LoginController extends Controller
{
    private $loginService;
    public function __construct(LoginService $loginService)
    {
        $this->loginService = $loginService;
    }

    /**
     *
     * @OA\Post(
     *     path="/api/login",
     *     tags={"login"},
     *     operationId="doLogin",
     *     @OA\Response(
     *         response=422,
     *         description="Invalid input",
     *     ),
     *       @OA\RequestBody(
     *         required=true,
     *         description="Request Body Description",
     *         @OA\JsonContent(
     *             @OA\Property(property="email", type="string", example="admin@test.com" ),
     *             @OA\Property(property="password", type="string", example="password" ),
     *           ),*         ),
     *     ),
     * )
     */
    public function doLogin(LoginFormRequest $request)
    {
        $responseLogin = $this->loginService->doLogin($request->validated());

        return response()->json( $responseLogin
            , $responseLogin['status'] == 'success' ? 200 : 400);
    }

}
