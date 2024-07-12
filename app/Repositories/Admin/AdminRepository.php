<?php

namespace App\Repositories\Admin;

use App\Models\Kyc;
use App\Models\Plan;
use App\Models\User;
use App\Models\Deposit;
use App\Models\Investment;
use App\Models\Withdrawal;

class AdminRepository{

    public function listUser(){
        $users = User::query();
        $role = request()->query('role');
        $username = request()->query('username');
        $status = request()->query('status');
        $email = request()->query('email');

        $query = $users
        ->where('username', 'LIKE', "%{$username}%")
        ->where('email', 'LIKE', "%{$email}%")
        ->when($role, function($query, $role){
            return $query->where('role', $role);
        })
        ->when($status, function($query, $status){
            return $query->where('status', $status);
        })
        ->latest()->paginate(10);

        return $query;
    }

    public function showUser($id)
    {
        $user = User::find($id);

        if($user){
            return response()->json([
                    'message' => 'user found successfully!',
                    'status' => 200,
                    'success' => true,
                    'user' => $user
                ]);
            } else {
                return response()->json([
                    'status' => 401,
                    'message' => 'Something went wrong!',
                    'success' => false,

                ]);
        }
    }

    public function listDeposit(){

        $deposits = Deposit::query();
        $status = request()->query('status');
        $reference = request()->query('reference');

        $query = $deposits
        ->when($reference, function($query, $reference){
            return $query->where('reference', $reference);
        })
        ->when($status, function($query, $status){
            return $query->where('status', $status);
        })
        ->latest()->paginate(10);

        return $query;
    }

    public function showDeposit($id){

        $deposit = Deposit::find($id);

        if($deposit){
            return response()->json([
                'success'=> true,
                'message'=> "deposit has been found successfully!",
                'deposit'=> $deposit,
             ],200);
            }else {
                return response()->json([
                    'status' => 401,
                    'message' => 'Something went wrong!',
                    'success' => false,
                ]);
            }
    }

    public function updateDepositStatus($id){

        $deposit = Deposit::find($id);

        request()->validate([
            'status' => 'required|string|max:50',
        ]);

        $deposit->update([
            'status'=>request()->status
        ]);

        if($deposit->status == 'approved'){

            $recipient = User::where('id', $deposit->user_id)->first();

            $usd_balance = $recipient->account->usd_balance += $deposit->amount_usd;

            $recipient->account->update([
                'usd_balance' => $usd_balance,
            ]);

        }

        if($deposit){
            return response()->json([
                'success'=> true,
                'message'=> "Deposit status has been updated successfully!",
                'deposit'=> $deposit,
            ],201);
        }else {
            return response()->json([
                'status' => 401,
                'message' => 'Something went wrong!',
                'success' => false,
            ]);
        }
    }

    public function listWithdrawal(){

        $withdrawals = Withdrawal::query();
        $status = request()->query('status');
        $reference = request()->query('reference');

        $query = $withdrawals
        ->when($reference, function($query, $reference){
            return $query->where('reference', $reference);
        })
        ->when($status, function($query, $status){
            return $query->where('status', $status);
        })
        ->latest()->paginate(10);

        return $query;
    }

    public function showWithdrawal($id){

        $withdrawal = Withdrawal::find($id);

        if($withdrawal){
            return response()->json([
                'success'=> true,
                'message'=> "withdrawal has been found successfully!",
                'withdrawal'=> $withdrawal,
             ],200);
            }else {
                return response()->json([
                    'status' => 401,
                    'message' => 'Something went wrong!',
                    'success' => false,
                ]);
            }
    }

    public function updateWithdrawalStatus($id){

        $withdrawal = Withdrawal::find($id);

        request()->validate([
            'status' => 'required|string|max:50',
        ]);

        $withdrawal->update([
            'status'=>request()->status
        ]);

        if($withdrawal->status == 'approved'){

            $recipient = User::where('id', $withdrawal->user_id)->first();

            $usd_balance = $recipient->account->usd_balance -= $withdrawal->amount_usd;

            $recipient->account->update([
                'usd_balance' => $usd_balance,
            ]);

        }

        if($withdrawal){
            return response()->json([
                'success'=> true,
                'message'=> "Withdrawal status has been updated successfully!",
                'withdrawal'=> $withdrawal,
            ],201);
        }else {
            return response()->json([
                'status' => 401,
                'message' => 'Something went wrong!',
                'success' => false,
            ]);
        }
    }

    public function addPlan(){

        request()->validate([
            'name' => 'required|string',
            'min_price' => 'required|numeric',
            'max_price' => 'required|numeric',
            'roi' => 'required|numeric',
            'frequency' => 'required|string',
        ]);

        $plan = Plan::create([
            'name' => request()->name,
            'min_price' => request()->min_price,
            'max_price' => request()->max_price,
            'roi' => request()->roi,
            'frequency' => request()->frequency,
        ]);

        if($plan){
            return response()->json([
                'success'=> true,
                'message'=> "Plan has been added successfully!",
                'plan'=> $plan,
            ],201);
        }else {
            return response()->json([
                'status' => 401,
                'message' => 'Something went wrong!',
                'success' => false,
            ]);
        }
    }

