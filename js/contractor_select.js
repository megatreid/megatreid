$(document).ready(function () {
	$('#city_id_contr').change(function () {
		var city_id_contr = $(this).val();
		if (city_id_contr == '0')
		{
			$('#id_contractor').html('<option></option>');
			$('#id_contractor').attr('disabled', true);
			return(false);
		}

		$('#id_contractor').html('<option>загрузка...</option>');
		$('#id_contractor').attr('disabled', true);

		var url = 'get_contractor.php';
		
		$.get(
			url,
			"city_id=" + city_id_contr,
			function (result) {
				if (result.type == 'error') {
					alert('error');
					return(false);
				}
				else {
					var options = '';
					
					$(result.orgnames).each(function() {
						options += '<option value="' + $(this).attr('id_contractor') + '">' +$(this).attr('org_name') + ' ' + $(this).attr('ownership') + '</option>';
					});

					$('#id_contractor').html('<option value="0">- выберите контрагента -</option>'+options);
					$('#id_contractor').attr('disabled', false);
				}
			},
			"json"
		);
	});
});