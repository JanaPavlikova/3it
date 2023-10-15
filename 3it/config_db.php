<?php
$mysqlhost	=	"localhost";
$mysqldb	=	"3it";
$mysqluser	=	"3it";
$mysqlpass	=	"3it987";

// definice icony v tlacitkach
$ico_trash = "<i class='fas fa-trash'></i>";
$ico_import = "<i class='fas fa-file-import'></i>";
$ico_back = "<i class='fas fa-undo-alt'></i>";
$ico_logout = "<i class='fas fa-user-check'></i>";

function encrypt_decrypt($action, $string)
{
    global $mysqldb;
    $output = false;
    $encrypt_method = "AES-256-CBC";
    $secret_key = "Key" . $mysqldb;
    $secret_iv = "Iv" . $mysqldb;
    $key = hash('sha256', $secret_key);
    $iv = substr(hash('sha256', $secret_iv), 0, 16);
    if ($action == 'encrypt') {
        $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
        $output = base64_encode($output);
    } else if ($action == 'decrypt') {
        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
    }
    return $output;
}
