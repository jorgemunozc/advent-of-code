<?php

declare(strict_types=1);

function isWordNice(string $word): int
{
    $disallowedWords = '/ab|cd|pq|xy/';
    if (preg_match($disallowedWords, $word)) {
        return 0;
    }
    if (preg_match_all('/[aeiou]/', $word) > 2 && preg_match('/([a-z])\1(?!\1)/', $word)) {
        return 1;
    }
    return 0;
}

function isWordNice2(string $word): int
{
    if (preg_match('/([a-z]{2}).*\1/', $word) && preg_match('/([a-z]).\1/', $word)) {
        return 1;
    }
    return 0;
}

function numberOFNiceWords(array $words, int $version = 1): int
{
    $niceWords = 0;
    foreach ($words as $word) {
        $niceWords += match ($version) {
            1 => isWordNice($word),
            2 => isWordNice2($word),
            default => 0
        };
    }
    return $niceWords;
}

function fromFile(): array
{
    return file('inputs/day5.dat');
}

function test()
{
    printf("%s\n", numberOFNiceWords(fromFile(), 2));
}

test();
// echo preg_match_all('/[aeiou]+/', "ugknbfddgicrmopn");
// echo "\n";
// echo preg_match('/([a-z])\1(?!\1)/', "ugknbfddgicrmopn");
