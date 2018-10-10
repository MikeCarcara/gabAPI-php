<?php
/*
		Gab.com API | OAuth Example written in PHP by Michael Carcara <michael.carcara@gmail.com>
		
		This is an example PHP script for Gab.com's API to grant an access token for your PHP application

*/
																									session_start();

//
// Set the URI of your Gab.com App
//
		define("REDIRECT_URI", "{PATH TO FILE}/gab.php?callback");
//
// Set the client ID of your Gab.com App
//
		define("CLIENT_ID", "{YOUR CLIENT ID}");
/* 
Login to your Gab account as the developer and register your application under Settings > Developer Apps.
*/		
//
// Set the client secret of your Gab.com App
//
		define("CLIENT_SECRET", "{YOUR CLIENT SECRET}");
/* 
Do not share your client secret with anyone!
*/
//
// Set the requested permissions of your Gab.com APP_SCOPE
//
		define("APP_SCOPE", "read");
/* 
Scope Variables: read, notifications, write-post, engage-post, engage-post
"SCOPES MUST BE SEPARATED WITH SINGLE SPACES"
*/


// Allow user to grant your app permissions for this example we are using user feed
if(!isset($_GET['callback'])){
header('Location: https://api.gab.com/oauth/authorize?response_type=code&client_id='.CLIENT_ID.
'&scope='.APP_SCOPE.'&redirect_uri='.REDIRECT_URI.'');
}else{
//	Strip the Gab.com API code from url
$strip_uri = explode("&code=", $_SERVER['REQUEST_URI']);
$code = $strip_uri[1];
// Send code to Gab.com via curl
$ch = curl_init( "https://api.gab.com/oauth/token" );
// Setup request to send json via POST
$payload = json_encode( array( "grant_type" => "authorization_code", "code" => $code, "client_id" => CLIENT_ID,
"client_secret" => CLIENT_SECRET, "redirect_uri" => REDIRECT_URI."" ) );
curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
// Return response instead of printing
curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
// Send request for user access token
$access_token = curl_exec($ch);
// Set PHP session varible to carry across your Gab.com App
// Execute Gab.com PHP API Script for user feed 
/*

We will be using my user feed for the example

*/
// Decode JSON Bearer Token
$callback = json_decode($access_token);
// Execute Gab.com Feed with curl
$curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_URL => "https://api.gab.com/v1.0/users/MichaelCarcara/feed/?after=2018-10-03T19:35:47+00:00",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_HTTPHEADER => array(
    "Authorization: Bearer ".$callback->{'access_token'}
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
  echo $response;
}

}

?>