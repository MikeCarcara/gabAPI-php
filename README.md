# Gab.com API | OAuth/PHP Example

Gab.com API | OAuth/PHP Example written in PHP by Michael Carcara <michael.carcara@gmail.com>;
This is an example PHP script for Gab.com's API to grant an access token for your PHP application

## License

This project is licensed under the GNU License - see the [LICENSE.md](LICENSE.md) file for details

## Acknowledgments

* Gab.com Developers API	

## Getting Started

Simply upload the gab.php file to your HTTP/HTTPS server and configure the define() variables to your Gab.com App settings


### Prerequisites

```
Gab.com PRO account and understanding of PHP scripting 
```

### Source : gab.php

```
<?php
/*
		Gab.com API | OAuth Example written in PHP by Michael Carcara <michael.carcara@gmail.com>
		
		This is an example PHP script for Gab.com's API to grant an access token for your PHP application
		
		My Github: https://www.github.com/MikeCarcara/gabAPI-php
		My Site: https://www.slavematrix.com/
		GAB Developers: https://developers.gab.com
*/

//
// Set the URI of your Gab.com App
//
		define("REDIRECT_URI", "{PATH TO FILE}/gab.php?callback");
/* 
Be sure to make this the same REQUEST_URI in your App Settings on Gab.com
*/

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
SCOPES MUST BE SEPARATED WITH SINGLE SPACES {example: read notifications write-post}
Scope Variables: read, notifications, write-post, engage-post, engage-post
*/

// Allow user to grant your app permissions for this example we are using user feed
if(!isset($_GET['callback'])){
	header('Location: https://api.gab.com/oauth/authorize?response_type=code&client_id='.CLIENT_ID.
		'&scope='.APP_SCOPE.'&redirect_uri='.REDIRECT_URI.'');
}else{
	
//Strip the Gab.com API code from url
	$strip_uri = explode("&code=", $_SERVER['REQUEST_URI']);
		$code = $strip_uri[1];
		
// Send code to Gab.com via cURL
	$ch = curl_init( "https://api.gab.com/oauth/token" );
	
// Setup request to send JSON via cURL POST
	$payload = json_encode( 
		array( 
			"grant_type" => "authorization_code", 
			"code" => $code, "client_id" => CLIENT_ID,
			"client_secret" => CLIENT_SECRET, 
			"redirect_uri" => REDIRECT_URI."" ) );
		curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
		curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
		
// Return response instead of printing
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		
// Send request for user access token
	$access_token = curl_exec($ch);
	
// Decode JSON Bearer Token
	$callback = json_decode($access_token);

/*

Execute Gab.com API's documentation of PHP for user feed 
We will be using my user feed for the example

source: https://developers.gab.com/#d7cf64ab-6b39-4c6a-22c7-e3a1d0f848ef

all code past this point will be up to you (the developer) to troubleshoot and integrate into your app
*/

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
```

### Gab.com API Examples

Complete Documentation : https://developers.gab.com


#### User Details Example

```
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://api.gab.com/v1.0/me/",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_HTTPHEADER => array(
    "Authorization: Bearer ACCESS_TOKEN"
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
```

#### Post Example

```
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://api.gab.com/v1.0/posts",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => "------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"body\"\r\n\r\nSample post with multiple media attachments\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"media_attachments[]\"\r\n\r\nsample-media-attachment-id-1\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"media_attachments[]\"\r\n\r\nsample-media-attachment-id-2\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"reply_to\"\r\n\r\n\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"is_quote\"\r\n\r\n\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"nsfw\"\r\n\r\n\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"premium_min_tier\"\r\n\r\n\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"group\"\r\n\r\n\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"topic\"\r\n\r\n\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"poll\"\r\n\r\n\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"poll_option_1\"\r\n\r\n\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"poll_option_2\"\r\n\r\n\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"poll_option_3\"\r\n\r\n\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"poll_option_4\"\r\n\r\n\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW--",
  CURLOPT_HTTPHEADER => array(
    "Authorization: Bearer ACCESS_TOKEN",
    "content-type: multipart/form-data; boundary=----WebKitFormBoundary7MA4YWxkTrZu0gW"
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
```

#### Feed Example

```
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://api.gab.com/v1.0/feed/?before=2018-10-03T19:35:47+00:00",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_HTTPHEADER => array(
    "Authorization: Bearer ACCESS_TOKEN"
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
```

#### Popular Users Example

```
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://api.gab.com/v1.0/popular/users/",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_HTTPHEADER => array(
    "Authorization: Bearer ACCESS_TOKEN"
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
```
