<?php

namespace App\Http\Controllers\Admin;

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
use Illuminate\Support\Facades\Validator;
use App\Repositories\Admin\AdminRepository;

class AdminController extends Controller
{
    private $adminRepository;

    public function __construct(AdminRepository $adminRepository)
    {
        $this->adminRepository = $adminRepository;

    }

    public function listUser(){

        return $this->adminRepository->listUser();
    }

    public function showUser($id){

        return $this->adminRepository->showUser($id);
    }

    public function listDeposit(){

        return $this->adminRepository->listDeposit();
    }

    public function showDeposit($id){

        return $this->adminRepository->showDeposit($id);
    }

    public function updateDepositStatus($id){

        return $this->adminRepository->updateDepositStatus($id);
    }

    public function addPlan(){

        return $this->adminRepository->addPlan();
    }

    public function listPlan(){

        return $this->adminRepository->listPlan();
    }

    public function deletePlan($id){

        return $this->adminRepository->deletePlan($id);
    }

    public function updatePlan($id){

        return $this->adminRepository->updatePlan($id);
    }

    public function listWithdrawal(){

        return $this->adminRepository->listWithdrawal();
    }

    public function showWithdrawal($id){

        return $this->adminRepository->showWithdrawal($id);
    }

    public function updateWithdrawalStatus($id){

        return $this->adminRepository->updateWithdrawalStatus($id);
    }

    public function listInvestment(){

        return $this->adminRepository->listInvestment();
    }

    public function showInvestment($id){

        return $this->adminRepository->showInvestment($id);
    }

    public function updateInvestmentStatus($id){

        return $this->adminRepository->updateInvestmentStatus($id);
    }

    public function listKYC(){

        return $this->adminRepository->listKYC();
    }

    public function updateKYC($id){

        return $this->adminRepository->updateKYC($id);
    }

}
