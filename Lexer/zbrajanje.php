<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Zbrajanje</title>
</head>
<body>
<a href="index.php">Početna</a><br><br>
<?php
$ulaz = "";
$ulaz_err = $trazi_err = $broji_err = "";
$showForm = true;
$greska = false;
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (isset($_GET["ulaz"])) {
        if (empty($_GET["ulaz"])) {
            $ulaz_err = "Ulazni niz ne smije biti prazan!";
        }
        else {
            $ulaz = test_input($_GET["ulaz"]);
            $duljina = strlen($ulaz);
            $ulaz2 = str_split($ulaz);
            $lista = array("0","1","2","3","4","5","6","7","8","9");
            for ($i=0; $i<$duljina; $i++) {
                if (!in_array($ulaz2[$i], $lista)) {
                    $ulaz_err = "Samo brojevi!";
                    $greska = true;
                }
            }
        }
        if ($greska===false) {
            include "funkcije.php";
                if (zbroji((int)$ulaz) != -1) {
                    $result = zbroji((int)$ulaz);
                    $showForm = false;
                }
         else {
                $greska = true;
            }
        }
    }


}
function test_input($data) {
    $data = trim($data);
    $data = stripcslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
if ($showForm) {
?>
    <form method="get" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <label for="ulaz">Ulazni niz brojeva:
            <input type="text" name ="ulaz" value="<?php echo $ulaz; ?>">
            <span class ="error">* <?php echo $ulaz_err; ?></span>
        </label><br><br>
        <input type ="submit"><br>
        <?php
        if ($greska === true) {
            echo "";
        }
        ?>

    </form>
<?php }
else echo $result;
?>


</body>
</html>
