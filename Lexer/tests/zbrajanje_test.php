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
                'http://127.0.0.1/DZ_03/zbrajanje.php?ulaz=!<>',
                '!&lt;&gt;'
            );
        }
    ],
    [
        'Nevalidni znak ispise poruku i ponovo obrazac',
        function (): ?string
        {
            return assertGetOnUrlContains(
                'http://127.0.0.1/DZ_03/zbrajanje.php?ulaz=!<>',
                'Samo brojevi','Ulazni niz ne smije biti prazan!',
                '<input type="text" name="ulaz"'
            );
        }
    ],
    [
        'Ispravno broji',
        function (): ?string {
            $expected = <<<'HTML'
<pre>45</pre>
HTML;

            return assertGetOnUrlContains(
                'http://127.0.0.1/DZ_03/zbrajanje.php?ulaz=123456789',
                $expected
            );
        },
    ],
];