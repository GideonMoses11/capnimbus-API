<?php

namespace App\Http\Controllers\Withdrawal;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Withdrawal\WithdrawalRepository;

class WithdrawalController extends Controller
{
    private $withdrawalRepository;

    public function __construct(WithdrawalRepository $withdrawalRepository)
    {
        $this->withdrawalRepository = $withdrawalRepository;

    }

    public function save(){

        return $this->withdrawalRepository->create();
    }

    public function find($id){

        return $this->withdrawalRepository->show($id);
    }

    public function index(){

        return $this->withdrawalRepository->index();
    }
}
