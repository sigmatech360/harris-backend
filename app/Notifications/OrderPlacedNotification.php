<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\DatabaseMessage;
use App\Services\FirebaseService;

class OrderPlacedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $order;

    public function __construct($order)
    {
        $this->order = $order;
    }

    // This will store it in DB
    public function via($notifiable)
    {
        return ['database'];
    }

    // Optional: if you want to also email
    // public function toMail($notifiable) { ... }

    public function toDatabase($notifiable)
    {
        try{
        // Send FCM here
        $devices = $notifiable->userDevices;
        $firebase = new FirebaseService();
        
        if(!empty($devices)){
            foreach($devices as $device){
                 $token = $device->device_token;
                \Log::info("Sending FCM to token: {$token}");
                if ($token) {
                    $validation = $firebase->validateToken($token);
                    if($validation == true){
                        $firebase->sendToToken(
                            $device->device_token,
                            'Order Placed',
                            "Order #{$this->order->id} was placed.",
                            ['order_id' => $this->order->id]
                        );
                    }else{
                         \Log::warning("Invalid FCM token, skipping: $token");
                    }

                }
            }
        }
        }catch(\Exception $e){
            \Log::info('FCM notification failed in OrderPlacedNotification! '.$e->getMessage());
        }
        
        return [
            'title' => 'Order Placed',
            'body'  => "Order #{$this->order->id} has been placed successfully.",
            'order_id' => $this->order->id,
        ];
    }
}
