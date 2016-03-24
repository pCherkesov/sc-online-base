$(document).ready(function(){
	$("#tel_human").mask("(999) 999-9999");

	$('#clientList').searchable({
		searchField: "#clientListSearch",
		searchType: 'default'
	});

	$('#deviceList').searchable({
		searchField: "#deviceListSearch",
		searchType: 'default'
	});

	$('#clientListPanel').collapse('hide');
	$('#deviceList').collapse('hide');
	$('#serialList').collapse('show');
	$('#fio_human').focus();
	$("#id_client").val("1");

	$('#noFields').on('click', function() {
		$("#id_client").val("1");
		$('#clientListPanel').collapse('hide');
		$('#fio_human').focus();
		$('#deviceList').collapse('hide');
		$('#serialList').collapse('hide');
	});

	$('#affixFields').on('click', function() {
		$('#clientListPanel').collapse('hide');
		$('#deviceList').collapse('hide');
		$('#serialList').collapse('hide');
	});

	$('#clientFields, #org').on('click', function() {
		$('#clientListPanel').collapse('show');
		$("#clientListSearch").focus();
		$('#deviceList').collapse('hide');
		$('#serialList').collapse('hide');
	});

	$('#deviceFields').on('focus', function() {
		$('#deviceList').collapse('show');
		$("#deviceListSearch").focus();
		$('#clientListPanel').collapse('hide');
		$('#serialList').collapse('hide');
	});

	$('#serialFields').on('click', function() {
		$('#clientListPanel').collapse('hide');
		$('#deviceList').collapse('hide');
		$('#serialList').collapse('show');
	});

	$("#clientList a").on('click', function (event) {
		$("#id_client").val( $(this).attr('data-model-id') );
		$("#tel_org").val( $(this).attr('data-client-tel') );
		$("#fio_org").val( $(this).html() );
		event.preventDefault();
	});
});