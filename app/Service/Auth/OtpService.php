<?php

namespace App\Service\Auth;

use App\Models\Otp;
use Illuminate\Support\Facades\Hash;
use Kreait\Firebase\Factory;

class OtpService
{
    protected $auth;

    public function __construct()
    {
        $firebaseCredentialsPath = storage_path('firebase/telegram-90c89-firebase-adminsdk-houta-eeff8f2a7a.json');
        $factory = (new Factory)->withServiceAccount($firebaseCredentialsPath);
        $this->auth = $factory->createAuth();
    }

    /**
     * Generate and send OTP using Firebase
     *
     * @param string $mobileNumber
     * @return void
     */
    public function generateOtp(string $mobileNumber)
    {
        try {
            // Generate a 6-digit OTP
            $otp = 122517;
            $expiresAt = now()->addMinutes(5);

            // Store OTP in database (for verification later)
            Otp::create([
                'mobile_number' => $mobileNumber,
                'otp' => bcrypt($otp),
                'expires_at' => $expiresAt,
            ]);

            // Send OTP via Firebase (you need to implement this in your front-end)
            $this->sendOtp($mobileNumber, $otp);
        } catch (\Exception $exception) {
            app('log')->error('[generateOtp]'.$exception->getMessage());
        }
    }

    /**
     * Send OTP via Firebase (to be implemented in the front-end)
     *
     * @param string $mobileNumber
     * @param string $otp
     * @return void
     */
    public function sendOtp(string $mobileNumber, string $otp)
    {
        // Firebase handles OTP sending via SMS through the front-end SDK
        // This method is intended for backend operations; SMS sending should be handled by Firebase in your front-end.
    }

    /**
     * Verify the OTP
     *
     * @param string $mobileNumber
     * @param string $inputOtp
     * @return bool
     */
    public function verifyOtp(string $mobileNumber, string $inputOtp)
    {
        $otpRecord = Otp::where('mobile_number', $mobileNumber)
            ->where('expires_at', '>', now())
            ->latest()
            ->first();

        if ($otpRecord && Hash::check($inputOtp, $otpRecord->otp)) {
            return true;
        }

        return false;
    }
}
