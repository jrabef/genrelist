<?PHP
require_once("./include/membersite_config.php");

if(isset($_POST['submitted']))
{
   if($fgmembersite->Login())
   {
        $fgmembersite->RedirectToURL("login-home.php");
   }
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US" lang="en-US">
<head>
      <meta http-equiv='Content-Type' content='text/html; charset=utf-8'/>
      <title>Login</title>
      <link rel="STYLESHEET" type="text/css" href="style/fg_membersite.css" />
	  <link rel="stylesheet" type="text/css" href="style/style.css">
      <script type='text/javascript' src='scripts/gen_validatorv31.js'></script>
</head>
<body>

	<h1 id="siteheading">Defiant Rock</h1>

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
	<br>	

<div id="outerWrapper">

	<div id='fg_membersite'>
	<form id='login' action='<?php echo $fgmembersite->GetSelfScript(); ?>' method='post' accept-charset='UTF-8'>
	<fieldset >
	<legend>Login</legend>

	<input type='hidden' name='submitted' id='submitted' value='1'/>

	<div class='short_explanation'>* required fields</div>

	<div><span class='error'><?php echo $fgmembersite->GetErrorMessage(); ?></span></div>
	<div class='container'>
		<label for='username' >UserName*:</label><br/>
		<input type='text' name='username' id='username' value='<?php echo $fgmembersite->SafeDisplay('username') ?>' maxlength="50" /><br/>
		<span id='login_username_errorloc' class='error'></span>
	</div>
	<div class='container'>
		<label for='password' >Password*:</label><br/>
		<input type='password' name='password' id='password' maxlength="50" /><br/>
		<span id='login_password_errorloc' class='error'></span>
	</div>

	<div class='container'>
		<input type='submit' name='Submit' value='Submit' />
	</div>
	<div class='short_explanation'><a href='reset-pwd-req.php'>Forgot Password?</a></div>
	</fieldset>
	</form>
	<!-- client-side Form Validations:
	Uses the excellent form validation script from JavaScript-coder.com-->

	<script type='text/javascript'>
	// <![CDATA[

		var frmvalidator  = new Validator("login");
		frmvalidator.EnableOnPageErrorDisplay();
		frmvalidator.EnableMsgsTogether();

		frmvalidator.addValidation("username","req","Please provide your username");
		
		frmvalidator.addValidation("password","req","Please provide the password");

	// ]]>
	</script>
	</div>

</div>

</body>
</html>