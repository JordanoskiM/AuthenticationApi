<?php

namespace App\Http\Controllers;

use App\Mail\SendMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class EmailManagementController extends Controller
{
    public function sendEmail()
    {
        try {
            if (Auth::user()->user_role == 'Admin') {
                $title = 'Invitation to sign up';
                $sendmail = Mail::to('emailtestapp@mailinator.com')->send(new SendMail($title));
                if (empty($sendmail)) {
                    return response()->json(['message' => 'Mail Sent Successfully', 'link' => 'http://127.0.0.1:8000/api/auth/register'], 200);
                } else {
                    return response()->json(['message' => 'Mail Sent fail'], 400);
                }
            } else {
                return response()->json(['message' => 'Unauthorized'], 401);
            }
        } catch(\Exception $e) {
            return response()->json(['status' => 'false', 'message' => $e->getMessage(), 'data' => []], 500);
        }

    }


}
