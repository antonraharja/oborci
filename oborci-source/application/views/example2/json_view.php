<?php
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Sat, 14 Feb 1979 08:00:00 GMT");
echo json_encode($output);
?>