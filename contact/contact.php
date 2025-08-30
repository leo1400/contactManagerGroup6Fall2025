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
			<button onclick="doLogout()">Logout</button>
	</div>

</body>

</html>
