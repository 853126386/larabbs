<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

class VerificationCodesController extends Controller
{


    public function store(VerficationCodeoRequst $requst,EasySms $easySms){
        $phone=$requst->phone;

        $validated=$requst->validated();
        return $this->response->array(['test_message'=>'test code']);
    }
}
