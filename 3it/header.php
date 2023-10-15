<?php

$cpage = "utf-8";
date_default_timezone_set("Europe/Prague");
include "config_db.php";
$int3it = mysqli_connect($mysqlhost, "$mysqluser", "$mysqlpass", "$mysqldb");
if (!mysqli_set_charset($int3it, "utf8")) {
    echo "Zřejmě špatný connect na db";
}

///////////////////////////////
if (isset($_COOKIE)) {
    foreach ($_COOKIE as $x => $ax) {
        ${$x} = $ax;
    }
}
if (!isset($login)) {
    $login = isset($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] : '';
}
$uinfo = mysqli_query($intsoumar, "select * from PEOPLE where LOGIN='$login' and (PEOPLE.STATUS=1 or PEOPLE.AUTOMAT=1)");
$ruinfo = mysqli_fetch_object($uinfo);
$lname =  $ruinfo->LNAME;
$fname =  $ruinfo->FNAME;
$fullname = $fname . " " . $lname;
$rauto =  $ruinfo->AUTO;
///////////////////////////////

if (isset($_GET)) {
    foreach ($_GET as $x => $ax) {
        //	echo "<br>get $x  $ax ";
        ${$x} = $ax;
    }
}
if (isset($_POST)) {
    foreach ($_POST as $x => $ax) {
        //	echo "<br>post $x  $ax ";
        ${$x} = $ax;
    }
}
