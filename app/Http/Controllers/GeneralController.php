<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Traits\ApiResponser;

class GeneralController extends Controller
{
    use ApiResponser;
    public function contactForm(Request$request){

        $msg = "";
        $validate = validator::make(
            $request->all(),
            [
                'name' => 'required',
                'phone' => 'sometimes',
                'email' => 'required|email:strict',
                'reason' => 'sometimes',
                'message' => 'required',
            ]
        );
        if ($validate->fails()) {
            $response =
                [
                    'status' => false,
                    'message' => $validate->errors()
                ];
            return response()->json($response, 400);
        }

        $data = [
            'name' => $request->name,
            'phone' => $request->phone??'',
            'email' => $request->email,
            'reason' => $request->reason??'',
            'msg' => $request->message,
            'details' => [
                'title' => 'Query/Contact Form',
                'heading' => 'Query/Contact Form',
                // 'content' => $request->message,
                'WebsiteName' =>env('APP_NAME')
            ]

        ];
        $datamail = Mail::send('mail.sendContactEmail', $data, function ($message) use ($data) {
            $message->to('devjames166@gmail.com')->subject($data['details']['heading']);
        });

        if (!$datamail) {
            $response = [
                'status' => false, 
                'message' => 'Failed to send email'
            ];
            return response()->json($response,403);
        }

        $response = [
            'status' => true,
            'message' => 'Your query has been submitted successfully, we will contact you shortly',
        ];

        return response()->json($response, 200);
    }
}
