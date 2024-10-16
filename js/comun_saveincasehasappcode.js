$(document).ready(function() {

	try {
		// cookiepolicy // 
		var k = 'cookiepolicy-agreed',
		v = 'true',
		p = $('#cookie-policy'),
		h = p.height();		

		if ($.cookie(k) !== v) {
			p.css('bottom', -1 * h).removeClass('cp-hidden');
			p.on('click', 'a.cp-close', function (e) {
				e.preventDefault();
				p.animate({bottom: -1 * h}, 300, 'easeInOutCirc');
				$.cookie('cookiepolicy-statistics', 'true', {expires: 365, path: '/'});
				$.cookie(k, v, {expires: 365, path: '/'});
				p.css('bottom', -1 * h).addClass('cp-hidden');
			}).animate({bottom: 0}, 1200, "easeOutBounce");
		}

	  }
	  catch(err) {
		
	  }

	$('.share').on('click', function(){	
		url = $(this).attr("href");
		window.open(url,'ventanacompartir', 'toolbar=0, status=0, width=650, height=450');
		return false;
	}); 

	$('.navbar-toggle').on('click', function(){
		if ($(this).hasClass("wsactive"))		{
			//$(".menu").css('height', 'auto');	
			$(".menu").css('left', '-100%');				
			$("body").css('overflow-y', 'scroll');
			$(this).removeClass("wsactive");
		} else {
			//$(".menu").css('height', '100%');		
			$(".menu").css('left', '0px');		
			$("body").css('overflow-y', 'hidden');
			$(this).addClass("wsactive");
		}
	});

	$('.dropdown .nav-link').on('click', function(){
		if ($(this).hasClass("dactive"))		{
			$("#wsnavtoggle").show();
			$(".navbar-back").hide();
			$(this).removeClass("dactive");
		} else {
			$("#wsnavtoggle").hide();
			$(".navbar-back").show();
			$(this).addClass("dactive");
		}
	});

	$('.navbar-back').on('click', function(){
		$("#wsnavtoggle").show();
		$(".navbar-back").hide();
		$(".dropdown-menu").removeClass("show");	
		$('.dropdown .nav-link').removeClass("dactive");	
	});

	

	function toggleIcon(e) {
		$(e.target)
			.prev('.panel-heading')
			.find(".more-less")
			.toggleClass('bi-chevron-compact-down bi-chevron-compact-up');
	}
	
	$('.panel-group').on('hidden.bs.collapse', toggleIcon);
	$('.panel-group').on('shown.bs.collapse', toggleIcon);	

	$('.pop_config_cookies').on('click', function (e) {
		e.preventDefault();		
		$('.config-box').animate({
			opacity: 1
		}, 1, function(){
			$('#cookie-config').show();
		});
	})

	$('.cp-acept').on('click', function (e) {
		e.preventDefault();
		h = $('#cookie-policy').height();
		$('#cookie-policy').animate({bottom: -1 * h}, 300, 'easeInOutCirc');

		$("#cookie_statistics").prop("checked", true);  

		$.cookie('cookiepolicy-statistics', 'true', {expires: 365, path: '/'});
		$.cookie('cookiepolicy-agreed', 'true', {expires: 365, path: '/'});

		$('.config-box').animate({
			opacity: 0
		}, 300, function(){
			$('#cookie-config').hide();
		});
	})

	$('.cp-guardar').on('click', function (e) {
		e.preventDefault();
		h = $('#cookie-policy').height();
		valor_statistics = $('#cookie_statistics').prop('checked');
		$('#cookie-policy').animate({bottom: -1 * h}, 300, 'easeInOutCirc');
		$.cookie('cookiepolicy-statistics', valor_statistics, {expires: 365, path: '/'});
		$.cookie('cookiepolicy-agreed', 'true', {expires: 365, path: '/'});

		if (!valor_statistics)
		{
			$.removeCookie('_ga',  { path: '/' });
			$.removeCookie('_gat', { path: '/' });
			$.removeCookie('_gid', { path: '/' });
		}

		$('.config-box').animate({
			opacity: 0
		}, 300, function(){
			$('#cookie-config').hide();
		});
	})
	/***************** */
	

});


