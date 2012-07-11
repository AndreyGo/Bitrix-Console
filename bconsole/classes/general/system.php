<?php

class CSystemCMD {
	
	function CSystemCMD()
	{
		
	}
	
	/**
	 * Change Directory
	 */
	function cmd_cd()
	{
		global $CONSOLE;
		$cd = trim($CONSOLE->action);
		
		if ($cd == '') {
			$CONSOLE->errorOut('Missing directory name');
			return ;
		} 
		
		if (in_array($cd,array('^','back','<','up'))) {
			if ($CONSOLE->pwd == '/') return ;
			$arPwd = explode('/',$CONSOLE->pwd);
			unset($arPwd[count($arPwd)-2]);			
			$cd = implode('/',$arPwd);			
		}
		
		if ($cd == './') return ;
		
		if ($cd[0] == '.') {
			$cd[0] = ''; $cd = trim($cd);
		}
		
		if ($cd[0] == '/') {
			$path = $CONSOLE->rootPath . $cd;
		} else {
			$path = $CONSOLE->rootPath . $CONSOLE->pwd . $cd;			
		}
		
		$path = str_replace('//','/',$path);
		
		if (is_dir($path)) {
			$path = str_replace($CONSOLE->rootPath,'',$path);
			if ($path[0] != '/') {
				$path = '/' . $path;
			} elseif($path[strlen($path)-1] != '/') {
				$path .= '/';
			}
			
			$CONSOLE->changePwd($path);
		} else {
			$CONSOLE->errorOut('Not directory ' . $path);
		}
			
	}
	
	function cmd_pwd()
	{
		global $CONSOLE;
		
		if ($CONSOLE->action == 'full') {
			$CONSOLE->out(str_replace('//','/',$CONSOLE->rootPath . $CONSOLE->pwd));
		} else {
			$CONSOLE->out($CONSOLE->pwd);
		}
	}
	
	function cmd_login()
	{
		global $USER;
		$GLOBALS['CONSOLE']->out('You logged as '.$USER->GetLogin());
	}
	
	function cmd_version()
	{
		$GLOBALS['CONSOLE']->out('Version of Bitrix Console is ' . $GLOBALS['CONSOLE']->version);
	}
	
	function cmd_bversion()
	{
		$GLOBALS['CONSOLE']->out('Version of 1C-Bitrix CMS is ' . SM_VERSION);
	}
	
	function cmd_author()
	{
		$GLOBALS['CONSOLE']->out('Author of this project is Slashinin Andrey Aleksandrovich'."\n".'E-Mail: slashinin.andrey@gmail.com'."\n".'ICQ: 266-570'."\n" .'Skype: andrey.slashinin');
	}
	
	function cmd_history()
	{
		if ($GLOBALS['CONSOLE']->action == '') {
			if (count($GLOBALS['CONSOLE']->arHistory) == 0) {
				$GLOBALS['CONSOLE']->out('History is empty');
				return ;
			}
			$r = '';
			foreach ($GLOBALS['CONSOLE']->arHistory as $h) {
				$r .= $h . "\n";
			}
			$GLOBALS['CONSOLE']->out($r);
		} elseif ($GLOBALS['CONSOLE']->action == 'clear') {
			$GLOBALS['CONSOLE']->clearHistory();
			$GLOBALS['CONSOLE']->out('History was cleared');			
		} else {
			$GLOBALS['CONSOLE']->out('Unknown parameter');
		}
	}
	
	function cmd_root_path()
	{
		$GLOBALS['CONSOLE']->out($GLOBALS['CONSOLE']->rootPath);
	}
	
	function cmd_mkdir()
	{
		if (trim($GLOBALS['CONSOLE']->action) == '') {
			$GLOBALS['CONSOLE']->errorOut('Missing parameter');
			return ;
		}
		
		$newDirPath = $GLOBALS['CONSOLE']->rootPath . $GLOBALS['CONSOLE']->pwd . $GLOBALS['CONSOLE']->action;
		if (is_dir($newDirPath)) {
			$GLOBALS['CONSOLE']->errorOut('Directory exists');
			return ;
		}
		
		if (mkdir($newDirPath)) {
			$GLOBALS['CONSOLE']->out('Directory successfully created');
		} else {
			$GLOBALS['CONSOLE']->errorOut('Error. Directory not created.' . $newDirPath);
		}
	}
	
	function cmd_dir() {$this->cmd_ls();}
	function cmd_ls()
	{
		$path = $GLOBALS['CONSOLE']->rootPath . $GLOBALS['CONSOLE']->pwd;
		$arFiles = getDirectoryListing($path,true);
		$arDir = getDirectoryListing($path,false,true);
		
		sort($arFiles);
		sort($arDir);
		
		$arDirContent = array_merge($arDir,$arFiles);
		foreach ($arDirContent as $id => $result) {
			$fullPath = $path . $result;
			$arResult[] = getPerms($fullPath) .'	' .getFileGroup($fullPath).'	'.getFileOwner($fullPath).'	'.getFileModifyTime($fullPath).'	'. $result;
		}
		
		$GLOBALS['CONSOLE']->out(implode("\n",$arResult));
	}
	
	function cmd_env()
	{
		global $CONSOLE;
		
		if (trim($CONSOLE->action) == '') {
			$CONSOLE->out('Enter environment variable, please.');
			return '';
		}

		$env = $CONSOLE->getEnv($CONSOLE->action);
		
		if ($env == null) {
			$CONSOLE->out('Environment variable is not exists.');
			return '';
		}
		
		$CONSOLE->out($env);
	}
	
	function cmd_import()
	{
		global $CONSOLE;
		$batchFile = trim($CONSOLE->action);
		
		if ($batchFile == '') {
			$CONSOLE->errorOut('File not found.');
			return ;
		}
		
		$batchFile = $CONSOLE->currentPath($batchFile);
		if (!file_exists($batchFile)) {
			$CONSOLE->errorOut('File not found.');
			return ;
		}
		
		$notLogging = $CONSOLE->getParam('logging','y') == 'n';	
		$CONSOLE->runBatch(file_get_contents($batchFile),!$notLogging);
	}
	
}

?>