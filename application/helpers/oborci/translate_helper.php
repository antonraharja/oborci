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
function translate($str) {
	// TODO Translation codes
	return $str;
}

/**
 * Shortify translate() to t()
 * @param string $str String to be translated
 * @return string Translated string
 */
function t($str) {
	// in case php gettext is installed
	if (function_exists('_')) {
		return _($str);
	} else {
		return translate($str);
	}
}

?>
