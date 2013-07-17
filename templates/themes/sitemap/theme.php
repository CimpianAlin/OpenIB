<?php
	require 'info.php';
	
	function sitemap_build($action, $settings) {
		global $config;
		
		// Possible values for $action:
		//	- all (rebuild everything, initialization)
		//	- news (news has been updated)
		//	- boards (board list changed)
		//	- post (a post has been made)
		
		$boards = explode(' ', $settings['boards']);
		
		$threads = array();
		
		foreach ($boards as $board) {
			$query = query(sprintf("SELECT `id` AS `thread_id`, (SELECT `time` FROM `posts_%s` WHERE `thread` = `thread_id` OR `id` = `thread_id` ORDER BY `time` DESC LIMIT 1) AS `lastmod` FROM `posts_%s` WHERE `thread` IS NULL", $board, $board)) or error(db_error());
			$threads[$board] = $query->fetchAll(PDO::FETCH_ASSOC);
		}
				
		file_write($settings['path'], Element('themes/sitemap/sitemap.xml', Array(
			'settings' => $settings,
			'config' => $config,
			'threads' => $threads,
			'boards' => $boards,
		)));
	}
