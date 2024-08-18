<?php

namespace App\Repositories\Withdrawal;

use App\Models\Deposit;
use App\Models\Withdrawal;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class WithdrawalRepository{

    public function create(){

        $user = auth()->user();

        if (!$user->kyc) {
            return response()->json([
                'status' => 403,
                'message' => 'KYC verification is required before you can withdraw.',
                'success' => false,
            ], 403);
        }

        request()->validate([
            'amount_usd' => 'numeric|required',
            'wallet_address' => 'string|nullable',
        ]);

        $userAccount = auth()->user()->account;

        $account_balance = $userAccount->usd_balance;

        $requested_amount = request()->amount_usd;

        // Calculate 5% charge
        $charge = $requested_amount * 0.05;
        $amount_after_charge = $requested_amount - $charge;

        if(request()->amount_usd > $account_balance){
            return response()->json([
                'message' => 'Insufficient funds',
                'status' => 401,
                'success' => false,
            ]);
        }

        $withdrawal = Withdrawal::create([
            'reference' => $this->generateUniqueReference(),
            'amount_usd' => $amount_after_charge,
            'wallet_address' => auth()->user()->kyc->wallet_address ?: request()->wallet_address,
            'user_id' => auth()->user()->id
        ]);

        if($withdrawal){
            return response()->json([
                    'message' => 'withdrawal initiated successfully!',
                    'status' => 201,
                    'success' => true,
                    'withdrawal' => $withdrawal,
                    'charge' => $charge,
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

        $withdrawals = Withdrawal::query()->where('user_id', auth()->user()->id);

        $reference = request()->query('reference');
        $status = request()->query('status');

        $query = $withdrawals
                ->when($reference, function($query, $reference){
                    return $query->where('reference', $reference);
                })
                ->when($status, function($query, $status){
                    return $query->where('status', $status);
                })
                ->latest()
                ->paginate();

        return $query;


        if($withdrawals){
            return response()->json([
                    'message' => 'withdrawals found successfully!',
                    'status' => 200,
                    'success' => true,
                    'withdrawals' => $withdrawals,
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
        $withdrawal = Withdrawal::find($id);
        if(auth()->user()->id != $withdrawal->user_id){
            return response()->json([
                'status' => 402,
                'message' => 'Bad Request',
                'success' => false,
            ]);
        }
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

    private function generateUniqueReference()
    {
        do {
            $reference = strtoupper(Str::random(10));
        } while (Withdrawal::where('reference', $reference)->exists());

        return $reference;
    }



}
