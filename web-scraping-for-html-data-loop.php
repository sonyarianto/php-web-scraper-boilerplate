<?php
	$startExecution = microtime(true);

	// autoload for composer based library here
	// require __DIR__ . '/vendor/autoload.php';

	// prepare database connection here
	// include dirname(__FILE__) . '/include_db_config.php';

	// prepare any additional library here
	// include dirname(__FILE__) . '/include_additional_functions.php';
	
	// prepare any global variable needed here
	// $var1 = 'xxx';
	// $var2 = 5;
	// $var3 = [];

	// Additional notes:
	// - I always force that any date should be save to database in GMT TZ
	// - PHP libcurl should be installed
	// - phpxml extension should be installed for DOM query

	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, 'URL_TO_SCRAPE');
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curl, CURLOPT_ENCODING, "");
	curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
	curl_setopt($curl, CURLOPT_TIMEOUT, 30);
	curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($curl, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
	curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/79.0.3945.88 Safari/537.36');
	$curlData = curl_exec($curl);

	if(curl_errno($curl) == 28) {
		$isTimeout = true;
	} else {
		$isTimeout = false;
	}

	curl_close($curl);

	if($isTimeout) {
		echo "Timeout!\n"; 
		exit;
	}

	if(trim($curlData) == '') {
		echo "Curl data empty!\n";
		exit;
	}

	// additional data cleansing here
	// - replace string
	// - add specific data to string
	// - etc

	// for HTML format that require data loop
	$xmlDoc = new DOMDocument();
	@$xmlDoc->loadHTML($curlData);

	$xPathDoc = new DOMXpath($xmlDoc);

	$xPathQuery = $xPathDoc->query("(//ul[@class='content'])[1]/li");

	if($xPathQuery->length == 0) {
		$isEntryFound = false;
		echo "No data!\n";
		exit;
	} else {
		$isEntryFound = true;
	}

	if($isEntryFound) {
		foreach($xPathQuery as $data) {
			// DO SOMETHING HERE
			// e.g. get data elements and put to array for next process
      echo $data->getElementByTagName('a')->item(0)->getAttribute('href') . "\n";
		}
	}

	echo "Script running on " . (microtime(true) - $startExecution) . " seconds.\n";
