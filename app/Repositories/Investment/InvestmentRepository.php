<?php

namespace App\Repositories\Investment;

use Carbon\Carbon;
use App\Models\Plan;
use App\Models\Investment;

class InvestmentRepository{

    public function create($id){

        $plan = Plan::find($id);

        if(!$plan){
            return response()->json([
                'message' => 'Plan not found',
                'status' => 404,
                'success' => false,
            ]);
        }

        request()->validate([
            'amount_usd' => 'numeric|required',
        ]);

        $userAccount = auth()->user()->account;

        $account_balance = $userAccount->usd_balance;

        if(request()->amount_usd < $plan->min_price){
            return response()->json([
                'message' => `minimum investment amount is: $plan->min_price`,
                'status' => 401,
                'success' => false,
            ]);
        }

        if(request()->amount_usd > $account_balance){
            return response()->json([
                'message' => 'Insufficient funds',
                'status' => 401,
                'success' => false,
            ]);
        }

        $investment = Investment::create([
            'amount_usd' => request()->amount_usd,
            'roi' => $plan->roi,
            'expiry_date' => Carbon::now()->addDays(3),
            'status' => 'in-progress',
            'user_id' => auth()->user()->id,
            'plan_id' => $plan->id
        ]);

        $new_bal = $account_balance - request()->amount_usd;

        $userAccount->update([
            'usd_balance' => $new_bal
        ]);

        if($investment){
            return response()->json([
                    'message' => 'investment created successfully!',
                    'status' => 201,
                    'success' => true,
                    'investment' => $investment,
                ]);
            } else {
                return response()->json([
                    'status' => 401,
                    'message' => 'Something went wrong!',
                    'success' => false,
                ]);
        }

    }

    public function index(){

        $investments = Investment::with('plan')->where('user_id', auth()->user()->id)->paginate();

        if($investments){
            return response()->json([
                    'message' => 'investments found successfully!',
                    'status' => 200,
                    'success' => true,
                    'investments' => $investments,
                ]);
            } else {
                return response()->json([
                    'status' => 401,
                    'message' => 'Something went wrong!',
                    'success' => false,
                ]);
        }
    }

    public function show($id){

        $investment = Investment::find($id);

        if(auth()->user()->id != $investment->user_id){
            return response()->json([
                'status' => 402,
                'message' => 'Bad Request',
                'success' => false,
            ]);
        }
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



}
