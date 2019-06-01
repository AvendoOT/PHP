<?php
declare(strict_types = 1);
function ponavljanje(string $ulaz, string $trazi, string $broji): int {
    $ulaz = strtolower($ulaz);
    $trazi = strtolower($trazi);
    $duljna = strlen($ulaz);
    $broji = explode(",", $broji);
    $brojac = 0;
    $iter = 0;
    $broj_trazi = 0;
    foreach ($broji as $proba) {
        if ($proba === $trazi) {
            return -1;
        }
    }
    for ($ajmo = 0; $ajmo < $duljna; $ajmo++) {
        if ($ulaz[$ajmo] == $trazi) {
            $broj_trazi++;
        }
    }
    if ($broj_trazi != 0) {
        while (
            ($iter < $duljna) &&  ($ulaz[$iter] != $trazi)){
            foreach ($broji as $br) {
                if ($ulaz[$iter] === strtolower($br)) {
                    $brojac++;
                }
            }
            $iter++;
        }
        return $brojac;
        //print $broji;
    }
    else return -1;
    //return $broji;
}
function zbroji(int $ulaz): ?string {
    if ($ulaz < 0) return null;
// castamo u string
    $ulaz = (string)$ulaz;
    $brojac = 0;
    $duljina = strlen($ulaz);
    $izlaz = "";
    if (empty($ulaz)) {
        return -1;
    }
    for ($iter = 0; $iter<$duljina; $iter++) {
        $brojac += $ulaz[$iter];
    }
// pri ispisu vracamo u int
    for ($iter = 0; $iter < $duljina; $iter++) {
        if ($iter === $duljina-1) {
            // printf("%d = %d", (int)($ulaz[$iter]), $brojac);
            $izlaz.=(int)($ulaz[$iter])." = ".$brojac;
        }
        else {
            // printf("%d + ", (int)($ulaz[$iter]));
            $izlaz.=(int)($ulaz[$iter])." + ";
        }
    }
    return $izlaz;
}
function transformiraj(string $ulaz): ?string
{
    $paragraf = false;
    $header = false;
    $br_hash = 0;
    $italic = false;
    $bold = false;
    $izlaz = "";
    for ($iter = 0; isset($ulaz[$iter]); $iter++) {
        // escapeovi
            if ($ulaz[$iter] === "\\") {
                if (isset($ulaz[$iter + 1])) {
                    if ($ulaz[$iter + 1] === "*") {
                        //print $ulaz[$iter+1];
                        $izlaz .= $ulaz[$iter + 1];
                        $iter++;
                    } else if ($ulaz[$iter + 1] === "#") {
                        //print "#";
                        $izlaz .= $ulaz[$iter + 1];
                        $iter++;
                    } else if ($ulaz[$iter + 1] === "\\") {
                        // print "\\";
                        $izlaz .= "\\";
                        $iter++;
                    } else {
                        $izlaz .= $ulaz[$iter];
                    }
                }

            } else if ($ulaz[$iter] === "*" &&
                isset($ulaz[$iter+1]) &&
                $ulaz[$iter + 1] === "*") {
                if ($bold === true) {
                    if ($italic === true) {
                        $italic = false;
                        return null;
                        //exit("Greška");
                    }
                    $bold = false;
                    // printf("</strong>");
                    $izlaz .= "</strong>";
                } else {
                    // printf("<strong>");
                    $izlaz .= "<strong>";
                    $bold = true;
                }
                $iter = $iter + 1;
            } else if ($ulaz[$iter] === "*" &&
                isset($ulaz[$iter+1]) &&
                $ulaz[$iter + 1] != "*") {
                if ($italic === true) {
                    if ($bold === true) {
                        $bold = false;
                        return null;
                    }
                    $italic = false;
                    $izlaz .= "</i>";
                } else {
                    $izlaz .= "<i>";
                    $italic = true;
                }
            } else if ($ulaz[$iter] === "#" &&
                isset($ulaz[$iter-1]) ||
                $ulaz[$iter - 1] === "\n") {
                $br_hash = 1;
                while ($ulaz[$iter + $br_hash] === "#" &&
                    $br_hash < 7) {
                    $br_hash++;
                }
                $izlaz .= "<h" . $br_hash . ">";
                $header = true;
                $iter += $br_hash;
            } else if ($ulaz[$iter] === "\n") {
                if ($header === true) {
                    $header = false;
                    $paragraf = true;
                    $izlaz .= "</h" . $br_hash . "><p>";
                }
                if (isset($ulaz[$iter + 1])) {
                    if ($ulaz[$iter + 1] === "\n") {
                        if ($paragraf === false) {
                            $paragraf = true;
                            //print "<p>";
                            $izlaz .= "<p>";
                        } else {
                            $paragraf = false;
                            // print "</p>";
                            $izlaz .= "</p>";
                        }
                    }
                }
            } else {
                $izlaz .= $ulaz[$iter];
            }

        }
    return $izlaz;
}
