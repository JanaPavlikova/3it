<html>

<head>
    <?php include "./header.php"; ?>
    <?php include "./proc.php"; ?>
    <script language="JavaScript" src="./_js/basic.js"></script>
    <script src="./_js/upload.js"></script>
    <?php echo "<link href='./_css/fontawesome.css' rel='stylesheet'>\n"; ?>
    <?php echo "<LINK href='./_css/list.css' rel=STYLESHEET type=text/css>" ?>
</head>

<body onload=opener.document.RElist.submit();ClickCancel();>
    <script>
        Myloader();
    </script>
    <?php
    //<body onload=opener.document.RElist.submit();ClickCancel();>
    echo "<center><div id='loader' class='loader'></div>\n";
    echo "<div id='content'>\n";
    if ($myFileImport != "") {
        $mainpath = $_SERVER['DOCUMENT_ROOT'];
        $mypath = $mainpath . "/tmp/";
        switch ($typ) {
            case "csv":
                $sql = "load data local infile '$mypath$myFileImport' 
                into table zaznamy 
                fields terminated by ',' 
                enclosed by ''
                lines terminated by '\r\n'";
                $res = sql($mysqldb, "$sql");
                break;
            case "xml":
                $sql = "load XML local infile '$mypath$myFileImport' 
                    into table zaznamy 
                    rows identified by '<ZAZNAM>';";
                $res = sql($mysqldb, "$sql");

                break;
            case "json":
                // Get JSON file and decode contents into PHP arrays/values
                $jsonFile = "$mypath$myFileImport";
                $jsonData = json_decode(file_get_contents($jsonFile), true);
                // Iterate through JSON and build INSERT statements
                foreach ($jsonData as $id => $row) {
                    $insertPairs = array();
                    foreach ($row as $key => $val) {
                        $insertPairs[addslashes($key)] = addslashes($val);
                    }
                    $insertKeys = '`' . implode('`,`', array_keys($insertPairs)) . '`';
                    $insertVals = '"' . implode('","', array_values($insertPairs)) . '"';
                    $sql = "INSERT INTO zaznamy ({$insertKeys}) VALUES ({$insertVals});";
                    $res = sql($mysqldb, "$sql");
                }
                break;
        }
    }
    Table_Header("", "border=1 cellspacing=0 cellpadding=2 align=center");
    Table_R();
    Table_D("id='CancelButton' class=myhead onclick=ClickCancel();", "$ico_back&nbsp;ZPĚT");
    Table_ER();
    Table_End();
    echo "</div>";

    ?>
    <script>
        function ClickCancel() {
            this.close();
        }
    </script>
</body>

</html>