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
                'http://127.0.0.1/DZ_03/md.php?ulaz=!<>',
                '!&lt;&gt;'
            );
        }
    ],
    [
        'Nevalidni znak ispise poruku i ponovo obrazac',
        function (): ?string
        {
            return assertGetOnUrlContains(
                'http://127.0.0.1/DZ_03/md.php?ulaz=!<>',
                'Nedozvoljeni znak', 'Samo tekstualne datoteke', 'Max size 1 KB',
                '<input type="file" name="ulaz"'
            );
        }
    ],
    [
        'Ispravno se generira graf',
        function (): ?string {
            $expected = <<<'HTML'
<pre><h1> Zaglavlje 1 </h1></pre>
HTML;
            if (file_exists('./transformirani.html')) {
            return assertGetOnUrlContains(
                'http://127.0.0.1/DZ_03/md.php?ulaz=./transformirani%20.html',
                $expected
            );
                }
        },
    ],
];