$(document).ready(function(){
	$("#firma_tel").mask("(999) 999-9999");

	$('[data-toggle=tooltip]').tooltip();

	function notify (type, text) {
		$.jGrowl.defaults.closerTemplate = ''; //'<div class="alert alert-info">Close All</div>';

		var alertTypes = ['success', 'info', 'warning', 'danger'];

		$('#jGrowl-container').jGrowl({
			header: type.toUpperCase() + ' Notification',
			message: text,
			group: 'alert-' + type,
			life: 5000
		});
	};

	$('#search').focus();
	$('#search-clear').on('click', function(){ $('#search').val('').trigger('keyup').focus();} );

	$('#table-works').searchable({
		striped: true,
		oddRow: { 'background-color': '#f5f5f5' },
		evenRow: { 'background-color': '#ffffff' },
		searchType: 'fuzzy'
	});

	$(".statusbar label").on('click', function () {
			var id = $(this).attr('data-id');
			var value = $(this).attr('data-value');
		$.ajax({
			type: "GET",
			url: 'ajax/status.php',
			data: "q="+value+"&idr="+id,
			success: function(data, textStatus){
				notify('success', "Статус изменён");
			},
			error: function(jqXHR, textStatus, errorThrown){
				notify('danger', textStatus);
			}
		});
	});

	if($("#work-worker").attr('data-worker-id') == 1)
	$("#worker-name").hide();
	else $("#worker-select").hide();

	$(".onPartsAdd").on('click', function () {
		$("#parts").append($("#parts_row").html());
	});

	$(".onWorkEdit").on('click', function (event) {
		var id = $(this).attr('data-work-id');
		$("#editFrame").show();
		$("#editForm").attr("action", "index.php?r=single/_edit");		
		$("#edit_id_work").val(id);
		$.ajax({
			type: "GET",
			url: 'ajax/edit_work.php',
			data: "id="+id,
			dataType: "json",
			success: function(data, textStatus){
				$("#edit_date").val(data.date);
				$("#edit_text").html(data.text);
				$("#edit_price").val(data.price);
				$("#parts").html("");
				if(data.hardJson) {
					$.each(data.hardJson, function(id, text) {
						$("#parts_row").show();
						$("#parts_row").find("input[type=text]").val(text.edit_hard);
						$("#parts_row").find("input[type=number]").val(text.edit_hardprice);
						$("#parts_row").clone().appendTo('#parts');
						$("#parts_row").hide();
					});
				};
				$("#edit_worker option").each(function (index) {
					$(this).removeAttr('selected');
					if($(this).val() == data.id_worker) {
						$(this).attr("selected", true);
					}
				});
				$("html, body").animate({scrollDown: $("#editFrame")}, 500);
				$("#edit_text").focus();
			},
			error: function(jqXHR, textStatus, errorThrown){
				alert(textStatus);
			}
		});
		event.preventDefault();
	});

	$(".onWorkAdd").on('click', function (event) {
		$("#editFrame").show();
		$("#editForm").attr("action", "index.php?r=single/_create");
		$("#edit_id_work").val("0");
		$("#edit_date").val($(this).attr('data-curr-date'));
		$("#edit_text").html("");
		$("#edit_price").val("");
		$("#parts").html("");
		$("#edit_worker option").each(function (index) {
			$(this).removeAttr('selected');
			if( $(this).val() == $("#work-worker").attr("data-worker-id") ) {
				$(this).attr("selected", true);
			}
		});
		$("html, body").animate({scrollDown: $("#editFrame")}, 500);
		$("#edit_text").focus();

		event.preventDefault();
	});

	$("#editForm").on('submit', function(event) {
		var id = $("#edit_id_work").val();
		if(id == 0) var action = "create";
		else var action = "edit";

		var parts = new Array();
		var parts_price = 0;
		$("#parts .row").each(function(index) {
			parts.push({ 
				'edit_hard': $(this).find("input[type=text]").val(), 
				'edit_hardprice':  parseInt($(this).find("input[type=number]").val()),
			});
			parts_price = parts_price + parseInt($(this).find("input[type=number]").val());
		});
		$("#edit_parts").val(JSON.stringify(parts));
		$("#edit_parts_price").val(parts_price);

		// var json = JSON.stringify({
		// 	'edit_date': $('#edit_date').val(),
		// 	'edit_text': $('#edit_text').html(),
		// 	'edit_price':  parseInt($('#edit_price').val()),
		// 	'edit_parts': $('#edit_parts').val(),
		// 	'edit_parts_price':  parseInt($("#edit_parts_price").val()),
		// 	'edit_worker': $('#edit_worker').val(),
		// });
	});
});