<?php

	ob_start();
	
	prepareSettings();

	checkIfDummyFolderExists();
	
	checkIfDummyFileExists();
	
	echo 'Start. <br/ ><br/ >';
	echo 'Test-URL: <a href="'.$settings->scheme.'://'.$settings->host.$settings->urlPath.$settings->dummyPath.'1/'.'">'.$settings->host.$settings->urlPath.$settings->dummyPath.'1/'.'</a> <br /><br />';
	echo 'rows;delay [ms];sleep [s] <br />';

	file_put_contents($settings->dummyPath.'.htaccess', $settings->htAccess->firstLine);

	for ($i = 1; $i <= $settings->limit; ++$i)
	{

		$settings->htAccess->lines .= PHP_EOL.'Redirect 302 '.$settings->urlPath.$settings->dummyPath.$i.'/'.' '.$settings->urlPath.$settings->dummyPath.'index.php';
			
		if ($i % round($settings->limit / $settings->chunks) === 0)
		{
		
			file_put_contents($settings->dummyPath.'.htaccess', $settings->htAccess->lines, FILE_APPEND);

			queryDummyUrl($i);

		}

		++$settings->lines;

	}

	echo 'End.';
	
	
	function queryDummyUrl($i)
	{
	    global $settings;

	    for ($j = 1; $j <= $settings->repeat; ++$j)
	    {
		    $startTime = microtime(true);

		    $randomIndex = rand(1, $i);
		    		    
		    $dummy = file_get_contents($settings->scheme.'://'.$settings->host.$settings->urlPath.$settings->dummyPath.$randomIndex.'/');
		    
		    $endTime = round(1000 * (microtime(true) - $startTime));

		    echo str_pad('',4096);
		    ob_flush();
		    flush();

		    echo $settings->lines.';'.$endTime.';'.$settings->coolDown.'<br />';

		    sleep($settings->coolDown);

	    }
	}
	

	function prepareSettings()
	{
	    global $settings;

	    $settings = json_decode(file_get_contents('config.json'));

	    $settings->scriptName = basename(__FILE__);

	    if ($settings->scheme == NULL) {
		
		$settings->scheme = $_SERVER['REQUEST_SCHEME'];
		
	    }
	    
	    $settings->urlPath = str_replace($settings->scriptName, '', $_SERVER['REQUEST_URI']);

	    $settings->completePath = $_SERVER['DOCUMENT_ROOT'] . $settings->urlPath;

	    $settings->host = str_replace($settings->scriptName, '', $_SERVER['HTTP_HOST']);
	
	    $settings->lines = 1;

	}
	
	function checkIfDummyFileExists() 
	{
	    global $settings;
	    
	    if (!file_exists($settings->completePath.$settings->dummyPath.$settings->dummyFile)) {

		if (!is_writable($settings->completePath.$settings->dummyPath) OR !is_writable($settings->completePath.$settings->dummyPath.$settings->dummyFile))
		{
		    die('No permission to create dummy file in '.$settings->completePath.$settings->dummyPath);
		}

		file_put_contents($settings->completePath.$settings->dummyPath.$settings->dummyFile, $settings->dummyContent);

	    }

	}
	
	function checkIfDummyFolderExists()
	{
	    global $settings;
	    
	    if (!file_exists($settings->completePath.$settings->dummyPath)) {

		if (!is_writable($settings->completePath))
		{
		    die('No permission to create dummy folder '.$settings->completePath.$settings->dummyPath);
		}

		mkdir($settings->completePath.$settings->dummyPath, 0777, true);

		file_put_contents($settings->completePath.$settings->dummyPath.$settings->dummyFile, $settings->dummyContent);

	    }

	}
	