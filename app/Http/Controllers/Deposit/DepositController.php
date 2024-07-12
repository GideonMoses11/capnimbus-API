<?php

namespace App\Http\Controllers\Deposit;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Deposit\DepositRepository;

class DepositController extends Controller
{
    private $depositRepository;

    public function __construct(DepositRepository $depositRepository)
    {
        $this->depositRepository = $depositRepository;

    }

    public function save(){

        return $this->depositRepository->create();
    }

    public function find($id){

        return $this->depositRepository->show($id);
    }

    public function index(){

        return $this->depositRepository->index();
    }
}
