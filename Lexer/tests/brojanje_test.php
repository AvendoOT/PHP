<?php
declare(strict_types=1);

function assertGetOnUrlContains(string $url, ...$needles): ?string
{
    $content = file_get_contents($url);

    foreach ($needles as $needle) {
        if (false === strpos($content, $needle)) {
            return 'Ocekivano nije dobiveno u url-u ' . $url;
        }
    }

    return null;
}
return [
    [
        'Obrazac nije ranjiv na XSS',
        function (): ?string
        {
            return assertGetOnUrlContains(
                'http://127.0.0.1/DZ_03/brojanje.php?ulaz=!<>&trazi=!<>&broji=!<>',
                '!&lt;&gt;'
            );
        }
    ],
    [
        'Nevalidni znak ispise poruku i ponovo obrazac',
        function (): ?string
        {
            return assertGetOnUrlContains(
                'http://127.0.0.1/DZ_03/brojanje.php?ulaz=!<>&trazi=!<>&broji=!<>',
                'Samo slova','Ulazni niz ne smije biti prazan!','Unesite znak za pretraživanje', 'Samo jedno slovo', 'Samo slovo',
                '<input type="text" name="ulaz"', '<input type="text" name="trazi"','<input type="text" name="broji"'
            );
        }
    ],
    [
        'Ispravno broji',
        function (): ?string {
            $expected = <<<'HTML'
<pre>8</pre>
HTML;

            return assertGetOnUrlContains(
                'http://127.0.0.1/DZ_03/brojanje.php?ulaz=aaaabbacdz&trazi=z&broji=a,b,c',
                $expected
            );
        },
    ],
];