<!DOCTYPE html>
<html>
	<?php
		session_start();
		if(!isset($_SESSION['userid'])){
			header("Location: http://contactymanager.shop/");
		}
	?>
	<head>
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width" />
		<title>contacty</title>
		<script src="js/code.js"></script>
		<link rel="stylesheet" href="css/styles.css">
		<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
	</head>
	<body>
		<p id="welcome">Welcome, <?php echo $_SESSION['firstname']?>, you are signed in</p>
		
		<div class="wrapper">
			<div id="contact-div">
				<!-- Search contacts -->
				<div style="text-align: center; color: #FCF9EC" id="search-contact-div">
					<h2 style="margin-bottom: 5px">Search Contact</h2>
					<form class="contact-form">
						<label for="searchbar" style="display: none">Search</label>
						<input type="text" id="searchbar" placeholder="eg. 'RickL'">
					</form>
				</div>
			
				<!-- Display contact -->
				<div id="display-contact-div">
					<br>
					<p style="display: none">
					<i title="Update contact" class="material-icons" id="edit-icon">edit</i>
					<i title="Delete contact" class="material-icons" id="trash-icon">delete_forever</i>
					</p>
					<i title="Add new contact" onclick="showAddContact()" class="material-icons" id="add-icon">add</i>
				</div>
			</div>
			
			<!-- Add contact -->
			<div id="add-background">
				<div style="display: none" class="menu-div" id="add-contact-div">
					<i title="Close menu" onclick="hideAddContact()" class="material-icons" id="cancel-icon">cancel</i><br>

					<h2>Add Contact</h2>
					
					<label for="contact-fname">Contact First Name</label><br>
					<input type="text" class="add-element" id="contact-fname" onkeyup="checkEnterContact()"><i class="material-icons problem-icons" id="problem-contact-fname">report_problem</i><br><br>

					<label for="contact-lname">Contact Last Name</label><br>
					<input type="text" class="add-element" id="contact-lname" onkeyup="checkEnterContact()"><i class="material-icons problem-icons" id="problem-contact-lname">report_problem</i><br><br>

					<label for="contact-phone">Contact Phone</label><br>
					<input type="text" class="add-element" id="contact-phone" onkeyup="checkEnterContact()"><i class="material-icons problem-icons" id="problem-phone">report_problem</i><br><br>

					<label for="new-email">Contact Email</label><br>
					<input type="email" class="add-element" id="contact-email" onkeyup="checkEnterContact()"><i class="material-icons problem-icons" id="problem-email">report_problem</i><br><br>
					
					<p class="add-element" id="error-message">Enter a valid contact!</p>

					<button class="primary add-element" id="add-button" onclick="addContact()">Add contact</button>
					<span id="add-contact-result"></span><br><br>
				</div>
			</div>
			<div id="logout-button">
				<button style="margin-bottom: 180px" class="primary" onclick="doLogout()">Logout</button>
			</div>
		</div>
	</body>
</html>
