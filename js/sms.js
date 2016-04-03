$(document).ready(function(){
	$('#search').focus();
	$('#search-clear').on('click', function(){ $('#search').val('').trigger('keyup').focus();} );
		
	$(".onSmsStatus").on('click', function() {
		var id = $(this).attr("data-smsc-id");
		var tel = $(".statusTel").html();
		$.ajax({
				type: "POST",
				url: './ajax/sms_status.php',
				data: "id="+id+"&tel="+tel,
				success: function(data){
						notify('success', "Статус обновлён на "+data);
				},
				error: function(jqXHR, textStatus, errorThrown){
						notify('danger', textStatus + " " + errorThrown);
				},
		});
	});	
});
