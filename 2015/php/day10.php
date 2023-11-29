<?php

declare(strict_types=1);

function lookAndSay(string $initialNumber, int $timesToPlay): int
{
    $numToString = $initialNumber;
    for ($i = 0; $i < $timesToPlay; $i += 1) {
        $matches = [];
        $j = 0;
        foreach (str_split($numToString) as $digit) {
            if (empty($matches)) {
                $matches[] = $digit;
            } elseif (str_contains($matches[$j], $digit)) {
                $matches[$j] .= $digit;
            } else {
                $matches[] = $digit;
                $j += 1;
            }
        }
        $numToString = "";
        foreach ($matches as $match) {
            $numToString .= strlen($match) . $match[0];
        }
    }
    return strlen($numToString);
}

function lookAndSay2(string $initialNumber, int $timesToPlay): int
{
    $numToString = $initialNumber;
    for ($i = 0; $i < $timesToPlay; $i += 1) {
        $matches = [];
        $j = 0;
        foreach (str_split($numToString) as $digit) {
            if (empty($matches)) {
                $matches[] = [$digit, 1];
            } elseif ($matches[$j][0] === $digit) {
                $matches[$j][0] += 1;
            } else {
                $matches[] = [$digit, 1];
                $j += 1;
            }
        }
        $numToString = "";
        foreach ($matches as $match) {
            $numToString .= $match[1] . $match[0];
        }
    }
    return strlen($numToString);
}

function test()
{
    $n = "1113122113";
    // $n = 1;
    printf("%d\n", lookAndSay($n, 50));
}

test();
