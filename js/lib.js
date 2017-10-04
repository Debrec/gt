//var fechaArrGlobal = Array();
function pad (n, length) {
    var  n = n.toString();
    while(n.length < length)
         n = "0" + n;
return n;
}

function selectopt(field,cod) {
	select = document.getElementById(field+cod);
	for (i = 0;i<select.options.length;i++) {
		if (select.options[i].selected) {
			if (field == 'fecha') {
				return select.options[i].innerHTML;
			} else {
				return select.options[i].value;
			}
		}
	}
}

function updatefechahora(cod,fechaArray,curf,curh,curm,curs) {
	var fecha,hora,minu,sec;
	fecha = selectopt('fecha',cod);
	hora = selectopt('hora',cod);
	minu = selectopt('min',cod);
	sec = selectopt('sec',cod);
	fechahora(cod,fechaArray,fecha,hora,minu,sec,curf,curh,curm,curs);	
}

function fechahora(cod,fechaArr,fecha,hora,minu,sec) {
	var opth,selectfecha,selecthour,selectmin,selectsec,i,fecha,hora,minu,sec,cod,curh,curm,curs,fin;
	selectfecha = document.getElementById("fecha"+cod);
	var f = new Date();
	curf = f.getFullYear()+"-"+pad(f.getMonth()+1,2)+"-"+pad(f.getDate(),2);
	curh = f.getHours();
	curm = f.getMinutes();
	curs = f.getSeconds();
	if (fecha != curf) {
		sel = fecha;
		svf = 1;
	} else {
		sel = curf;
		svf = 0;
	}
	selectfecha.options.length=0;
	for (i=0;i<fechaArr.length;i++) {
		optfecha = document.createElement('option');
		optfecha.value = i;
		optfecha.innerHTML = fechaArr[i];
		if (fechaArr[i] == sel) {
			optfecha.selected=true;
		}
		selectfecha.appendChild(optfecha);
	}

	selecthour = document.getElementById("hora"+cod);
	if (svf || (hora < curh)) {			
		sel = hora;
		svh = 1;
	} else {
		sel = curh;
		svh=0;
	}

	if (svf) {
		fin = 24;		
	} else {
		fin = curh;
	}

	selecthour.options.length=0;		
	for (i=0;i<=fin;i++) {
		if (i<10) {
			val=pad(i,2);
		} else {
			val=i;
		}
		opth = document.createElement('option');
    	opth.value = val;
    	opth.innerHTML = val;
    	if (i == sel) {
    		opth.selected=true;
    	}
    	selecthour.appendChild(opth);
	}
	selectmin = document.getElementById("min"+cod);
	if (svf || svh || (minu < curm)) {			
		sel = minu;
		svm = 1;
	} else {
		sel = curm;
		svm=0;
	}
	if (svf || svh) {
		fin = 59;		
	} else {
		fin = curm;
	}
	selectmin.options.length=0;
	for (i=0;i<=fin;i++) {
		if (i<10) {
			val=pad(i,2);
		} else {
			val=i;
		}
		opth = document.createElement('option');
    	opth.value = val;
    	opth.innerHTML = val;
    	if (i == sel) {
    		opth.selected=true;
    	}
    	selectmin.appendChild(opth);
	}
	selectsec = document.getElementById("sec"+cod);
	if (svf || svm || svh || (sec < curs)) {			
		sel = sec;
	} else {
		sel = curs;
	}
	if (svf || svh || svm) {
		fin = 59;		
	} else {
		fin = curs;
	}
	selectsec.options.length=0;	
	for (i=0;i<=fin;i++) {
		if (i<10) {
			val=pad(i,2);
		} else {
			val=i;
		}
		opth = document.createElement('option');
    	opth.value = val;
    	opth.innerHTML = val;
     	if (i == sel) {
    		opth.selected=true;
    	}
    	selectsec.appendChild(opth);
	}
}
