<?php

// Your Firebase Cloud Messaging server key
//$serverKey = 'AAAAj6JYo_A:APA91bFvD_XV2qLXUSEZAzX8AFQxae0qn6hwxN9pIjWhoiYa-gxtlkOLwc-Zm-bZtPCvpWfQl-f8ZkHJN_9XR-7_M7EJcVjyvC7b_pZLN7QiuREUaHeWWP-cKXB8FQM1KUVZ9Qb-9NTr';
$serverKey = 'AAAAj6JYo_A:APA91bGN7D9-ri8lxeWG3LHdDCN4L98SNk8WfaxcHqNZ3vjz_bP3ZSeUXIaAxALylNYgMuwHN8ACWsVxUxUfmgq6GUAR4rbmyDDSG_lqa4puSZmSfluxBmB9m6wVQjd0YsXykKhB74-x';
// The device registration token you want to send the message to
$deviceToken = 'euHgZESrSmm0w93Xpr2fqI:APA91bG9PK1VUW_vBcZibYs_mLGYdttozLIGByzbYUdMlpCwTnXp-voAhCSWmVZJ9A3pJ6v3NFt6ZeWUmv4ppgFaaWO8OLrxHqjdIgmSEPxK_QBVUCjF5u23kSox7-mmBZfxEiWedumN';

// FCM endpoint
$url = 'https://fcm.googleapis.com/v1/projects/chargingstation-9d4be/messages:send';

// Message payload
$message = [
    'message' => [
        'token' => $deviceToken,
        'notification' => [
            'title' => 'Test Notification',
            'body' => 'This is a test notification from PHP',
        ],
    ],
];

// Headers
$headers = [
    'Content-Type: application/json',
    'Authorization: Bearer ' . $serverKey,
];

// Initialize cURL
$ch = curl_init();

// Set cURL options
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Use only for local testing

// Set payload
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($message));

// Execute cURL request
$response = curl_exec($ch);

// Check for errors
if ($response === false) {
    echo 'cURL error: ' . curl_error($ch);
} else {
    // Print response
    echo 'Response: ' . $response;
}

// Close cURL
curl_close($ch);
