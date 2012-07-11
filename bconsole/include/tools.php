<?php

function getPerms($file)
{
	$perms = fileperms($file);
	
	if (($perms & 0xC000) == 0xC000) {
		// Сокет
		$info = 's';
	} elseif (($perms & 0xA000) == 0xA000) {
		// Символическая ссылка
		$info = 'l';
	} elseif (($perms & 0x8000) == 0x8000) {
		// Обычный
		$info = '-';
	} elseif (($perms & 0x6000) == 0x6000) {
		// Специальный блок
		$info = 'b';
	} elseif (($perms & 0x4000) == 0x4000) {
		// Директория
		$info = 'd';
	} elseif (($perms & 0x2000) == 0x2000) {
		// Специальный символ
		$info = 'c';
	} elseif (($perms & 0x1000) == 0x1000) {
		// Поток FIFO
		$info = 'p';
	} else {
		// Неизвестный
		$info = 'u';
	}
	
	// Владелец
	$info .= (($perms & 0x0100) ? 'r' : '-');
	$info .= (($perms & 0x0080) ? 'w' : '-');
	$info .= (($perms & 0x0040) ?
			(($perms & 0x0800) ? 's' : 'x' ) :
			(($perms & 0x0800) ? 'S' : '-'));
	
	// Группа
	$info .= (($perms & 0x0020) ? 'r' : '-');
	$info .= (($perms & 0x0010) ? 'w' : '-');
	$info .= (($perms & 0x0008) ?
			(($perms & 0x0400) ? 's' : 'x' ) :
			(($perms & 0x0400) ? 'S' : '-'));
	
	// Мир
	$info .= (($perms & 0x0004) ? 'r' : '-');
	$info .= (($perms & 0x0002) ? 'w' : '-');
	$info .= (($perms & 0x0001) ?
			(($perms & 0x0200) ? 't' : 'x' ) :
			(($perms & 0x0200) ? 'T' : '-'));
	
	return $info;
}

function getFileOwner($file)
{
	$uid = fileowner($file);
	if (function_exists('posix_getpwuid')) {
		$owner = posix_getpwuid($uid);
		return $owner['name'];
	} else {
		return $uid;
	}
}

function getFileGroup($file)
{
	$gid = filegroup($file);
	if (function_exists('posix_getgrgid')) {
		$group = posix_getgrgid($gid);
		return $group['name'];
	} else {
		return $gid;
	}
}

function getFileModifyTime($file)
{
	return date ("M d Y H:i:s",filemtime($file));
}

function createFile($filepath,$filecontent)
{
	$h = fopen($filepath,"w");
	if (!$h) {
		return false;
	}	
	fwrite($h, $filecontent);
	fclose($h);
	return true;
}

function saveFromTemplate($filepath,$templateName,$params = array())
{
	$templatePath = CONSOLE_TEMPLATES_PATH.$templateName.'.txt';
	
	if (!file_exists($templatePath)) {
		return 'Template not found';
	}
	
	$template = file_get_contents($templatePath);
	
	
	if (is_array($params) && count($params) > 0) {
		$tmp = $params;
		$params = array();		
		foreach ($tmp as $v => $p) {
			$params['#'.strtoupper($v).'#'] = $p;
		}
		$template = strtr($template, $params);
	}
	
	return createFile($filepath,$template);
}


function getDirectoryListing($path,$onlyFiles=false,$onlyDirs=false)
{
	if ($handle = opendir($path)) {
		$arFiles = $arDir = array();
		while (false !== ($file = readdir($handle))) {
			$file = trim($file);
			$fullPath = $path . '/' . $file;
			if ($file == '.' || $file == '..') continue;
			if (is_dir($fullPath)) {
				$arDir[] = $file;
			} else {
				$arFiles[] = $file;
			}
		}
		closedir($handle);
		
		if ($onlyFiles == true & $onlyDirs == false) {
			return $arFiles;
		} elseif ($onlyFiles == false & $onlyDirs == true) {
			return $arDir;
		} else {
			return array_merge($arDir,$arFiles);
		}
	} else {
		return array();
	}
}

/**
 * From PHP.NET
 * @param str $dir
 */
function rrmdir($dir) {
	if (is_dir($dir)) {
		$objects = scandir($dir);
		foreach ($objects as $object) {
			if ($object != "." && $object != "..") {
				if (filetype($dir."/".$object) == "dir") rrmdir($dir."/".$object); else unlink($dir."/".$object);
			}
		}
		reset($objects);
		rmdir($dir);
	}
}

/**
 * Show help for $section command
 * @param str $section
 */
function getHelpText($section)
{
	$helpFile = CONSOLE_HELP_PATH . 'cmd_' . $section . '.txt';
	if (file_exists($helpFile)) {
		return file_get_contents($helpFile);
	} else {
		return 'Help for cmd "' . $section . '" not found.';
	}
}