$(window).on('resize', function(){
	p = $('#cookie-policy'),
	h = p.height();
	p.css('bottom', -1 * h).removeClass('cp-hidden');
});


function checkvalidate(checks) {
	 var selected = ''; 
	$(checks +' input[type=checkbox]').each(function(){
		if (this.checked) {
			selected += $(this).val()+', ';
		}
	}); 
    if (selected != '') 
		return true;
	else
	    return false;
}

function no_XSS (valor) {
	valor = replace(valor,"javascript","")
	valor = replace(valor,"<","")
	valor = replace(valor,">","")
	valor = replace(valor,"alert","")
	valor = replace(valor,"\"","")
	valor = replace(valor,"+","")
	valor = trim(valor)
	return valor;
}

// Removes leading whitespaces
function LTrim( value ) {	
	var re = /\s*((\S+\s*)*)/;
	return value.replace(re, "$1");	
}

// Removes ending whitespaces
function RTrim( value ) {	
	var re = /((\s*\S+)*)\s*/;
	return value.replace(re, "$1");	
}

// Removes leading and ending whitespaces
function trim( value ) {	
	return LTrim(RTrim(value));	
}
function replace(texto,s1,s2){
	return texto.split(s1).join(s2);
}

function validarEmail(valor) {
	if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(valor))
	{
		return (true)
	} else {
		return (false);
	}
}
function isNumber(n) {
  return !isNaN(parseFloat(n)) && isFinite(n);
}
function solo_numeros(e) { // 1
    tecla = (document.all) ? e.keyCode : e.which; // 2
    if (tecla==8 || tecla==9 || tecla==0) return true; // backspace=8, tab=9,0
	patron = /\d/; // Solo acepta números
    te = String.fromCharCode(tecla); // 5
    return patron.test(te); // 6
} 
function solo_letras(e) { // 1
    tecla = (document.all) ? e.keyCode : e.which; // 2
    if (tecla==8) return true; // 3
	patron =/[A-Za-zñÑ\s]/; // Solo acepta letras, pero acepta también las letras ñ y Ñ
    te = String.fromCharCode(tecla); // 5
    return patron.test(te); // 6
} 

function solo_letras_numeros(e) {
    tecla = (document.all) ? e.keyCode : e.which;
    if (tecla==8 || tecla==9 || tecla==0) return true; // backspace=8, tab=9,0
	var patron = /^[a-zA-Z0-9._-]*$/;
    te = String.fromCharCode(tecla); 
    return patron.test(te); 
} 

function validarFecha(Cadena){   
    var Fecha= new String(Cadena);  
    var RealFecha= new Date();  
    var Ano= new String(Fecha.substring(Fecha.lastIndexOf("/")+1,Fecha.length))   
    var Mes= new String(Fecha.substring(Fecha.indexOf("/")+1,Fecha.lastIndexOf("/")))   
    var Dia= new String(Fecha.substring(0,Fecha.indexOf("/")))   
  
    if (isNaN(Ano) || Ano.length<4 || parseFloat(Ano)<1900){   
        //alert('El formato de Año es incorrecto')   
        return false   
    }   
    // Valido el Mes   
    if (isNaN(Mes) || parseFloat(Mes)<1 || parseFloat(Mes)>12){   
        //alert('El formato de Mes es incorrecto')   
        return false   
    }   
    // Valido el Dia   
    if (isNaN(Dia) || parseInt(Dia, 10)<1 || parseInt(Dia, 10)>31){   
       	//alert('El formato de Día es incorrecto')   
        return false   
    }   
    if (Mes==4 || Mes==6 || Mes==9 || Mes==11 || Mes==2) {   
        if (Mes==2 && Dia > 28 || Dia>30) {   
           //alert('Día incorrecto')   
            return false   
        }   
    }   
	return (true)
} 
function FormatNumber(Numero, Decimales) {

	pot = Math.pow(10,Decimales);
	num = parseInt(Numero * pot) / pot;
	nume = num.toString().split('.');

	entero = nume[0];
	decima = nume[1];

	if (decima != undefined) {
		fin = Decimales-decima.length; }
	else {
		decima = '';
		fin = Decimales; }

	for(i=0;i<fin;i++)
	  decima+=String.fromCharCode(48); 

	var num = entero.replace(/\./g,'');
	if(!isNaN(num)){
		num = num.toString().split('').reverse().join('').replace(/(?=\d*\.?)(\d{3})/g,'$1.');
		num = num.split('').reverse().join('').replace(/^[\.]/,'');
	}


	num=num+','+decima;
	return num;
}


