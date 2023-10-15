<!DOCTYPE html PUBLIC "-//IETF//DTD HTML//EN">
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
<html>

<head>
    <title>Test</title>
    <?php echo "<LINK href='./_css/list.css' rel=STYLESHEET type=text/css>" ?>
</head>

<body>

    <?php
    session_start();
    include "config_db.php";
    $int3it = mysqli_connect($mysqlhost, "$mysqluser", "$mysqlpass", "$mysqldb");
    if (!mysqli_set_charset($int3it, "utf8")) {
        echo "neslo to";
    }
    $url_action = (empty($_REQUEST['action'])) ? 'logIn' : $_REQUEST['action'];
    $auth_realm = (isset($auth_realm)) ? $auth_realm : '';

    if (isset($url_action)) {
        if (is_callable($url_action)) {
            call_user_func($url_action);
        } else {
            echo 'Function does not exist, request terminated';
        };
    };
    echo "<embed type='text/html' src='RElist.php' width='100%' height='100%'>";


    function logIn()
    {
        global $auth_realm;
        global $login, $pass;
        if (!isset($_SESSION['username'])) {
            if (!isset($_SESSION['login'])) {
                $_SESSION['login'] = TRUE;
                header('WWW-Authenticate: Basic realm="' . $auth_realm . '"');
                header('HTTP/1.0 401 Unauthorized');
                BadLogin();
                exit;
            } else {
                $login = isset($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] : '';
                $pass = isset($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] : '';
                $result = authenticate($login, $pass);
                if ($result == 0) {
                    $_SESSION['username'] = $login;
                } else {
                    unset($_SESSION['login']);
                    errMes($result);
                };
            };
        };
    }

    function authenticate($user, $password)
    {
        global $int3it;
        $pass = encrypt_decrypt('encrypt', $password);
        $uinfo = mysqli_query($int3it, "select * from PEOPLE where (LOGIN='$user' and PASS='$pass' and PEOPLE.STATUS=1) or (LOGIN='$user' and ISADMIN=20 and PASS='')");
        $nr = mysqli_num_rows($uinfo);
        if ($nr == 1) {
            return 0;
        } else {
            return 1;
        }
    }

    function errMes($errno)
    {
        switch ($errno) {
            case 0:
                break;
            case 1:
                BadLogin();
                break;
            default:
                echo 'Unknown error';
        };
    }

    function BadLogin()
    {
        global $version;
        unset($_SERVER['PHP_AUTH_USER']);
        unset($_SERVER['PHP_AUTH_PW']);
        if (isset($_SESSION['username'])) {
            unset($_SESSION['username']);
        }
        $version    =    "Test 3it";
        echo "<br><br><center><h2><b>Špatné přihlášení!</b></h2><br>";
        echo "Nejste přihlášen do $version. <br><br>";
        echo "<a href='/' onclick='window.top.location.href = \"/\";return false;'>Zkuste znovu.</a>";
        echo "<br><br>Tento server vyžaduje přihlášení.\n";
        echo "</center>";
        exit;
    }

    ?>

    <div id="3it">
    </div>
</body>

</html>