<?php
 return array (
  'prefix' => 'module',
  'type' => 'redis',
  'auto_start' => true,
  'host' => '127.0.0.1',
  'port' => '6379',
  'password' => '',
  'select' => 0,
  'expire' => '7200',
  'timeout' => '0',
  'persistent' => true,
  'session_name' => 'RESTY_PHPSESSID:',
  'record' => '1200',
);
?>