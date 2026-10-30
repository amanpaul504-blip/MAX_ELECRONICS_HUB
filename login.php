<?php

session_start();
session_unset();
session_destroy();
header('Location: http://localhost/MAX_ELECTRONICS_HUB/index.php');
exit();

?>