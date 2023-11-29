<?php
declare(strict_types=1);

function lookAndSay(string $str)
{
	return preg_replace_callback('#(\d)\1*#', function ($matches) {	
		return strlen($matches[0]).$matches[1];
    }, $str);
}

$num = "1113122113";

foreach (range(1 ,60) as $i) {
	$num = lookAndSay($num);
}
echo strlen($num) . PHP_EOL;