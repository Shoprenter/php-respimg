<?php

	require('../src/Respimg.class.php');
	$image = new Respimg('test.jpg');
	$image->betterResize(300, 0, false);
	$image->writeImage('test-300.jpg');
	print 'done';

?>
