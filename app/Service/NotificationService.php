<?php
namespace App\Service;

use App\Models\Notification;
use App\Models\NotificationUser;
use App\Models\NotificationAdmin;
use App\Models\User;
use App\Models\Admin;

use App\Service\Firebase;

class NotificationService
{
    /*
        Store all user related notification
    */
    public static function storeUserNotification($title,$message, $user_ids = []){

        $notification = Notification::create([
            'title' =>$title,
            'message' => $message
        ]);

        if(!empty($user_ids)){
            foreach ($user_ids as $key => $ids) {
                NotificationUser::create([
                    'user_id' => $ids,
                    'notification_id' => $notification->id,
                ]);
            }
        }

    }

    /*
        Store all Admins and their restaurant,seller related notification
    */
    public static function storeAdminNotification($title,$message, $admin_ids = []){

        $notification = Notification::create([
            'title' =>$title,
            'message' => $message
        ]);

        if(!empty($admin_ids)){
            foreach ($admin_ids as $key => $ids) {
                NotificationAdmin::create([
                    'admin_id' => $ids,
                    'notification_id' => $notification->id,
                ]);
            }
        }

    }

    /*
        Send all user push notification and save notification.
    */
    public static function saveAndsendUserPushNotification($title, $message, $ids){
        
        if(empty($ids)) return false;       //rtun if ids empty

        /* Send firebase notification */
        if(admin_setting(config('static.firebase.api-key'))){
            $user_list = User::whereIn('id', $ids)->get();
            if(!empty($user_list)){
                foreach ($user_list as $key => $user) {
                    if(!empty($user->firebase_token))   Firebase::sendNotify($user->firebase_token, $title, $message);     // push firebase notification
                }    
            }
        }


        // store all user related notification
        Self::storeUserNotification($title, $message, $ids);

        return true;
    }

    
    /*
        Send all user push notification and save notification.
    */
    public static function saveAndsendAdminPushNotification($title, $message, $ids){
        
        if(empty($ids)) return false;       //rtun if ids empty

        /* Send firebase notification */
        if(admin_setting(config('static.firebase.api-key'))){
            $user_list = Admin::whereIn('id', $ids)->get();
            if(!empty($user_list)){
                foreach ($user_list as $key => $user) {
                    if(!empty($user->firebase_token))   Firebase::sendNotify($user->firebase_token, $title, $message);     // push firebase notification
                }    
            }
        }


        // store all user related notification
        Self::storeAdminNotification($title, $message, $ids);

        return true;
    }

    




}
