$(document).ready(function () {
	$('#id_customer').change(function () {
		var id_customer = $(this).val();
		if (id_customer == '0') {
			$('#id_project').html('<option>- выберите проект -</option>');
			$('#id_project').attr('disabled', true);
			$('#city_id').html('<option>- выберите город -</option>');
			$('#city_id').attr('disabled', true);
			$('#id_object').html('<option>- выберите объект -</option>');
			$('#id_object').attr('disabled', true);
			document.getElementById('link_project').innerHTML = '';
			return(false);
		}
		document.getElementById('link_project').innerHTML = '<a href="/newproject.php?id_customer=' + id_customer + '">Добавить проект?</a>';
		
		$('#id_project').attr('disabled', true);
		$('#id_project').html('<option>загрузка...</option>');
		$('#city_id').html('<option>- выберите город -</option>');
		$('#city_id').attr('disabled', true);
		$('#id_object').html('<option>- выберите объект -</option>');
		$('#id_object').attr('disabled', true);		
		
		var url = 'get_project.php';
		
		$.get(
			url,
			"id_customer=" + id_customer,
			function (result) {
				if (result.type == 'error') {
					alert('error');
					return(false);
				}
				else {
					var options = '';

					
					$(result.projects).each(function() {
						options += '<option value="' + $(this).attr('id_project') + '">' + $(this).attr('projectname') + '</option>';
					});


					
					$('#id_project').html('<option value="0">- выберите проект -</option>'+options);
					$('#id_project').attr('disabled', false);
					$('#city_id').html('<option>- выберите город -</option>');
					$('#city_id').attr('disabled', true);
					$('#id_object').html('<option>- выберите объект -</option>');
					$('#id_object').attr('disabled', true); 
							
				}
			},
			"json"
		);
	});

$('#id_project').change(function () {
		var id_project = $(this).val(); //$('#region_id :selected').val();
		//alert (region_id);
		if (id_project == '0') {
			$('#city_id').html('<option>- выберите город -</option>');
			$('#city_id').attr('disabled', true);
			$('#id_object').html('<option>- выберите объект -</option>');
			$('#id_object').attr('disabled', true);
			document.getElementById('link_object').innerHTML = '';
			return(false);
		}
		document.getElementById('link_object').innerHTML = '<a href="/newobject.php?id_project=' + id_project + '">Добавить объект?</a>';
		$('#city_id').html('<option>загрузка...</option>');
		$('#city_id').attr('disabled', true);
		$('#id_object').html('<option>- выберите объект -</option>');
		$('#id_object').attr('disabled', true);
		
		var url = 'get_objects_city.php';
		
		$.get(
			url,
			"id_project=" + id_project,
			
			function (result) {
				if (result.type == 'error') {
					alert('error');
					return(false);
				}
				else {
					var options = ''; 
					$(result.objects).each(function() {
						options += '<option value="' + $(this).attr('city_id') + '">' + $(this).attr('city_name') + '</option>'; 
						
					});
					$('#city_id').html('<option value="0">- выберите город -</option>'+options);		
					$('#city_id').attr('disabled', false);
					$('#id_object').html('<option>- выберите объект -</option>');
					$('#id_object').attr('disabled', true); 

				}
			},
			"json" 
		);
	});
$('#city_id').change(function () {
		var city_id = $(this).val(); 
		if (city_id == '0') {
			$('#city_id').html('<option>- выберите город -</option>');
			$('#city_id').attr('disabled', true);
			return(false);
		}
		$('#id_object').attr('disabled', true);
		$('#id_object').html('<option>загрузка...</option>');
		
		var url = 'get_object.php';
		var id_pr = $('#id_project').val();
		$.get(
			url,
			"city_id=" + city_id + "&id_project=" + id_pr,
			
			function (result) {
				if (result.type == 'error') {
					alert('error');
					return(false);
				}
				else {
					var options = ''; 
					$(result.citys).each(function() {
						options += '<option value="' + $(this).attr('id_object') + '">' + 'Объект: ' + $(this).attr('shop_number') + '. ' + $(this).attr('address') + '</option>'; 
						
					});
					$('#id_object').html('<option value="0">- выберите объект -</option>'+options);		
					$('#id_object').attr('disabled', false);

				}
			},
			"json" 
		);
	});










	
});
