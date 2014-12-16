<html land="en">
<head>
    <meta charset="utf-8">
	<?php include 'includes.php'; ?>

</head>

<body>

	<h1>Defiant Rock</h1>

	<div id="cssmenu">
		<ul>
			<li class='active'><a href='index.php'><span>Home</span></a></li>
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

	<?php if(!$fgmembersite->CheckLogin()) { ?>
		<div id='fg_membersite_content'>
		<h2>Please login to view the website content.</h2>
		<ul>
		<li><a href='register.php'>Register</a></li>
		<li><a href='confirmreg.php'>Confirm registration</a></li>
		<li><a href='login.php'>Login</a></li>
		</ul>
		</div>
	<?php 
	} 
	else
	{ ?>
		<div id='fg_membersite_content'>

			
				<span id="mainheading">Featured Playlists<hr></span>
				
				<?php
				include 'connect.php';
				
				if ($dbSuccess) {
					$lists = mysql_query("select playlistid, name, userid from playlist where featured = 1 and featureddate >= DATE(NOW()) - INTERVAL 1 MONTH");
					
					if ($lists != false)
					{
						echo "<ul>";
						
						while ($row = mysql_fetch_array($lists, MYSQL_NUM)) {
							$result = mysql_query('select username from user where userid = '.$row[2]);
							$row2 = mysql_fetch_assoc($result);
							$user = $row2["username"];

							echo "<li><a href=\"playlist.php?id=".$row[0]."\">".$row[1]."</a> by ".$user."</li>";
							mysql_free_result($result);
						}
						
						echo "</ul>";
						
						mysql_free_result($lists);
					}
				}
				
				?>
				
		</div>
	
	<?php } ?>
</div>

</body>
</html>