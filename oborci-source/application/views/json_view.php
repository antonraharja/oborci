<?php
header("Pragma: public");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private", false);
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Sat, 14 Feb 1979 08:00:00 GMT");
header("Content-Type: application/json");
json_encode($output);
?>