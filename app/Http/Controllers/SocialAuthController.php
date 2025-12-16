<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Str;
use App\Models\Device;
use Illuminate\Support\Facades\Validator;
use Kreait\Firebase\Exception\Auth\FailedToVerifyToken;

class SocialAuthController extends Controller
{
//     public function login(Request $request)
//     {
//         $validate = validator::make(
//             $request->all(),
//             [
//               'token' => 'required',
//               'provider' => 'required|in:google,apple'
//             ]
//         );
//         if ($validate->fails()) {
//             $response =
//                 [
//                     'status' => false,
//                     'message' => $validate->errors()
//                 ];
//             return response()->json($response, 400);
//         }

//         $firebaseAuth = app('firebase.auth');
//         // try {
//             $verifiedIdToken = $firebaseAuth->verifyIdToken($request->token);
//         // } catch (\Kreait\Firebase\Exception\Auth\FailedToVerifyToken $e) {
//             // \Log::error('Firebase token verification failed: '.$e->getMessage());
// //             return response()->json(['error' => 'Invalid token. '.$e->getMessage()], 401);
// // }
//         $uid = $verifiedIdToken->claims()->get('sub');
//         $firebaseUser = $firebaseAuth->getUser($uid);

//         $email = $firebaseUser->email;

//         $user = User::firstOrCreate(
//             ['email' => $email],
//             ['name' => $firebaseUser->displayName ?? 'Unknown']
//         );

//         // Create Sanctum token
//         $token = $user->createToken('mobile_app')->plainTextToken;

//         return response()->json([
//             'status' => true,
//             'token' => $token,
//             'user' => $user,
//             'message' => 'Login token created successfully'
//         ],200);
//     }

    public function login(Request $request)
    {
        $validate = validator::make(
            $request->all(),
            [
              'token' => 'required',
              'provider' => 'required|in:google.com,apple.com'
            ]
        );
        if ($validate->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validate->errors()
            ], 400);
        }
    
        $firebaseAuth = app('firebase.auth');
        try {
            $verifiedIdToken = $firebaseAuth->verifyIdToken($request->token);
        } catch (FailedToVerifyToken $e) {
            \Log::error('Token verification failed: ' . $e->getMessage());
            return response()->json(['error' => 'Invalid token'], 401);
        } catch (\Exception $e) {
            \Log::error('Unexpected error: ' . $e->getMessage());
            return response()->json(['error' => 'Server error'], 500);
        }
    
        $uid = $verifiedIdToken->claims()->get('sub');
        $firebaseUser = $firebaseAuth->getUser($uid);
    
        $email = $firebaseUser->email;
    
        $user = User::firstOrCreate(
            ['email' => $email],
            [
            'name' => $firebaseUser->displayName ?? 'Unknown',
            'password' => Hash::make(Str::random(24)) 
            ]
        );
    
        $token = $user->createToken('Google Sign-in')->plainTextToken;
        
        if($request->has('device_token')){
            $isExists = Device::where('user_id', $user->id)->where('device_token', $request->device_token)->exists();
            if(!$isExists){
                Device::create([
                    'user_id' => $user->id,
                    'device_token' => $request->device_token,
                    ]);
            }
        }
    
        return response()->json([
            'status' => true,
            'token' => $token,
            'user' => $user,
            'firebaseUser' => $firebaseUser,
            'message' => 'Successfully logged in'
        ], 200);
    }

}
