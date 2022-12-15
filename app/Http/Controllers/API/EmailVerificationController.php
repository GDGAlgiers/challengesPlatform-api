<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Verified;
class EmailVerificationController extends BaseController

{

    public function verify($id, $hash) {

        $user = User::find($id);
        abort_if(!$user, 403);
        abort_if(!hash_equals($hash, sha1($user->getEmailForVerification())), 403);

        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();

            event(new Verified($user));
        }

        return view("welcome");
    }

    public function resend(Request $request) {
        $request->user()->sendEmailVerificationNotification();

        return $this->sendResponse([], "Successfully sent the verification link to your email!");
    }
}
