<?php

class CCMDSection {
	
	function cmd_create()
	{
		global $CONSOLE;
		
		$CONSOLE->setEnv('LAST_SECTION_NAME',null);
		
		// Создание раздела, по всем стандартам битрикса
		if ($CONSOLE->value == '') {
			$CONSOLE->errorOut('Please, input section name');
			return ;
		}
		
		// Проверяем, может такой раздел уже существует
		if (is_dir($CONSOLE->currentPath($CONSOLE->value))) {
			$CONSOLE->errorOut('Section already exists');
			return ;
		}
		
		// Создаём папку
		if (!mkdir($CONSOLE->currentPath($CONSOLE->value))) {
			$CONSOLE->errorOut("Can't create directory '".$CONSOLE->value."'");
			return ;
		}
		
		$title = $CONSOLE->getParam('title','Section title',true);
		$content = $CONSOLE->getParam('content','',true);			
		
		$r = saveFromTemplate($CONSOLE->currentPath($CONSOLE->value) . '/.section.php', '.section',array('title' => $title,'property_array' => $CONSOLE->getParamsAsArrayParams()));
		if (!is_bool($r)) {
			$CONSOLE->errorOut($r);
			return;
		} elseif ($r == false) {
			$CONSOLE->errorOut("Can't create file in directory ".$CONSOLE->value);
			return ;
		}
		
		if ($CONSOLE->getParam('no-index','') != 'y') {
			$r = saveFromTemplate($CONSOLE->currentPath($CONSOLE->value) . '/index.php','index',array('title' => $title,'content' => $content));
			if (!is_bool($r)) {
				$CONSOLE->errorOut($r);
				return;
			} elseif ($r == false) {
				$CONSOLE->errorOut("Can't create file in directory ".$CONSOLE->value);
				return ;
			}
		}
		
		$CONSOLE->setEnv('LAST_SECTION_NAME',$CONSOLE->value);
		$CONSOLE->out('Section created successfuly');
	}
	
	function cmd_list()
	{
		global $CONSOLE,$sSectionName;
		$arDir = getDirectoryListing($CONSOLE->currentPath());
		$arResult = array();
		foreach ($arDir as $dir) {
			if (file_exists($CONSOLE->currentPath() . $dir .'/.section.php')) {
				include $CONSOLE->currentPath() . $dir .'/.section.php';
			} else {
				continue;
			}
			
			$arResult[] = '[' . $dir . '] - ' . $sSectionName;
		}
		
		$CONSOLE->out(implode("\n",$arResult));
	}
	
	function cmd_delete()
	{
		global $CONSOLE;
		if ($CONSOLE->value == '') {
			$CONSOLE->errorOut('Please, input section name');
			return ;
		}
		
		if (preg_match('#/?bitrix/?#i',$CONSOLE->value)) {
			$CONSOLE->errorOut('Impossible action');
			return ;			
		}
		
		if (!is_dir($CONSOLE->currentPath($CONSOLE->value))) {
			$CONSOLE->errorOut('Directory not exists');
			return ;	
		}
		
		if ($CONSOLE->getParam('confirm','') != 'delete') {
			$CONSOLE->errorOut('All sub directories and files will be deleted! For confirm, enter an parameter "-confirm=delete"');
			return ;	
		}

		
		rrmdir($CONSOLE->currentPath($CONSOLE->value));
		$CONSOLE->out('Section "'.$CONSOLE->value.'" successfully deleted.');
	}
	
	function cmd_move()
	{
		global $CONSOLE;
		
		if ($CONSOLE->value == '') {
			$CONSOLE->errorOut('Please, input sources section name.');
			return ;
		}		
				
		if ($CONSOLE->getParam('to',false) == false) {
			$CONSOLE->errorOut('Please, input destination section name as parameter "-to".');
			return ;
		}

		$fromPath = $CONSOLE->currentPath($CONSOLE->value);
		$toFolder = $CONSOLE->getParam('to');
		
		if (!is_dir($fromPath)) {
			$CONSOLE->errorOut('Source section is not directory.');
			return ;
		}
		
		if (!is_dir($CONSOLE->currentPath($toFolder))) {
			$CONSOLE->errorOut('Destination section is not directory.');
			return ;
		}
		
		$fromFolder = $CONSOLE->value;
		$toPath = $CONSOLE->currentPath($toFolder) .'/'.$fromFolder;
		
		if (CopyDirFiles($fromPath,$toPath,true,true,true)) {
			rmdir($fromPath);
			$CONSOLE->out('Section "'.$CONSOLE->value.'" successfully moved.');
		} else {
			$CONSOLE->errorOut('Error while moving section "'.$CONSOLE->value.'".');
		}
		
	}
	
	function cmd_update()
	{
		global $CONSOLE;
		if (trim($CONSOLE->value) == '' ) {
			$CONSOLE->errorOut('Please, input section name for update');
			return ;
		}
		
		if (count($CONSOLE->arParams) == 0) {
			$CONSOLE->errorOut('Please enter at least one parameter');
			return ;
		}
		
		$name = $CONSOLE->getParam('name',false,true);
		$title = $CONSOLE->getParam('title',false,true);
		
		if ($name != false) {			
			if (is_dir($CONSOLE->currentPath($name))) {
				$CONSOLE->errorOut('Section with name "'.$name.'" already exists.');
				return ;
			}
			
			if (!rename($CONSOLE->currentPath($CONSOLE->value), $CONSOLE->currentPath($name))) {
				$CONSOLE->errorOut('Error while rename section.');
				return ;
			}
		}
		
		$CONSOLE->out('Update section "'.$CONSOLE->value.'" completed.');
	}
}