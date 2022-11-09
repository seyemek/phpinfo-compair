<?php
class compair {
	public $servers			= [];
	public $names			= [];
	public $phpinfos		= [];
	public $err_color		= 'w3-text-red';
	public $is_only_err		= 0; //0 all item, 1 only err, 2 only no-err
	public $hide_heads		= [] ;//['PHP Variables', 'Apache Environment', 'mysqlnd'];
	public $nohide_item		= [] ;//['IP', 'LOCAL IP', 'OS', 'PHP', 'APACHE', 'MYSQL', 'NEW SOFTWARE' ];
	public $compair_hide_existing= true;
	public $compair	= [] ;
	var $tpl;
	function curl($url)
	{
		$process = curl_init($url);
		curl_setopt($process, CURLOPT_SSL_VERIFYHOST, false); // Ignore cert errors?
		curl_setopt($process, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($process, CURLOPT_CUSTOMREQUEST, "GET");     
		curl_setopt($process, CURLOPT_RETURNTRANSFER, true);            
		curl_setopt($process, CURLOPT_USERPWD, "username:password");      
		$return = curl_exec($process);
		curl_close($process);
		return $return;
	}
	function ini()
	{
		$ini = [];
		foreach($this->servers as $server_key => $server_values)
		{
			$arr = json_decode( $this->curl($server_values['url']), true);
			$symk[] =  key( current( current($arr) ) );
			$ini = array_merge_recursive($ini, $arr);
		}
		foreach($ini as $keys => $vals)
		{
			foreach($vals as $key => $val)
			{
				foreach($symk as $ky => $vl)
				{
					
					$ini[$keys][$key]['c'] = isset($ini[$keys][$key]['c']) ? $ini[$keys][$key]['c'] : 1;
					if(!isset($val[$vl]))
					{
						$ini[$keys][$key][$vl] = array_fill(0, $ini[$keys][$key]['c'], 'unsupported');
					}
					else
					{
						$ini[$keys][$key]['c'] = count($ini[$keys][$key][$vl]);
					}
				}
			}
		}
		$this->phpinfos = $ini;
		$this->get_template();
	}
	function info()
	{
		$this->names = [];
		
		foreach($this->servers as $key => $val)
		{
			$this->names['c'] = 0; 
			$this->names[$key][0] = 'unsupported'; 
			$this->names[$key][1] = 'unsupported'; 
			$this->names[$key][2] = 'unsupported'; 
		}
		$this->get_info();
	}
	function get_info()
	{
		foreach($this->servers as $server_key => $server_values)
		{
			$_html =  $this->curl($server_values['url']);
			$exp = explode('<h1>', $_html);
			$exp = explode('<h2>', $exp[1]);
			unset($exp[0]);
			foreach($exp as $key => $val)
			{
				preg_match('#^(.*)</h2>#m', $val, $matches);
				$header = strip_tags($matches[1]);
				$doc = new DOMDocument();
				@$doc->loadHTML($val);
				$xpath=new DOMXPath($doc);
				foreach($xpath->query('//table/tr') as $tr)
				{
					$line_data = [];
					foreach ($tr->childNodes as $tds)
					{
						if( @$tds->tagName == 'td')
						{
							foreach ($tds->childNodes as $td)
							{
								$str = trim((string)$td->textContent);
								$str = $str === '1' ? 'On' : $str;
								$str = $str === '0' ? 'Off' : $str;
								$line_data[] = $str;
							}
						}
					}
					if(count($line_data) === 2 or count($line_data) === 3  )
					{
						$x = $line_data[0];
						
						$this->phpinfos[$header][$x] 				= isset($this->phpinfos[$header][$x]) ? $this->phpinfos[$header][$x] : $this->names;
						$this->phpinfos[$header][$x]['c'] 			= $this->phpinfos[$header][$x]['c'] < (count($line_data) -1) ? (count($line_data) -1) : $this->phpinfos[$header][$x]['c'];
						$this->phpinfos[$header][$x][$server_key] 	= array_replace_recursive( $this->phpinfos[$header][$x][$server_key], $line_data );
					}
					
				}
			}
		}
		$this->get_template();
	}
	function get_template()
	{
		foreach($this->phpinfos as $header => $group_item)
		{
			if(!in_array("{$header}", $this->hide_heads) )
			{
				$this->tpl->assign_block_vars('list', array('header' => $header ));
				foreach($this->servers as $server)
				{
					$this->tpl->assign_block_vars('list.head',  ['IP' => "{$server['name']}" ] );
				}
				
				foreach($group_item as $first_td => $order_tds)
				{
					if('GROUPSz' == $first_td)
					{
						echo $first_td."\n";
						print_r($order_tds);
					}
					$cee = isset($order_tds['c']) ? $order_tds['c'] : 2 ;
					$cee = @$order_tds['c'] ;
					unset($order_tds['c']);
					$err = count(array_map("unserialize", array_unique(array_map("serialize", $order_tds))));
					if('GROUPSz' == $first_td)
					{
						print_r(  array_unique(array_map("serialize", $order_tds))  );
						var_dump($err);
					}
					$err = $err !== 1 ? true : false;
					if('GROUPSz' == $first_td)
					{
						
						var_dump($err);
					}
					
					
					switch ($this->is_only_err)
					{
						case 0:
							$status = true;
						break;
						case 1:
							$status = $err ? true : false;
						break;
						case 2:
							$status = $err ? false : true;
						break;
					};
					if(in_array("{$first_td}", $this->nohide_item) )
					{
						$status = true;
					}
					if($status)
					{
						$this->tpl->assign_block_vars('list.lst', array('first_td' => $first_td));
						if(in_array("{$first_td}", array_keys($this->compair )) )
						{
							foreach($order_tds as $key => $td)
							{
								$exp = explode($this->compair[$first_td], $td[1]);
								foreach($exp as $key => $val)
								{
									$all[$val] = '';
								}
							}
							ksort($all);
							foreach($order_tds as $key => $td)
							{
								$exp = array_flip(explode($this->compair[$first_td], $td[1]));
								$new = [];
								foreach($all as $ky => $td)
								{
									if(!isset($exp[$ky]) and $this->compair_hide_existing)
									{
										$new[] =  "<span class=\"{$this->err_color}\">{$ky}</span>";
									}
									elseif(!$this->compair_hide_existing)
									{
										$new[] =  '<span class="'. (isset($exp[$ky]) ? '' : $this->err_color).'" >'."{$ky}</span>";
									}
									
								}
								$order_tds[$key][1] = implode(', ', $new);
								
							}
							unset($all);
						}
						foreach($order_tds as $ky => $td)
						{
							
							$this->tpl->assign_block_vars('list.lst.lt', array('w' => ceil(80/(count($order_tds))) ));
							unset($td[0]);
							if($cee == 1)
							{
								unset($td[2]);
							}
							foreach($td as $k => $v)
							{
								if(in_array("{$first_td}", array_keys($this->compair )) )
								{
									$this->tpl->assign_block_vars('list.lst.lt.l', array('item' => $v));
								}
								else
								{
									$this->tpl->assign_block_vars('list.lst.lt.l', array('item' => '<span class="'.($err ? $this->err_color : '').'">'."{$v}</span>"));
								}
							}
						}
					}
				}
			}
		}
		
		$old = $this->tpl->_tpldata['list.'];
		unset($this->tpl->_tpldata['list.']);
		foreach($old as $key => $val)
		{
			if(isset($val['lst.']))
			{
				$this->tpl->_tpldata['list.'][] = $val ;
			}
		}
	}
}
?>