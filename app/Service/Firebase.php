<?php

namespace App\Service;

class Firebase
{
    public static function sendNotify($deviceToken, $title = '', $msg = '')
    {
        $arrNotificationMessage = [
            'title' => $title,
            'text' => $msg,
            'sound' => 'mySound',
            'priority' => 'high',
        ];

        $extraData = [
            'any_extra_data' => 'any data',
        ];

        $ch = curl_init('https://fcm.googleapis.com/fcm/send');
        
        $server_key = admin_setting(config('static.firebase.server-key'));

        $header = ['Content-Type: application/json', 'Authorization: key='.$server_key];

        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POST, 0);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "{ \"notification\": " . json_encode($arrNotificationMessage) . ", \"data\":" . json_encode($extraData) . ", \"to\" : " . json_encode($deviceToken) . '}');

        $result = curl_exec($ch);

        \Log::critical('Service\Firebase.php: ' . json_encode($result));

        curl_close($ch);
        return true;
    }
}
