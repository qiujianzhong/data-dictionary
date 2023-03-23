<?php

require_once __DIR__ . '/unirest.class.php';

function crawRecords() {

	echo "启动 crawRecords ..." . PHP_EOL;

	$tranRecords = [];
	$tranRecordsFile = __DIR__. '/tranRecords.dat';
	if (file_exists($tranRecordsFile)) {
		$tranRecords = require($tranRecordsFile);
	}

	$crawRecords = [];
	$crawRecordsFile = __DIR__. '/crawRecords.dat';
	if (file_exists($crawRecordsFile)) {
		$crawRecords = require($crawRecordsFile);
	}

	Unirest::verifyPeer(false);
	Unirest::timeout(30);

	if (!empty($crawRecords)) {
		foreach ($crawRecords as $item) {
			if (preg_match("/[\x7f-\xff]/", $item)) {
				$tranRecords[$item] = $item;
			} else {
				if (!array_key_exists($item, $tranRecords)) {

					$return = crawItem($item);
					if (!empty($return))
					$tranRecords[$item] = $return;
				}
			}
		}
	}

	file_put_contents($tranRecordsFile, 
		'<?php return ' . var_export($tranRecords, true) . ';?>');

}

function crawItem($item) {
	echo "crawItem:{$item}" . PHP_EOL;

	// 如果是中文字符就直接返回
	if (preg_match("/[\x7f-\xff]/", $item)) {
		echo "crawItem -- translate {$item}" . PHP_EOL;
		return $item;
	}

	usleep(1000);

// http://fanyi.youdao.com/translate?doctype=json&type=EN2ZH_CN&i=hello

	$url = 'http://fanyi.youdao.com/translate';
	$headers = [];
	//EN2ZH_CN可以改为其他要翻译的语言
	try {
		$response = Unirest::get($url, $headers, [
			"doctype"	=> 'json',
			"type"	=> 'EN2ZH_CN',
			"i"	=> $item,
		]);	
	} catch (Exception $ex) {
		echo "crawItem error" . $ex->getMessage() . PHP_EOL;
		return;
	}
	
	$dataArr = json_decode($response->raw_body, true);
/*
Array
(
    [type] => EN2ZH_CN
    [errorCode] => 0
    [elapsedTime] => 4
    [translateResult] => Array
        (
            [0] => Array
                (
                    [0] => Array
                        (
                            [src] => Restaurant UID
                            [tgt] => 餐厅UID
                        )

                )

        )

)
 */

	if (!json_last_error()) {
		if (!empty($dataArr['translateResult']) && !empty($dataArr['translateResult'][0])) {
			$d = $dataArr['translateResult'][0];
			$ret = !empty($d[0]) && !empty($d[0]['tgt']) ? $d[0]['tgt'] : '';
			if (!empty($ret)) {
				echo "crawItem -- translate {$ret}" . PHP_EOL;
			}
			return $ret;
		}
	}
	return null;
}

crawRecords();

