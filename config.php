<?php if (session_status() == PHP_SESSION_NONE) session_start();

error_reporting(E_ALL ^ E_NOTICE);
$HOST = 'http://localhost/';
$APP_URL = 'http://localhost/developerDashboard/';
$scan_dir = '/var/www/html/';//__DIR__;
$excluded_folders = array('.' ,'..', '.metadata');

function _pr($data=''){echo '<pre>';print_r($data);echo '</pre>';}
function _h($data=''){return htmlspecialchars($data);}
function __($data=''){echo htmlspecialchars($data);}
function _br(){echo '<br/>';}

function get_data($url) {
    $ch = curl_init();
    $timeout = 5;
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
}

$mapper = array(
    'COMMLOAN' => 'https://www.commloan-localhost.com/login',
    'COMMLOAN-LIVE' => 'https://www.commloan-live.com/login',
);


$db = new PDO("mysql:host=localhost;dbname=", 'root', 'root');
$db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );
$sqlite = new PDO("sqlite:developer_dashboard.sqlite");
$sqlite->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );

$site_title = 'Localhost: Developers Dashboard';