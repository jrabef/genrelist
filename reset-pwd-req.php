<?PHP
require_once("./include/membersite_config.php");

$emailsent = false;
if(isset($_POST['submitted']))
{
   if($fgmembersite->EmailResetPasswordLink())
   {
        $fgmembersite->RedirectToURL("reset-pwd-link-sent.html");
        exit;
   }
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US" lang="en-US">
<head>
      <meta http-equiv='Content-Type' content='text/html; charset=utf-8'/>
      <title>Reset Password Request</title>
	  <link rel="stylesheet" type="text/css" href="style/style.css">
      <link rel="STYLESHEET" type="text/css" href="style/fg_membersite.css" />
      <script type='text/javascript' src='scripts/gen_validatorv31.js'></script>
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

		<div id='fg_membersite'>
		<form id='resetreq' action='<?php echo $fgmembersite->GetSelfScript(); ?>' method='post' accept-charset='UTF-8'>
		<fieldset >
		<legend>Reset Password</legend>

		<input type='hidden' name='submitted' id='submitted' value='1'/>

		<div class='short_explanation'>* required fields</div>

		<div><span class='error'><?php echo $fgmembersite->GetErrorMessage(); ?></span></div>
		<div class='container'>
			<label for='username' >Your Email*:</label><br/>
			<input type='text' name='email' id='email' value='<?php echo $fgmembersite->SafeDisplay('email') ?>' maxlength="50" /><br/>
			<span id='resetreq_email_errorloc' class='error'></span>
		</div>
		<div class='short_explanation'>A link to reset your password will be sent to the email address</div>
		<div class='container'>
			<input type='submit' name='Submit' value='Submit' />
		</div>

		</fieldset>
		</form>
		<!-- client-side Form Validations:
		Uses the excellent form validation script from JavaScript-coder.com-->

		<script type='text/javascript'>
		// <![CDATA[

			var frmvalidator  = new Validator("resetreq");
			frmvalidator.EnableOnPageErrorDisplay();
			frmvalidator.EnableMsgsTogether();

			frmvalidator.addValidation("email","req","Please provide the email address used to sign-up");
			frmvalidator.addValidation("email","email","Please provide the email address used to sign-up");

		// ]]>
		</script>

		</div>

	</div>
		
</body>
</html>