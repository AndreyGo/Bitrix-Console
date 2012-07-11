<?php
$cmd = 'page create -title=News -page-break=Break -zend_name_saver="Three plus four" -zenga="Привет семья" -zitta=asa';
//$cmd = 'users create test -name="New User - for this ))#$%^&*()_____________) 1+1=2" -group=1,2 -last_name=Tester';
$cmd = 'section delete andrey -param1=value1 -param2=/value2 -param3=value3 -param4=value4 ';
$cmd = 'section delete cat -confirm-delete -yes=go -test=true';
$params = array();

if (preg_match_all('#([a-z0-9_\/.]+)|(-([a-z0-9-_]+)="?(.*)"?)#i', $cmd, $params))
{
	echo '<pre>';
	//print_r($params);
	echo '</pre>';

	
	if (isset($params[0][3])) {
		$exParam = $params[0][3];
	} elseif (isset($params[0][2]) && substr_count($params[0][2], '-')) {
		$exParam = $params[0][2];
	}
	
	preg_match_all('/-([A-Za-z0-9-_]+)=([a-zA-Z0-9,.\/]+) |-([A-Za-z0-9-_]+)="(.*)"/USi', $exParam, $_params);
	
	echo '<pre>';
	print_r($_params);
	echo '</pre>';
	
	$arParams = array();
	for ($i=0;$i<=count($_params[0])-1; $i++)
	{
		$p = trim($_params[1][$i]);
		if ($p == '') {
			$p = trim($_params[3][$i]);
			$v = trim($_params[4][$i]);
		} else {
			$v = trim($_params[2][$i]);
		}
		
		$arParams[$p] = $v;
	}
	
	echo '<pre>';
	//print_r($arParams);
	echo '</pre>';
} 


?>