<?php
	$url[] = 'http://127.0.0.1:80/php.php';
	$url[] = 'http://127.0.0.1:81/php.php';
	$url[] = 'http://127.0.0.1:82/php.php';
	
	$is_only_err 		= false;  // true => only err items; false => all items
	$compair_only_err	= false;	
	$compair_list = [
						'general' 	=> 
							[
								'mod' => ', ', 
								'ext' => ', ',
							],
						'ini' 		=> 
							[
								'disable_functions' => ',',
							],
						];
						
	include('inc/template.php');
	include('inc/curl.php');
	$curl = new cURL();
	$tpl = new Template("style/tpl/" );
	
	$php = [];
	foreach($url as $ukey => $uval)
	{
		//$arr = json_decode( @file_get_contents($uval, 0, stream_context_create(["http"=>["timeout"=>2]])), true );
		$arr =  json_decode($curl->get($uval), true);
		$php = array_merge_recursive($php, $arr);
		foreach($compair_list as $ckeys => $cvals)
		{
			foreach($cvals as $ckey => $cval)
			{
				
				if(isset($arr[$ckeys][$ckey]))
				{
					foreach(current($arr[$ckeys][$ckey]) as $cky => $clv)
					{
						$exp = explode($cval, $clv);
						foreach($exp as $ky => $vl)
						{
							$all[$ckeys.'--'.$ckey][$vl] = $vl;
						}
						ksort($all[$ckeys.'--'.$ckey]);
					}
				}
			}
		}
	}
	
	foreach(current(current($php)) as $keys => $vals)
	{
		$keyler[$keys] = '';
		$ip[$keys] = current($vals);
		
	}
	foreach($php as $keys => $vals)
	{
		$first = true;
		foreach($vals as $key => $val)
		{
			$err = array_map("unserialize", array_unique(array_map("serialize", $val)));
			$error = (  count($val) === count($url) and count($err) === 1)? false : true;
			if( !$is_only_err or $error)
			{
				if($first)
				{
					$tpl->assign_block_vars('list', [ 'header' => $keys ] );
					foreach($ip as $ikey => $ival)
					{
						$tpl->assign_block_vars('list.head', [ 'IP' => $ival] );
					}
					$first = false;
				}
				$tpl->assign_block_vars('list.lst', [ 'first_td' => $key ] );
				if(count($val) != count($url))
				{
					foreach($keyler as $cckey => $ccval)
					{
						foreach(current($val) as $ckey => $cval)
						{
							$newval[$cckey][$ckey] = isset($val[$cckey][$ckey]) ? $val[$cckey][$ckey] : '';
						}
					}
					$val = $newval;
				}
				foreach($val as $ky => $vl)
				{
					$tpl->assign_block_vars('list.lst.lt', [ 'w' => ceil(80/count($url)) ] );
					
					foreach($vl as $k => $v)
					{
						if(in_array($key, array_keys($compair_list[$keys]) ))
						{
							$exp = explode($compair_list[$keys][$key], $v);
							$diff = array_diff( $all[$keys.'--'.$key] , $exp);
							$v = $compair_only_err ? [] : $all[$keys.'--'.$key] ;
							foreach($diff as $dkey => $dval)
							{
								$color = ($is_only_err and $compair_only_err)? '' : 'class="w3-text-red"';
								$v[$dval] = "<span {$color}>".$dval.'</span>';
							}
							unset($v['']);
							$v = implode($compair_list[$keys][$key]. ' ', $v) ;
						}
						else
						{
							
							if(!$is_only_err and $error)
							{

								$v = "<span class='w3-text-red'>{$v}</span>";
							}
						}
						$tpl->assign_block_vars('list.lst.lt.l', [ 'item' => "{$v}" ] );
					}
				}
			}
		}
	}
	$tpl->set_filenames([ 'body' => 'comparison.html' ]);
	$tpl->pparse('body');
?>
	
