<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Http\Controllers\BaseController;
use Hash;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

/**
 * 사용자 인증관련 API입니다.
 */
class AuthController extends BaseController
{
    /**
     * 회원가입
     *
     */
    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'access_id' => ['required', Rule::unique('users')->whereNull('deleted_at')],
            'password' => 'required|string',
            'name' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->sendError('유효성 오류', $validator->errors(), 422);
        }

        $arrSet = $request->all();
        $arrSet['password'] = Hash::make($arrSet['password']);
        $student = User::create($arrSet);

        $arrRet = [
            'token' => $student->createToken('MyApp')->plainTextToken,
        ];

        return $this->sendResponse($arrRet, '회원등록 성공');
    }

    /**
     *
     * 로그인
     */
    public function login(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'access_id' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('유효성 오류', $validator->errors(), 422);
        }

        if (!User::where('access_id', $request->access_id)->exists() || !Auth::attempt(['access_id' => $request->access_id, 'password' => $request->password])) {
            return $this->sendError('로그인 정보를 확인할 수 없습니다. 계정정보를 다시 확인해주시기 바랍니다.', [], 404);
        }

        if (Auth::attempt(['access_id' => $request->access_id, 'password' => $request->password])) {
            $user = Auth::user();

            return $this->sendResponse([
                'token' => $user->createToken('auth_token')->plainTextToken,
            ], '로그인 성공');
        } else {
            return $this->sendError('인증오류', [], 401);
        }   
    }

    /**
     *
     * 로그아웃
     *
     */
    public function logout(Request $request): JsonResponse
    {
        $user = Auth::user();
        if (!$user) {
            return $this->sendError('로그인 상태가 아닙니다.', [], 401);
        }
        $user->currentAccessToken()->delete();
        return $this->sendResponse([], '로그아웃 성공');
    }
}