    public function listPlan(){

        $plans = Plan::all();

        if($plans){
            return response()->json([
                'success'=> true,
                'message'=> "Plans have been found successfully!",
                'plans'=> $plans,
            ],201);
        }else {
            return response()->json([
                'status' => 401,
                'message' => 'Something went wrong!',
                'success' => false,
            ]);
        }
    }

    public function deletePlan($id){

        $plan = Plan::find($id);

        $plan->delete();

        if($plan){
            return response()->json([
                'success'=> true,
                'message'=> "plan has been deleted successfully!",
                'plan'=> $plan,
             ],200);
            }else {
                return response()->json([
                    'status' => 401,
                    'message' => 'Something went wrong!',
                    'success' => false,
                ]);
            }
    }

    public function updatePlan($id){

        $plan = Plan::find($id);

        request()->validate([
            'name' => 'nullable|string',
            'min_price' => 'nullable|numeric',
            'max_price' => 'nullable|numeric',
            'roi' => 'nullable|numeric',
            'frequency' => 'nullable|string',
        ]);

        $plan->update([
            'name' => !empty(request()->name) ? request()->name : $plan->name,
            'min_price' => !empty(request()->min_price) ? request()->min_price : $plan->min_price,
            'max_price' => !empty(request()->max_price) ? request()->max_price : $plan->max_price,
            'roi' => !empty(request()->roi) ? request()->roi : $plan->roi,
            'frequency' => !empty(request()->frequency) ? request()->frequency : $plan->frequency,
        ]);


        if($plan){
            return response()->json([
                'success'=> true,
                'message'=> "plan has been updated successfully!",
                'plan'=> $plan,
             ],200);
            }else {
                return response()->json([
                    'status' => 401,
                    'message' => 'Something went wrong!',
                    'success' => false,
                ]);
            }
    }

    public function listKYC(){

        $kycs = Kyc::query();
        $status = request()->query('status');
        $wallet_address = request()->query('wallet_address');

        $query = $kycs
        ->when($wallet_address, function($query, $wallet_address){
            return $query->where('wallet_address', $wallet_address);
        })
        ->when($status, function($query, $status){
            return $query->where('status', $status);
        })
        ->latest()->paginate(10);

        return $query;
    }

    public function updateKYC($id){

        $kyc = Kyc::find($id);

        request()->validate([
            'status' => 'required|string|max:50',
        ]);

        $kyc->update([
            'status'=>request()->status
        ]);

        if($kyc){
            return response()->json([
                'success'=> true,
                'message'=> "kyc status has been updated successfully!",
                'kyc'=> $kyc,
            ],201);
        }else {
            return response()->json([
                'status' => 401,
                'message' => 'Something went wrong!',
                'success' => false,
            ]);
        }
    }

    public function listInvestment(){

        $investments = Investment::query()->with('plan', 'user');
        $status = request()->query('status');
        $amount_usd = request()->query('amount_usd');
        $roi = request()->query('roi');

        $query = $investments
            ->when($amount_usd, function($query, $amount_usd){
                return $query->where('amount_usd', $amount_usd);
            })
            ->when($roi, function($query, $roi){
                return $query->where('roi', $roi);
            })
            ->when($status, function($query, $status){
                return $query->where('status', $status);
            })->latest()->paginate(10);

        return $query;
    }

    public function showInvestment($id){

        $investment = Investment::with('plan', 'user')->find($id);

        if($investment){
            return response()->json([
                'success'=> true,
                'message'=> "investment has been found successfully!",
                'investment'=> $investment,
             ],200);
            }else {
                return response()->json([
                    'status' => 401,
                    'message' => 'Something went wrong!',
                    'success' => false,
                ]);
            }
    }

    public function updateInvestmentStatus($id){

        $investment = Investment::with('plan', 'user')->find($id);

        request()->validate([
            'status' => 'required|string|max:50',
        ]);

        $investment->update([
            'status'=>request()->status
        ]);

        if($investment->status == 'paid'){

            $recipient = User::where('id', $investment->user_id)->first();

            $profit = $investment->amount_usd + $investment->roi;

            $usd_balance = $recipient->account->usd_balance += $profit;

            $recipient->account->update([
                'usd_balance' => $usd_balance,
            ]);

            // $bonus = $investment->amount_usd * 0.5/100;

            // $upline = User::where('ref_code', $investment->user->upline_code)->first();

            // $upline_account = Account::where('user_id', $upline->id)->first();

            // $upline_ref_balance = $upline_account->referral_balance;

            // $top_up = $upline_ref_balance += $bonus;

            // $upline_account->update([
            //     'referral_balance' => $top_up
            // ]);

        }

        if($investment){
            return response()->json([
                'success'=> true,
                'message'=> "Investment status has been updated successfully!",
                'investment'=> $investment,
            ],201);
        }else {
            return response()->json([
                'status' => 401,
                'message' => 'Something went wrong!',
                'success' => false,
            ]);
        }
    }
}
