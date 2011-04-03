<?php
/**
 * PHP Plurk API Common Lib.
 *
 * @package   php-plurk-api
 * @category  API
 * @version   php-plurk-api 1.4.2
 * @license   http://www.opensource.org/licenses/bsd-license.php BSD License
 * @link      http://code.google.com/p/php-plurk-api
 *
 */
Class common {


	function __consturct() {}

	function __deconstruct() {}

	/**
	 * funciton log
	 * message log
	 *
	 * @param $message
	 */
	function log($message = '')
	{
		if( ! file_exists(PLURK_LOG_PATH)) 	touch(PLURK_LOG_PATH);
		$source = file_get_contents(PLURK_LOG_PATH);
		$source .= date("Y-m-d H:i:s - ") . $message . "\n";
		file_put_contents(PLURK_LOG_PATH, $source);
	}
}
