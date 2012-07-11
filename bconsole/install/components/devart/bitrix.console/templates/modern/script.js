function successSend(responseText)
{
	var value = $('textarea#consoleLog').html();
	$('textarea#consoleLog').html(value + responseText);
	scrollWindow('consoleLog');
	$('#consoleCmd').val('').focus();
}

function successBatch(responseText)
{
	var value = $('textarea#consoleLog').html();
	$('textarea#consoleLog').html(value + responseText);
	scrollWindow('consoleLog');
	$('textarea#batchCmd').attr('value','');
}

function scrollWindow(id)
{
	var vm=document.getElementById(id);
	vm.scrollTop = vm.scrollHeight;
}

function sendCommand(fmObject)
{
	var src = $(fmObject).attr('action');
	var consoleCmd = $('#consoleCmd').val();
	
	$('#lastCmd').val(consoleCmd);
	
	if ($.trim(consoleCmd) == 'clear') {
		$('textarea#consoleLog').html('');
		$('#consoleCmd').val('').focus();
		return false;
	}
	
	if ($.trim(consoleCmd) == 'exit') {
		document.location = '/';
		return false;
	}
	
	$.get(src,{cmd: consoleCmd, submit: 'success'},function(data){
		successSend(data);	
	});
	
	return false;
}

function sendBatch(fmObject)
{
	var src = $(fmObject).attr('action');
	var batchCmd = $('textarea#batchCmd').attr('value');

	$.get(src,{batch: batchCmd, submit: 'success'},function(data){
		successBatch(data);	
	});
	
	return false;
}
