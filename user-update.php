<html land="en">
<head>
    <meta charset="utf-8">
	<?php include_once 'includes.php'; 
			include 'connect.php';
	?>
	
	<script type="text/javascript" src="scripts\jquery.validate.js"></script>
	<script>
	$(document).ready(function() {
		$("#user-update").validate({
			rules: {
				username: {
					required: true,
					minlength: 4,
				},
				email: {
					email: true,
				},
				name: {
					required: true,
				},
				password: {
					required: true,
					minlength: 6,
					equalTo: "#verify",
				},
				
			}
		
		});
		
	});
	</script>
</head>

<body>

	<h1>Defiant Rock</h1>

	<div id="cssmenu">
		<ul>
			<li><a href='index.php'><span>Home</span></a></li>
			<li class='active'><a href='user.php'><span>Users</span></a></li>
			<li><a href='article.php'><span>Articles</span></a></li>
				<ul>
					<li><a href='#'><span>Reviews</span></a></li>
					<li><a href='#'><span>Interviews</span></a></li>
				</ul>
			<li><a href='search.php'><span>Search</span></a></li>
			<li><a href='playlist.php'><span>Playlists</span></a></li>
			<li><a href='band.php'><span>Bands</span></a></li>
				<ul>
					<li><a href='bandtag.php'><span>Band Tags</span></a></li>
					<li><a href='songtag.php'><span>Song Tags</span></a></li>
				</ul>
		</ul>
	</div>
		
	<!--- show user menu --->
	<div class="floatright">
	<?PHP
		require_once("./include/membersite_config.php");

		if(!$fgmembersite->CheckLogin()) {
			echo "<a href='register.php'>Register</a> or <a href='login.php'>Login</a>";
		}
		else{
			echo '<a href="login-home.php">'.$fgmembersite->UserFullName().'</a><br>';
			echo "<a href='logout.php'>Logout</a>";
		}
	?>
	</div>
	
<div id="outerWrapper">
						
<?php
	/*
	*update user data*
	*/
		//Execute code ONLY if connections were successful 	
		if ($dbSuccess && $fgmembersite->CheckLogin()) {
			
			if (!empty ($_POST["submit"]))
			{
				$query = 'update user set';

				// validate phone #
				$pattern = '/^\(?[2-9]\d{2}\)?[-\s]\d{3}-\d{4}$/';
				$phone = $_POST["phonenumber"];
				if (preg_match($pattern, $_POST["phonenumber"])) {
					//# is valid, remove extra characters
					$pattern = '/[\(\)\-\s]/';
					$replacement = '';
					$phone = preg_replace($pattern, $replacement, $phone);
				}
				else
				{
					$phone = "";
					echo '<script>alert("Invalid phone number");
					location.reload();
					</script>';
				}
				
				if (!empty ($_POST["username"]))
				{
					$query = $query." username = '".trim($_POST["username"])."'";
				}
				if (!empty ($_POST["name"]))
				{
					$query = $query.", name = '".trim($_POST["name"])."'";
				}
				if (!empty ($_POST["password"]))
				{
					$pass = trim($_POST["password"]);
					$pass = md5($pass);
					$query = $query.", password = '".$pass."'";
				}
				if (!empty ($_POST["city"]))
				{
					$query = $query.", city = '".trim($_POST["city"])."'";
				}
				if (!empty ($_POST["state"]))
				{
					$query = $query.", state = '".trim($_POST["state"])."'";
				}
				if (!empty ($_POST["country"]))
				{
					$query = $query.", country = '".trim($_POST["country"])."'";
				}
				if (!empty ($_POST["sex"]))
				{
					$query = $query.", sex = '".trim($_POST["sex"])."'";
				}
				if (!empty ($_POST["email"]))
				{
					$query = $query.", email = '".trim($_POST["email"])."'";
				}
				if ($phone != "")
				{
					$query = $query.", phonenumber = '".$phone."'";
				}
				if (!empty ($_POST["about"]))
				{
					$query = $query.", about = '".trim($_POST["about"])."'";
				}
				
				$query = $query.' where username = "'.$fgmembersite->getUsername().'"';
				
				$result = mysql_query($query);
				
				if (!$result)
					die('Invalid query: ' . mysql_error());
				else
					echo 'Profile updated';

			}
			else
			{	
				// user form
				echo "<div class='form'>";
					echo '<form id="user-update" action="user-update.php" name="myform" method="POST">
						<label>Username</label>
						<input name="username" required>
					
						<label>Password</label>
						<input name="password" type="password" required>
						
						<label>Verify Password</label>
						<input id="verify" name="verify" type="password" required>
						
						<label>Full Name</label>
						<input name="name" required>
					
						<input type="radio" name="sex" value="m"> Male<br>
						 <input type="radio" name="sex" value="f"> Female

						 <label>City</label>
						 <input name="city">
						 
						 <label>State</label>
						<input name="state">						
						
						 <label>Country</label>
						<input name="country">
						
						<label>Email</label>
						<input name="email" type="email" placeholder="yourname@website.com">
            
						<label>Phone #</label>
						<input name="phonenumber" type="tel">
			
						<label>About</label>
						<textarea name="about"></textarea>
            
						<input id="submit" name="submit" type="submit" value="Update">
						 
						</form>';
					
				echo "</div>";
						
			}
		
		}
	?>

	

</div>

</body>
</html>