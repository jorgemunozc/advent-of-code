<?php

declare(strict_types=1);

function getMd5(string $input = ''): int
{
    $i = 0;
    do {
        $i++;
        $md5 = md5("$input$i");
    } while (substr_compare($md5, "000000", 0, 6));
    return $i;
}

function test()
{
    $input = "ckczppom";
    printf("Min number for %s is: %d\n", $input, getMd5($input));
}

test();
