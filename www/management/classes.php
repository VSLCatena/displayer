<?php 
use Doctrine\Common\ClassLoader;

	require('../../vendor/doctrine/common/lib/Doctrine/Common/ClassLoader.php');

	$classLoader = new ClassLoader('Doctrine', '../../vendor/doctrine/');
	$classLoader->register();
?>