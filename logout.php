<?php

session_start();
session_destroy();

header("Location: /phpmysql/index.php");
exit;