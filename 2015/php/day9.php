<?php

declare(strict_types=1);

function shortestDistance(): void
{
    $locations = [];
    $visitedLocations = [];
    foreach (file('inputs/day9.dat') as $distanceBetweenLocations) {
        [$path, $distance] = explode(" = ", $distanceBetweenLocations);
        [$locationA, $locationB] = explode(' to ', $path);
        if (isset($locations[$locationA])) {
            $locations[$locationA][$locationB] = $distance;
        } else {
            $locations[$locationA] = [$locationB => $distance];
        }
        $visitedLocations[$locationA] = false;
        $visitedLocations[$locationB] = false;
    }
    var_dump($locations);
}

shortestDistance();
