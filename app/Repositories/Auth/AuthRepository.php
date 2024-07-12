<?php

namespace App\Repositories\Auth;

use Throwable;
use Carbon\Carbon;
use App\Models\Kyc;
use App\Models\User;
// use App\Mail\VerifyMail;
// use App\Mail\RegisterMail;
use Ramsey\Uuid\Uuid;
use App\Models\Profile;
use App\Mail\WelcomeMail;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Mail\PasswordResetMail;
use App\Mail\ForgotPasswordMail;
use Illuminate\Support\Facades\DB;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;


class AuthRepository{

    public function signup(Request $request) {

        $request->validate([
            'username' => 'required|string|max:50|unique:users',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|min:5|max:15|confirmed',
            'password_confirmation' => 'required|string|min:5|max:15',
            'phone' => 'nullable|numeric',
            'country' => 'nullable|string',
        ]);

        $num_code = mt_rand(10000, 99999);

        $str_code = Str::random(6);

        $ref_code = $num_code.$str_code;

        $duplicate_code = User::where('ref_code', $ref_code)->first();

        if($duplicate_code){
            $ref_code = $num_code.$str_code;
        }

        $user = User::create([
            'username'=> $request->username,
            'password' => Hash::make($request->password),
            'email'=> $request->email,
            'phone'=> $request->phone,
            // 'role'=> 'user',
            'country'=> $request->country,
            'ref_code' => $ref_code,
            'upline_code' => $request->upline_code ? $request->upline_code : '',
        ]);

        $upline = User::where('ref_code', $user->upline_code)->first();

        if($upline){

            $user->update([
                'referrer_id' => $upline->id
            ]);

        }

        $user->account()->create([
            'user_id' => $user->id
        ]);

        return response()->json([
            'success' => true,
            'message' => 'User signed up Successfully',
            'data' => [
                'user' => $user
            ]
        ], 201);
    }


    public function signin(Request $request)
{
    $request->validate([
        'login'    => 'required',
        'password' => 'required',
    ]);

    $login_type = filter_var($request->input('login'), FILTER_VALIDATE_EMAIL)
        ? 'email'
        : 'username';

    $request->merge([
        $login_type => $request->input('login')
    ]);

    $login_credential = $request->input('login');

    if (!$token = Auth::attempt($request->only($login_type, 'password'))) {
        return response()->json([
            'error'   => 'Unauthorized',
            'success' => false,
            'status'  => 401,
            'message' => 'Invalid credentials'
        ], 401);
    }

    // Authentication successful, now check for banned user
    $banned_user = User::where(function ($q) use ($login_credential) {
        return $q->where('email', $login_credential)
            ->orWhere('username', $login_credential);
    })->where('status', 'banned')->first();

    // Check banned user
    if ($banned_user) {
        return response()->json(['message' => 'This account was banned temporarily!'], 402);
    }

    return $this->createNewToken($token);
}


    // public function signin(Request $request){

    //     $request->validate($request, [
    //         'login'    => 'required',
    //         'password' => 'required',
    //     ]);

    //     $login_type = filter_var($request->input('login'), FILTER_VALIDATE_EMAIL )
    //         ? 'email'
    //         : 'username';

    //     $request->merge([
    //         $login_type => $request->input('login')
    //     ]);

    //     $login_credential = $request->input('login');

    //     if (!$token = Auth::attempt($request->only($login_type, 'password'))){
    //         return response()->json([
    //             'error' => 'Unauthorized',
    //             'success' => false,
    //             'status' => 401,
    //             'message' => 'Invalid credentials'
    //             ], 401);
    //     }

    //     //banned user
    //     $banned_user = User::where(function($q) {
    //         return $q->where('email', request()->input('login'))
    //                  ->orWhere('username', request()->input('login'));
    //       })->where('status', 'banned')->first();

    //     //check banned user
    //     if($banned_user){
    //         return response()->json(['message' => 'This account was banned temporarily!'], 402);
    //     }

    //     //deactivated user
    //     // $deactivated_user = User::where(function($q) {
    //     //     return $q->where('email', request()->input('login'))
    //     //              ->orWhere('username', request()->input('login'));
    //     //   })->where('account_status', 'deactivated')->first();

    //     // //check deactivated user
    //     // if($deactivated_user){
    //     //     return response()->json(['message' => 'This account was deactivated temporarily!'], 403);
    //     // }

    //     return $this->createNewToken($token);
    // }

    protected function createNewToken($token){
        return response()->json([
            'token' => $token,
            'token_type' => 'bearer',
            // you dont fix maximium expired at here, it in config/auth.php
            'expires_in' => auth('api')->factory()->getTTL() * 120,
            'user' => User::where('id',auth()->user()->id)->first()
        ],200);
    }

    public function refresh() {
        return $this->createNewToken(auth()->refresh());
    }

