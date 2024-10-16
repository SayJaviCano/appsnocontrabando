function ajax() {
	// De esta forma se obtiene la instancia del objeto XMLHttpRequest
	if(window.XMLHttpRequest) {
	  connection = new XMLHttpRequest();
	}
	else if(window.ActiveXObject) {
	  connection = new ActiveXObject("Microsoft.XMLHTTP");
	}
   
	var v_referrer = document.referrer;
	var v_title = document.title; 
	var v_url = document.URL; 
	if (v_referrer=="") { v_referrer = "Null"; }

	v_referrer = replace(v_referrer,"&","|");
	v_url = replace(v_url,"&","|");	

	var param = v_referrer + "#" + v_title + "#" + v_url;  	
	connection.onreadystatechange = response;   
	connection.open('POST', dominio+'includes/ajax_analytics.php');
	connection.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	connection.send("param=" + param + "&nocache=" + Math.random());
  }
   
	function response() {
		if(connection.readyState == 4) {
			//alert(connection.responseText);
		}
	}
	function replace(texto,s1,s2){
		return texto.split(s1).join(s2);
	}  
ajax();