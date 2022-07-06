<?php
	$url[] = 'http://127.0.0.1:80/info.php';
	$url[] = 'http://127.0.0.1:81/info.php';
	$url[] = 'http://127.0.0.1:82/info.php';

	$is_err 			= 0; // 1 only err 0 all item
	$compair_only_err	= false;	// true only empty item, false all item
	$ignor_list_head	= [
							'PHP Variables',
							'Environment',
						];
	$ignor_list_item	= [
							'Apache Environment-PATH',
						];
	$compair_list 		= [
							'Loaded Modules' => ' ', 
							'disable_functions' => ',',
							'Protocols' => ', ',
							'Hashing Engines' => ' ',
							'Loaded plugins' => ',',
							'Interfaces' => ', ',
							'Classes' => ', ',
						];
	
	include('inc/template.php');
	include('inc/curl.php');
	$curl = new cURL();
	$tpl = new Template("style/tpl/" );
	
	$names = [];
	foreach($url as $key => $val)
	{
		$names[$key]= []; 
		
	}
	$php = [];
	foreach($url as $ukey => $uval)
	{
	//	$_html =  @file_get_contents(, 0, stream_context_create(["http"=>["timeout"=>2]]));
		$_html =  $curl->get($uval);
		$exp = explode('<h1>', $_html);
		$exp = explode('<h2>', $exp[1]);
		unset($exp[0]);
		foreach($exp as $key => $val)
		{
			preg_match_all('#^(.*)</h2>#m', $val, $matches, PREG_SET_ORDER, 0);
			$header = strip_tags($matches[0][1]);
			$doc = new DOMDocument();
			@$doc->loadHTML($val);
			$xpath=new DOMXPath($doc);
			foreach($xpath->query('//table/tr') as $tr)
			{
				$arr = array();
				foreach ($tr->childNodes as $tds)
				{
					if( @$tds->tagName == 'td')
					{
						foreach ($tds->childNodes as $td)
						{
							$td->textContent = trim($td->textContent) === 'On' ? '1' : trim($td->textContent);
							$td->textContent = trim($td->textContent) === 'Off' ? '0' : trim($td->textContent);
							$arr[] = (string)$td->textContent;
						}
					}
				}
				if(!empty($arr[0]) and count($arr)!== 5 )
				{
					$x = $arr[0];
					if(!in_array("{$header}-{$x}", $ignor_list_item) AND !in_array($header, $ignor_list_head))
					{
						if(in_array($x, array_keys($compair_list) ))
						{
							$exp = explode($compair_list[$x], $arr[1]);
							foreach($exp as $key => $val)
							{
								$all[$x][$val] = $val;
							}
							ksort($all[$x]);
						}
						if( $x === 'HTTPS')
						{
							print_r($arr);
						}
						unset($arr[0]);
						$php[$header][$x] = isset($php[$header][$x]) ? $php[$header][$x] : $names;
						$php[$header][$x][$ukey] = $arr;
					}
				}
			}
		}
	}
	foreach($php as $keys => $vals)
	{
		$first = true;
		foreach($vals as $key => $val)
		{
			$err = count(array_map("unserialize", array_unique(array_map("serialize", $val))));
			
			if( $err !== $is_err)
			{
				if($first)
				{
					$tpl->assign_block_vars('list', array('header' => $keys ));
					foreach($url as $ukey => $uval)
					{
						$tpl->assign_block_vars('list.head',  ['IP' => "{$uval}" ] );
					}
					$first = false;
				}
				$tpl->assign_block_vars('list.lst', array('first_td' => $key));
				
				foreach($val as $ky => $vl)
				{
					$vl = empty($vl) ? ['empty values'] : $vl;
					$tpl->assign_block_vars('list.lst.lt', array('w' => ceil(80/count($names)) ));
					foreach($vl as $k => $v)
					{
						if(in_array($key, array_keys($compair_list) ))
						{
							$exp = explode($compair_list[$key], $v);
							$diff = array_diff( $all[$key] , $exp);
							$v = $compair_only_err ? [] : $all[$key] ;
							foreach($diff as $dkey => $dval)
							{
								$color = ($is_err === 1 and $compair_only_err) ? '' : 'class="w3-text-red"';
								$v[$dval] = "<span {$color}>".$dval.'</span>';
							}
							unset($v['']);
							$v = implode($compair_list[$key]. ' ', $v) ;
						}
						else
						{
							if($err !== 1 and $is_err !== 1)
							{
								$v = "<span class='w3-text-red'>{$v}</span>";
							}
						}
						
						$tpl->assign_block_vars('list.lst.lt.l', array('item' => $v));
					}
				}
			}
		}
	}
	$tpl->set_filenames(array('body' => 'comparison.html'));
	$tpl->pparse('body');
?>
