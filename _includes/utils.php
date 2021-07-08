<?php
abstract class Role
{
  public const ADMIN = "ADMIN";
  public const USER = "USER";
}

abstract class Validate
{
  static function check(array $input_fields, mixed $on = false): void
  {
    if ($on === false) $on = $_REQUEST;
    foreach ($input_fields as $value) {
      if (empty($on[$value])) {
        throw new Exception("'$value' tidak boleh kosong.", 100);
      }
    }
  }

  static function post_int(string $var_name): int
  {
    $value = filter_var((int) filter_input(INPUT_POST, $var_name, FILTER_SANITIZE_NUMBER_INT), FILTER_VALIDATE_INT);
    if ($value === false) throw new Exception("'$var_name' hanya boleh berisi angka.", 101);
    return $value;
  }

  static function get_int(string $var_name): int
  {
    $value = filter_var(filter_input(INPUT_GET, $var_name, FILTER_SANITIZE_NUMBER_INT), FILTER_VALIDATE_INT);
    if ($value === false) throw new Exception("'$var_name' hanya boleh berisi angka.", 101);
    return $value;
  }

  static function int(mixed $value): int
  {
    $value = filter_var($value, FILTER_VALIDATE_INT);
    if ($value === false) throw new Exception("Invalid integer.", 101);
    return $value;
  }

  static function post_string(string $var_name): string
  {
    return filter_input(INPUT_POST, $var_name, FILTER_SANITIZE_STRING);
  }

  static function get_string(string $var_name): string
  {
    return filter_input(INPUT_GET, $var_name, FILTER_SANITIZE_STRING);
  }
}

function check_username_exists(string $username, \PDO $db): bool
{
  $data_pengguna = $db->prepare("SELECT Username FROM pengguna WHERE Username = :username LIMIT 1");
  $is_selected = $data_pengguna->execute(["username" => $username]);
  if ($is_selected === false) throw new Exception("Gagal menemukan pengguna dengan username $username.", 200);
  $pengguna = $data_pengguna->fetch();
  return $pengguna === false ? false : true;
}
