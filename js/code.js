const urlBase = "http://localhost:8080/LAMPAPI/"
const phpBase = ".php"
const loginUrlBase = "http://localhost:8080/contact/";
function doLogin() {

	let userLogin = document.getElementById("userLogin").value;
	let userPassword = document.getElementById("userPassword").value;


	let userLoginInfo = { "login": userLogin, "password": userPassword }
	let jsonUserLoginInfo = JSON.stringify(userLoginInfo);

	// how do i send this to my login.api
	// xmlhttprequest 
	let htr = new XMLHttpRequest();
	let url = urlBase + "Login" + phpBase;
	htr.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			console.log(JSON.parse(htr.responseText));
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
function doLogout() {
	window.location.href = window.location.href = loginUrlBase + "logout" + phpBase;
}
