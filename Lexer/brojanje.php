<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Brojanje</title>
</head>
<body>
<a href="index.php">Početna</a><br><br>
<?php
$ulaz = $trazi = $broji = "";
$ulaz_err = $trazi_err = $broji_err = "";
$showForm = true;
$greska = false;
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (isset($_GET["ulaz"]) && isset($_GET["trazi"]) && isset($_GET["broji"])) {
       if (empty($_GET["ulaz"])) {
           $ulaz_err = "Ulazni niz ne smije biti prazan!";
       }
       else {
           $ulaz = test_input($_GET["ulaz"]);
           /*if (!preg_match("/^[A-Za-z]*$/", $ulaz)) {
               $ulaz_err = "Samo slova";
           }*/
           $duljina = strlen($ulaz);
           $ulaz2 = str_split($ulaz);
           $lista = array();
           $lista = range('a', 'z');
           for ($i=0; $i<$duljina; $i++) {
               if (!in_array(strtolower($ulaz2[$i]),$lista)) {
                   $ulaz_err = "Samo slova";
                   $greska = true;
               }
           }
       }
       if (empty($_GET["trazi"])) {
            $trazi_err = "Unesite znak za pretraživanje";
        }
        else {
            $trazi = test_input($_GET["trazi"]);
            $duljina = strlen($trazi);
            if ($duljina > 1){
                $greska = true;
                $trazi_err = "Samo jedno slovo";
            }
            if ($greska === false) {
                $lista = array();
                $lista = range('a', 'z');
                if (!in_array(strtolower($trazi),$lista)) {
                    $trazi_err = "Samo slovo";
                    $greska = true;
                }
            }

           /* if (!preg_match("/^[A-Za-z]$/", $trazi)) {
                $trazi_err = "Samo jedno slovo!";
            }*/
        }
        if (empty($_GET["broji"])) {
            $broji_err = "Unesite niz za pretraživanje";
            $greska = true;
        }
        else {
            $broji = test_input($_GET["broji"]);
            /*if (!preg_match("/^[A-Za-z](,[A-Za-z])*$/", $broji)) {
                $broji_err = "Samo slova odvojena zarezom!";
                $greska = true;
            }*/
            $duljina = strlen($broji);
            $ulaz2 = str_split($broji);
            $lista = range('a', 'z');
            for ($ajmo = 0; $ajmo<$duljina; $ajmo++) {
                if ($ulaz2[0] === ',' || $ulaz2[$duljina-1] === ',') {
                    $broji_err = "Samo slova odvojena zarezom";
                    $greska = true;
                    break;
                }
                if (in_array($ulaz2[$ajmo], $lista)) {
                    if (isset($ulaz2[$ajmo+1]) && isset($ulaz2[$ajmo-1])) {
                        if ($ulaz2[$ajmo+1] != ',' || in_array($ulaz2[$ajmo-1], $lista) === true) {
                            $broji_err = "Samo slova odvojena zarezom";
                            $greska = true;
                            break;
                        }
                    }
                    if (isset($ulaz2[$ajmo+1])) {
                        if (in_array($ulaz2[$ajmo+1], $lista)) {
                            $broji_err = "Samo slova odvojena zarezom";
                            $greska = true;
                            break;
                        }
                    }
                }
                else if ($ulaz2[$ajmo] === ',') {
                    if (isset($ulaz2[$ajmo+1]) && isset($ulaz2[$ajmo-1])) {
                        if ($ulaz2[$ajmo+1] === ',') {
                            $broji_err = "Samo slova odvojena zarezom";
                            $greska = true;
                            break;
                        }
                    }
                }
                else {
                    $broji_err = "Samo slova odvojena zarezom";
                    $greska = true;
                    break;
                }
            }
        }
        if ($greska === false) {
            include "funkcije.php";
            if (ponavljanje($ulaz, $trazi, $broji) != -1) {
                $result = ponavljanje($ulaz, $trazi, $broji);
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
?>
<?php
if ($showForm) {
?>
<form method="get" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
<label for="ulaz">Ulazni niz znakova (string):
    <input type="text" name ="ulaz" value="<?php echo $ulaz; ?>">
    <span class ="error">* <?php echo $ulaz_err; ?></span>
</label><br><br>
    <label for="trazi">Znak pretraživanja::
        <input type="text" name ="trazi" value="<?php echo $trazi; ?>">
        <span class="error">* <?php echo $trazi_err; ?></span>
    </label><br><br>
    <label for="broji">Znakovi koje broji (odvojeni zarezom):
        <input type="text" name ="broji" value="<?php echo $broji; ?>">
        <span class="error">* <?php echo $broji_err; ?></span>
    </label><br><br>
    <input type ="submit"><br>
    <?php
    if ($greska === true) {
        echo "Greška";
    }
    ?>

</form>
<?php }
else echo $result;
?>
</body>
</html>


