const appOrigin = window.location.origin;
const urlBase = `${appOrigin}/LAMPAPI/`;
const phpBase = ".php";
const loginUrlBase = `${appOrigin}/contact/`;
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

	userLogin = userLogin.trim();
	userPassword = userPassword.trim();
	userFirstname = userFirstname.trim();
	userLastname = userLastname.trim();

	const registerResult = document.getElementById("register-result");
	if (registerResult) {
		registerResult.innerHTML = "";
	}

	const passwordPattern = /^[A-Za-z]{8,}$/;
	if (!passwordPattern.test(userPassword)) {
		if (registerResult) {
			registerResult.innerHTML = "Password must be at least 8 letters (A-Z only).";
		}
		return;
	}

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
				if (registerResult) {
					registerResult.innerHTML = result.message;
				}
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

function submitContact() {
	const submitBtn = document.getElementById("add-button");
	const mode = submitBtn ? submitBtn.dataset.mode : "add";

	let contactFirstName = document.getElementById("contact-fname").value;
	let contactLastName = document.getElementById("contact-lname").value;
	let contactPhone = document.getElementById("contact-phone").value;
	let contactEmail = document.getElementById("contact-email").value;

	const errorBanner = document.getElementById("error-message");
	if (errorBanner) {
		errorBanner.style.display = "none";
	}
	
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
		const fnameField = document.getElementById("contact-fname");
		if (fnameField) {
			fnameField.classList.remove("input-error");
		}
		const fnameIcon = document.getElementById("problem-contact-fname");
		if (fnameIcon) {
			fnameIcon.style.display = "none";
		}
	}
	
	if(contactLastName == "") {
		incorrectField("contact-lname", "problem-contact-lname");
		valid = false;
	}
	else {
		const lnameField = document.getElementById("contact-lname");
		if (lnameField) {
			lnameField.classList.remove("input-error");
		}
		const lnameIcon = document.getElementById("problem-contact-lname");
		if (lnameIcon) {
			lnameIcon.style.display = "none";
		}
	}
	
	if (!emailRegex.test(contactEmail)) {
		incorrectField("contact-email", "problem-email");
		valid = false;
	}
	else {
		const emailField = document.getElementById("contact-email");
		if (emailField) {
			emailField.classList.remove("input-error");
		}
		const emailIcon = document.getElementById("problem-email");
		if (emailIcon) {
			emailIcon.style.display = "none";
		}
	}

	if (!phoneRegex.test(contactPhone)) {
		incorrectField("contact-phone", "problem-phone");
		valid = false;
	}
	else {
		const phoneField = document.getElementById("contact-phone");
		if (phoneField) {
			phoneField.classList.remove("input-error");
		}
		const phoneIcon = document.getElementById("problem-phone");
		if (phoneIcon) {
			phoneIcon.style.display = "none";
		}
	}
	
	if(!valid)
		return;

	let contactInfo = { "firstname": contactFirstName, "lastname": contactLastName, "phone": contactPhone, "email": contactEmail };
	let endpoint = "Add_Contact";

	if (mode === "edit" && currentEditContactId !== null) {
		endpoint = "Update_Contact";
		contactInfo.id = currentEditContactId;
	}

	const requestBody = JSON.stringify(contactInfo);
	const url = urlBase + endpoint + phpBase;

	fetch(url, {
		method: "POST",
		headers: { "Content-Type": "application/json" },
		credentials: "include",
		body: requestBody
	})
	.then(function(response) {
		return response.text();
	})
	.then(function(body) {
		let result = null;
		try {
			result = JSON.parse(body);
		} catch (error) {
			throw new Error("Invalid JSON response");
		}

		let spanAddContactResult = document.getElementById("add-contact-result");
		if (spanAddContactResult) {
			spanAddContactResult.innerHTML = result && result.message ? result.message : "";
		}

		if (result && result.status === "success") {
			hideAddContact();
			searchContacts(lastSearchTerm);
		} else if (result && result.status === "failure") {
			updateSearchFeedback(result.message || "Unable to save contact.", true);
		}
	})
	.catch(function(error) {
		updateSearchFeedback("Unable to save contact. Please try again.", true);
		console.error(error);
	});
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

