<?PHP
require_once("./include/membersite_config.php");

if(isset($_POST['submitted']))
{
   if($fgmembersite->RegisterUser())
   {
        $fgmembersite->RedirectToURL("thank-you.html");
   }
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US" lang="en-US">
<head>
    <meta http-equiv='Content-Type' content='text/html; charset=utf-8'/>
    <title>Register</title>
	 <link rel="stylesheet" type="text/css" href="style/style.css">
    <link rel="STYLESHEET" type="text/css" href="style/fg_membersite.css" />
    <script type='text/javascript' src='scripts/gen_validatorv31.js'></script>
    <link rel="STYLESHEET" type="text/css" href="style/pwdwidget.css" />
    <script src="scripts/pwdwidget.js" type="text/javascript"></script>      
</head>
<body>

	<h1>Defiant Rock</h1>

	<div id="cssmenu">
		<ul>
			<li><a href='index.php'><span>Home</span></a></li>
			<li><a href='user.php'><span>Users</span></a></li>
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

		<!-- Form Code Start -->
		<div id='fg_membersite'>
		<form id='register' action='<?php echo $fgmembersite->GetSelfScript(); ?>' method='post' accept-charset='UTF-8'>
		<fieldset >
		<legend>Register</legend>

		<input type='hidden' name='submitted' id='submitted' value='1'/>

		<div class='short_explanation'>* required fields</div>
		<input type='text'  class='spmhidip' name='<?php echo $fgmembersite->GetSpamTrapInputName(); ?>' />

		<div><span class='error'><?php echo $fgmembersite->GetErrorMessage(); ?></span></div>
		<div class='container'>
			<label for='name' >Your Full Name*: </label><br/>
			<input type='text' name='name' id='name' value='<?php echo $fgmembersite->SafeDisplay('name') ?>' maxlength="50" /><br/>
			<span id='register_name_errorloc' class='error'></span>
		</div>
		<div class='container'>
			<label for='email' >Email Address*:</label><br/>
			<input type='text' name='email' id='email' value='<?php echo $fgmembersite->SafeDisplay('email') ?>' maxlength="50" /><br/>
			<span id='register_email_errorloc' class='error'></span>
		</div>
		<div class='container'>
			<label for='username' >UserName*:</label><br/>
			<input type='text' name='username' id='username' value='<?php echo $fgmembersite->SafeDisplay('username') ?>' maxlength="50" /><br/>
			<span id='register_username_errorloc' class='error'></span>
		</div>
		<div class='container' style='height:80px;'>
			<label for='password' >Password*:</label><br/>
			<div class='pwdwidgetdiv' id='thepwddiv' ></div>
			<noscript>
			<input type='password' name='password' id='password' maxlength="50" />
			</noscript>    
			<div id='register_password_errorloc' class='error' style='clear:both'></div>
		</div>

		<div class='container'>
			<input type='submit' name='Submit' value='Submit' />
		</div>

		</fieldset>
		</form>
		<!-- client-side Form Validations:
		Uses the excellent form validation script from JavaScript-coder.com-->

		<script type='text/javascript'>
		// <![CDATA[
			var pwdwidget = new PasswordWidget('thepwddiv','password');
			pwdwidget.MakePWDWidget();
			
			var frmvalidator  = new Validator("register");
			frmvalidator.EnableOnPageErrorDisplay();
			frmvalidator.EnableMsgsTogether();
			frmvalidator.addValidation("name","req","Please provide your name");

			frmvalidator.addValidation("email","req","Please provide your email address");

			frmvalidator.addValidation("email","email","Please provide a valid email address");

			frmvalidator.addValidation("username","req","Please provide a username");
			
			frmvalidator.addValidation("password","req","Please provide a password");

		// ]]>
		</script>

		<!--
		Form Code End (see html-form-guide.com for more info.)
		-->
	</div>
	
</body>
</html>