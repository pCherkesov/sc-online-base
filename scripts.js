function freeze(value)
{
	if(value==1){
		document.getElementById('client').disabled = false;
		document.getElementById('client_fio').disabled = true;
		document.getElementById('client_tel').disabled = true;
	}
	if(value==2){
		document.getElementById('client').disabled = true;
		document.getElementById('client_fio').disabled = false;
		document.getElementById('client_tel').disabled = false;
	}
	if(value==3){
		form.models.disabled = false;
		form.client_tel.disabled = true;
	}
	if(value==4){
		form.models.disabled = true;
		form.client_tel.disabled = true;
	}
	if(value==5){
		form.models.disabled = true;
		form.client_tel.disabled = false;	
	}
	if(value==11){
		document.getElementById('radio_0').checked = true;
		document.getElementById('radio_1').checked = false;
		document.getElementById('client_fio').disabled = true;
		document.getElementById('client_tel').disabled = true;
	}
	if(value==12){
		doSecond('void', 0, 0);
		document.getElementById('radio_0').checked = false;
		document.getElementById('radio_1').checked = true;
		document.getElementById('client_fio').disabled = false;
		document.getElementById('client_tel').disabled = false;
	}
	if(value==13){
		doSecond('name', 0, 0);
		document.getElementById('client_index').value = 'firma';
		document.getElementById('index_chelovek').className = 'client_index_passive';
		document.getElementById('index_firma').className = 'client_index_active';
		document.getElementById('client_firma').className = 'firma';
		document.getElementById('client_chelovek').className = 'chelovek_hid';
	}
	if(value==14){
		doSecond('void', 0, 0);
		document.getElementById('client_index').value = 'chelovek';
		document.getElementById('index_firma').className = 'client_index_passive';
		document.getElementById('index_chelovek').className = 'client_index_active';
		document.getElementById('client_chelovek').className = 'chelovek';
		document.getElementById('client_firma').className = 'firma_hid';
	}
}