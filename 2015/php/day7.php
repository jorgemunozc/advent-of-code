<?php

declare(strict_types=1);

function getWireValue(string $targetWireId, array &$instructions, array &$knownValuesInCircuit): int
{
    if (is_numeric($targetWireId)) {
        return (int) $targetWireId;
    }
    if (isset($knownValuesInCircuit[$targetWireId])) {
        return $knownValuesInCircuit[$targetWireId];
    }
    $wireWasFound = false;
    foreach ($instructions as $instruction) {
        ['op' => $operation, 'wireId' => $wireId] = parseInstruction($instruction);
        if ($targetWireId === $wireId) {
            $wireWasFound = true;
            break;
        }
    }
    if (! $wireWasFound) {
        throw new Exception('wire doesnt exist!');
    }
    $operationSegments = explode(' ', $operation);
    $gate = match (count($operationSegments)) {
        2 => $operationSegments[0],
        3 => $operationSegments[1],
        default => '',
    };
    $operands = match (count($operationSegments)) {
        1 => [$operationSegments[0]],
        2 => [$operationSegments[1]],
        3 => [$operationSegments[0], $operationSegments[2]],
        default => []
    };
    $result = match ($gate) {
        'AND' => getWireValue($operands[0], $instructions, $knownValuesInCircuit)
            & getWireValue($operands[1], $instructions, $knownValuesInCircuit),
        'OR' => getWireValue($operands[0], $instructions, $knownValuesInCircuit)
            | getWireValue($operands[1], $instructions, $knownValuesInCircuit),
        'NOT' => ~ getWireValue($operands[0], $instructions, $knownValuesInCircuit) & 0xFFFF,
        'LSHIFT' => getWireValue($operands[0], $instructions, $knownValuesInCircuit) << (int)$operands[1],
        'RSHIFT' => getWireValue($operands[0], $instructions, $knownValuesInCircuit) >> (int)$operands[1],
        '' => getWireValue($operands[0], $instructions, $knownValuesInCircuit),
        default => - 1,
    };
    $knownValuesInCircuit[$targetWireId] = $result;
    return $result;
}

function parseInstruction(string $instruction): array
{
    [$operation, $wire] = explode(' -> ', $instruction);
    return ['op' => $operation, 'wireId' => $wire];
}

function test()
{
    $input = file('inputs/day7.dat', FILE_IGNORE_NEW_LINES);
    $wire = 'a';
    $circuit = [];
    printf("%s: %d\n", $wire, getWireValue($wire, $input, $circuit));
}

function pretest()
{
    $circuit = [];
    $instructions = [
        'NOT y -> i',
        '123 -> x',
        'y RSHIFT 2 -> g',
        'x AND y -> d',
        'x LSHIFT 2 -> f',
        'x OR y -> e',
        '456 -> y',
        'NOT x -> h',
    ];
    echo getWireValue('g', $instructions, $circuit) . PHP_EOL;
}

test();
