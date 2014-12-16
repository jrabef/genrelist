<?PHP
require_once("./include/membersite_config.php");

if(!$fgmembersite->CheckLogin())
{
    $fgmembersite->RedirectToURL("login.php");
    exit;
}

if(isset($_POST['submitted']))
{
   if($fgmembersite->ChangePassword())
   {
        $fgmembersite->RedirectToURL("changed-pwd.html");
   }
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US" lang="en-US">
<head>
      <meta http-equiv='Content-Type' content='text/html; charset=utf-8'/>
      <title>Change password</title>
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
		<form id='changepwd' action='<?php echo $fgmembersite->GetSelfScript(); ?>' method='post' accept-charset='UTF-8'>
		<fieldset >
		<legend>Change Password</legend>

		<input type='hidden' name='submitted' id='submitted' value='1'/>

		<div class='short_explanation'>* required fields</div>

		<div><span class='error'><?php echo $fgmembersite->GetErrorMessage(); ?></span></div>
		<div class='container'>
			<label for='oldpwd' >Old Password*:</label><br/>
			<div class='pwdwidgetdiv' id='oldpwddiv' ></div><br/>
			<noscript>
			<input type='password' name='oldpwd' id='oldpwd' maxlength="50" />
			</noscript>    
			<span id='changepwd_oldpwd_errorloc' class='error'></span>
		</div>

		<div class='container'>
			<label for='newpwd' >New Password*:</label><br/>
			<div class='pwdwidgetdiv' id='newpwddiv' ></div>
			<noscript>
			<input type='password' name='newpwd' id='newpwd' maxlength="50" /><br/>
			</noscript>
			<span id='changepwd_newpwd_errorloc' class='error'></span>
		</div>

		<br/><br/><br/>
		<div class='container'>
			<input type='submit' name='Submit' value='Submit' />
		</div>

		</fieldset>
		</form>
		<!-- client-side Form Validations:
		Uses the excellent form validation script from JavaScript-coder.com-->

		<script type='text/javascript'>
		// <![CDATA[
			var pwdwidget = new PasswordWidget('oldpwddiv','oldpwd');
			pwdwidget.enableGenerate = false;
			pwdwidget.enableShowStrength=false;
			pwdwidget.enableShowStrengthStr =false;
			pwdwidget.MakePWDWidget();
			
			var pwdwidget = new PasswordWidget('newpwddiv','newpwd');
			pwdwidget.MakePWDWidget();
			
			
			var frmvalidator  = new Validator("changepwd");
			frmvalidator.EnableOnPageErrorDisplay();
			frmvalidator.EnableMsgsTogether();

			frmvalidator.addValidation("oldpwd","req","Please provide your old password");
			
			frmvalidator.addValidation("newpwd","req","Please provide your new password");

		// ]]>
		</script>

		<p>
		<a href='login-home.php'>Home</a>
		</p>

		</div>
		<!--
		Form Code End (see html-form-guide.com for more info.)
		-->
	</div>
	
</body>
</html>