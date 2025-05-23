<?php

namespace App\Services;

use App\Mail\OTPMail;
use App\Repositories\Auth\AuthRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;


class AuthService
{

    public function __construct(
        protected CacheService $cacheService,
        protected AuthRepository $authRepository
    ) {}

    public function register(array $data)
    {
        $user = $this->authRepository->register($data);

        $otp = $this->generateOTP($user);

        $otpToken = $this->generateOTPToken();

        $this->cacheOTPToken($otpToken, $otp, $user);

        $this->sendOTP($user, $otp);

        return [
            'message'   => "Cadastro iniciado. Verifique seu e-mail para o código de verificação.",
            'otp_token'  => $otpToken,
        ];
    }

    public function login(array $credentials): array
    {
        if (!Auth::attempt($credentials)) {
            return [
                'data' => ['error' => 'Credenciais inválidas'],
                'status' => 401,
            ];
        }

        $user = Auth::user();

        $otp = $this->generateOTP($user);
        $this->sendOTP($user, $otp);

        $otpToken = $this->generateOTPToken();
        $this->cacheOTPToken($otpToken, $otp, $user);

        return [
            'data' => [
                'message' => 'OTP enviado para seu e-mail/SMS',
                'otp_token' => $otpToken,
            ],
            'status' => 200,
        ];
    }

    /**
     * Verifica o OTP e autentica o usuário.
     */
    public function verifyOTP(array $data): array
    {
        $cacheData = $this->cacheService->get('otp_token_' . $data['otp_token']);

        if (!$cacheData || $cacheData['otp'] != $data['otp']) {
            return [
                'data' => ['error' => 'OTP inválido ou expirado'],
                'status' => 401,
            ];
        }

        $user = $this->authRepository->findUser($cacheData['user_id']);

        if (!$user) {
            return [
                'data' => ['error' => 'Usuário não encontrado'],
                'status' => 404,
            ];
        }

        $token = $user->createToken('Auth Token')->accessToken;

        return [
            'data' => [
                'message' => 'Autenticado com sucesso',
                'token' => $token,
            ],
            'status' => 200,
        ];
    }

    /**
     * Gerar OTP e salvar no banco.
     */
    private function generateOTP($user): int
    {
        $otp = random_int(100000, 999999);
        $user->update([
            'otp' => $otp,
            'otp_expires_at' => now()->addMinutes(5),
        ]);

        return $otp;
    }

    /**
     * Envia o OTP por e-mail.
     */
    private function sendOTP($user, int $otp): void
    {
        Mail::to($user->email)->send(new OTPMail($otp));
    }

    private function generateOTPToken(): string
    {
        return Str::uuid();
    }

    private function cacheOTPToken(string $otpToken, int $otp, object $user): void
    {
        $this->cacheService->put('otp_token_' . $otpToken, ['otp' => $otp, 'user_id' => $user->id], env('CACHE_TIME_OTP'));
    }
}
