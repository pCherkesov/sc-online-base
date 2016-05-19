$(document).ready(function(){
	$('[data-toggle=tooltip]').tooltip();

	// ADD BackToTop Button
	$('body').append('<div id="backToTop" class="btn"><span class="glyphicon glyphicon-chevron-up"></span></div>');
	$(window).scroll(function () {
		if ($(this).scrollTop() <= 400) {
			$('#backToTop').fadeOut();
		} else {
			$('#backToTop').fadeIn();
		}
	}); 
	$('#backToTop').on('click', function(event){
		$("html, body").animate({ scrollTop: 0 }, 600);
		return false;
	});

	// Send mail to support
    $("#sendMail_submit").on('click', function() {
        var text = $("#sendMail_text").val();
        $.ajax({
                type: "POST",
                url: './ajax/send_mail.php',
                data: "msg="+text,
                success: function(data){
                        notify('success', "Сообщение отправлено <br>"+data);
                },
                error: function(jqXHR, textStatus, errorThrown){
                        notify('danger', textStatus + " " + errorThrown);
                },
                always: function() {
                }
        });
        $('#sendMail_modal').modal('hide');
    });

	$("#globalSMS_tel").mask("(999) 999-9999");
	$("#globalSMS_submit").on('click', function() {
		var id = 0;
		var tel = $("#globalSMS_tel").val();
		var text = $("#globalSMS_text").val();
		$(this).button('loading');
		$.ajax({
				type: "POST",
				url: './ajax/send_sms.php',
				data: "id="+id+"&tel="+tel+"&msg="+text,
				success: function(data){
						notify('success', "Сообщение отправлено <br>"+data);
				},
				error: function(jqXHR, textStatus, errorThrown){
						notify('danger', textStatus + " " + errorThrown);
				},
				always: function() {
				}
		});
		$('#globalSMS_modal').modal('hide');
	});

});

// jGrowl notify
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