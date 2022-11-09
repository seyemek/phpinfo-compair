<?php
	//php 5 7 8
	/**
	
	
	$server[] = ['name' => '127.0.0.1 - PHP 7',		'url'=> 'http://127.0.0.1/symk/info.php'];
	$server[] = ['name' => '127.0.0.2 - PHP 8',		'url'=> 'http://127.0.0.2/symk/info.php'];
	//$server[] = ['name' => '127.0.0.3 - PHP 5',		'url'=> 'https://127.0.0.3/symk/info.php'];
	/**/

	include('inc/template.php');
	include('inc/class.php');

	$info = new compair();
	
	$info->servers				= $server;
	$info->is_only_err			= 1; //0 all, 1 only err, 2 only no-err
	$info->hide_heads			= [];
	$info->hide_heads			= ['Apache Environment', 'PHP Variables', 'Environment' ];
	$info->compair				= ['Loaded Modules' => ' ', 'disable_functions' => ',', 'Protocols' => ', ', 'Hashing Engines' => ' ', 'Interfaces' => ', ', 'Classes' => ', '];
	$info->compair_hide_existing = true;
	$info->nohide_item			= ['Apache Version', 'Hostname:Port', 'Host', 'PHP Version'];

	$info->tpl = new Template("style/tpl/");
	$info->info();
	//print_r($info->phpinfos);
	//exit;
	$info->tpl->set_filenames(array('body' => 'comparison.html'));
	$info->tpl->pparse('body');
	
?>
