<?php

function smarty_function_filelist($params, &$smarty)
{
	$sort_key = 'name'; $sort_order = 1;

	$o = isset($_GET['o']) ? strtolower(trim($_GET['o'])) : 'name';
	if(preg_match('/(!)?(name|date|size)/', $o, $matches))
	{
		$sort_order = ($matches[1] == '!') ? -1 : 1;
		$sort_key = $matches[2];
	}

	if($hDir = opendir($params['path']))
	{
		$sort_id = (($sort_order == -1) ? '!' : '') . $sort_key;
		if(!$smarty->is_cached('files.tpl', $params['path'] . $o))
		{
			$files = array();
      $total = 0;
			while(($filename = readdir($hDir)) !== FALSE)
			{
				$fullpath = $params['path'] . $filename;
				$url = $params['url'] . rawurlencode($filename);

				if(fnmatch('.*', $filename)) continue;
				if(fnmatch('*.tpl*', $filename)) continue;

				$files[] = array('filename' => $filename,
												 'date' => filemtime($fullpath),
												 'linkto' => $url,
												 'size' => filesize($fullpath)
					              );
        $total += filesize($fullpath);
			}
			closedir($hDir);

			usort($files, create_function('$x, $y',
                                    "return compare_file_item($sort_key, $sort_order, \$x, \$y);"));

			$smarty->assign('url', $params['url']);
      $smarty->assign('total', $total);
      $smarty->assign('count', count($files));
			$smarty->assign('files', $files);
			$smarty->assign('order', $sort_id);
		}
		return $smarty->fetch('files.tpl', $params['path'] . $o);
	}
}

function compare_file_item($key, $order, $x, $y)
{
	switch($key)
	{
	case 'size':
	case 'date':
		// 必ず白黒つける
		if($x[$key] != $y[$key])
			return $order * (($x[$key] < $y[$key]) ? -1 : 1);
	case 'name':
	default:
		return $order * strcasecmp($x['filename'], $y['filename']);
	}
}

