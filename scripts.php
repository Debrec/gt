<SCRIPT>
	function checkmail() {
		var mail,tmail;
		mail = document.getElementById('email');
		email = mail.value;
    expr = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    if ( !expr.test(email) ) {
        alert("Error: La dirección de correo " + email + " es incorrecta.");
		}
	}
</SCRIPT>
