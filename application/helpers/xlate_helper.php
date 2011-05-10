<?php

/**
 * Translate string helper
 * @author Anton Raharja
 */

/**
 * Translate string
 * @param string $str String to be translated
 * @return string Translated string
 */
function xlate($str) {
	// TODO Translation codes
	return $str;
}

// in case php gettext is installed
if (!function_exists('_')) {
	/**
	 * Shortify xlate() to _()
	 * @param string $str String to be translated
	 * @return string Translated string
	 */
	function _($str) {
		return xlate($str);
	}
}

?>
