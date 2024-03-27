<?php 
require_once "db_connection.php";	

$sql = "SELECT registration_info.*, messenger.* FROM `registration_info` LEFT JOIN `messenger` ON `messenger`.`peer_id` = `registration_info`.`id` WHERE new=1 and user_notified=0";

$result = $conn->query($sql);

while($row = $result->fetch_assoc()): 
		$counter++;
echo $row['firebase_token']."<BR>";

$message = 'You have new message from a user.';

$title = 'Conti Sibiu Charging Stations';
$token = 'euHgZESrSmm0w93Xpr2fqI:APA91bG9PK1VUW_vBcZibYs_mLGYdttozLIGByzbYUdMlpCwTnXp-voAhCSWmVZJ9A3pJ6v3NFt6ZeWUmv4ppgFaaWO8OLrxHqjdIgmSEPxK_QBVUCjF5u23kSox7-mmBZfxEiWedumN';
//$token = 'f_cwxqPXQzCqiY5mn4KxbV:APA91bF1azlOw8jxUNgiQmcNCNaz3-Or2Xfso70wj2BWoMfFCl36FChw1sEZocsHQmP-5bLi1ZplPBFdwkCNv3NBt7pM5lzBbf7X_xSP4ppad37HLm-SUZQqoZqaaD30jDGeIQePNZ0K';


$tokens = array($row['firebase_token']);
$message_complete = array("body" => $message, "title" => $title);
$message_status = send_notification($tokens, $message_complete);
echo $message_status;

$sql_notified = "UPDATE messenger SET user_notified = 1 WHERE peer_id =".$row[peer_id]." AND user_notified = 0;";
$result_notified = $conn->query($sql_notified);


/*

$message = 'You have new message from a user.';
$title = 'Conti Sibiu Charging Stations';
$token = array($row['firebase_token']);
$message_complete = array("body" => $message, "title" => $title);
$message_status = send_notification($tokens, $message_complete);
echo $message_status;
*/
endwhile; 

/*
$message = 'You have new message from a user.';

$title = 'Conti Sibiu Charging Stations';
$token = 'euHgZESrSmm0w93Xpr2fqI:APA91bG9PK1VUW_vBcZibYs_mLGYdttozLIGByzbYUdMlpCwTnXp-voAhCSWmVZJ9A3pJ6v3NFt6ZeWUmv4ppgFaaWO8OLrxHqjdIgmSEPxK_QBVUCjF5u23kSox7-mmBZfxEiWedumN';
//$token = 'f_cwxqPXQzCqiY5mn4KxbV:APA91bF1azlOw8jxUNgiQmcNCNaz3-Or2Xfso70wj2BWoMfFCl36FChw1sEZocsHQmP-5bLi1ZplPBFdwkCNv3NBt7pM5lzBbf7X_xSP4ppad37HLm-SUZQqoZqaaD30jDGeIQePNZ0K';


$tokens = array($token);
$message_complete = array("body" => $message, "title" => $title);
$message_status = send_notification($tokens, $message_complete);
echo $message_status;
*/
function send_notification ($tokens, $message_complete)
{
    $url = 'https://fcm.googleapis.com/fcm/send';
    $fields = array(
        'registration_ids' => $tokens,
        'notification' => $message_complete
    );

    $headers = array(
        'Authorization:key = AAAAMJgNQZ8:APA91bGl0kXU80mB5EEH8-jNk4jjYFVGgFKDska6eSPDJSckh-gYr4r9lowFDJnH0kkCrCT9eCVmEL034GKjPCihIiNDmUpm_tOikRDma5ZcsLIwnSLiPa3WRUUzuudLa8fZ0gYZ0r6c', //Change API KEY HERE
        'Content-Type: application/json'
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);  
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
    
    $result = curl_exec($ch);           

    if ($result === FALSE) {
        die('Curl failed: ' . curl_error($ch));
    }
    curl_close($ch);
    return $result;
}


?>