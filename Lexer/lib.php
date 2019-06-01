<?php

declare(strict_types=1);


function endsWithPHP(string $path): bool
{
    return substr($path, -4) === '.php';
}

function requireFile($testFile): array
{
    return require_once $testFile;
}

function collectTestsIn(string $path): ?array
{
    if (!is_readable($path)) {
        return null;
    }

    $path = realpath($path);

    if (is_file($path) && endsWithPHP($path)) {
        return [$path];
    }

    if (!is_dir($path)) {
        return null;
    }

    $toProcess = [$path];
    $collectedTests = [];

    while (!empty($toProcess)) {
        $path = array_pop($toProcess);

        $dir = opendir($path);

        if (!$dir) {
            return null;
        }

        while (false !== ($item = readdir($dir))) {
            if ('.' === $item || '..' === $item) {
                continue;
            }

            $itemPath = $path . '/' . $item;

            if (!is_readable($itemPath)) {
                continue;
            }

            if (is_file($itemPath) && endsWithPHP($item)) {
                $collectedTests[] = $itemPath;

                continue;
            }

            if (is_dir($itemPath)) {
                $toProcess[] = $itemPath;

                continue;
            }
        }

        closedir($dir);
    }

    return $collectedTests;
}

function runTests(array $testFiles): array
{
    $collected = [];
    $failedInGeneral = false;

    foreach ($testFiles as $testFile) {
        $testsToRun = requireFile($testFile);

        foreach ($testsToRun as [$testName, $closureToCall]) {
            $result = $closureToCall();
            $failedCurrent = is_string($result);

            $failedInGeneral = $failedInGeneral || $failedCurrent;

            /* [Test name, True ili false, null ili string s opisom greske],  */
            $collected[] = [$testName, $failedCurrent, $result];
        }
    }

    return [$failedInGeneral, $collected];
}

function printTestResult(array $runTests): void
{

    $totalTestCount = 0;
    $failedTestCount = 0;

    foreach ($runTests as [$testName, $failed, $result]) {
        $totalTestCount++;

        if ($failed) {
            $failedTestCount++;
        }

        printf('%s: %s', $testName, null === $result ? 'Ok' : 'Failed');

        if ($failed) {
            printf(' - %s', $result);
        }

        echo "\n";
    }

    printf("Total: %d\n", $totalTestCount);
    printf("Ok: %d\n", $totalTestCount - $failedTestCount);
    printf("Failed: %d\n", $failedTestCount);
    printf("Status: %s\n", $failedTestCount > 0 ? 'Failed' : 'Ok');

}
