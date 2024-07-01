<?php

namespace App\Http\Controllers\Operation;

use App\actions\WalletOperationAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Wallet\AskedDepositRequest;
use App\Http\Requests\Wallet\DepositRequest;
use App\Http\Requests\Wallet\WithdrawRequest;
use App\Http\Responses\Response;
use App\Services\Operation\StudentAndTeacherOperationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WalletOperationController extends Controller
{

    private WalletOperationAction $walletOperationAction;
    public function __construct(WalletOperationAction $walletOperationAction){
        $this->walletOperationAction = $walletOperationAction;
    }

    //api to add deposit by admin
    public function deposit(DepositRequest $request) : JsonResponse
    {
        $data = [];
        try {
            $data = $this->walletOperationAction->deposit($request);
            return Response::Success($data['data'],$data['message'],$data['code']);

        }catch (\Throwable $th){
            $message=$th->getMessage();
            return Response::Error($data,$message);

        }

    }

    //api to send a request to deposit money
    public function asked_deposit(AskedDepositRequest $request) : JsonResponse
    {
        $data = [];
        try {
            $data = $this->walletOperationAction->asked_deposit($request);
            return Response::Success($data['data'],$data['message'],$data['code']);

        }catch (\Throwable $th){
            $message=$th->getMessage();
            return Response::Error($data,$message);

        }

    }

    //api to Withdraw funds from the wallet
    public function withdraw(WithdrawRequest $request) : JsonResponse
    {
        $data = [];
        try {
            $data = $this->walletOperationAction->withdraw($request);
            return Response::Success($data['data'],$data['message'],$data['code']);

        }catch (\Throwable $th){
            $message=$th->getMessage();
            return Response::Error($data,$message);

        }

    }

    // api to Display the current balance
    public function balance() : JsonResponse
    {
        $data = [];
        try {
            $data = $this->walletOperationAction->balance();
            return Response::Success($data['data'],$data['message'],$data['code']);

        }catch (\Throwable $th){
            $message=$th->getMessage();
            return Response::Error($data,$message);

        }

    }

    //function to View transaction history
    public function transactions() : JsonResponse
    {
        $data = [];
        try {
            $data = $this->walletOperationAction->transactions();
            return Response::Success($data['data'],$data['message'],$data['code']);

        }catch (\Throwable $th){
            $message=$th->getMessage();
            return Response::Error($data,$message);

        }

    }
}
