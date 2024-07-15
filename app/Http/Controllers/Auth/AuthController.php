<?php

namespace App\Http\Controllers\Auth;

use Throwable;
use Carbon\Carbon;
use App\Models\User;
use Ramsey\Uuid\Uuid;
// use App\Mail\VerifyMail;
// use App\Mail\RegisterMail;
use App\Models\Profile;
use App\Mail\WelcomeMail;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Mail\PasswordResetMail;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use App\Repositories\Auth\AuthRepository;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    private $authRepository;

    public function __construct(AuthRepository $authRepository)
    {
        $this->authRepository = $authRepository;

        $this->middleware('auth:api', ['except' => ['signin', 'signup', 'verified', 'resendLink','resetVerify','resetPassword', 'forgotPassword', 'forgotPin', 'resetPin', 'listPlan']]);
    }

    public function signup(Request $request) {

        return $this->authRepository->signup($request);

    }

    public function signin(Request $request){

        return $this->authRepository->signin($request);
    }

    public function signout() {

        return $this->authRepository->signout();

    }


    public function refresh() {
        return $this->authRepository->refresh();
    }

    public function userAccount() {

        return $this->authRepository->userAccount();

    }

    protected function createNewToken($token){
        return response()->json([
            'token' => $token,
            'token_type' => 'bearer',
            // you dont fix maximium expired at here, it in config/auth.php
            'expires_in' => auth('api')->factory()->getTTL() * 120,
            'user' => User::where('id',auth()->user()->id)->with('profile')->first()
        ],200);
    }


    public function updateProfile(){

        return $this->authRepository->updateProfileDetails();
    }

    public function updateProfilePhoto(){

        return $this->authRepository->profilePhoto();
    }

    public function changePassword(){

        return $this->authRepository->changePassword();
    }

    public function forgotPassword(){

        return $this->authRepository->forgotPassword();
    }

    public function resetPassword(){

        return $this->authRepository->resetPassword();
    }

    public function myReferrals(){

        return $this->authRepository->myReferrals();
    }

    public function myReferrer(){

        return $this->authRepository->myReferrer();
    }

    public function verifyKYC(){

        return $this->authRepository->verifyKYC();
    }

    public function myKYC(){

        return $this->authRepository->myKYC();
    }

    public function listPlan(){

        return $this->authRepository->listPlan();
    }


}
