const urlBase = "http://contactymanager.shop/LAMPAPI/"
const phpBase = ".php"
const loginUrlBase = "http://contactymanager.shop/contact/";
function doLogin() {

	let userLogin = document.getElementById("userLogin").value;
	let userPassword = document.getElementById("userPassword").value;


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
				let spanLoginResult = document.getElementById("loginResult");
				spanLoginResult.innerHTML = "login failed";
			}
		}
	};
	htr.open("POST", url, true);
	htr.setRequestHeader("Content-Type", "application/json");
	htr.send(jsonUserLoginInfo);

}
function doRegistration() {

	let userLogin = document.getElementById("newUser").value;
	let userPassword = document.getElementById("newPass").value;
	let userFirstname = document.getElementById("newFirstName").value;
	let userLastname = document.getElementById("newLastName").value;

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

				let spanRegisterResult = document.getElementById("registerResult");
				spanRegisterResult.innerHTML = result.message;
			}
		}
	}
	htr.open("POST", url, true);
	htr.setRequestHeader("Content-Type", "application/json");
	htr.send(jsonUserInfo);
}
function addContact() {
	let contactFirstName = document.getElementById("newContactFirstName").value;
	let contactLastName = document.getElementById("newContactLastName").value;
	let contactPhone = document.getElementById("newContactPhone").value;
	let contactEmail = document.getElementById("newContactEmail").value;

	// we add the user id in api;
	let contactInfo = { "firstname": contactFirstName, "lastname": contactLastName, "phone": contactPhone, "email": contactEmail };
	let jsonContactInfo = JSON.stringify(contactInfo);

	let url = urlBase + "Add_Contact" + phpBase;
	let htr = new XMLHttpRequest();

	htr.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			const result = JSON.parse(htr.responseText);
			let spanAddContactResult = document.getElementById("addContactResult");
			spanAddContactResult.innerHTML = result.message;
		}
	}
	htr.open("POST", url, true);
	htr.setRequestHeader("Content-Type", "application/json");
	htr.send(jsonContactInfo);
}
function doLogout() {
	window.location.href = loginUrlBase + "logout" + phpBase;
}
function showRegister() {
	document.getElementById("loginDiv").style.display = "none";
	document.getElementById("registerDiv").style.display = "block";
}

function showLogin() {
	document.getElementById("registerDiv").style.display = "none";
	document.getElementById("loginDiv").style.display = "block";
}

