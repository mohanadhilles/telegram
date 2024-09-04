<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Service\Auth\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    protected OtpService $otpService;

    public function __construct(OtpService $otpService)
    {
        $this->otpService = $otpService;
    }

    /**
     * Send OTP to the user's mobile number
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendOtp(Request $request)
    {
        $language = $request->header('Accept-Language', 'en');
        $request->validate([
            'mobile_number' => 'required|regex:/^\+[1-9]\d{1,14}$/',
        ]);

        $this->otpService->generateOtp($request->mobile_number);
        return response()->json(['message' => __('messages.response.otp', [], $language)], config('request.rsp_success'));
    }

    /**
     * Verify the OTP and authenticate the user
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function verify(Request $request)
    {
        $request->validate([
            'mobile_number' => 'required|regex:/^\+[1-9]\d{1,14}$/',
            'otp' => 'required|string',
        ]);

        $language = $request->header('Accept-Language', 'en');
        $isValid = $this->otpService->verifyOtp($request->mobile_number, $request->otp);

        if (!$isValid) {
            return response()->json(['message' => __('messages.response.invalid', [], $language)], config('request.rsp_unauthorized'));
        }

        $user = User::firstOrCreate(
            ['phone' => $request->mobile_number],
            [
                'name' => 'User',
                'password' => Hash::make('default_password'),
            ]
        );

        $token = $user->createToken('API Token')->plainTextToken;

        return response()->json([
            'message' => __('messages.response.verify', [], $language),
            'token' => $token,
        ], config('request.rsp_success'));
    }
}
