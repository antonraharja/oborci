<?php

/**
 * Translate string helper
 * @author Anton Raharja
 */

/**
 * Translate string
 * @param string $str String to be translated
 * @param string $lang_file Language file, with or without extension
 * @return string Translated string
 */
function oborci_translate($str, $lang_file=NULL) {
        global $application_folder;
        if (isset($lang_file)) {
                $lang_file = str_replace('..', '.', $lang_file);
                $lang_file = str_replace('|', '', $lang_file);
                $lang_file = str_replace('`', '', $lang_file);
                $lang_file = str_replace('\'', '', $lang_file);
                $lang_file = str_replace('"', '', $lang_file);
                $lang_file = $application_folder.'/language/'.$lang_file.'_lang.php';
                if (file_exists($lang_file)) {
                        // include a language file $lang_file
                        // the language file contains $lang array of text and its translation
                        // the language file used is the same language file supported by CI
                        @include_once($lang_file);
                }
        } else {
                $lang_file = $application_folder.'/language/oborci_lang.php';
                if (file_exists($lang_file)) {
                        @include_once($lang_file);
                }
        }
        $returns = isset($lang[$str]) ? $lang[$str] : $str;
	return $returns;
}

/**
 * Shortify translate() to t()
 * @param string $str String to be translated
 * @param string $lang_file Language file, with or without extension
 * @return string Translated string
 */
function t($str, $lang_file=NULL) {
        return oborci_translate($str, $lang_file);
}

?>
