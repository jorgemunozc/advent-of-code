<?php

declare(strict_types=1);

function nextWord(string $from): string
{
    $strArr = str_split($from);
    $strLen = strlen($from);
    $newlastCharCod = mb_ord($strArr[$strLen - 1]) + 1;
    if ($newlastCharCod === 105 | $newlastCharCod === 108 || $newlastCharCod === 111) {
        $newlastCharCod += 1;
    }
    if ($newlastCharCod > 122) {
        return nextWord(substr($from, 0, $strLen - 1)) . 'a';
    }
    return substr($from, 0, $strLen - 1) . mb_chr($newlastCharCod);
}

function test()
{
    $str = 'aaa';
    while ($str != 'xxx') {
        $str = nextWord($str);
        printf("%s\n", $str);
    }
}

test();
