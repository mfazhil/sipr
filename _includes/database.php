<?php
$db_host = array_key_exists("MYSQL_HOST", $_ENV) ? $_ENV["MYSQL_HOST"] : "localhost";
$db_user = array_key_exists("MYSQL_USER", $_ENV) ? $_ENV["MYSQL_USER"] : "root";
$db_pass = array_key_exists("MYSQL_PASSWORD", $_ENV) ? $_ENV["MYSQL_PASSWORD"] : "";
$db_name = array_key_exists("MYSQL_DATABASE", $_ENV) ? $_ENV["MYSQL_DATABASE"] : "sipr";

try {
  $db = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  echo "Gagal mengakses database. Error :" . $e->getMessage();
  exit;
}
