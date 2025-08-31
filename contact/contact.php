<?php
	session_start();
	if(!isset($_SESSION['userid'])){
		header("Location: http://contactymanager.shop/");
	}
?>
<html>

<head>
	<title>contacty</title>
	<script src="../js/code.js"></script>
	<link rel="stylesheet" href="../css/styles.css">
</head>

<body>
		<p>Welcome <?php echo $_SESSION['firstname']?> you are signed in</p>
	<div id="logoutButton">
			<button class="primary" onclick="doLogout()">Logout</button>
	</div>
<div class="wrapper">

		<!-- Register Form (hidden by default) -->
		<div id="addContactDiv">
			<h2>Add Contact</h2>
			<label>First Name</label><br>
			<input type="text" id="newContactFirstName"><br><br>

			<label>Last Name</label><br>
			<input type="text" id="newContactLastName"><br><br>

			<label>Phone</label><br>
			<input type="text" id="newContactPhone"><br><br>

			<label>Password</label><br>
			<input type="email" id="newContactEmail"><br><br>

			<button class="primary" onclick="addContact()">Add contact</button>
			<span id="addContactResult"></span><br><br>

		</div>

	</div>
</body>

</html>
