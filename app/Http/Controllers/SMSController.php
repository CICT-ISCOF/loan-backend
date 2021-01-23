<?php

namespace App\Http\Controllers;

use App\Models\SMS;
use Illuminate\Http\Request;

require __DIR__ . '../../../../vendor/autoload.php';

use Twilio\Rest\Client;



class SMSController extends Controller
{

    public function index()
    {
        //
    }


    public function store(Request $request)
    {
        $account_sid = 'ACecd20fbc83b92ecaa850fd158a7d73ae';
        $auth_token = 'cfa2f9591cf4b5e684425a996c785a9f';

        $twilio_number = "+17733668388";

        $client = new Client($account_sid, $auth_token);
        $recepient =  $request->input('recipient');
        $message = $request->input('message');


        $client->messages->create(

            $recepient,
            array(
                'from' => $twilio_number,
                'body' => $message
            )
        );

        return response('Message Sent', 200);
    }

    public function show(SMS $sMS)
    {
    }


    public function edit(SMS $sMS)
    {
        //
    }


    public function update(Request $request, SMS $sMS)
    {
        //
    }

    public function destroy(SMS $sMS)
    {
        //
    }
}
