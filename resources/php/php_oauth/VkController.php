<?php
include 'AuthorizeMeHeader.php';
$pereq_data=json_decode(file_get_contents('https://oauth.vk.com/access_token?client_id=5293223&client_secret=CNa1SLBjPSPDgTJz2ytS&redirect_uri=https://v2.viruviking.club/resources/php/php_oauth/VkController.php&code=' . $_GET['code']),TRUE);
$user_data_prep=json_decode(file_get_contents('https://api.vk.com/method/users.get?user_id=' . $pereq_data['user_id'] . '&fields=bdate,photo_200,domain,sex&v=5.45&access_token=' . $pereq_data['access_token']),TRUE);
$user_data=$user_data_prep['response'][0];

// Docs: https://vk.com/dev/api_requests
if (!is_null($user_data['bdate'])){
    $user_date=date_parse_from_format("j.n.Y", $user_data['bdate']);
    if($user_date['month'] > date('m') || $user_date['month'] == date('m') && $user_date['month'] > date('d')){
        $user_data['age'] = date('Y') - $user_date['year'] - 1;
    }else{
        $user_data['age'] = date('Y') - $user_date['year'];
    }
}else{
    $user_data['age']=null;
}
$User=array(
    'fullname'=>$user_data['first_name'] . ' ' . $user_data['last_name'],
    'firstname'=>$user_data['first_name'],
    'lastname'=>$user_data['last_name'],
    'email'=>null,
    'gender'=>$user_data['sex'],
    'age'=>$user_data['age'],
    'language'=>null,
    'picture'=>$user_data['photo_200'],
    'profile_link'=>'http://vk.com/'.$user_data['id'],
    );
include 'AuthorizeMe.php';