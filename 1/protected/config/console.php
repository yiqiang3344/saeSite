<?php
require_once(dirname(__FILE__).'/constant.cfg.php');
// This is the configuration for yiic console application.
// Any writable CConsoleApplication properties can be configured here.
//
return CMap::mergeArray(
	require(dirname(__FILE__).'/main.php'),
	array(
        'name'=>'site1',
		'components'=>array(
			/* uncomment the following to provide test database connection
			'db'=>array(
				'connectionString'=>'DSN for test database',
			),
			*/
		),
	)
);