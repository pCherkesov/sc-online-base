$(document).ready(function(){
	$("#tel_human").mask("(999) 999-9999");

	$('#ListPanel').collapse('hide');
	$('#fio_human').focus();
	$("#id_client").val("1");

	$('#noFields').on('click', function() {
		$("#id_client").val("1");
		$('#ListPanel').collapse('hide');
		$('#fio_human').focus();
	});

	$('#human').on('click', function() {
		$("#id_client").val("1");
		$('#ListPanel').collapse('hide');
	});

	$('#affixFields').on('click', function() {
		$('#ListPanel').collapse('hide');
	});

	$('#clientFields, #org').on('click', function() {
		loadList("client");
		$("#tel_org").val('');
		$("#fio_org").val('');
	});

	$('#deviceFields').on('click', function() {
		loadList("device");
	});

	$('#serialFields').on('click', function () {
		var args = '&client='+$("#id_client").val() + '&model='+ $("#id_model").val();
		loadList("serial", args);
	});

	function loadList (action, args = "") {
		$('#ListPanel').collapse('show');
		$('#List').html('<div id="loader"></div>');
		$.ajax({
			type: "GET",
			url: 'ajax/add.php',
			data: "action=" + action + args,
			success: function(data, textStatus){
				$('#List').html(data);
				$('#List').searchable({
					searchField: "#ListSearch",
					searchType: 'default',
					reset: 'true',
				});
				$("#ListSearch").val('').focus();
			},
			error: function(jqXHR, textStatus, errorThrown){
				notify('danger', textStatus);
			}
		});
	};


	$("#List").delegate(".on-client", 'click', function (event) {
		$("#id_client").val( $(this).attr('data-model-id') );
		$("#tel_org").val( $(this).attr('data-client-tel') );
		$("#fio_org").val( $(this).html() );
		event.preventDefault();
	});

	$("#List").delegate(".on-model", 'click', function (event) {
		$("#id_model").val( $(this).attr('data-model-id') );
		$("#device_name").val( $(this).html() );
		event.preventDefault();
	});
	
	$("#List").delegate(".on-serial", 'click', function (event) {
		$("#serial").val( $(this).html() );
		event.preventDefault();
	});	
});