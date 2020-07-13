<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use  App\Http\Requests\Api\VerficationCodeoRequst;
use Overtrue\EasySms\EasySms;

class VerificationCodesController extends Controller
{


    public function store(VerficationCodeoRequst $requst,EasySms $easySms){
        $phone=$requst->phone;


        $validated=$requst->validated();
        return $this->response->array(['test_message'=>'test code']);
    }
}
