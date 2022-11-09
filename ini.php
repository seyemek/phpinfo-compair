<?php
	//php 5 7 8
	/**/
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
