<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\DatabaseMessage;
use App\Services\FirebaseService;

class SocialMediaReportNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
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
                                $this->data['title'],
                                $this->data['body'],
                                ['order_id' => $this->data['order_id']]
                            );
                        }else{
                             \Log::warning("Invalid FCM token, skipping: $token");
                        }
        
                    }
                }
            }else{
                \Log::warning("not devices found while sending this notifications ".$this->data['title']);
            }
        }catch(\Exception $e){
            \Log::info('FCM notification failed in SocialMediaReportNotification! '.$e->getMessage());
        }

        return [
            'title' => $this->data['title'],
            'body'  =>  $this->data['body'],
            'order_id' =>  $this->data['order_id'],
        ];
    }
}
