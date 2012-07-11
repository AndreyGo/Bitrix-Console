<?php
include $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include.php';
CModule::IncludeModule('bconsole');

global $USER,$CONSOLE;

if ($USER->IsAdmin() !== true) {
	LocalRedirect('/');
	return false;
}

if (empty($_REQUEST['cmd']) && isset($_REQUEST['batch'])) {
	$CONSOLE->runBatch($_REQUEST['batch']);	
} else {
	$CONSOLE->run($_REQUEST['cmd']);
}

if (trim($CONSOLE->output) != '') {
	echo $CONSOLE->output ."\n\n";
} 
?>