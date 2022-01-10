<?php

namespace App\Http\Controllers;

use App\Mail\SendMail;
use App\Mail\VerificationEmail;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|string|unique:users',
                'password' => 'required|string|min:6',
                'user_name' => 'required|string|min:4|max:20',
            ]);

            if ($validator->fails()) {
                $error = $validator->errors()->all()[0];
                return response()->json(['status' => 'false', 'message' => $error, 'data' => []], 422);
            }

            $code = random_int(100000, 999999);

            $user = new User([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'user_name' => $request->user_name,
                'avatar' => $request->avatar,
                'verification_code' => $code,
                'user_role' => $request->user_role,
            ]);

            $user->save();

            $title = '6 Digit Verification code';

            $sendmail = Mail::to($request->email)->send(new VerificationEmail($title, $code));

            if (empty($sendmail)) {
                return response()->json(['message' => '6 digit code is sent to your email', 'code' => $code, 'link_for_verification' => 'http://127.0.0.1:8000/api/auth/register/' . $user->id . '/complete-register'], 200);
            } else {
                return response()->json(['message' => 'Register fail'], 400);
            }
        } catch(\Exception $e) {
            return response()->json(['status' => 'false', 'message' => $e->getMessage(), 'data' => []], 500);
        }
    }

    public function completeRegister(Request $request, $userId)
    {
        $user = User::find($userId);
        if($user->verification_code == $request->verification_code)
        {
            $user->update([
                'registered_at' => Carbon::now(),
            ]);
            return response()->json(['message' => 'User registered successfully'], 200);
        }

        return response()->json(['message' => 'Wrong code'], 400);
    }

    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_name' => 'required',
                'password' => 'required|string'
            ]);

            if ($validator->fails()) {
                $error = $validator->errors()->all()[0];
                return response()->json(['status' => 'false', 'message' => $error, 'data' => []], 422);
            }

            $credentials = request(['user_name', 'password']);

            if (!Auth::attempt($credentials)) {
                return response()->json(['message' => 'Wrong credentials'], 401);
            }

            $user = $request->user();
            $tokenResult = $user->createToken('Personal Access Token');
            $token = $tokenResult->accessToken;
            $tokenResult->expires_at = Carbon::now()->addWeeks(1);
            $token->save();

            return response()->json(['data' => [
                'user' => Auth::user(),
                'access_token' => $tokenResult->plainTextToken,
                'token_type' => 'Bearer',
                'expires_at' => Carbon::parse($tokenResult->expires_at)->toDateTimeString()
            ]]);
        } catch(\Exception $e) {
            return response()->json(['status' => 'false', 'message' => $e->getMessage(), 'data' => []], 500);
        }
    }
}
