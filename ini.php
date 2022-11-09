<?php
	//php 5.4
	/**
	$server[] = ['name' => '127.0.0.3',		'url'=> 'http://php5.dtsturkiye.com/symk/php.php'];
	$server[] = ['name' => '94.102.76.43',	'url'=> 'http://product.dtsturkiye.com//symk/php.php'];
	/**/
	
	//php 7.4 no ssl
	/**
	$server[] = ['name' => '10.180.64.157', 'url'=> 'http://dmctr.dtsturkiye.com/symk/info.php'];
	$server[] = ['name' => '127.0.0.1',		'url'=> 'http://php7.dtsturkiye.com/symk/info.php'];
	$server[] = ['name' => '94.102.76.44', 	'url'=> 'https://booking.dtsturkiye.com/symk/info.php'];
	$server[] = ['name' => '88.248.2.178', 	'url'=> 'http://webdtstr.dtsturkiye.com/symk/info.php'];
	/**/
	
	//php 7.4
	/**/
	$server[] = ['name' => '94.102.76.44', 	'url'=> 'https://94.102.76.44/symk/php.php'];
	$server[] = ['name' => '88.248.2.178', 	'url'=> 'https://88.248.2.178/symk/php.php'];
	$server[] = ['name' => '10.180.64.157', 	'url'=> 'https://10.180.64.157/symk/php.php'];
	//$server[] = ['name' => '10.180.64.169', 	'url'=> 'https://10.180.64.169/symk/php.php'];
	//$server[] = ['name' => '127.0.0.2',		'url'=> 'https://php8.dtsturkiye.com/symk/php.php'];
	$server[] = ['name' => '127.0.0.1',		'url'=> 'https://127.0.0.1/symk/php.php'];
	/**/
	
	//php 5 7 8
	/**
	$server[] = ['name' => '127.0.0.1 - Php 7',		'url'=> 'http://127.0.0.1/symk/php.php'];
	$server[] = ['name' => '127.0.0.2 - Php 8',		'url'=> 'http://127.0.0.2/symk/php.php'];
	//$server[] = ['name' => '127.0.0.3 - Php 5',		'url'=> 'http://127.0.0.3/symk/php.php'];
	/**/

	include('inc/template.php');
	include('inc/class.php');
	$ini = new compair();
	$ini->servers		= $server;
	$ini->is_only_err	= 1;
	$ini->hide_heads	= [];
	$ini->compair_hide_existing = true;
	$ini->compair	= ['MOD' => ', ', 'EXT' => ', ', 'SOFTWARE' => ', ', 'disable_functions' => ','];
	$ini->nohide_item	= ['IP', 'LOCAL IP', 'OS', 'PHP', 'APACHE', 'MYSQL', 'NEW SOFTWARE','GROUPS' ];

	$ini->tpl = new Template("style/tpl/");
	$ini->ini();
	
	$ini->tpl->set_filenames(array('body' => 'comparison.html'));
	$ini->tpl->pparse('body');
	
?>
