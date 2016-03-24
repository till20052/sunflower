<?php

$configuration = array(
	'GLOBAL' => array(
		'db_key' => 'master',
		'databases' => array(
			'master' => array(
				'hostname' => 'localhost',
				'port' => '3306',
				'username' => 'sunflower',
				'password' => 'nEAAFBMQWF9A2d4p',
				'database' => 'sunflower',
				'driver' => 'mysql'
			)
		)
	)
);

Config::set('Configuration', $configuration);
