<?php
session_start();
if (!isset($_SESSION['userid'])) {
    header('Location: http://contactymanager.shop/');
    exit();
}

$firstName = isset($_SESSION['firstname']) ? $_SESSION['firstname'] : '';
$lastName = isset($_SESSION['lastname']) ? $_SESSION['lastname'] : '';

$firstInitial = $firstName !== '' ? strtoupper(substr($firstName, 0, 1)) : '';
$lastInitial = $lastName !== '' ? strtoupper(substr($lastName, 0, 1)) : '';
$initials = $firstInitial . $lastInitial;
if ($initials === '') {
    $initials = 'SM';
}

$displayName = trim($firstName . ' ' . $lastName);
if ($displayName === '') {
    $displayName = 'Mate';
}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width" />
		<title>Seamates</title>
		<script src="../js/code.js"></script>
		<link rel="stylesheet" href="../css/styles.css">
		<link rel="icon" type="image/svg+xml" href="../img/seamates-fish.svg">
		<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap">
		<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
	</head>
	<body class="app-body">
		<div class="ocean-backdrop" aria-hidden="true">
			<span class="bubble bubble-1"></span>
			<span class="bubble bubble-2"></span>
			<span class="bubble bubble-3"></span>
			<span class="bubble bubble-4"></span>
			<span class="bubble bubble-5"></span>
		</div>

		<main class="app-shell">
			<header class="app-header">
				<div class="app-brand">
					<div class="logo-badge">SM</div>
					<div class="brand-copy">
						<h1>Seamates</h1>
						<p>Keep your pod connected.</p>
					</div>
				</div>
				<div class="app-user">
					<div class="user-avatar" aria-hidden="true"><?php echo htmlspecialchars($initials); ?></div>
					<div class="user-meta">
						<span class="user-greeting">Welcome back, <?php echo htmlspecialchars($displayName); ?>.</span>
						<button class="secondary ghost" type="button" onclick="doLogout()">Sign out</button>
					</div>
				</div>
			</header>

			<section class="app-content">
				<section class="panel search-panel">
					<header class="panel-header">
						<h2>Find your mates</h2>
						<p>Search across your Seamates reef to surface any connection instantly.</p>
					</header>
					<form class="contact-form search-form">
						<label class="sr-only" for="searchbar">Search contacts</label>
						<div class="search-input">
							<span class="material-icons" aria-hidden="true">search</span>
							<input type="text" id="searchbar" placeholder="Search your mates" autocomplete="off">
						</div>
					</form>
					<div id="search-feedback" class="search-feedback"></div>
					<div class="panel-toolbar">
						<div class="toolbar-copy">
							<h3>Add a new mate</h3>
							<p>Log someone new so they stay in the pod.</p>
						</div>
						<button type="button" class="primary halo" onclick="showAddContact()">
							<span class="material-icons" aria-hidden="true">add</span>
							<span>Add contact</span>
						</button>
					</div>
				</section>

				<section class="panel contacts-panel">
					<header class="panel-header">
						<h2>Your pod</h2>
						<p>Everything you need to keep each relationship afloat.</p>
					</header>
					<div id="display-contact-div" class="contacts-wrapper">
						<div id="contacts-list" class="contacts-list"></div>
					</div>
				</section>
			</section>
		</main>

		<div id="add-background" class="modal-backdrop">
			<div class="modal-card" id="add-contact-div" style="display:none;">
				<button class="modal-close" type="button" id="cancel-icon" onclick="hideAddContact()">
					<span class="material-icons" aria-hidden="true">close</span>
					<span class="sr-only">Close add contact modal</span>
				</button>
				<h2>Add Contact</h2>
				<p class="modal-subtitle">Keep your pod thriving with up-to-date details.</p>

				<div class="modal-body">
					<label class="field-label" for="contact-fname">First name<span class="required-indicator" aria-hidden="true">*</span><span class="sr-only"> required</span></label>
					<div class="modal-field">
						<input type="text" class="auth-input" id="contact-fname" onkeyup="checkEnterContact()" autocomplete="given-name">
						<i class="material-icons problem-icons" id="problem-contact-fname">report_problem</i>
					</div>

					<label class="field-label" for="contact-lname">Last name<span class="required-indicator" aria-hidden="true">*</span><span class="sr-only"> required</span></label>
					<div class="modal-field">
						<input type="text" class="auth-input" id="contact-lname" onkeyup="checkEnterContact()" autocomplete="family-name">
						<i class="material-icons problem-icons" id="problem-contact-lname">report_problem</i>
					</div>

					<label class="field-label" for="contact-phone">Phone<span class="required-indicator" aria-hidden="true">*</span><span class="sr-only"> required</span></label>
					<div class="modal-field">
						<input type="text" class="auth-input" id="contact-phone" onkeyup="checkEnterContact()" inputmode="numeric" placeholder="10 digits">
						<i class="material-icons problem-icons" id="problem-phone">report_problem</i>
					</div>

					<label class="field-label" for="contact-email">Email<span class="required-indicator" aria-hidden="true">*</span><span class="sr-only"> required</span></label>
					<div class="modal-field">
						<input type="email" class="auth-input" id="contact-email" onkeyup="checkEnterContact()" autocomplete="email">
						<i class="material-icons problem-icons" id="problem-email">report_problem</i>
					</div>

					<p id="error-message" class="modal-error">Please fill out every field with valid Seamates details.</p>
				</div>

				<div class="modal-footer">
					<button class="primary" id="add-button" type="button" onclick="submitContact()">Add contact</button>
					<span id="add-contact-result" class="auth-feedback"></span>
				</div>
			</div>
		</div>
	</body>
</html>
