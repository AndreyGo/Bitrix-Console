<?
    
    	if (!CopyDirFiles(MODULE_BCONSOLE_PATH.'/install/components', $_SERVER["DOCUMENT_ROOT"]."/bitrix/components", true, true))    
    		throw new Exception('Rights violation: Can not copy components files to '.$_SERVER["DOCUMENT_ROOT"]."/bitrix/components");    
    	exit('Game Over!');
    	return true;
    }