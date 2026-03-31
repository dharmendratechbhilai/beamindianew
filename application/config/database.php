<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
| -------------------------------------------------------------------
| DATABASE CONNECTIVITY SETTINGS

	'hostname' => 'localhost',
	'username' => "u151751738_beamuser",
	'password' => "d8kAW93K2Gu#sQ.",
	'database' => "u151751738_beamtime",

| -------------------------------------------------------------------
*/
$active_group = 'default';
$query_builder = TRUE;

$db['default'] = array(
	'dsn'	=> '',
	'hostname' => ($_SERVER['SERVER_NAME'] === 'localhost') ? 'localhost:3315' : "localhost",
	'username' => ($_SERVER['SERVER_NAME'] === 'localhost') ? 'root' : "u151751738_beamuser",
	'password' => ($_SERVER['SERVER_NAME'] === 'localhost') ? "" : "d8kAW93K2Gu#sQ.",
	'database' => ($_SERVER['SERVER_NAME'] === 'localhost') ? "beam-time" : "u151751738_beamtime",
	'dbdriver' => 'mysqli',
	'dbprefix' => '',
	'pconnect' => FALSE,
	'db_debug' => (ENVIRONMENT !== 'production'),
	'cache_on' => FALSE,
	'cachedir' => 'application/cache/',
	'char_set' => 'utf8',
	'dbcollat' => 'utf8_general_ci',
	'swap_pre' => '',
	'encrypt' => FALSE,
	'compress' => FALSE,
	'stricton' => FALSE,
	'failover' => array(),
	'save_queries' => TRUE
);
