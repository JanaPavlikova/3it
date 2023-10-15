<?php include "./header.php" ?>
<?php
$mypath = './tmp/';
$arr_file_types = ['text/csv'];
switch ($typ) {
    case "csv":
        $arr_file_types = ['text/csv'];
        break;
    case "xml":
        $arr_file_types = ['text/xml'];
        break;
    case "json":
        $arr_file_types = ['application/json'];
        break;
}
if (!(in_array($_FILES['file']['type'], $arr_file_types))) {
    echo "false";
    return;
}


$filename = $_FILES['file']['name'];
$file_extension = substr($filename, strpos($filename, ".") + 1);
$file_extension = strtolower($file_extension);

$filename = "REimport_" . time() . "." . $file_extension;

move_uploaded_file($_FILES['file']['tmp_name'], $mypath . $filename);

echo './tmp/' . $filename;
die;
