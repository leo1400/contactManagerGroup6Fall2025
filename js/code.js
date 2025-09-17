const urlBase = "http://contactymanager.shop/LAMPAPI/"
const phpBase = ".php"
const loginUrlBase = "http://contactymanager.shop/contact/";
function doLogin() {

	let userLogin = document.getElementById("user-login").value;
	let userPassword = document.getElementById("user-password").value;


	let userLoginInfo = { "login": userLogin, "password": userPassword }
	let jsonUserLoginInfo = JSON.stringify(userLoginInfo);

	// xmlhttprequest 
	let htr = new XMLHttpRequest();
	let url = urlBase + "Login" + phpBase;
	htr.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			const result = JSON.parse(htr.responseText);
			if (result.status == "success") {
				window.location.href = loginUrlBase + "contact" + phpBase;

			} else {
				let spanLoginResult = document.getElementById("login-result");
				spanLoginResult.innerHTML = "login failed";
			}
		}
	};
	htr.open("POST", url, true);
	htr.setRequestHeader("Content-Type", "application/json");
	htr.send(jsonUserLoginInfo);

}

function doRegistration() {

	let userLogin = document.getElementById("register-user").value;
	let userPassword = document.getElementById("register-password").value;
	let userFirstname = document.getElementById("register-fname").value;
	let userLastname = document.getElementById("register-lname").value;

	let userInfo = { "firstname": userFirstname, "lastname": userLastname, "login": userLogin, "password": userPassword };
	let jsonUserInfo = JSON.stringify(userInfo);

	let url = urlBase + "Register" + phpBase;

	let htr = new XMLHttpRequest();
	htr.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			const result = JSON.parse(htr.responseText);
			if (result.status == "success") {
				window.location.href = loginUrlBase + "contact" + phpBase;
			} else {

				let spanRegisterResult = document.getElementById("register-result");
				spanRegisterResult.innerHTML = result.message;
			}
		}
	}
	htr.open("POST", url, true);
	htr.setRequestHeader("Content-Type", "application/json");
	htr.send(jsonUserInfo);
}

//	Allow use of enter key to submit add contact input after filling out email
function checkEnterContact() {
	if(event.key === "Enter") {
		document.getElementById("add-button").click();
	}
}

function addContact() {
	let contactFirstName = document.getElementById("contact-fname").value;
	let contactLastName = document.getElementById("contact-lname").value;
	let contactPhone = document.getElementById("contact-phone").value;
	let contactEmail = document.getElementById("contact-email").value;
	
	//	Validate no empty or incorrect fields
	let emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
	let phoneRegex = /^\d{10}$/;
	
	let valid = true;
	
	//	Highlight the field and show warning next to input
	if(contactFirstName  == "") {
		incorrectField("contact-fname", "problem-contact-fname");
		valid = false;
	}
	else {
		document.getElementById("contact-fname").style = '';
		document.getElementById("problem-contact-fname").style.display = "none";
	}
	
	if(contactLastName == "") {
		incorrectField("contact-lname", "problem-contact-lname");
		valid = false;
	}
	else {
		document.getElementById("contact-lname").style = '';
		document.getElementById("problem-contact-lname").style.display = "none";
	}
	
	if (!emailRegex.test(contactEmail)) {
		incorrectField("contact-email", "problem-email");
		valid = false;
	}
	else {
		document.getElementById("contact-email").style = '';
		document.getElementById("problem-email").style.display = "none";
	}

	if (!phoneRegex.test(contactPhone)) {
		incorrectField("contact-phone", "problem-phone");
		valid = false;
	}
	else {
		document.getElementById("contact-phone").style = '';
		document.getElementById("problem-phone").style.display = "none";
	}
	
	if(!valid)
		return;

	// Add the contact via API
	let contactInfo = { "firstname": contactFirstName, "lastname": contactLastName, "phone": contactPhone, "email": contactEmail };
	let jsonContactInfo = JSON.stringify(contactInfo);

	let url = urlBase + "Add_Contact" + phpBase;
	let htr = new XMLHttpRequest();

	htr.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			const result = JSON.parse(htr.responseText);
			let spanAddContactResult = document.getElementById("add-contact-result");
			spanAddContactResult.innerHTML = result.message;
		}
	}
	htr.open("POST", url, true);
	htr.setRequestHeader("Content-Type", "application/json");
	htr.send(jsonContactInfo);
	
	//	Hide box after completion
	hideAddContact();
}

function doLogout() {
	window.location.href = loginUrlBase + "logout" + phpBase;
}

function showRegister() {
	document.getElementById("login-div").style.display = "none";
	document.getElementById("register-div").style.display = "block";
}

function showLogin() {
	document.getElementById("register-div").style.display = "none";
	document.getElementById("login-div").style.display = "block";
}

function showAddContact() {
	//	Reset all input fields to their default state
	resetAddFields();
	
	//	Gray out background
	document.getElementById("add-background").style.display = "block";

	document.getElementById("add-contact-div").style.display = "block";
}

function hideAddContact() {
	//	Reset all input fields to their default state
	resetAddFields();
	
	//	Remove grayed out background
	document.getElementById("add-background").style.display = "none";

	//	Switch back to searchbar with contact list
	document.getElementById("error-message").style.display = "none";
	document.getElementById("add-contact-div").style.display = "none";
}

function resetAddFields() {
	resetField("contact-fname", "problem-contact-fname");
	resetField("contact-lname", "problem-contact-lname");
	resetField("contact-phone", "problem-phone");
	resetField("contact-email", "problem-email");
}

function resetField(fieldId, problemId) {
	document.getElementById(fieldId).value = '';
	document.getElementById(fieldId).style = '';
	document.getElementById(problemId).style.display = "none";
}

function incorrectField(fieldId, problemId) {
	document.getElementById(fieldId).style =
	"background-color: red; box-shadow:  0 4px 12px rgba(1, 0, 0, 0);";
	
	document.getElementById(problemId).style.display = "inline";
	document.getElementById("error-message").style.display = "block";
}
