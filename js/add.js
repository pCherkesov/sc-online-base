$(document).ready(function(){
	$("#tel_human").mask("(999) 999-9999");

	$('#on-addWork')[0].reset();
	$("#id_client").val("1");
	$('#ListPanel').collapse('hide');
	$('#fio_human').focus();


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
		$("#on-ListName").html("Клиенты");
		loadList("client");
		$("#tel_org").val('');
		$("#fio_org").val('');
	});

	$('#deviceFields').on('click', function() {
		$("#on-ListName").html("Устройства");
		loadList("device");
	});

	$('#serialFields').on('click', function () {
		$("#on-ListName").html("Серийные номера");
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

	$("#on-addWork").on('submit', function(event) {
		var error = 0;

		$("#on-addWork input").each(function (count) {
			$(this).removeClass('has-error');
			$(this).removeClass('has-success');
		});

		if($("#id_client").val() == "1" && $("#fio_human").val() == "") {
			$("#fio_human").addClass('has-error');
			error = 1;
		}
		if($("#id_model").val() == ""){
		$("#device_name").addClass('has-error');
			error = 1;
		}
		
		if(error == 1) { event.preventDefault(); }
	});
});