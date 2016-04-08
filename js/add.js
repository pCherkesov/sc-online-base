$(document).ready(function(){
	$("#tel_human").mask("(999) 999-9999");
	$("#editClient_tel").mask("(999) 999-9999");

	$('#on-addWork')[0].reset();
	$("#id_client").val("1");
	$('#ListPanel').collapse('hide');
	$('#fio_human').focus();

	$(".on-searchClear").on('click', function(event) {
		$("#ListSearch").val('');
	});

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
		loadList("serial", args, false);
	});

	function loadList (action, args = "", focus = true) {
		$('#ListPanel').collapse('show');
		$('#List').html('<div id="loader"></div>');
		$.ajax({
			type: "POST",
			url: 'ajax/add.php',
			data: "action=" + action + args,
			success: function(data, textStatus){
				$('#List').html(data);
				$('#List').searchable({
					searchField: "#ListSearch",
					searchType: 'default',
					reset: 'true',
				});
				if(focus == true) {	$("#ListSearch").val('').focus(); }
			},
			error: function(jqXHR, textStatus, errorThrown){
				notify('danger', textStatus);
			}
		});
	};


	$("#List").on('click', "a.on-client", function (event) {
		$("#id_client").val( $(this).attr('data-model-id') );
		$("#tel_org").val( $(this).attr('data-client-tel') );
		$("#fio_org").val( $(this).html() );
		event.preventDefault();
	});

	$("#List").on('click', ".on-addClient", function(event) {
		$("#editClient_id").val('0');
		$("#editClient_name").val('');
		$("#editClient_tel").val('');
		$("#editClient_modal").modal('show');
	});

	$("#List").on('click', "span.on-client", function (event) {
		$("#editClient_id").val($(this).attr('data-client-id'));
		$("#editClient_name").val($(this).attr('data-client-name'));
		$("#editClient_tel").val($(this).attr('data-client-tel'));
		$("#editClient_modal").modal('show');
	});

	$("#editClient_submit").on('click', function(event) {
		var id = $("#editClient_id").val();
		var client = $("#editClient_name").val();
		var client_tel = $("#editClient_tel").val();
		$.ajax({
			type: "POST",
			url: 'ajax/add_edit_client.php',
			data: "id_client="+id+"&client_name="+client+"&client_tel="+client_tel,
			success: function(data, textStatus){
				$("#editClient_modal").modal('hide');
				loadList("client");
			},
			error: function(jqXHR, textStatus, errorThrown){
				notify('danger', textStatus);
			}
		});
	});

	$("#List").on('click', "span.on-model", function (event) {
		$("#editModel_id").val($(this).attr('data-model-id'));
		$("#editModel_type").val($(this).attr('data-model-type'));
		$("#editModel_brand").val($(this).attr('data-model-brand'));
		$("#editModel_name").val($(this).attr('data-model-name'));
		$("#editModel_modal").modal('show');
	});

	$('#editModel_type').typeahead({
		source: function (query, process) {
			return loadModel('type', query, process);
		}
	});

	$('#editModel_brand').typeahead({
		source: function (query, process) {
			return loadModel('brand', query, process);
		}
	});

	$('#editModel_name').typeahead({
		source: function (query, process) {
			return loadModel('model', query, process);
		}
	});

	function loadModel(type, query, process) {
		return $.post(
			'ajax/add_edit_model.php', 
			{'action': 'typeahead-'+type, 'name': query},
			function (response) {
				var data = new Array();
				if(response !== null) {
					$.each(response, function(i, name) {
						data.push(name);
					});
				}
				return process(data);
			},
			'json'
		);
	}

	$("#List").on('click', ".on-serial", function (event) {
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