    public function userAccount() {
        $user = User::where('id',auth()->user()->id)
        // ->with('profile')
        ->first();
        return response()->json($user,200);
    }

    public function signout() {
        auth()->logout();

        return response()->json(['message' => 'User successfully signed out']);
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

    public function forgotPassword(){
        // If email does not exist
        request()->validate([
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

            $isOtherToken = DB::table('password_reset_tokens')->where('email', $email);

            $url = "https://peppi.vercel.app/auth/reset-password?token=$token&email=$email";

            // $token = Str::random(10);
            if(!$isOtherToken->first()){
                $data = DB::table('password_reset_tokens')->insert([
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
            $isOtherToken = DB::table('password_reset_tokens')->where('email',request()->email);

            try {

                Mail::to($email)->send(new ForgotPasswordMail($email, $token, $user, $url));

             } catch (\Throwable $th) {
                //  throw $th;
             }

            return response()->json(['success'=> true,
            'message'=> "A reset link has been sent to your email"]);

    }

    public function resetPassword(){

        request()->validate([
            'email' => 'required|string|email',
            'password' => 'required|string|confirmed|min:5',
            'token' => 'required|string|min:10',
        ]);

        $check= DB::table('password_reset_tokens')->where('email',request()->email)
        ->where('token',request()->token);
        if(!$check->first())
        {
           return response()->json(['success'=> false, 'message'=> "Invalid Email or Token"],401);
        }
        $user = User::where('email',$check->first()->email)->update(['password'=>Hash::make(request()->password)]);
        $check->delete();
        return response()->json(['success'=> true, 'message'=> "Password Updated"],200);

    }

    public function updateProfileDetails(){

        $user = auth()->user();
        // $profile = auth()->user()->profile;

        request()->validate([
            'first_name' => 'nullable|string',
            'last_name' => 'nullable|string',
            'gender' => 'nullable|string|max:100',
            'address' => 'nullable|string|max:500',
            'country' => 'nullable|string|max:100',
        ]);

        $user->update([
            'first_name'=> !empty(request()->first_name) ? request()->first_name : $user->first_name,
            'last_name'=> !empty(request()->last_name) ? request()->last_name : $user->last_name,
            'gender'=> !empty(request()->gender) ? request()->gender : $user->gender,
            'address'=> !empty(request()->address) ? request()->address : $user->address,
            'country'=> !empty(request()->country) ? request()->country : $user->country,
        ]);

        return response()->json([
            'message' => 'User profile updated successfully!',
            'user' => $user,
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
        $baseUrl = 'https://peppy-stores.nyc3.digitaloceanspaces.com/';

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

    public function myReferrals(){

        $user = auth()->user();

        $referrals = $user->referrals()->latest()->paginate();

        if($referrals){
            return response()->json([
                'success'=> true,
                'message'=> "referrals found successfully",
                'referrals'=> $referrals,
            ],200);
        } else {
            return response()->json([
                'success'=> false,
                'message'=> "referrals not found...try again!",
            ],401);
        }
    }

    public function myReferrer(){

        $user = auth()->user();

        $referrer = $user->referrer()->latest()->paginate();

        if($referrer){
            return response()->json([
                'success'=> true,
                'message'=> "referrer found successfully",
                'referrer'=> $referrer,
            ],200);
        } else {
            return response()->json([
                'success'=> false,
                'message'=> "referrer not found...try again!",
            ],401);
        }
    }

    public function verifyKYC(){

        $user = auth()->user();

        request()->validate([
            'full_name' => 'required|string|max:50|unique:kycs',
            'gender' => 'nullable|string',
            'wallet_address' => 'nullable|string',
            'document' => 'nullable|image|mimes:jpeg,jpg,png,gif,svg|max:10000',
        ]);

        $kyc_photo = null;

        if (request()->file('document')) {
            $kycDoc = request()->file('document');
            $file_name = time() . '_' . $kycDoc->getClientOriginalName();
            $kyc_pic = $kycDoc->storeAs('public/kyc/docs', $file_name);
            $kyc_photo = Storage::url($kyc_pic);  // get the public URL
        }

        $kyc = Kyc::create([
            'full_name' => request()->full_name,
            'gender' => request()->gender,
            'wallet_address' => request()->wallet_address,
            'document' => $kyc_photo,
            'user_id' => $user->id,
        ]);

        return response()->json([
            'kyc' => $kyc,
            'success' => true,
            'message' => 'Kyc submitted successfully!'
        ], 200);


    }

    public function myKYC(){

        $kyc = auth()->user()->kyc;

        if($kyc){
            return response()->json([
                'success'=> true,
                'message'=> "kyc found successfully",
                'kyc'=> $kyc,
            ],200);
        } else {
            return response()->json([
                'success'=> false,
                'message'=> "kyc not found...try again!",
            ],401);
        }
    }
}
