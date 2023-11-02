<?php

define("HOST","localhost");
define("USER","root");
define("PASSWORD","root");
define("DATABASE","api_db");
define("PORT",'3307');

$connection = new PDO("mysql:host=".HOST.';dbname='.DATABASE,USER,PASSWORD);
