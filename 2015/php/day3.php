<?php

declare(strict_types=1);

//>: east
//^: north
//v: south
//<: west
function delivering(array $instructions): int
{
    $currentCoordinate = [0,0];
    $numberOfDeliveriesPerHouse = [
        '(0,0)' => 1,
    ];
    foreach ($instructions as $instruction) {
        match ($instruction) {
            '^' => $currentCoordinate[1] += 1,
            '>' => $currentCoordinate[0] += 1,
            '<' => $currentCoordinate[0] -= 1,
            'v' => $currentCoordinate[1] -= 1,
        };
        $currentHouseCoords = "($currentCoordinate[0],$currentCoordinate[1])";
        $numberOfDeliveriesPerHouse[$currentHouseCoords] = isset($numberOfDeliveriesPerHouse[$currentHouseCoords]) ? $numberOfDeliveriesPerHouse[$currentHouseCoords] + 1 : 1;
        // $data = sprintf("x: %d - y: %d\n", ...$currentCoordinate);
        // file_put_contents('outputs/day3.dat', $data, FILE_APPEND);
    }
    // printf("x: %d, y: %d\n", $currentCoordinate[0], $currentCoordinate[1]);
    return count(array_filter($numberOfDeliveriesPerHouse, function ($numberOfGifts) {
        return $numberOfGifts > 0;
    }));
}

function deliveringWithRoboSanta(array $instructions): int
{
    $currentCoordinateSanta = [0,0];
    $currentCoordinateRoboSanta = [0,0];
    $numberOfDeliveriesPerHouse = [
        '(0,0)' => 2,
    ];
    foreach ($instructions as $index => $instruction) {
        $movingCurrently = $index % 2 === 0 ? 'Santa' : 'RoboSanta';
        $currentCoordinate = "currentCoordinate$movingCurrently";
        match ($instruction) {
            '>' => ${$currentCoordinate}[0] += 1,
            '^' => ${$currentCoordinate}[1] += 1,
            '<' => ${$currentCoordinate}[0] -= 1,
            'v' => ${$currentCoordinate}[1] -= 1,
        };
        $currentHouseCoords = sprintf("(%d,%d)", ...${$currentCoordinate});
        $numberOfDeliveriesPerHouse[$currentHouseCoords] = isset($numberOfDeliveriesPerHouse[$currentHouseCoords]) ? $numberOfDeliveriesPerHouse[$currentHouseCoords] + 1 : 1;
    }
    printf("total houses visited: %d\n", count($numberOfDeliveriesPerHouse));
    return count(array_filter($numberOfDeliveriesPerHouse, function ($numberOfGifts) {
        return $numberOfGifts > 0;
    }));
}

function fromFile(): array
{
    $rawInput = file_get_contents('inputs/day3.dat');
    return str_split($rawInput);
}

function test()
{
    printf("Houses with at least one gift: %d\n", deliveringWithRoboSanta(fromFile()));
}

test();