function showAddContact(prefillContact = null) {
	if (prefillContact) {
		setModalMode("edit", prefillContact);
	} else {
		setModalMode("add");
	}

	const backdrop = document.getElementById("add-background");
	const modal = document.getElementById("add-contact-div");
	if (backdrop) {
		backdrop.style.display = "flex";
	}
	if (modal) {
		modal.style.display = "block";
	}
}

function hideAddContact() {
	setModalMode("add");

	const backdrop = document.getElementById("add-background");
	const modal = document.getElementById("add-contact-div");
	if (backdrop) {
		backdrop.style.display = "none";
	}
	if (modal) {
		modal.style.display = "none";
	}
}

function setModalMode(mode, contact) {
	const modal = document.getElementById("add-contact-div");
	const heading = modal ? modal.querySelector("h2") : null;
	const submitBtn = document.getElementById("add-button");
	const errorMessage = document.getElementById("error-message");
	const resultSpan = document.getElementById("add-contact-result");

	if (resultSpan) {
		resultSpan.innerHTML = "";
	}
	if (errorMessage) {
		errorMessage.style.display = "none";
	}

	clearValidationIndicators();

	if (!submitBtn) {
		return;
	}

	if (mode === "edit" && contact) {
		currentEditContactId = contact.id;
		if (heading) {
			heading.textContent = "Edit Contact";
		}
		submitBtn.textContent = "Save changes";
		submitBtn.dataset.mode = "edit";

		populateContactForm(contact);
	} else {
		currentEditContactId = null;
		if (heading) {
			heading.textContent = "Add Contact";
		}
		submitBtn.textContent = "Add contact";
		submitBtn.dataset.mode = "add";
		resetAddFields();
	}
}

function resetAddFields() {
	clearValidationIndicators();
	resetField("contact-fname", "problem-contact-fname");
	resetField("contact-lname", "problem-contact-lname");
	resetField("contact-phone", "problem-phone");
	resetField("contact-email", "problem-email");
}

function resetField(fieldId, problemId) {
	const field = document.getElementById(fieldId);
	if (field) {
		field.value = '';
		field.classList.remove("input-error");
	}
	const indicator = document.getElementById(problemId);
	if (indicator) {
		indicator.style.display = "none";
	}
}

function incorrectField(fieldId, problemId) {
	const field = document.getElementById(fieldId);
	if (field) {
		field.classList.add("input-error");
	}

	const indicator = document.getElementById(problemId);
	if (indicator) {
		indicator.style.display = "inline";
	}

	const errorMessage = document.getElementById("error-message");
	if (errorMessage) {
		errorMessage.style.display = "block";
	}
}

function clearValidationIndicators() {
	const problemIds = [
		"problem-contact-fname",
		"problem-contact-lname",
		"problem-phone",
		"problem-email"
	];
	const fieldIds = [
		"contact-fname",
		"contact-lname",
		"contact-phone",
		"contact-email"
	];

	problemIds.forEach(function(id) {
		const element = document.getElementById(id);
		if (element) {
			element.style.display = "none";
		}
	});

	fieldIds.forEach(function(id) {
		const field = document.getElementById(id);
		if (field) {
			field.classList.remove("input-error");
		}
	});

	const errorMessage = document.getElementById("error-message");
	if (errorMessage) {
		errorMessage.style.display = "none";
	}
}

function populateContactForm(contact) {
	const firstField = document.getElementById("contact-fname");
	const lastField = document.getElementById("contact-lname");
	const phoneField = document.getElementById("contact-phone");
	const emailField = document.getElementById("contact-email");

	if (firstField) {
		const value = contact && contact.firstname ? contact.firstname : '';
		firstField.value = value;
	}
	if (lastField) {
		const value = contact && contact.lastname ? contact.lastname : '';
		lastField.value = value;
	}
	if (phoneField) {
		const value = contact && contact.phone ? String(contact.phone) : '';
		phoneField.value = value;
	}
	if (emailField) {
		const value = contact && contact.email ? contact.email : '';
		emailField.value = value;
	}
}

