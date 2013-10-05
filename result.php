<?php
session_start();

//configurations
  $client_id = 'Your client id';
  $clientsecret = 'Your client secret';
  $redirectUri = '.../result.php';
  $maxresults = 100; // maximum address. you can adjust depending on your preference
  
// setting parameters

$authcode = $_GET["code"];
$fields=array(
'code'=>  urlencode($authcode),
'client_id'=>  urlencode($client_id),
'client_secret'=>  urlencode($clientsecret),
'redirect_uri'=>  urlencode($redirectUri),
'grant_type'=>  urlencode('authorization_code') );

//url-ify the data for the POST

$fields_string = '';

foreach($fields as $key=>$value){ $fields_string .= $key.'='.$value.'&'; }

$fields_string	=	rtrim($fields_string,'&');

//open connection
$ch = curl_init();

curl_setopt($ch,CURLOPT_URL,'https://accounts.google.com/o/oauth2/token'); //set the url, number of POST vars, POST data

curl_setopt($ch,CURLOPT_POST,5);

curl_setopt($ch,CURLOPT_POSTFIELDS,$fields_string);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Set so curl_exec returns the result instead of outputting it.

curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); //to trust any ssl certificates

$result = curl_exec($ch); //execute post

curl_close($ch); //close connection

//print_r($result);

//extracting access_token from response string

$response   =  json_decode($result);

$accesstoken = $response->access_token;


if( $accesstoken!='')

$_SESSION['token']= $accesstoken;

//passing accesstoken to obtain contact details

$xmlresponse=  file_get_contents('https://www.google.com/m8/feeds/contacts/default/full?max-results='.$maxresults.'&oauth_token='. $_SESSION['token']);

//reading xml using SimpleXML

$xml=  new SimpleXMLElement($xmlresponse);

$xml->registerXPathNamespace('gd', 'http://schemas.google.com/g/2005');

$result = $xml->xpath('//gd:email');

$count = 0;
$subject = "Invitation mail.";
$message = "Enter your message here";
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= 'Content-type:text/html; charset=iso-8859-1' . "\r\n";
$headers .= "From: youremail@domainname.com \r\n";
?>
<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf8" />
      <title>Google Powered Login Form</title>
   </head>
<body>
<h1>Mail Contacts</h1>
<?php    
foreach ($result as $title) {
	$count++;
    //echo $count.". ".$title->attributes()->address . "<br>";
    $to = $title->attributes()->address;
    mail($to, $subject, $message, $headers);

}
?>
</body>
</html>
