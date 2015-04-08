<?php

	require_once __DIR__ . '/../vendor/autoload.php'; // Autoload files using Composer autoload

	$image = new Respimg(__DIR__ . '/test.jpg');
	$image->betterResize(300, 0, false);
	$image->writeImage(__DIR__ . '/test-300.jpg');
	echo "done\n";

?>
