<?php

declare(strict_types=1);

function inMemoryLength(string $stringLiteral): int
{
    $str = trim($stringLiteral, '"');
    $str = preg_replace('#\\\x[0-9a-f]{2}#', '%', $str);
    $str = str_replace('\"', '"', $str);
    $str = str_replace("\\\\", '\\', $str);
    return strlen($str);
}

function processStrings(array $strings): void
{
    $total = 0;
    foreach ($strings as $string) {
        eval("\$str = $string;");
        $total += strlen($string) - strlen($str);
    }
    printf("total: %d\n", $total);
}

function getSpaceEncondedStrings(array $strings): void
{
    $total = 0;
    foreach ($strings as $string) {
        $total += strlen(encoded($string)) - strlen($string);
        printf("encoded: %d - literal: %d\n", strlen(encoded($string)), strlen($string));
    }
    printf("Total encoded: %d\n", $total);
}

function encoded(string $original): string
{
    $str = str_replace('\\', "\\\\", $original);
    $str = str_replace('"', '\"', $str);
    return "\"$str\"";
}

function test()
{
    $input = file('inputs/day8.dat', FILE_IGNORE_NEW_LINES);
    processStrings($input);
}

function test2()
{
    $input = file('inputs/day8.dat', FILE_IGNORE_NEW_LINES);
    getSpaceEncondedStrings($input);
}

function pretest()
{
    $input = file('outputs/inputday8.dat', FILE_IGNORE_NEW_LINES);
    getSpaceEncondedStrings($input);
}

test2();