function unescape_html(cadena)
{	
	cadena=cadena.replace("&lt;", "<")
	cadena=cadena.replace("&gt;", ">")
	cadena=cadena.replace("&amp;", "&")
	cadena=cadena.replace("&quot;", "'")

	cadena=cadena.replace("&aacute;","á")
	cadena=cadena.replace("&eacute;","é")
	cadena=cadena.replace("&iacute;","í")
	cadena=cadena.replace("&oacute;","ó")
	cadena=cadena.replace("&uacute;","ú")

	cadena=cadena.replace("&Aacute;","Á")
	cadena=cadena.replace("&Eacute;","É")
	cadena=cadena.replace("&Iacute;","Í")
	cadena=cadena.replace("&Oacute;","Ó")
	cadena=cadena.replace("&Uacute;","Ú")

	cadena=cadena.replace("&iquest;", "¿")
	cadena=cadena.replace("&ntilde;", "ñ")	
	cadena=cadena.replace("&nbsp;", " ")
	return cadena;
}


var numeros="0123456789";
var letras="abcdefghyjklmnñopqrstuvwxyz";
var letras_mayusculas="ABCDEFGHYJKLMNÑOPQRSTUVWXYZ";

function tiene_numeros(texto){
   for(i=0; i<texto.length; i++){
      if (numeros.indexOf(texto.charAt(i),0)!=-1){
         return 1;
      }
   }
   return 0;
} 

function tiene_letras(texto){
   texto = texto.toLowerCase();
   for(i=0; i<texto.length; i++){
      if (letras.indexOf(texto.charAt(i),0)!=-1){
         return 1;
      }
   }
   return 0;
} 

function tiene_minusculas(texto){
   for(i=0; i<texto.length; i++){
      if (letras.indexOf(texto.charAt(i),0)!=-1){
         return 1;
      }
   }
   return 0;
} 

function tiene_mayusculas(texto){
   for(i=0; i<texto.length; i++){
      if (letras_mayusculas.indexOf(texto.charAt(i),0)!=-1){
         return 1;
      }
   }
   return 0;
} 
function seguridad_clave(clave){

	/*
	Tiene letras y números: +30%
	Tiene mayúsculas y minúsculas: +30%
	Tiene entre 4 y 5 caracteres: +10%
	Tiene entre 6 y 8 caracteres: +30%
	Tiene más de 8 caracteres: +40% 
	*/

	var seguridad = 0;
	if (clave.length!=0){
		if (tiene_numeros(clave) && tiene_letras(clave)){
			seguridad += 30;
		}
		if (tiene_minusculas(clave) && tiene_mayusculas(clave)){
			seguridad += 30;
		}
		if (clave.length >= 4 && clave.length <= 5){
			seguridad += 10;
		}else{
			if (clave.length >= 6 && clave.length <= 8){
				seguridad += 30;
			}else{
				if (clave.length > 8){
					seguridad += 40;
				}
			}
		}
	}
	return seguridad				
}	

function getCheckedRadioValue(radioGroupName) {
	var rads = document.getElementsByName(radioGroupName),
	 i;
	for (i=0; i < rads.length; i++)
	   if (rads[i].checked)
		   return rads[i].value;
	return 0; // or undefined, or your preferred default for none checked
 }
 