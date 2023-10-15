<html>

<head>
    <?php include "./header.php"; ?>
    <?php include "./proc.php"; ?>
    <script language="JavaScript" src="./_js/basic.js"></script>
    <script src="./_js/upload.js"></script>
    <?php echo "<link href='./_css/fontawesome.css' rel='stylesheet'>\n"; ?>
    <?php echo "<LINK href='./_css/list.css' rel=STYLESHEET type=text/css>" ?>
</head>

<body>
    <?php
    echo "<center><div id='loader' class='loader'></div>\n";
    echo "<div id='content'>\n";

    echo "<form name=RE method=POST action='REpimport.php' ENCTYPE='multipart/form-data'>\n";
    echo "<input type=hidden name=myFileImport value=''>\n";
    echo "<input type=hidden name=typ value='$typ'>\n";
    Table_Header("", "border=1 cellspacing=0 cellpadding=2 align=center");
    Table_R();
    echo "<td colspan=2>";
    ?>

    <?php echo "<div id=\"drop_file_zone_import\" ondrop=\"upload_file_import(event,'$typ')\" ondragover=\"return false\">\n"; ?>
    <div id="drag_upload_file_import">
        <p>Přetáhněte soubor sem</p>
        <p>nebo</p>
        <?php echo "<p><input type=\"button\" value=\"Vybrat soubor\" onclick=\"file_explorer_import('$typ');\" /></p>\n"; ?>
        <input type="file" id="selectfile_import" />
    </div>
    </div>
    <br>
    <div class="import-content">
    </div>
    <?php

    echo "</td>";
    Table_ER();

    Table_R();
    //    Table_D("id='ImportButton' class=myheaddisable onclick = Myloader();document.RE.submit();", "$ico_import&nbsp;IMPORT $typ");
    Table_D("id='ImportButton' class=myheaddisable ", "$ico_import&nbsp;IMPORT $typ");
    Table_D("id='CancelButton' class=myhead onclick=ClickCancel();", "$ico_back&nbsp;ZPĚT");
    Table_ER();
    Table_End();
    echo "</form>";
    echo "</div>";
    ?>
    <script>
        function ClickCancel() {
            this.close();
        }
    </script>

</body>

</html>