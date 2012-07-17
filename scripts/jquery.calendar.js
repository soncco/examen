jQuery(function($){
	$.datepicker.regional['es'] = {clearText: 'Limpiar', clearStatus: '',
		closeText: 'Cerrar', closeStatus: '',
		prevText: '&lt;Anterior', prevStatus: '',
		nextText: 'Siguiente&gt;', nextStatus: '',
		currentText: 'Hoy', currentStatus: '',
		monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio',
		'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
		monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun',
		'Jul','Ago','Sep','Oct','Nov','Dic'],
		monthStatus: '', yearStatus: '',
		weekHeader: 'Sm', weekStatus: '',
		dayNames: ['Domingo','Lunes','Martes','Mi&eacute;rcoles','Jueves','Viernes','S&aacute;dabo'],
		dayNamesShort: ['Dom','Lun','Mar','Mi&eacute;','Juv','Vie','S&aacute;b'],
		dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','S&aacute;'],
		dayStatus: 'DD', dateStatus: 'D, M d',
		dateFormat: 'yy-mm-dd', firstDay: 0, 
		initStatus: '', isRTL: false
		//maxDate: '0D'
		};
	$.datepicker.setDefaults($.datepicker.regional['es']);
});

$(document).ready(function() {
	$('.date').datepicker($.datepicker.regional["es"]);

});