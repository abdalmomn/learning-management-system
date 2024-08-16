<?php

namespace App\actions;

use App\Models\Transaction;
use App\Models\User;
use App\Notifications\CheckTeacherMail;
use App\Notifications\SendAskDepositMail;
use App\Notifications\sendReplayDepositMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class WalletOperationAction
{

    //function to send a request to deposit money
    public function asked_deposit($request){
        $user =User::query()->where('id' ,Auth::id())->first();
        $admin = User::query()->where('id', 1)->first();
        $data = [];
        $data['amount'] = $request->amount;
        $data['name'] = $user->full_name;
        $data['user_id'] = $user->id;
        Notification::send($admin, new SendAskDepositMail($data));
        $message = __('strings.sending message successfully');
        $code = 200 ;

        return [
            'data' => $user->wallet ?? [],
            'message' => $message,
            'code' => $code,
        ];
    }

    //function to add money by admin
    public function deposit($request)
    {
        // التحقق من توقيع الرابط
        if ($request->hasValidSignature() && Auth::id() == 1) {
            // الحصول على القيم من الطلب
            $user_id = $request->query('user_id');
            $amount = $request->query('amount');

            // العثور على المستخدم
            $user = User::find($user_id);
            if (!is_null($user)) {
                // تحديث محفظة المستخدم
                $user->wallet += $amount;
                $user->save();

                // تسجيل المعاملة
                Transaction::create([
                    'user_id' => $user->id,
                    'type' => 'deposit',
                    'amount' => $amount,
                ]);

                // إرسال الإشعار
                Notification::send($user, new SendReplayDepositMail($amount));

                // رسالة النجاح
                $message = __('strings.Funds deposited successfully');
                $code = 200;
            } else {
                // رسالة الخطأ عند عدم العثور على المستخدم
                $message = __('strings.This user not found');
                $code = 404;
            }
        } else {
            // رسالة الخطأ عند عدم امتلاك الأذونات أو رابط غير صالح
            $message = __('strings.You do not have permission to deposit money or invalid link');
            $code = 403;
        }

        // إرجاع الرد
        return [
            'data' => $user->wallet ?? [],
            'message' => $message,
            'code' => $code,
        ];
    }

    //function to Withdraw funds from the wallet
    public function withdraw($request)
    {

        $id = Auth::id();
        $user = User::query()->where('id' , $id)->first();
        if ($user->wallet < $request->amount) {
            $message = __('strings.you can not withdraw money more than you have');
            $code = 401;
        }else {

            $user->wallet -= $request->amount;
            $user->save();

            // سجل المعاملة
            Transaction::create([
                'user_id' => $user->id,
                'type' => 'withdraw',
                'amount' => $request->amount,
            ]);

            $message = __('strings.Withdrawal completed successfully');
            $code = 200;

        }

        return [
            'data' => [],
            'message' => $message,
            'code' => $code,
        ];
    }

    //function to Display the current balance
    public function balance()
    {
        $id = Auth::id();
        $user = User::query()->where('id' , $id)->first();
        return [
            'data' => $user->wallet,
            'message' => __('strings.Getting your wallet successfully'),
            'code' => 200,
        ];
    }

    //function to View transaction history
    public function transactions()
    {
        $id = Auth::id();
        $transactions = Transaction::query()->where('user_id', $id)->get();

        return [
            'data' => $transactions,
            'message' => __('strings.Getting all your transactions successfully'),
            'code' => 200,
        ];
    }





}
