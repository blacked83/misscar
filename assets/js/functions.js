//Funcion para validar el ingreso de datos
var letras=' ABCÇDEFGHIJKLMNÑOPQRSTUVWXYZabcçdefghijklmnñopqrstuvwxyzàáÀÁéèÈÉíìÍÌïÏóòÓÒúùÚÙüÜ';
var numeros='1234567890';
var signos=',.:;@-_';
var signosmatematicos='+-=()*/';
var personal='<>#$%&?¿{}!¡';
var especiales = '\'\"';
var sololetras = numeros+signos+signosmatematicos+personal+especiales;
var sololetrasnum = signos+signosmatematicos+personal+especiales;

function validar(evnt,tipo) { 
	var temporal; 
	temporal = document.all?parseInt(evnt.keyCode): parseInt(evnt.which);
	if (temporal == 13 || temporal == 8 || temporal == 0 || temporal == 9) return true;
	return (tipo.indexOf(String.fromCharCode(temporal)) != -1);
}

function rechazar(evnt,tipo) { 
	var temporal; 
	temporal = document.all?parseInt(evnt.keyCode): parseInt(evnt.which);
	if (temporal == 13 || temporal == 8 || temporal == 0 || temporal == 9) return true;
	return (tipo.indexOf(String.fromCharCode(temporal)) == -1);
}

function is_email(correo){
	let regex = /^\S+@(\S+\.\S+)+$/;
	return regex.test(correo);
}

function is_phone(telf){
	let regex = /^\+?[0-9]{9,14}$/
	return regex.test(telf);
}

function esRuc(ruc){
	if(ruc.length == 13){
		if(!parseInt(ruc))
			return false;
		var rucArray = [];
		var coeficiente;
		var modulo = 11;
		rucArray.push(ruc.substr(0,2));
		rucArray.push(ruc.substr(2,1));
		if(parseInt(rucArray[0]) == 0 || parseInt(rucArray[0]) > 24)
			return false;
		if(parseInt(rucArray[1]) < 0 || (parseInt(rucArray[1]) > 6 && parseInt(rucArray[1]) < 9))
			return false;
		if(rucArray[1] == '6'){
			rucArray.push(ruc.substr(3,5));
			rucArray.push(ruc.substr(9,4));
			rucArray.push(ruc.substr(8,1));
			coeficiente = '3.2.7.6.5.4.3.2';
		} else {
			rucArray.push(ruc.substr(3,6));
			rucArray.push(ruc.substr(10,3));
			rucArray.push(ruc.substr(9,1));
			if(rucArray[1] == '9'){
				coeficiente = '4.3.2.7.6.5.4.3.2';
			}else{
				coeficiente = '2.1.2.1.2.1.2.1.2';
				modulo = 10;
			}
		}
		let coefArray = coeficiente.split('.');
		let numVerif = rucArray[0] + rucArray[1] + rucArray[2];
		let acumRUC = 0;
		for(i = 0; i < numVerif.length; i++){
			let num = parseInt(numVerif.substr(i,1)) * parseInt(coefArray[i]);
			if(modulo == 10 && num > 9)
				num = num - 9;
			acumRUC += num;
		}
		let residuo = acumRUC % modulo;
		let numval = residuo > 0 ? modulo - residuo : 0;
		if(numval === parseInt(rucArray[4])){
			return true;
		}else{
			return false;
		}

	}else if(ruc.length == 10){
		if(!parseInt(ruc))
			return false;
		var cedArray = [];
		var coeficiente = '2.1.2.1.2.1.2.1.2';
		var modulo = 10;
		cedArray.push(ruc.substr(0,2));
		cedArray.push(ruc.substr(2,1));
		if(parseInt(cedArray[0]) == 0 || parseInt(cedArray[0]) > 24)
			return false;
		if(parseInt(cedArray[1]) < 0 || parseInt(cedArray[1]) >= 6)
			return false;
		cedArray.push(ruc.substr(3,6));
		cedArray.push(ruc.substr(9,1));
		
		let coefArray = coeficiente.split('.');
		let numVerif = cedArray[0] + cedArray[1] + cedArray[2];
		let acumCED = 0;
		for(i = 0; i < numVerif.length; i++){
			let num = parseInt(numVerif.substr(i,1)) * parseInt(coefArray[i]);
			if(num > 9)
				num = num - 9;
			acumCED += num;
		}
		let residuo = acumCED % modulo;
		let numval = residuo > 0 ? modulo - residuo : 0;
		if(numval === parseInt(cedArray[3])){
			return true;
		}else{
			return false;
		}
	}else{
		return false;
	}
}

function formatPhone(tel){
    if(tel.length == 10){
        return '('+tel.substr(0,3)+') '+tel.substr(3,7);
    }else if(tel.length == 9){
        if(parseInt(tel.substr(0,1)) == 0){
			return '('+tel.substr(0,2)+') '+tel.substr(2,7);
		}else{
			return '(0'+tel.substr(0,2)+') '+tel.substr(2,7);
		} 
	}else if(tel.length == 8){
		return '(0'+tel.substr(0,1)+') '+tel.substr(1,7);
	}else{
        return tel;
    }
}

//Función para dar formato tipo Capital al Texto
function capitalFormat(texto){
	var textos = new Array("de","del","la","el", "en", "y", "demas", "con", "los", "al", "a");
	var textosmayus = new Array("c.c","c.c.","c/c","i", "ii","iii","iv","v","vi","vii","viii","ix","x","c.a","c.a.","s.a","s.a.","s.r.l","s.r.l.");
	var articulo;
	var count;
	var newtext = texto.value.toLowerCase();
	var arraytext = newtext.split(" ");
	var auxtext;
	
	for(i = 0; i <= arraytext.length - 1; i++)
	{
		articulo = false;
		count = 0;
		while(articulo == false && count <= textos.length - 1)
		{
			if(arraytext[i] == textos[count] && i > 0) articulo = true;
			count++;
		} 
		if(articulo == false)
		{
			articulo = false;
			count = 0;
			while(articulo == false && count <= textosmayus.length - 1)
			{
				if(arraytext[i] == textosmayus[count])
				{
					articulo = true;
					arraytext[i] = arraytext[i].toUpperCase();
				}
				count++;
			} 
			if(articulo == false)
			{
				if(arraytext[i].length > 1)
				{
					auxtext = arraytext[i].substring(0,1);
					arraytext[i] = auxtext.toUpperCase() + arraytext[i].substring(1,arraytext[i].length);
				} else {
					auxtext = arraytext[i].toUpperCase();
					arraytext[i] = auxtext;
				}
			}
		}
	}
	texto.value = arraytext.join(" ");
}