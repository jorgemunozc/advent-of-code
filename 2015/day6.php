<?php

declare(strict_types=1);

function parseInstruction(string $rawInstruction): array
{
    $params = explode(' ', $rawInstruction);
    $endCoord = explode(',', array_pop($params));
    array_pop($params);
    $startFromCoord = explode(',', array_pop($params));
    $action = implode(' ', $params);
    return [$action, $startFromCoord, $endCoord];
}

function modifyTreeLights(array &$tree, string $instruction)
{
    if (count($tree) < 1000 || count($tree[0]) < 1000) {
        throw new RangeException("Invalid tree size");
    }
    [$action, $startFromCoord, $endCoord] = parseInstruction($instruction);
    for ($i = $startFromCoord[0]; $i <= $endCoord[0]; $i += 1) {
        for ($j = $startFromCoord[1]; $j <= $endCoord[1]; $j += 1) {
            $tree[$i][$j] = match ($action) {
                'turn on' => 1,
                'turn off' => 0,
                'toggle' => (int)!$tree[$i][$j]
            };
        }
    }
}

function modifyBrigthnessOfTreeLights(array &$tree, string $instruction)
{
    if (count($tree) < 1000 || count($tree[0]) < 1000) {
        throw new RangeException("Invalid tree size");
    }
    [$action, $startFromCoord, $endCoord] = parseInstruction($instruction);
    for ($i = $startFromCoord[0]; $i <= $endCoord[0]; $i += 1) {
        for ($j = $startFromCoord[1]; $j <= $endCoord[1]; $j += 1) {
            $tree[$i][$j] = match ($action) {
                'turn on' => $tree[$i][$j] + 1,
                'turn off' => max(0, $tree[$i][$j] - 1),
                'toggle' => $tree[$i][$j] + 2
            };
        }
    }
}

function modifyTreeLightsInBatch(array &$tree, array $instructions, callable $callback)
{
    foreach ($instructions as $instruction) {
        $callback($tree, $instruction);
    }
}

function numberOfLightsLitOnTree(array $tree): int
{
    $litUpLights = 0;
    foreach ($tree as $row) {
        foreach ($row as $light) {
            $litUpLights += $light;
        }
    }

    return $litUpLights;
}

function inputFromFile()
{
    return file('inputs/day6.dat');
}

function test()
{
    $newTree = array_fill(0, 1000, array_fill(0, 1000, 0));
    $input = [
        // 'turn on 0,0 through 999,999',
        // 'toggle 0,0 through 999,0',
        'turn on 499,499 through 500,500',
    ];
    $input = inputFromFile();
    modifyTreeLightsInBatch($newTree, $input, modifyTreeLights(...));
    // file_put_contents('outputs/day6.dat', sprintf("arbol Modificado:\n\t%s\n", json_encode($newTree)));
    printf("The three has %d lights on.\n", numberOfLightsLitOnTree($newTree));
}

function test2()
{
    $newTree = array_fill(0, 1000, array_fill(0, 1000, 0));
    $input = inputFromFile();
    modifyTreeLightsInBatch($newTree, $input, modifyBrigthnessOfTreeLights(...));
    // file_put_contents('outputs/day6.dat', sprintf("arbol Modificado:\n\t%s\n", json_encode($newTree)));
    printf("The three has %d lights on.\n", numberOfLightsLitOnTree($newTree));
}

test2();
