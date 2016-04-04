<?php
include 'AuthorizeMeHeader.php';
$user_data=json_decode(file_get_contents('https://graph.facebook.com/me?fields=id,name,first_name,last_name,birthday,email,gender,link&access_token='.$my_token['access_token']),TRUE);
//Array{
//    [id] => 100000317390816
//    [name] => Стас Протасевич
//    [first_name] => Стас
//    [last_name] => Протасевич
//    [birthday] => 07/03/1988
//    [gender] => male
//    [email] => stanislav.protasevich@gmail.com
//    [timezone] => 2
//    [locale] => ru_RU
//}
//
// Docs: http://ruseller.com/lessons.php?id=1670
if (!is_null($user_data['birthday'])){
    $user_date=date_parse_from_format("j/n/Y", $user_data['birthday']);
    if($user_date['month'] > date('m') || $user_date['month'] == date('m') && $user_date['month'] > date('d')){
        $user_data['age'] = date('Y') - $user_date['year'] - 1;
    }else{
        $user_data['age'] = date('Y') - $user_date['year'];
    }
}else{
    $user_data['age']=null;
}
$User=array(
    'fullname'=>$user_data['name'],
    'firstname'=>$user_data['first_name'],
    'lastname'=>$user_data['last_name'],
    'email'=>$user_data['email'],
    'gender'=>$user_data['gender'],
    'age'=>$user_data['age'],
    'language'=>$user_data['locale'],
    'picture'=>'http://graph.facebook.com/'.$user_data['id'].'/picture?type=large',
    'profile_link'=>$user_data['link'],
    );
include 'AuthorizeMe.php';
