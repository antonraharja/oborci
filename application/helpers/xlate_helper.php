<?php

/**
 * Translate string
 * @param string $str String to be translated
 * @return string Translated string
 */
function xlate($str) {
	return $str;
}

/**
 * Shortify xlate() to _()
 * @param string $str String to be translated
 * @return string Translated string
 */
function _($str) {
	return xlate($str);
}

?>
