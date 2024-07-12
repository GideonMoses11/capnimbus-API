<?php

namespace App\Repositories\Deposit;

use App\Models\Deposit;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class DepositRepository{

    public function create(){

        request()->validate([
            'amount_usd' => 'numeric|required',
        ]);

        $deposit = Deposit::create([
            'reference' => $this->generateUniqueReference(),
            'amount_usd' => request()->amount_usd,
            'user_id' => auth()->user()->id
        ]);

        if($deposit){
            return response()->json([
                    'message' => 'deposit created successfully!',
                    'status' => 201,
                    'success' => true,
                    'deposit' => $deposit,
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

        $deposits = Deposit::query()->where('user_id', auth()->user()->id);

        $reference = request()->query('reference');
        $status = request()->query('status');

        $query = $deposits
                ->when($reference, function($query, $reference){
                    return $query->where('reference', $reference);
                })
                ->when($status, function($query, $status){
                    return $query->where('status', $status);
                })
                ->latest()
                ->paginate();

        return $query;


        // ->paginate();
        if($deposits){
            return response()->json([
                    'message' => 'deposits found successfully!',
                    'status' => 200,
                    'success' => true,
                    'deposits' => $deposits,
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
        $deposit = Deposit::find($id);
        if(auth()->user()->id != $deposit->user_id){
            return response()->json([
                'status' => 402,
                'message' => 'Bad Request',
                'success' => false,
            ]);
        }
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

    private function generateUniqueReference()
    {
        do {
            $reference = strtoupper(Str::random(10));
        } while (Deposit::where('reference', $reference)->exists());

        return $reference;
    }



}
