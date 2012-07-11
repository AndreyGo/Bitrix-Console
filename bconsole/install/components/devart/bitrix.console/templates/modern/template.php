<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?php // $APPLICATION->AddHeadScript($templateFolder . '/js/jquery.js')?>
<?php //$APPLICATION->AddHeadScript($templateFolder . '/js/jquery.form.js')?>

<div class="u-console">
	<textarea id="consoleLog" readonly="readonly" name="consoleLog"></textarea>
	<form action="/bitrix/components/devart/bitrix.console/cmd.php" method="post" name="fmConsole" onsubmit="return sendCommand(this);">
		<div class="u-console-command">		
			<div class="u-console-login" id="consoleLogin">[<?php echo $USER->GetLogin();?>]$</div>
			<input class="u-input-cmd" type="text" id="consoleCmd" name="cmd" value="" autocomplete="off" />
		</div>
	</form>
	<input type="hidden" name="lstCmd" id="lastCmd" value="" />
</div>

<div class="u-batch">
	<form action="/bitrix/components/devart/bitrix.console/cmd.php" method="post" name="fmConsole" onsubmit="return sendBatch(this);">
		<textarea name="batch" id="batchCmd"></textarea>
		<input type="submit" name="send" value="Send" />
	</form>
</div>
<script type="text/javascript">	
	$('#consoleCmd').keydown(function(event) {		
		
		if (event.which == 38) {
			var lastCmd = $('#lastCmd').val();	
			if ($.trim(lastCmd) != '') {
		  		$(this).val(lastCmd);
			}
		}

		return true;
	});

	document.getElementById('consoleCmd').focus();
</script>