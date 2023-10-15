<?php
function sql($db, $q)
{
    global $debug, $int3it, $mysqldb, $errlogfile, $login;
    global $log;
    $start = TIME();
    $ints = $int3it;
    if ($db == $mysqldb) {
        $_r = mysqli_query($ints, "$q");
    } else {
        echo "!! NEZNÁMÁ DATABÁZE $db !!";
    }
    if ($debug >= 1) {
        echo "$db<br>$q<br>\n";
    }
    if (!$_r) {
        if (strstr($q, "select")) {
            echo "<br>database error!<br>\n";
            if ($debug == 0) {
                echo "$db<br>$q<br>\n";
            }
            $err_file = fopen("$errlogfile", "a");
            $x = sprintf("DB error (select) %s $login \n%s: %s", Date("d.m.Y H:i", time()), $db, $q);
            fputs($err_file, "$x\n");
            fclose($err_file);
        }
        if (strstr($q, "insert") || strstr($q, "update") || strstr($q, "delete")) {
            $erno = mysqli_errno($ints);
            if ($erno == 1062) {
                if ($debug >= 1) {
                    echo "Duplicate unique key!<br>\n";
                }
            } else {
                echo "<br>database error!<br>\n";
                if ($debug == 0) {
                    echo "$db<br>$q<br>\n";
                }
                $err_file = fopen("$errlogfile", "a");
                $x = sprintf("DB error (update) %s - %s \n%s: %s", Date("d.m.Y", time()), $login, $db, $q);
                fputs($err_file, "$x\n");
                fclose($err_file);
            }
        }
    } else {
        if ($debug >= 1) {
            if (strstr($q, "insert") || strstr($q, "update") || strstr($q, "delete")) {
                $_nr = mysqli_affected_rows($ints);
                echo "Insert, update or delete with $_nr affected rows!<br>\n";
            }
        }
    }
    $stop = TIME();
    if ($log == 1) {
        printf("%d: %s - %s\n", $stop - $start, date($start, "d.m.Y H:m:i"), date($stop, "H:m:i"));
    }
    return ($_r);
}

function Table_Header($title, $parm = "")
{
    if ($title != "") {
        echo "<h2><center>$title</h2>\n";
    }
    echo "<table $parm>\n";
}

function Table_End()
{
    echo "</table>\n";
}

function Table_R($parm = "")
{
    echo "<tr $parm>\n";
}

function Table_D($parm, $content, $text_title = "")
{
    if ("$text_title" != "") {
        $text_title = "title='$text_title'";
    }
    echo "<td $parm $text_title>\n";
    if ($content != "") {
        echo "$content\n";
    }
    echo "</td>\n";
}

function Table_H($parm, $content, $field_order = "", $field_order_desc = "", $x = "", $text_title = "")
{
    if ("$x" != "") {
        global ${$x};
        $vorder = ${$x};
    } else {
        $vorder = "";
    }
    if ("$text_title" != "") {
        $text_title = "title='$text_title'";
    }
    echo "<th $parm $text_title >$content\n";
    if (("$field_order" != "") && ("$field_order_desc" != "") && ("$vorder" != "")) {
        if ($field_order != $vorder) {
            if ($field_order_desc != $vorder) {
                echo "&nbsp;<a class=head href=\"\" title='setřiď vzestupně' onclick=\"return(ord('$field_order','$x',$mform))\" >";
                echo "<i class='fas fa-border-none'></i>\n";
            } else {
                echo "&nbsp;<a class=head href=\"\" title='setřiď vzestupně' onclick=\"return(ord('$field_order','$x',$mform))\">";
                echo "<i class='fas fa-sort-amount-up-alt'></i>\n";
            }
        } else {
            echo "&nbsp;<a class=head href=\"\" title='setřiď sestupně' onclick=\"return(ord('$field_order_desc','$x',$mform))\">";
            echo "<i class='fas fa-sort-amount-down'></i>\n";
        }
        echo "</a>";
    }
    if (("$field_order" != "") && ("$field_order_desc" == "") && ("$vorder" != "")) {
        echo "&nbsp;<a class=head href=\"\" title='setřiď podle pořadí zadávání' onclick=\"return(ord('$field_order','$x',$mform))\" >";
        echo "<i class='fas fa-list-ol'></i>\n";
    }
    echo "</th>\n";
}

function Table_ER()
{
    echo "</tr>\n";
}
