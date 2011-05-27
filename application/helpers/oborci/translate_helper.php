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
        $returns = NULL;
	if (function_exists('_')) {
                // in case php gettext is installed
		$returns = _($str);
	} else {
                // your translation codes here
                $lang['Test'] = 'Test';
                $lang['Coba'] = 'Test';
                $lang['Uji'] = 'Test';
		$returns = isset($lang[$str]) ? $lang[$str] : $str;
	}
	return $returns;
}

/**
 * Shortify translate() to t()
 * @param string $str String to be translated
 * @return string Translated string
 */
function t($str) {
        return translate($str);
}

?>