let searchTimeout = null;
let currentEditContactId = null;
let lastSearchTerm = "";

function updateSearchFeedback(message, isError) {
	const feedbackElement = document.getElementById("search-feedback");
	if (!feedbackElement) {
		return;
	}
	const hasMessage = message && message.trim().length > 0;
	feedbackElement.textContent = hasMessage ? message : "";
	feedbackElement.style.display = hasMessage ? "block" : "none";
	if (isError) {
		feedbackElement.classList.add("search-feedback-error");
	} else {
		feedbackElement.classList.remove("search-feedback-error");
	}
}

function renderContacts(contacts) {
	const listElement = document.getElementById("contacts-list");
	if (!listElement) {
		return;
	}
	listElement.innerHTML = "";

	if (!Array.isArray(contacts) || contacts.length === 0) {
		return;
	}

	contacts.forEach(function(contact) {
		const card = document.createElement("div");
		card.className = "contact-card";
		if (contact && typeof contact.id !== "undefined") {
			card.dataset.contactId = contact.id;
		}
		card.dataset.contact = JSON.stringify(contact);

		const header = document.createElement("div");
		header.className = "contact-card-header";

		const nameElement = document.createElement("p");
		nameElement.className = "contact-name";
		nameElement.textContent = formatContactName(contact);

		const actionsElement = document.createElement("div");
		actionsElement.className = "contact-card-actions";

		const editButton = document.createElement("button");
		editButton.type = "button";
		editButton.className = "contact-action-btn contact-action-edit";
		editButton.dataset.action = "edit";
		editButton.textContent = "Edit";

		const deleteButton = document.createElement("button");
		deleteButton.type = "button";
		deleteButton.className = "contact-action-btn contact-action-delete";
		deleteButton.dataset.action = "delete";
		deleteButton.textContent = "Delete";

		actionsElement.appendChild(editButton);
		actionsElement.appendChild(deleteButton);

		header.appendChild(nameElement);
		header.appendChild(actionsElement);

		const detailsElement = document.createElement("div");
		detailsElement.className = "contact-details";

		const phoneElement = document.createElement("span");
		phoneElement.className = "contact-phone";
		const formattedPhone = formatPhone(contact && contact.phone ? String(contact.phone) : "");
		phoneElement.textContent = formattedPhone ? `Phone: ${formattedPhone}` : "Phone: not provided";

		const emailElement = document.createElement("span");
		emailElement.className = "contact-email";
		emailElement.textContent = contact && contact.email ? `Email: ${contact.email}` : "Email: not provided";

		detailsElement.appendChild(phoneElement);
		detailsElement.appendChild(emailElement);

		card.appendChild(header);
		card.appendChild(detailsElement);

		listElement.appendChild(card);
	});
}

function formatPhone(raw) {
	const digits = raw.replace(/\D/g, "");
	if (digits.length === 10) {
		const area = digits.slice(0, 3);
		const prefix = digits.slice(3, 6);
		const line = digits.slice(6);
		return `(${area}) ${prefix}-${line}`;
	}
	if (digits.length === 11 && digits.startsWith("1")) {
		const area = digits.slice(1, 4);
		const prefix = digits.slice(4, 7);
		const line = digits.slice(7);
		return `+1 (${area}) ${prefix}-${line}`;
	}
	if (!digits) {
		return "";
	}
	return raw;
}

function formatContactName(contact) {
	const first = contact && contact.firstname ? contact.firstname.trim() : "";
	const last = contact && contact.lastname ? contact.lastname.trim() : "";
	const name = (first + " " + last).trim();
	if (name.length > 0) {
		return name;
	}
	if (contact && contact.email) {
		return contact.email;
	}
	return "Unnamed contact";
}

