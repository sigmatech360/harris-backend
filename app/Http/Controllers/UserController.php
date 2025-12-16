<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Order;
use App\Models\Pricing;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Notifications\SocialMediaReportNotification;

class UserController extends Controller
{
    use ApiResponser;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::where('user_role','<>',1)->latest()->get();
            $response =
            [
                'status' => true,
                'data' => $users,
                'message' => 'All users'
            ];
            return response()->json($response, 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id=null)
     {  
        $id =  auth()->user()->user_role != 1 ? auth()->id() : $id;
        $user = User::where('user_role','<>',1)->where('id' ,$id)->first();

        if($user !== null){
            $response =
            [
                'status' => true,
                'data' => $user,
                'message' => 'user found'
            ];
            return response()->json($response, 200);
        }else{
            $response =
            [
                'status' => false,
                'message' => 'user not found!!'
            ];
            return response()->json($response, 400);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id=null)
    {  
        $id =  auth()->user()->user_role !== 1 ? auth()->id() : $id;
        $user = User::where('user_role','<>',1)->where('id' ,$id)->first();
        if($user !== null){
            $response =
            [
                'status' => true,
                'data' => $user,
                'message' => 'user found'
            ];
            return response()->json($response, 200);
        }else{
            $response =
            [
                'status' => false,
                'message' => 'user not found!!'
            ];
            return response()->json($response, 400);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id=null)
    {
        $id =  auth()->user()->user_role !== 1 ? auth()->id() : $id;
        $msg = "";
        $validate = validator::make(
            $request->except('email'),
            [
                'name' => 'required|string|max:255',
                'phone' => 'sometimes|string|max:20',
                'address' => 'sometimes|string',
                'city' => 'sometimes|string|max:150',
                'zip_code' => 'sometimes|string|max:10',
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
 
        $input = $request->except('email');
        if(!empty($request->image)){
            $extension = $request->file('image')->getClientOriginalExtension();
            $fileName = time(). '.' . $extension;
            $request->file('image')->move('images/users-profile/', $fileName);
            $input['image'] = 'images/users-profile/'.$fileName;
        }
        $user = User::where('user_role','<>',1)->where('id',$id)->first();
        if($user !== null){           
            $updated = $user->update($input);
            if(!$updated){
                $msg = "Failed to update!";
                $response = [
                    'status' => false,
                    'data' => $user,
                    'message' => $msg,
                ];
        
                return response()->json($response, 400);        
            }

            $msg = "User updated successfully";
            $response = [
                'status' => true,
                'data' => $user,
                'message' => $msg,
            ];
    
            return response()->json($response, 200);    
        }else{
            $response =
                [
                    'status' => false,
                    'message' => 'user not found !!'
                ];
            return response()->json($response, 400);
        }
        
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::where('user_role','<>',1)->where('id',$id)->first();
        if($user !== null){
            $user->delete();
            $response =
            [
                'status' => true,
                'message' => 'user Removed Successfully'
            ];
            return response()->json($response, 200);
        }else{
            $response =
            [
                'status' => false,
                'message' => 'user not found!!'
            ];
            return response()->json($response, 400);
        }
    }
    
    
     /**
     * Display the specified resource.
     */
     
    function myOrders($id=null){
        $order = null;
        if($id){
            $order = Order::where('id',$id)->where('user_id',auth()->id())->first();
            if(!$order){
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid order id',
                ], 200);
            }
            return response()->json([
                'status' => true,
                'message' => 'Order fetched successfully',
                'data' => $order
            ], 200);
            
        }else{
            $order = Order::where('user_id',auth()->id())->latest()->get();
        }
        
        return response()->json([
            'status' => true,
            'message' => 'All orders realted to authenticated user',
            'data' => $order
        ], 200);
    } 
    
    //admin 
    public function showAdmin()
     {  
        $user = User::where('user_role',1)->first();

        if($user !== null){
            $response =
            [
                'status' => true,
                'data' => $user,
                'message' => 'user found'
            ];
            return response()->json($response, 200);
        }else{
            $response =
            [
                'status' => false,
                'message' => 'user not found!!'
            ];
            return response()->json($response, 400);
        }
    }

    public function updateAdmin(Request $request)
    {
            $msg = "";
            $validate = validator::make(
                $request->except('email'),
                [
                    'name' => 'required|string|max:255',
                    'phone' => 'sometimes|string|max:20',
                    'address' => 'sometimes|string',
                    'city' => 'sometimes|string|max:150',
                    'zip_code' => 'sometimes|string|max:10',
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
     
            $input = $request->all();
            if(!empty($request->image)){
                $extension = $request->file('image')->getClientOriginalExtension();
                $fileName = time(). '.' . $extension;
                $request->file('image')->move('images/users-profile/', $fileName);
                $input['image'] = 'images/users-profile/'.$fileName;
            }
            $user = User::where('user_role',1)->first();
            if($user !== null){           
                $updated = $user->update($input);
                if(!$updated){
                    $msg = "Failed to update!";
                    $response = [
                        'status' => false,
                        'data' => $user,
                        'message' => $msg,
                    ];
            
                    return response()->json($response, 400);        
                }
    
                $msg = "Admin profile updated successfully";
                $response = [
                    'status' => true,
                    'data' => $user,
                    'message' => $msg,
                ];
        
                return response()->json($response, 200);    
            }else{
                $response =
                    [
                        'status' => false,
                        'message' => 'Admin profile not found !!'
                    ];
                return response()->json($response, 400);
            }
            
        }
        
    function allOrders(){
        $orders = Order::latest()->get();
        return response()->json([
            'status' => true,
            'message' => 'All orders',
            'data' => $orders
        ], 200);
    }     

    public function orderView(string $id)
    { 
        $order = Order::find($id);
        if(empty($order)){
            return response()->json([
                'status' => false,
                'message' => 'order not found'
            ], 200);
        }
        
        return response()->json([
                'status' => true,
                'data' => $order,
                'message' => 'Order found'
            ], 200);
    }
    
    public function orderUpdate(Request $request, string $id)
    {
        $validate = validator::make(
            $request->all(),
            [
                'is_social_media_report_sent' => 'required|in:0,1',
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

        $order = Order::find($id);
        if(empty($order)){
             return response()->json([
                    'status' => false,
                    'message' => 'order not found !!'
                ], 200);
        }
        $order->update(['is_social_media_report_sent'=>$request->is_social_media_report_sent]);
        return response()->json([
                'status' => true,
                'data' => $order,
                'message' => 'Order status updated successfully',
            ], 200);    
        
    }
    
    public function sendSocialMediaReport(Request $request, string $id)
    {
        $validate = Validator::make(
            $request->all(),
            [
                'email' => 'required|email',
                'pdf' => 'required|mimes:pdf'
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
        
        $pdfPath = storage_path('app/public/reports');
        if (!file_exists($pdfPath)) {
            mkdir($pdfPath, 0755, true);
        }
        
        $path=null;
        if($request->hasFile('pdf')){
                $extension = $request->file('pdf')->getClientOriginalExtension();
                $fileName = 'social_media_report_' .time(). '.' . $extension;
                $request->file('pdf')->move($pdfPath, $fileName);
                $path = "{$pdfPath}/{$fileName}";
            }
        
        $order = Order::where('id',$id)->where('has_social_media_report',1)->where('is_social_media_report_sent',0)->first();
        if(empty($order)){
             return response()->json([
                    'status' => false,
                    'message' => 'Ivalid order id or order does not have social media report or report has already sent'
                ], 200);
        }
        
         // Send email
        Mail::send('mail.report', [], function ($m) use ($request, $path) {
            $m->to($request->email)->subject('Social Media Report From My Virtual PI');
            $m->attach($path);
        });
       
        $order->update([
            'is_social_media_report_sent'=>1,
            'social_media_report_path'=>$path
        ]);
        
        
        $data = [
            'title' => 'Social Media Report Received',
            'body' => "The requested social media report in order# {$order->id} has been sent to this email {$request->email}.",
            'order_id' => $order->id,
            ];
        $user = User::find($order->user_id);
        if($user){
            $user->notify(new SocialMediaReportNotification($data));
        }
        
            
        return response()->json([
                'status' => true,
                'data' => $order,
                'message' => 'Social media report send to email successfully',
            ], 200);    
        
    }
    
    public function dashboardAnalytics(){
        $orders = Order::all();
        $orderTotal=0;
        foreach($orders as $order){
            $orderTotal+=$order->total;
        }
        $data = [
            'order_total' => number_format($orderTotal,2)
            ];
        return response()->json([
                'status' => true,
                'data' => $data,
                'message' => 'Dashboard analytics',
            ], 200);    
    }
    
    // pricing - only for admin
    public function getPricing(){
        $pricing = Pricing::get()->first();
        return response()->json([
                'status' => true,
                'data' => $pricing,
                'message' => 'Pricing get successfully',
            ], 200);    
    } 
    
    public function updatePricing(Request $request){
        $validate = validator::make(
            $request->all(),
            [
                'types' => 'required|array',
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
        $pricing = Pricing::get()->first();
        $pricing->update(['types'=>$request->types]);
        return response()->json([
                'status' => true,
                'data' => $pricing,
                'message' => 'Pricing updated successfully',
            ], 200);    
    }
    
    // notificatoins
    public function allNotifications() {
        $notifications = auth()->user()
            ->notifications()
            ->latest()
            ->get();
    
        return response()->json([
            'status' => true,
            'data' => $notifications,
            'message' => 'All notifications fetched successfully',
        ], 200);
    }
    
    public function unreadNotifications() {
        $unreadNotifications = auth()->user()
            ->unreadNotifications()
            ->latest()
            ->get();
    
        return response()->json([
            'status' => true,
            'data' => $unreadNotifications,
            'message' => 'All unread notifications fetched successfully',
        ], 200);
    }

    public function markAsRead($id) {
        $notification = auth()->user()->unreadNotifications->where('id', $id)->first();
        if($notification){
            $notification->markAsRead();
            return response()->json([
                'status' => true,
                'message' => 'Notification marked as read.',
            ], 200);  
        }
        return response()->json([
                'status' => false,
                'message' => 'Invalid notification id or already marked as read.',
            ], 200); 
    }
    public function markAllAsRead()
    {
        $user = auth()->user();
        $unreadNotifications = $user->unreadNotifications;
    
        if ($unreadNotifications->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'No unread notifications found.',
            ], 200);
        }
    
        $unreadNotifications->markAsRead();
    
        return response()->json([
            'status' => true,
            'message' => 'All notifications marked as read.',
        ], 200);
    }

       
}
