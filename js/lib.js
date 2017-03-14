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
			return select.options[i].value;
		}
	}
}

function updatehoras(cod,curh,curm,curs) {
	var hora, minu, sec;
	hora = selectopt('hora',cod);
	minu = selectopt('min',cod);
	sec = selectopt('sec',cod);
	horas(cod,hora,minu,sec,curh,curm,curs);	
}

function horas(cod,hora,minu,sec,curh,curm,curs) {
	var opth,selecthour,selectmin,selectsec,i,hora,minu,sec,cod,curh,curm,curs,fin;
	selecthour = document.getElementById("hora"+cod);
	if (hora < curh) {			
		sel = hora;
		svh = 1;
	} else {
		sel = curh;
		svh=0;
	}
	fin = curh;
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
	if (svh || (minu < curm)) {			
		sel = minu;
		svm = 1;
	} else {
		sel = curm;
		svm=0;
	}
	if (svh) {
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
	if (svm || svh || (sec < curs)) {			
		sel = sec;
	} else {
		sel = curs;
	}
	if (svh || svm) {
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
