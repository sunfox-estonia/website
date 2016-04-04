<?php
include 'AuthorizeMeHeader.php';
$query_par = array('code' => $_GET['code'],'client_id' => '700082934855-sdrba0vc2mf1dpf75ho869tdghtdrv0g.apps.googleusercontent.com','client_secret' => 'EQJH6eb8yWTltfPVxwVY--yQ','redirect_uri' => 'https://viruviking.club/resources/php/php_oauth/GoogleController.php','grant_type' => 'authorization_code');
$query_prep = http_build_query($query_par);
$contextData = array (
    'method' => 'POST',
    'header' => "Connection: close\r\n".
                "Content-Length: ".strlen($query_prep)."\r\n".
                "Content-type: application/x-www-form-urlencoded",
    'content'=> $query_prep );
$result=json_decode(file_get_contents('https://accounts.google.com/o/oauth2/token',false,stream_context_create(array('http'=>$contextData))));
$user_data=json_decode(file_get_contents('https://www.googleapis.com/oauth2/v1/userinfo?access_token='.$result->{'access_token'}));

//object(stdClass)#2 (10) { 
//  ["id"]=> string(21) "113695279168289954986"
//  ["email"]=> string(19) "riigikogu@gmail.com"
//  ["verified_email"]=> bool(true)
//  ["name"]=> string(16) "Viktor Litvinkov"
//  ["given_name"]=> string(6) "Viktor"
//  ["family_name"]=> string(9) "Litvinkov"
//  ["link"]=> string(40) "https://plus.google.com/+ViktorLitvinkov"
//  ["picture"]=> string(92) "https://lh3.googleusercontent.com/-mHLkzXdU-7g/AAAAAAAAAAI/AAAAAAAAC6s/ary1QFhgfw0/photo.jpg"
//  ["gender"]=> string(4) "male"
//  ["locale"]=> string(2) "ru"
//}
//
// Docs: https://developers.google.com/+/web/api/rest/openidconnect/getOpenIdConnect?hl=vi

$User=array(
    'fullname'=>$user_data->{'name'},
    'firstname'=>$user_data->{'given_name'},
    'lastname'=>$user_data->{'family_name'},
    'email'=>$user_data->{'email'},
    'gender'=>$user_data->{'gender'},
    'language'=>$user_data->{'locale'},
    'picture'=>$user_data->{'picture'},
    'profile_link'=>$user_data->{'link'},
    );
include 'AuthorizeMe.php';
