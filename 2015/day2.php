<?php

declare(strict_types=1);

function getWrappingPaperForGiftOfDimensions(int $length, int $width, int $height): int
{
    $smallestSide = min($length * $width, $width * $height, $height * $length);
    $giftSurface = (2 * $length * $width) + (2 * $width * $height) + (2 * $height * $length);
    return $giftSurface + $smallestSide;
}

function getTotalWrappingPaperNeeded(array $giftsDimensions): int
{
    $totalPaper = 0;
    foreach ($giftsDimensions as $dimension) {
        $totalPaper += getWrappingPaperForGiftOfDimensions(...$dimension);
    }
    return $totalPaper;
}

function getTotalFeetOfRibonPerGift(int $length, int $width, int $height): int
{
    $total = 0;
    $totalRibbonForBow = $length * $width * $height;
    $sides = [$length, $width, $height];
    sort($sides, SORT_NUMERIC);
    $shortestSides = array_slice($sides, 0, 2);
    foreach ($shortestSides as $side) {
        $total += $side * 2;
    }
    return $total + $totalRibbonForBow;
}

function getTotalFeetOfRibonForNGifts(array $giftsDimensions): int
{
    $totalRequired = 0;
    foreach ($giftsDimensions as $gift) {
        $totalRequired += getTotalFeetOfRibonPerGift(...$gift);
    }
    return $totalRequired;
}

function shortestPath()
{
//
}

function fromFile(): array
{
    $RawInput = file("inputs/day2.dat");
    $input = array();
    foreach ($RawInput as $line) {
        $input[] = array_map('intval', explode("x", $line));
    }
    return $input;
}
function test()
{
    $input = fromFile();
    printf("Necessary paper is %d feet\n", getTotalWrappingPaperNeeded($input));
}

function test2()
{
    $input = [2,3,4];
    printf("%d\n", getWrappingPaperForGiftOfDimensions(...$input));
}

function testOneRibbonMaterial()
{
    printf("%d\n", getTotalFeetOfRibonPerGift(1, 1, 10));
}

function testTotalRibbonNeeded()
{

    printf("%d\n", getTotalFeetOfRibonForNGifts(fromFile()));
}

testTotalRibbonNeeded();
