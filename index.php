<?php
function D($var, $exit = 0)
{
	print '<div style="background-color: #ffffff; padding: 3px; z-index: 1000;"><pre style="text-align: left; font: normal 10px Courier; color: #000000;">';
	if ( is_array($var) || is_object($var) ) print_r($var);
	else var_dump($var);
	print '</pre></div>';
	if ( $exit ) exit;
}

error_reporting(E_ALL);
require_once 'CrmCore.php';
require 'config.php';

$crm = new CRM();
