<?php
require_once __DIR__ .'/sql.class.php';
require_once __DIR__ . '/tpl.class.php';

class xDict {

	private static $table = 'x_dict';
	
	/**
	 * @return TplEngine
	 */
	public static function getTplEngine(){
		static $tplEngine = null;
		if (!$tplEngine){

			$tplConfig = array(
				'templateDir' => __DIR__,
				'enableCache' => false,
			);
			$tplEngine = new TplEngine($tplConfig);
		}
		return $tplEngine;
	}

}

// -----------------------
// 
class G
{

	/**
	 * @var SqlDataSource
	 */
	static $ds = null;

	static $dicts = [];
	static $crawRecords = [];

	static function loadDicts()
	{
		$dictfile = __DIR__. '/tranRecords.dat';
		if (file_exists($dictfile))
		self::$dicts = require ($dictfile);
	}

	static function normalize($input, $delimiter = ',')
	{
		if (!is_array($input))
		{
			$input = explode($delimiter, $input);
		}
		$input = array_map('trim', $input);
		return array_filter($input, 'strlen');
	}

	static function js_alert($message = '', $after_action = '', $url = '')
	{
	    $out = "<script type=\"text/javascript\">\n";
	    if (!empty($message)) {
	        $out .= "alert(\"";
	        $out .= str_replace("\\\\n", "\\n", self::t2js(addslashes($message)));
	        $out .= "\");\n";
	    }
	    if (!empty($after_action)) {
	        $out .= $after_action . "\n";
	    }
	    if (!empty($url)) {
	        $out .= "document.location.href=\"";
	        $out .= $url;
	        $out .= "\";\n";
	    }
	    $out .= "</script>";
	    echo $out;
	    exit;
	}

	static function t2js($content)
	{
	    return str_replace(array("\r", "\n"), array('', '\n'), addslashes($content));
	}
}	


function app_init()
{
	error_reporting(E_ALL | E_STRICT);
	date_default_timezone_set('Asia/Shanghai');
	session_start();
	header("Content-Type: text/html;charset=utf-8");

	define('MYSQL_HOST','localhost');
	define('MYSQL_PORT','3306');
	define('MYSQL_DB','mysql');
	define('MYSQL_USER','root');
	define('MYSQL_PASS','root');

	$dsn = array(
			'type' => 'mysql',

			'dbpath'  => sprintf('mysql:host=%s;port=%d;dbname=%s', 
					MYSQL_HOST,
					MYSQL_PORT,
					MYSQL_DB
				),
			'login'	=> MYSQL_USER,
			'password' => MYSQL_PASS,

			'initcmd' => array(
					"SET NAMES 'utf8'",
				),

			'attr'	=> array(
					PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
					PDO::ATTR_PERSISTENT => false,
				),
		);

	G::$ds = Sql::ds($dsn);
	G::loadDicts();

	$q = 'index';
	if ( !empty($_GET['q']) )
	{
		$q = trim( $_GET['q'], "+ \t\r\n\0\x0B" );
		unset( $_GET['q'] );
		$q = preg_replace('/[^a-z0-9\.]/', '', $q);
	}
	if ( empty($q) ) $q = 'index';
	if ( strtolower($q) == 'init' ){
		echo 'qus!';exit;
	}
	$action = 'app_' . $q;
	if ( !is_callable($action) ) $action = 'app_index';

	$action();
}

function g_trans_table($name, $comment) {
	if (empty($name)) return;
	if (!empty($comment)) $name = $comment;

	if (!array_key_exists($name, G::$dicts)) {
		// add in craw records
		G::$crawRecords[] = $name;
	} else {
		return ' | 翻译: ' . G::$dicts[$name];
	}
}

function g_trans_field($field, $comment) {
	$name = $comment;
	if (empty($name)) $name = str_replace('_', ' ', $field);
	if (empty($name)) return;

	if (!array_key_exists($name, G::$dicts)) {
		// add in craw records
		G::$crawRecords[] = $name;
	} else {
		return G::$dicts[$name];
	}
}

app_init();

function app_index(){
	echo xDict::getTplEngine()->display('v-ls.php');
	if (!empty(G::$crawRecords)) {
		file_put_contents(__DIR__ . '/crawRecords.dat',
			'<?php return ' . var_export(G::$crawRecords, true) . ';?>');
	}
}

function app_trans() {
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		
		if (!empty($_POST['postbody'])) {
			$postbody = json_decode($_POST['postbody'], true);

			if (json_last_error() == JSON_ERROR_NONE) {
	         	
				foreach (G::$dicts as $key => $val) {
					$mkey = md5($key);
					if (array_key_exists($mkey, $postbody)) {
						G::$dicts[$key] = $postbody[$mkey];
					}
				}

				$tranRecordsFile = __DIR__. '/tranRecords.dat';
				file_put_contents($tranRecordsFile, 
					'<?php return ' . var_export(G::$dicts, true) . ';?>');
	        } else {
	        	echo "post body invalid!";
	        	exit;
	        }
		}
	}
	echo xDict::getTplEngine()->display('v-trans.php');
}