function searchContacts(rawTerm) {
	const term = rawTerm ? rawTerm.trim() : "";
	lastSearchTerm = term;
	updateSearchFeedback("Searching...", false);

	const payload = JSON.stringify({ search: term });
	const url = urlBase + "Search_Contacts" + phpBase;

	fetch(url, {
		method: "POST",
		headers: { "Content-Type": "application/json" },
		credentials: "include",
		body: payload
	})
	.then(function(response) {
		return response.text();
	})
	.then(function(body) {
		let data = null;
		try {
			data = JSON.parse(body);
		} catch (error) {
			throw new Error("Invalid JSON response");
		}

		if (Array.isArray(data)) {
			renderContacts(data);
			if (data.length === 0) {
				updateSearchFeedback("No contacts found.", false);
			} else {
				updateSearchFeedback("", false);
			}
			return;
		}

		if (data && data.status === "failure") {
			renderContacts([]);
			const message = data.message || "No contacts found.";
			const messageLower = message.toLowerCase();
			const isError = messageLower.indexOf("not logged in") !== -1 || messageLower.indexOf("error") !== -1;
			updateSearchFeedback(message, isError);
			return;
		}

		renderContacts([]);
		updateSearchFeedback("Unexpected response from server.", true);
	})
	.catch(function(error) {
		renderContacts([]);
		updateSearchFeedback("Unable to load contacts. Please try again.", true);
		console.error(error);
	});
}

function handleContactActionClick(event) {
	const actionButton = event.target.closest(".contact-action-btn");
	if (!actionButton) {
		return;
	}

	const card = actionButton.closest(".contact-card");
	if (!card || !card.dataset.contact) {
		return;
	}

	let contact = null;
	try {
		contact = JSON.parse(card.dataset.contact);
	} catch (error) {
		console.error("Unable to read contact data", error);
		return;
	}

	const action = actionButton.dataset.action;
	if (action === "edit") {
		startEditContact(contact);
	} else if (action === "delete") {
		deleteContact(contact);
	}
}

function startEditContact(contact) {
	if (!contact) {
		return;
	}
	showAddContact(contact);
}

function deleteContact(contact) {
	if (!contact || typeof contact.id === "undefined") {
		return;
	}

	const displayName = formatContactName(contact);
	const confirmed = window.confirm(`Delete ${displayName}? This cannot be undone.`);
	if (!confirmed) {
		return;
	}

	fetch(urlBase + "Delete_Contact" + phpBase, {
		method: "POST",
		headers: { "Content-Type": "application/json" },
		credentials: "include",
		body: JSON.stringify({ id: contact.id })
	})
	.then(function(response) {
		return response.text();
	})
	.then(function(body) {
		let result = null;
		try {
			result = JSON.parse(body);
		} catch (error) {
			throw new Error("Invalid JSON response");
		}

		if (result && result.status === "success") {
			searchContacts(lastSearchTerm);
		} else if (result && result.status === "failure") {
			updateSearchFeedback(result.message || "Unable to delete contact.", true);
		}
	})
	.catch(function(error) {
		updateSearchFeedback("Unable to delete contact. Please try again.", true);
		console.error(error);
	});
}

document.addEventListener("DOMContentLoaded", function() {
	setModalMode("add");

	const contactsList = document.getElementById("contacts-list");
	if (contactsList) {
		contactsList.addEventListener("click", handleContactActionClick);
	}

	const searchInput = document.getElementById("searchbar");
	const searchForm = document.querySelector(".contact-form");
	if (!searchInput || !searchForm) {
		return;
	}

	searchForm.addEventListener("submit", function(event) {
		event.preventDefault();
		searchContacts(searchInput.value);
	});

	searchInput.addEventListener("input", function() {
		if (searchTimeout) {
			clearTimeout(searchTimeout);
		}
		searchTimeout = setTimeout(function() {
			searchContacts(searchInput.value);
		}, 250);
	});

	searchContacts(searchInput.value);
});

