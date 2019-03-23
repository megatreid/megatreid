$(document).ready(function SelectOPT(){
	
	var spisok = document.querySelector('#work_type'),
		text = document.querySelectorAll('div #work_type_select');
	function option () {

		switch (spisok.value) {
		case '0':
			text[0].style.display = "none";
			text[1].style.display = "none";

			break;
		case '1':
			text[0].style.display = "";
			text[1].style.display = "none";

			break;
		case '2':
			text[0].style.display = "";
			text[1].style.display = "none";
			
			break;
		case '3':
			text[0].style.display = "none";
			text[1].style.display = "";
			
			break;
		default:
			text[0].style.display = "none";
			text[1].style.display = "none";
		<!--case '3': -->
     	<!-- text.forEach(function (e, i) {e.style.display = "";}); -->

			<!-- break; -->
	  }
	}
	   option();
spisok.addEventListener('change', function () {option();});
   });
$(document).ready(function Select_ispolnitel(){

	var spisok_isp = document.querySelector('#implementer'),
		text_isp = document.querySelectorAll('#contr_select');
	function option () {

		switch (spisok_isp.value) {
		case '0':
			text_isp[0].style.display = "none";
			text_isp[1].style.display = "";
			text_isp[2].style.display = "";
			text_isp[3].style.display = "";
			text_isp[4].style.display = "";
			text_isp[5].style.display = "";
			text_isp[6].style.display = "";
			text_isp[7].style.display = "";
			text_isp[8].style.display = "";
			text_isp[9].style.display = "";
			text_isp[10].style.display = "";
			text_isp[11].style.display = "";
			text_isp[12].style.display = "";
			text_isp[13].style.display = "";
			text_isp[14].style.display = "";
			
			break;
		case '1':
			text_isp[0].style.display = "";
			text_isp[1].style.display = "none";
			text_isp[2].style.display = "none";
			text_isp[3].style.display = "none";
			text_isp[4].style.display = "none";
			text_isp[5].style.display = "none";
			text_isp[6].style.display = "none";
			text_isp[7].style.display = "none";
			text_isp[8].style.display = "none";
			text_isp[9].style.display = "none";
			text_isp[10].style.display = "none";
			text_isp[11].style.display = "none";
			text_isp[12].style.display = "none";
			text_isp[13].style.display = "none";
			text_isp[14].style.display = "none";

			break;
	  }
	}
	   option();
spisok_isp.addEventListener('change', function () {option();});
   });