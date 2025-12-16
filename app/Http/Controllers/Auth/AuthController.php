<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Device;
use App\Models\ForgetOtp;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Mail\SimpleEmail;
use App\Traits\ApiResponser;

class AuthController extends Controller
{
    use ApiResponser;
    public function register(Request $request)
    {
        $msg = "";
        $validate = validator::make(
            $request->all(),
            [
                'name' => 'required|string|max:255',
                'email' => 'required|unique:users',
                'password' => 'required|confirmed',
                'password_confirmation' => 'required',
            ]
        );
        if ($validate->fails()) {
            $response =
                [
                    'status' => false,
                    'message' => $validate->errors()
                ];
            return response()->json($response, 200);
        }
 
 
        // Create a new User
        $input = $request->all();
        $input['password'] = $input['password'];
        $input['user_role'] = 2;
        $user = User::create($input);
        $msg = "User Registered Successfully";
        
        if($request->has('device_token')){
            Device::create([
                'user_id' => $user->id,
                'device_token' => $request->device_token,
            ]);
        }
        
        $response = [
            'status' => true,
            'data' => $user,
            'message' => $msg,
        ];
 
        return response()->json($response, 200);
    }
 
    public function login(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        
        if(!empty($user)){
        $msg = "";
        if ($user->user_role == 1) {
            $msg = "Admin Login SuccessFully";
        } else if ($user->user_role == 2) {
            $msg = "User Login SuccessFully";
        }
        if (!Auth::attempt([
            'email'=>$request->email, 
            'password'=>$request->password
            ])) {
            $response = [
                'status' => false,
                'message' => 'Unauthorized User!'
            ];
 
            return response()->json($response, 200);
        }
 
        // Authentication successful
        $success['token'] = $user->createToken('My Virtual Pi')->plainTextToken;
        $success['email'] = $user->email;
 
        // $this->tokencheck($success['token']);
        
        // add user device token, if changed
        if($request->has('device_token')){
            $isExists = Device::where('user_id', $user->id)->where('device_token', $request->device_token)->exists();
            if(!$isExists){
                Device::create([
                    'user_id' => $user->id,
                    'device_token' => $request->device_token,
                    ]);
            }
        }
        $response = [
            'status' => true,
            'data' => $success,
            'message' => $msg,
        ];
 
        return response()->json($response, 200);
        }
        else{
            $response = [
                'status' => false,
                'message' => 'User Not found!'
            ];
 
            return response()->json($response, 200);
        }
    }

    public function logout(Request $request)
    {
        $user = Auth::user();
        // delete user device toke
        if($request->has('device_token')){
            $userDevice = Device::where('user_id', $user->id)->where('device_token', $request->device_token)->first();
            if($userDevice){
                $userDevice->delete();
            }
        }
        $user->currentAccessToken()->delete();

        return $this->success(null, 'Logout Successfully');
    }

    public function forgot_password(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
        ]);

        if ($validator->fails()) {
            return Response(['status' => false, 'message' => $validator->errors()], 401);
        }

        $opt = rand(10000, 99990);

        // dd($opt);
        // $currentDate = Carbon::now()->format('d-M-Y');

        $check_user = User::where('email', $request->email)->select('id', 'email')->first();
        // dd($check_user);
        if (!isset($check_user)) {
            return response()->json(['status' => false, 'message' => "User not found"]);
        }

        ForgetOtp::updateOrCreate(
            ['email' => $request->email],
            [
                'email'     => $request->email,
                'otp'      => $opt
            ]
        );

        $data = [
            'email' => $request->email,
            'user' => $check_user,
            'details' => [
                'heading' => 'Forget Password Opt',
                'content' => 'Your forget password otp : ' . $opt,
                'WebsiteName' => 'Irving Segal'
            ]

        ];
        $datamail = Mail::send('mail.sendopt', $data, function ($message) use ($data) {
            $message->to($data['email'])->subject($data['details']['heading']);
        });

        if (!$datamail) {
            return response()->json(['status' => false, 'message' => 'Failed to send email']);
        }


        return response()->json(['status' => true, 'data' => $check_user, 'message' => "OTP send on your email address"]);
    }
    public function otp_verification(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'otp' => 'required',
        ]);

        if ($validator->fails()) {
            return Response(['status' => false, 'message' => $validator->errors()], 401);
        }

        $user = ForgetOtp::where(['email' => $request->email, 'otp' => $request->otp])->first();
        if (!isset($user)) {
            return response()->json(['status' => false, 'message' => "Otp is wrong"]);
        }
        $data['email'] = $user->email;
        $data['code'] = $user->otp;

        return response()->json(['status' => true, 'data' => $data, 'message' => "Otp verified successfully"]);
    }

    public function reset_password(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required|confirmed',
        ]);

        if ($validator->fails()) {
            return Response(['status' => false, 'message' => $validator->errors()], 401);
        }

        // dd(uniqid());
        $get_otp = ForgetOtp::where(['email' => $request->email, 'otp' => $request->otp])->first();
        if (!isset($get_otp)) {
            return response()->json(['status' => false, 'message' => "Otp is wrong"]);
        } else {
            $get_otp->delete();
        }
        $user = User::where('email', $request->email)->first();
        $user->password = bcrypt($request['password']);

        if ($user->save()) {
            return response()->json(['status' => true, 'message' => "Password Reset"]);
        }
    }
    
    public function changePass(Request $request)
    {
        $msg = "";
        $validate = validator::make(
            $request->all(),
            [
                'current_password' => 'required',
                'password' => 'required|confirmed',
                'password_confirmation' => 'required',
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
        
        $user = auth()->user();
        if($user !== null){
            if(!Hash::check($request->current_password,$user->password)){
                $response = [
                    'status' => false,
                    'data' => null,
                    'message' => 'Invalid current password!',
                ];
                return response()->json($response, 200);
            }
            $user->password = $request->password;
            $user->save();
            $response = [
                'status' => true,
                'data' => null,
                'message' => 'Password updated successfully',
            ];
    
            return response()->json($response, 200);    
        }else{
            $response =
                [
                    'status' => false,
                    'message' => 'An error occurred!'
                ];
            return response()->json($response, 400);
        }
        
    }

    public function checkLogin(){
        if(Auth::check()){
            return response()->json(['status' => true, 'message' => "Loged in"]);
        }else{
            return response()->json(['status' => true, 'message' => "Loged out"]);
        }
    }

}
