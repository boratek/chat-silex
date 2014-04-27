window.onload = initPage;
//var usernameValid = false;
//var passwordValid = false;

function initPage() {
  //document.getElementById("username").onblur = checkUsername;
  //document.getElementById("password2").onblur = checkPassword;
  //document.getElementById("register").disabled = true;
  //document.getElementById("register").onclick = registerUser;
  document.getElementById("email").onblur = checkEmail;
}

function checkEmail(){
	var email = document.getElementById("email").value;
	if (email.length == 0) {
        alert('Nie wpisałeś adresu!')
} else {
        alert('Twój tekst zaczyna się od: ' + email.charAt(0) );
}	
	
	}
/*
function checkUsername() {
  document.getElementById("username").className = "thinking";
  usernameRequest = createRequest();
  if (usernameRequest == null)
    alert("Nie można utworzyć żądania.");
  else {
    var theName = document.getElementById("username").value;
    var username = escape(theName);
    var url= "checkName.php?username=" + username;
    usernameRequest.onreadystatechange = showUsernameStatus;
    usernameRequest.open("GET", url, true);
    usernameRequest.send(null);
  }
}

function showUsernameStatus() {
  if (usernameRequest.readyState == 4) {
    if (usernameRequest.status == 200) {
      if (usernameRequest.responseText == "okay") {
        document.getElementById("username").className = "approved";
        document.getElementById("register").disabled = false;
        usernameValid = true;        
      } else {
        document.getElementById("username").className = "denied";
        document.getElementById("username").focus();
        document.getElementById("username").select();
        document.getElementById("register").disabled = true;
        usernameValid = false;
      }
      checkFormStatus();
    }
  }
}

function checkPassword() {
	var password1 = document.getElementById("password1");
	var password2 = document.getElementById("password2");
	password1.className = "thinking";
	
	// Zaczynamy od porównania obydwu haseł.
	if ((password1.value == "") || (password1.value != password2.value)) {
		password1.className = "denied";
    passwordValid = false;
    checkFormStatus();
		return;	
	}
	
	// Hasła są zgodne, więc wysyłamy żądanie do serwera.
	passwordRequest = createRequest();
	if (passwordRequest == null) {
		alert("Nie można utworzyć żądania");		
	} else {
		var password = escape(password1.value);
		var url = "checkPass.php?password=" + password;
		passwordRequest.onreadystatechange = showPasswordStatus;
		passwordRequest.open("GET", url, true);
		passwordRequest.send(null);
	}
}

function showPasswordStatus() {
	if (passwordRequest.readyState == 4) {
		if (passwordRequest.status == 200) {
			var password1 = document.getElementById("password1");
			if (passwordRequest.responseText == "okay") {
				password1.className = "approved";
				document.getElementById("register").disabled = false;
        passwordValid = true;
			} else {
				password1.className = "denied";
				password1.focus();
				password1.select();
				document.getElementById("register").disabled = true;
        passwordValid = false;
			}
      checkFormStatus();
		}
	}
}

function checkFormStatus() {
  if (usernameValid && passwordValid) {
    document.getElementById("register").disabled = false;
  } else {
    document.getElementById("register").disabled = true;
  }
}

function registerUser() {
  t = setInterval(scrollImages, 50);
  document.getElementById("register").value = "Przetwarzam...";
  registerRequest = createRequest();
  if (registerRequest == null) {
    alert("Nie można utworzyć żądania.");
  } else {
    var url = "register.php?username=" +
      escape(document.getElementById("username").value) + "&password=" +
      escape(document.getElementById("password1").value) + "&firstname=" +
      escape(document.getElementById("firstname").value) + "&lastname=" +
      escape(document.getElementById("lastname").value) + "&email=" +
      escape(document.getElementById("email").value) + "&genre=" +
      escape(document.getElementById("genre").value) + "&favorite=" +
      escape(document.getElementById("favorite").value) + "&tastes=" +
      escape(document.getElementById("tastes").value);
    registerRequest.onreadystatechange = registrationProcessed;
    registerRequest.open("GET", url, true);
    registerRequest.send(null);
  }
}

function registrationProcessed() {
  if (registerRequest.readyState == 4) {
    if (registerRequest.status == 200) {
      document.getElementById('wrapper').innerHTML =
        registerRequest.responseText;
    }
  }
}

function scrollImages() {
  var coverBarDiv = document.getElementById("coverBar");
  var images = coverBarDiv.getElementsByTagName("img");
  for (var i = 0; i < images.length; i++) {
    var left = images[i].style.left.substr(0, images[i].style.left.length - 2);
    if (left <=  -86) {
      left = 532;
    }
    images[i].style.left = (left - 1) + "px";
  }
}*/
