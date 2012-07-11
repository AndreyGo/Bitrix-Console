<?php 
if (!$USER->IsAdmin()) {
	$APPLICATION->AuthForm('Доступ только для администраторов!');
	return false;
}

$this->IncludeComponentTemplate();
?>