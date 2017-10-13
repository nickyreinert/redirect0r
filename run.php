<?php


	ob_start();
	
	$limit = 100000;
	
	$sleep = 2;
	
	$repeat = 5;
	
	$growHtaccess = TRUE;
	
	$chunks = 10;
	
	$basePath = str_replace(basename(__FILE__), '', $_SERVER['REQUEST_URI']);
	
	$currentUrl = str_replace(basename(__FILE__), '', $_SERVER['SCRIPT_URI']);
	
	$dummyPath = 'foobar';

	$htaccess = 'RewriteEngine On'. PHP_EOL;
	
	$countRows = 1;
	
	echo 'Start. <br/ ><br/ >';

	
	for ($i = 1; $i <= $limit; ++$i)
	{	
		if ($growHtaccess === TRUE)
		{
			
			$htaccess .= 'Redirect 302 '.$basePath.$dummyPath.$i.'/'.' '.$currentUrl.'index.php'.PHP_EOL;
			
		} else {

			$countRows	= 1;
		
			$htaccess = 'RewriteEngine On'. PHP_EOL;

			$htaccess = 'Redirect 302 '.$basePath.$dummyPath.$i.'/'.' '.$currentUrl.'index.php'.PHP_EOL;
		
		}
		
		if ($i === 1) 
		{
			echo 'Redir-Rule: '. $htaccess . '<br />';
			echo 'Test-URL: '.$currentUrl.$dummyPath.$i.'/ <br /><br />';
			echo 'rows;delay [ms];sleep [s] <br />';

	
		}

		
		if ($i % round($limit / $chunks) === 0 OR $i === 1)
		{
			file_put_contents('.htaccess', $htaccess);
			
			for ($j = 1; $j <= $repeat; ++$j)
			{
				$startTime = microtime(true);
				
				$dummy = file_get_contents($currentUrl.$dummyPath.$i.'/');

				$endTime = round(1000 * (microtime(true) - $startTime));
				
				echo str_pad('',4096);
				ob_flush();
				flush();
				
				echo $countRows.';'.$endTime.';'.$sleep.'<br />';
				
				sleep($sleep);
				
			}
			
		}
		
		++$countRows;
		
	}
	
	echo 'End.';
