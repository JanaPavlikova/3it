<html>

<head>
    <?php
    include "header.php";
    include "proc.php"
    ?>
    <script language="JavaScript" src="./_js/basic.js"></script>
    <?php echo "<link href='./_css/fontawesome.css' rel='stylesheet'>\n"; ?>
    <?php echo "<LINK href='./_css/list.css' rel=STYLESHEET type=text/css>" ?>
</head>

<body>

    <?php
    if (isset($delete) && $delete == 1) {
        // smazu vsechny zaznamy
        $res = sql("$mysqldb", "truncate table zaznamy");
    }
    if (isset($delete) && $delete == 2) {
        // smazu vybrane zaznamy
        $res = sql("$mysqldb", "select id from zaznamy");
        while ($r = mysqli_fetch_object($res)) {
            $chrecords = "ch" . $r->id;
            if (isset(${$chrecords})) {
                $resw = sql("$mysqldb", "delete from zaznamy where id=$r->id");
            }
        }
    }
    if ((!isset($order)) || ("$order" == "")) {
        $order = "ID";
    }
    $res = sql($mysqldb, "select *, DATE_FORMAT(DATE,'%d.%m.%Y') as mydate from zaznamy order by $order");
    $numrec = mysqli_num_rows($res);

    echo "<center><div id='loader' class='loader'></div>\n";
    echo "<div id='content'>\n";

    echo "<div>";
    echo "<div class=panel>";
    Table_Header("", "class=myhead CELLSPACING=0 CELLPADDING=0 border=1");
    Table_R();
    Table_D("id='Logout' class=myhead colspan=5 onclick='Logout();'", "$ico_logout&nbsp;ODHLÁŠENÍ", "Odhlášení ze systému");
    Table_ER();
    Table_R();
    Table_D("id='Importcsv' class=myhead onclick='Import(\"csv\");'", "$ico_import&nbsp;IMPORT CSV", "Import do záznamů ve formátu CSV");
    Table_D("id='Importxml' class=myhead onclick='Import(\"xml\");'", "$ico_import&nbsp;IMPORT XML", "Import do záznamů ve formátu XML");
    Table_D("id='Importjson' class=myhead onclick='Import(\"json\");'", "$ico_import&nbsp;IMPORT JSON", "Import do záznamů ve formátu JSON");
    $myclass = "myhead";
    $myonclick = "onclick = 'DeleteRecords(1);'";
    if ($numrec == 0) {
        $myclass = "myheaddisable";
        $myonclick = "";
    }
    Table_D("id='DeleteRecords' class=$myclass $myonclick", "$ico_trash&nbsp;SMAŽ VŠECHNY ZÁZNAMY", "Smaže všechny záznamy");
    $myonclick = "onclick = 'DeleteRecords(2);'";
    if ($numrec == 0) {
        $myonclick = "";
    }
    Table_D("id='DeleteRecords' class=$myclass $myonclick", "$ico_trash&nbsp;SMAŽ VYBRANÉ ZÁZNAMY", "Smaže označené záznamy");
    Table_ER();
    Table_End();

    echo "</div>";
    echo "</div>";
    echo "<div class=list>";
    echo "<form name=RElist method=POST action=RElist.php>";
    echo "<input type=hidden name=order value='$order'>\n";
    echo "<input type=hidden name=delete value=0>\n";

    Table_Header("Záznamy", "border=1 cellpadding=2 cellspacing=0", "");
    // hlavicka seznamu
    Table_R("");
    Table_H("class=center", "<input type=checkbox name=recordsall onclick=ChUnchall();>");
    Table_H("", "ID", "ID", "ID desc", "order");
    Table_H("", "JMÉNO", "JMENO", "JMENO desc", "order");
    Table_H("", "PŘÍJMENÍ", "PRIJMENI", "PRIJMENI desc", "order");
    Table_H("", "DATUM", "DATE", "DATE desc", "order");
    Table_ER();
    // konec hlavicky seznamu

    $suda = false;
    $nr = mysqli_num_rows($res);
    while ($r = mysqli_fetch_object($res)) {
        /* vypis jednoho radku*/
        if ($suda) {
            Table_R("id = tr$r->ID class='suda' onclick=Chrow($r->ID);");
            echo "<input type=hidden id=TrOldClass$r->ID name=TrOldClass$r->ID value='suda'>";
        } else {
            Table_R("id = tr$r->ID class='licha' onclick=Chrow($r->ID);");
            echo "<input type=hidden id=TrOldClass$r->ID name=TrOldClass$r->ID value='licha'>";
        }
        $chrecords = "ch" . $r->ID;
        $wch = "";
        if (isset(${$chrecords})) {
            $wch = "checked";
        }
        Table_D("class=center", "<input type=checkbox id=$chrecords name=$chrecords $wch onclick=Chrow($r->ID);>");
        Table_D("", "$r->ID", "ID $r->JMENO $r->PRIJMENI");
        Table_D("", "$r->JMENO", "JMÉNO $r->JMENO $r->PRIJMENI");
        Table_D("", "$r->PRIJMENI", "PŘÍJMENÍ $r->JMENO $r->PRIJMENI");
        Table_D("", "$r->mydate", "DATUM $r->JMENO $r->PRIJMENI");

        Table_ER();
        $suda = !$suda;
    }
    Table_End();
    echo "</form>\n";
    echo "</div>\n";
    echo "</div>\n";

    ?>

    <SCRIPT>
        function ord(val) {
            document.forms[0].elements['order'].value = val;
            Myloader();
            document.forms[0].submit();
            return false;
        }

        function Import(typ) {
            multipleScreenPopup('./REimport.php?typ=' + typ, 'ImportFile', 500, 300);
        }

        function DeleteRecords(typ = 1) {
            let text = 'Chcete opravdu odstranit všechny záznamy ?';
            if (typ == 2) {
                text = 'Chcete opravdu odstranit vybrané záznamy ?';
            }
            if (confirm(text)) {
                Myloader();
                document.RElist.delete.value = typ;
                document.RElist.submit();
            }

        }

        function Logout() {
            window.top.location.href = "./logout.php";
        }

        function ChUnchall() {
            let objcheckbox = document.getElementsByTagName("input");
            myvalue = document.RElist.recordsall.checked;
            for (let i = 0; i < objcheckbox.length; i++) {
                if (objcheckbox[i].type == "checkbox") {
                    // v poli jsou vsechny checkboxy
                    // dale musim vyradit globalni checkbox
                    let namech = objcheckbox[i].name;
                    namech = namech.substr(2);
                    if (convertNumber(namech) != 0) {
                        Chrow(namech, 1, myvalue);
                    }
                    //                    Chrow(namech);
                    //                    objcheckbox[i].checked = myvalue;
                }
            }
        }

        function Chrow(id, all = 0, tovalue = false) {
            let mycheckbox = "ch" + id;
            let objcheckbox = document.getElementById(mycheckbox);
            let mytr = "tr" + id;
            let objtr = document.getElementById(mytr);
            if (all == 1) {
                if (tovalue) {
                    objtr.className = "marked";
                } else {
                    let myoldtrclass = "TrOldClass" + id;
                    let myoldclass = document.getElementById(myoldtrclass).value;
                    objtr.className = myoldclass;
                }
                objcheckbox.checked = tovalue;
            } else {
                if (objcheckbox.checked) {
                    let myoldtrclass = "TrOldClass" + id;
                    let myoldclass = document.getElementById(myoldtrclass).value;
                    objtr.className = myoldclass;
                } else {
                    objtr.className = "marked";
                }
                objcheckbox.checked = !objcheckbox.checked;
            }
        }
    </SCRIPT>

</body>

</html>