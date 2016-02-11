<?php
require_once( "/var/www/admin/www/v2.viruviking.club/resources/php/php_hybridauth/config.php" );
require_once( "/var/www/admin/www/v2.viruviking.club/resources/php/php_hybridauth/Hybrid/Auth.php" );

$hybridauth = new Hybrid_Auth( $config_file_path );
$provider_name = $_GET["provider"];

// See more example: http://hybridauth.sourceforge.net/userguide/Integrating_HybridAuth_Social_Login.html
