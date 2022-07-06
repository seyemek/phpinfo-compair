<?php
$uniq = uniqid();
$ext = get_loaded_extensions();
sort($ext);
$mod = apache_get_modules();
sort($mod);
foreach(ini_get_all() as $key => $val)
{
	unset($val['access']);
	$ini[$key][$uniq] = $val;
}
$arr = [
		'general'=>[ 
			'ip'	=> [ $uniq => [ "{$_SERVER['SERVER_ADDR']}:{$_SERVER['SERVER_PORT']}" ] ],
			'php'	=> [ $uniq => [ PHP_VERSION ]],
			'apache'=> [ $uniq => [ apache_get_version() ]],
			'os'	=> [ $uniq => [ PHP_OS ]],
			'ext'	=> [ $uniq => [ implode( ', ', $ext) ]],
			'mod'	=> [ $uniq => [ implode( ', ', $mod) ]],
			],
		'ini'=> $ini,
		];
echo json_encode($arr);
?>
