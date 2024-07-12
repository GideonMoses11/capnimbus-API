<?php

namespace App\Http\Controllers;

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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api', ['except' => ['signin', 'signup', 'verified', 'resendLink','resetVerify','resetPassword', 'forgotPassword', 'forgotPin', 'resetPin']]);
    }

    public function signup(Request $request) {
        $request->validate([
            'username' => 'required|string|max:50|unique:users',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|min:5|max:15',
            'role' => 'nullable|string|max:15',
        ]);

        $user = User::create([
            'id' => $this->generateUuid(),
            'username'=> $request->username,
            'password' => Hash::make($request->password),
            'email'=> $request->email,
            'role'=> 'user',
            ]);

            $pro_photo = 'https://soccernity.sfo3.digitaloceanspaces.com/user/default/soc_photo.png';

            $user->profile()->create([
                'id' => $this->generateUuid(),
                'profile_photo' => $pro_photo,
                'user_id' => $user->id
            ]);

            // $this->RegisterWebHook($user);

            // $url = "https://soccernity.com/auth/signin";
            // try {
            //     Mail::to($user->email)->send(new WelcomeMail($user, $url));
            // } catch (\Throwable $th) {
            //     // throw $th;
            // }

        return response()->json([
            'message' => 'User successfully registered',
            'user' => $user,
        ], 201);
    }

    public function signin(Request $request){

        $this->validate($request, [
            'login'    => 'required',
            'password' => 'required',
        ]);

        $login_type = filter_var($request->input('login'), FILTER_VALIDATE_EMAIL )
            ? 'email'
            : 'username';

        $request->merge([
            $login_type => $request->input('login')
        ]);

        $login_credential = $request->input('login');

        if (!$token = Auth::attempt($request->only($login_type, 'password'))){
            return response()->json([
                'error' => 'Unauthorized',
                'success' => false,
                'status' => 401,
                'message' => 'Invalid credentials'
                ], 401);
        }

        //banned user
        $banned_user = User::where(function($q) {
            return $q->where('email', request()->input('login'))
                     ->orWhere('username', request()->input('login'));
          })->where('status', 'banned')->first();

        //check banned user
        if($banned_user){
            return response()->json(['message' => 'This account was banned temporarily!'], 402);
        }

        //deactivated user
        // $deactivated_user = User::where(function($q) {
        //     return $q->where('email', request()->input('login'))
        //              ->orWhere('username', request()->input('login'));
        //   })->where('account_status', 'deactivated')->first();

        // //check deactivated user
        // if($deactivated_user){
        //     return response()->json(['message' => 'This account was deactivated temporarily!'], 403);
        // }

        return $this->createNewToken($token);
    }

    public function signout() {
        auth()->logout();

        return response()->json(['message' => 'User successfully signed out']);
    }

    public function verified($email,$verification_code)
    {
        $user = User::where('email',$email)
        ->where('verification_code', $verification_code)->first();
        // invalid code or email from user
       if (!$user) {
        return response()->json(['success'=> false, 'message'=> "Verification code or Email is invalid."],401);
       }
    //    verify user now
       $verified_mail = Carbon::now();
       $user->update(['email_verified_at' => $verified_mail]);
       return redirect($this->baseurl."/auth/signin?email=$email");
    }

    public function  resendLink(Request $request){
        $request->validate([
            'email' => 'required|string|email'
        ]);
         $user=User::where('email',$request->email)->first();
         return $this->sendCode($user);
    }

    public function sendCode($user)
    {
        // initialize and update code here
        $verification_code = mt_rand(1000000000, 9999999999);
        $email = $user->email;
        $url = "api/auth/verified/$email/$verification_code";
        $user->update(['verification_code'=>$verification_code]);
        // send code to mail here example token to email
       try {
            // Mail::to($user->email)->send(new VerifyMail($user, $verification_code, $url, $email));
       } catch (\Throwable $th) {
           //throw $th;
       }

        return response()->json([
            'success'=> true,
            'message'=> "We have sent a verification to your email address",
            'email'=> $user->email,
         ],200);

    }

    public function refresh() {
        return $this->createNewToken(auth()->refresh());
    }

    public function userAccount() {
        $user = User::where('id',auth()->user()->id)
        ->with('profile')
        ->first();
        return response()->json($user,200);
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

    public function resetPassword(){

        request()->validate([
            'email' => 'required|string|email',
            'password' => 'required|string|confirmed|min:5',
            'token' => 'required|string|min:10',
        ]);

        $check= DB::table('password_resets')->where('email',request()->email)
        ->where('token',request()->token);
        if(!$check->first())
        {
           return response()->json(['success'=> false, 'message'=> "Invalid Email or Token"],401);
        }
        $user = User::where('email',$check->first()->email)->update(['password'=>Hash::make(request()->password)]);
        $check->delete();
        return response()->json(['success'=> true, 'message'=> "Password Updated"],200);

    }

    public function forgotPassword(Request $request){
        // If email does not exist
        request()->validate( [
            'email' => 'required|string|email',
        ]);
        $email = request()->email;
        $user = User::where('email', $email)->first();
        if(!$user) {
            return response()->json(['success'=> false,
            'message'=> "Email not found!"],401);
        }

            // If email exists
            $token = Str::random(10);

            $isOtherToken = DB::table('password_resets')->where('email', $email);

            $url = "https://soccernity-beta.netlify.app/auth/reset-password?token=$token&email=$email";

            // $token = Str::random(10);
            if(!$isOtherToken->first()){
                $data= DB::table('password_resets')->insert([
                     'email'=>$email,
                     'token'=>$token,
                     'created_at'=>now()
                  ]);
             }
             else{
                $isOtherToken->update([
                     'email'=>$email,
                     'token'=>$token,
                     'created_at'=>now()
                ]);
              }



            //  save token and mail to reset table
            $isOtherToken = DB::table('password_resets')->where('email',request()->email);


            try {

                Mail::to($email)->send(new PasswordResetMail($email, $token, $user, $url));

             } catch (\Throwable $th) {
                //  throw $th;
             }

            return response()->json(['success'=> true,
            'message'=> "A reset link has been sent to your email"]);

    }

    public function forgotPin(Request $request){
        // If email does not exist
        request()->validate( [
            'email' => 'required|string|email',
        ]);
        $email = request()->email;
        $user = User::where('email', $email)->first();
        if(!$user) {
            return response()->json(['success'=> false,
            'message'=> "Email not found!"],401);
        } else {
            // If email exists
            $token = Str::random(10);

            $isPinToken = DB::table('pin_resets')->where('email', $email);

            $url=$this->baseurl."/auth/reset-pin?token=$token&email=$email";

            // $token = Str::random(10);
            if(!$isPinToken->first()){
                $data= DB::table('pin_resets')->insert([
                     'email'=>$email,
                     'token'=>$token,
                     'created_at'=>now()
                  ]);
             }
             else{
                $isPinToken->update([
                     'email'=>$email,
                     'token'=>$token,
                     'created_at'=>now()
                ]);
              }
            // $isOtherToken = DB::table('password_resets')->insert([
            //     'email' => $email,
            //     'token' => $token,
            //     'created_at' => Carbon::now()
            // ]);


            //  save token and mail to reset table
            $isPinToken = DB::table('pin_resets')->where('email',request()->email);


            try {

                // Mail::to($email)->send(new PinResetMail( $email, $token, $user, $url));

             } catch (\Throwable $th) {
                 //throw $th;
             }

            return response()->json(['success'=> true,
            'message'=> "A pin reset link has been sent to your email"]);

        }
    }

    public function changePassword(){

        request()->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|confirmed|min:5|string'
        ]);

        $user = auth()->user();

         // The passwords matches

        if (!Hash::check(request()->current_password, $user->password))
         {
            return response()->json([
                'success'=> false,
                'message'=> "Current password is invalid!"]
                ,401);
         }

        // Current password and new password same
        if (strcmp(request()->current_password, request()->new_password) == 0)
         {
            return response()->json([
                'success'=> false,
                'message'=> "New password cannot be same as current password!"]
                ,402);
         }

         $updated_user = $user->update([
             'password' => Hash::make(request()->new_password)
         ]);

         if($updated_user){
            return response()->json([
                'updated_user'=> $updated_user,
                'success'=> true,
                'message'=> "Password changed successfully!"]
                ,200);
         }

    }

    public function updateProfileDetails(){

        $user = auth()->user();
        $profile = auth()->user()->profile;

        request()->validate([
            'first_name' => 'nullable|string|between:5,100',
            'last_name' => 'nullable|string|between:5,100',
            'dob' => 'nullable|string|max:100',
            'gender' => 'nullable|string|max:100',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
        ]);

        $profile->update([
            'first_name'=> !empty(request()->first_name) ? request()->first_name : $profile->first_name,
            'last_name'=> !empty(request()->last_name) ? request()->last_name : $profile->last_name,
            'dob'=> !empty(request()->dob) ? request()->dob : $profile->dob,
            'gender'=> !empty(request()->gender) ? request()->gender : $profile->gender,
            'address'=> !empty(request()->address) ? request()->address : $profile->address,
            'city'=> !empty(request()->city) ? request()->city : $profile->city,
            'state'=> !empty(request()->state) ? request()->state : $profile->state,
            'country'=> !empty(request()->country) ? request()->country : $profile->country,
            ]);

        return response()->json([
            'message' => 'User profile updated successfully!',
            'user' => $user,
            'profile' => $profile,
        ], 201);

    }

    public function updateUsername(){

        $user = auth()->user();

        request()->validate([
            'username' => 'nullable|string|max:50|unique:users',
        ]);

        $user->update([
            'username'=> !empty(request()->username) ? request()->username : $user->username,
            ]);

        return response()->json([
            'message' => 'Users username updated successfully!',
            'user' => $user,
        ], 201);

    }

    public function profilePhoto(){

        $user = auth()->user();
        $profile = auth()->user()->profile;
        $baseUrl = 'https://soccernity.sfo3.digitaloceanspaces.com/';

        request()->validate([
            'profile_photo' => 'nullable|image|mimes:jpeg,jpg,png,gif,svg|max:2048',
        ]);

        if(request()->file('profile_photo')){
            $user_image = request()->file('profile_photo');
            $file_name = $user_image->getClientOriginalName();
            $user_pic = Storage::disk('do_spaces')->putFileAs('user/profile',$user_image,time().'_'. $file_name, 'public');
            $profile_photo = $baseUrl.$user_pic;
        }

        $profile->update([
            'profile_photo' => !empty($profile_photo) ? $profile_photo : $profile->profile_photo,
        ]);

        return response()->json([
            'user' => $user,
            'success' => true,
            'message' => 'Profile photo updated successfully!'
        ], 200);


    }

    public function coverPhoto(){
        $user = auth()->user();
        $profile = auth()->user()->profile;
        $baseUrl = 'https://soccernity.sfo3.digitaloceanspaces.com/';

        request()->validate([
            'cover_photo' => 'nullable|image|mimes:jpeg,jpg,png,gif,svg|max:2048',
        ]);

        if(request()->file('cover_photo')){
            $cover_image = request()->file('cover_photo');
            $file_name = $cover_image->getClientOriginalName();
            $cover_pic = Storage::disk('do_spaces')->putFileAs('user/cover',$cover_image,time().'_'. $file_name, 'public');
            $cover_photo = $baseUrl.$cover_pic;
        }


        $profile->update([
            'cover_photo' => !empty($cover_photo) ? $cover_photo : $profile->cover_photo,
        ]);

        return response()->json([
            'user' => $user,
            'success' => true,
            'message' => 'Cover photo updated successfully!'
        ], 200);


    }

    public function deactivateAccount(){

        $user = auth()->user();

        request()->validate([
            'password' => 'required|string|max:50',
        ]);

        if(!Hash::check(request()->password, $user->password))
        {
           return response()->json([
               'success'=> false,
               'message'=> "Incorrect password!"]
               ,403);
        }

        $user->update([
            'account_status'=> 'deactivated',
            ]);

        if($user){
            return response()->json([
                'message' => 'User account has been deactivated successfully!',
                'user' => $user,
            ], 200);
        } else {
            return response()->json([
                'message' => 'Something went wrong!',
                'success' => false,
            ], 401);
        }

    }


}
