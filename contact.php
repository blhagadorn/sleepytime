<?php
if(empty($_POST) || !isset($_POST)) {

  ajaxResponse('error', 'Post cannot be empty.');

} else {

  $postData = $_POST;
  $dataString = implode($postData,",");

  $mailgun = sendMailgun($postData);

  if($mailgun) {

    ajaxResponse('success', 'Great success.', $postData, $mailgun);

  } else {

    ajaxResponse('error', 'Mailgun did not connect properly.', $postData, $mailgun);

  }

}

function ajaxResponse($status, $message, $data = NULL, $mg = NULL) {
  $response = array (
    'status' => $status,
    'message' => $message,
    'data' => $data,
    'mailgun' => $mg
    );
  $output = json_encode($response);
  exit($output);
}

function sendMailgun($data) {

  $api_key = 'key-c21b88954470a78c53f617cfd3a5f280';
  $api_domain = 'mg.sleepytimebylisa.com';
  $send_to = 'blhagadorn@gmail.com';


  $name = $data['name'];
  $email = $data['email'];
  $phone = $data['phone'];
  $address = $data['address'];
  $birthdate = $data['birthdate'];
  $selService = $data['selService'];
  $find = $data['find'];
  $content = $data['message'];

    date_default_timezone_set("America/Chicago");
    $mydate =  date("Y-m-d h:i:sa", $d);
    $subject = "New Customer Form - $mydate";

  $messageBody = "Hi Lisa, you've received a new inquiery! :\n\n\tContact: $name ($email)\n\tPhone: $phone\n\tAddress: $address\n\tBirth Date: $birthdate\n\tService Requested: $selService\n\tHow they found out: $find\n\tMessage: $content\n Let me know if this doesn't show up correctly!";

  $config = array();
  $config['api_key'] = $api_key;
  $config['api_url'] = 'https://api.mailgun.net/v3/mg.sleepytimebylisa.com/messages';

  $message = array();
  $message['from'] = 'Lisa from Sleepytime<lisa.sleepytime@gmail.com>';
  $message['to'] = 'lisa.sleepytime@gmail.com';
  $message['bcc'] = 'blhagadorn@gmail.com';
  $message['h:Reply-To'] = $email;
  $message['subject'] = $subject;
  $message['text'] = $messageBody;

  $curl = curl_init();

  curl_setopt($curl, CURLOPT_URL, $config['api_url']);
  curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
  curl_setopt($curl, CURLOPT_USERPWD, "api:{$config['api_key']}");
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
  curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
  curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
  curl_setopt($curl, CURLOPT_POST, true);
  curl_setopt($curl, CURLOPT_POSTFIELDS,$message);

  $result = curl_exec($curl);

  curl_close($curl);
  return $result;

}
?>