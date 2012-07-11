<?php 
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

IncludeModuleLangFile(__FILE__);

define('CONSOLE_LIBRARY_PATH',$_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/bconsole/library/');
define('CONSOLE_TEMPLATES_PATH',$_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/bconsole/templates/');
define('CONSOLE_HELP_PATH',$_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/bconsole/help/');

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/bconsole/classes/general/bconsole.php");

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/bconsole/include/tools.php"); 

$CONSOLE = new CBConsole();
$GLOBALS['CONSOLE'] = &$CONSOLE;