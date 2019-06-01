<?php
include "funkcije.php";
$ulaz = "";
$errors = "";
if(isset($_FILES['ulaz'])) {
    $file_name = $_FILES["ulaz"]["name"];
    $file_temp = $_FILES["ulaz"]["tmp_name"];
    $file_size = filesize($file_temp);
    $tmp = explode('.', $_FILES["ulaz"]["name"]);
    $file_ext = strtolower(end($tmp));
    $extensions = array("txt");
    if (in_array($file_ext, $extensions) === false) {
        $errors = "Samo tekstualne datoteke";
    }
    if ($file_size > 1024) {
        $errors = "Max size 1 KB";
    }
    if ($errors == "") {
        $otvori = fopen($file_temp, "r");
        if ($otvori) {
            while (($line = fgetc($otvori)) !== false) {
                if (ctype_print($line) || ctype_cntrl($line)) {
                    // ctype_cntrl koristim da sustav dopusti nove redove
                    // ovako lijepo obranim sustav od XSS napada bez da kasnije moram petljati
                    $ulaz .= htmlentities($line);
                }
                else {
                    $errors = "Nedozvoljeni znak";
                    $ulaz = empty($ulaz);
                    break;
                    //fclose($otvori);
                }
            }
            fclose($otvori);
        }
        else {
            $errors = "Ne moze se otvoriti";
            $ulaz = empty($ulaz);
        }

    }
    if($ulaz != empty($ulaz)) {
        $trans = transformiraj($ulaz);
        if ($trans != null) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="transformirani.html"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . strlen($trans));
            //echo htmlentities($trans);
            echo $trans;
            exit;
        }
    }
}
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Markdown</title>
</head>
<body>
<a href="index.php">Početna</a><br>

<form  method="post" enctype="multipart/form-data" action="<?php echo htmlentities($_SERVER["PHP_SELF"]);?>">
    <label for="datoteka">Izaberi datoteku </label>
    <input type="file" name="ulaz" value="<?php
        if (isset($_FILES["ulaz"])) {
            echo  $_FILES["ulaz"]["name"];
        }
    ?>">
    <span class ="error">* <?php echo $errors;;?></span><br>
    <input type="submit" /><br>
</form>

</body>
</html>