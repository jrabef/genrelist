<html land="en">
<head>
    <meta charset="utf-8">
	<?php include_once 'includes.php';
		include 'connect.php';
	?>
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
		*list user page*
		*/
			
			//Execute code ONLY if connections were successful 	
			if ($dbSuccess) {
				
				if (isset($_GET["user"]))
				{
					if (!empty(($_GET["user"])))
					{
						$user = trim($_GET["user"]);
						
						// user bio
						$result = mysql_query('select * from user where username = "'.$user.'"');
						$row = mysql_fetch_assoc($result);
						$userid = $row["userid"];
						$name = $row["name"];
						$sex = $row["sex"];
						$city = $row["city"];
						$state = $row["state"];
						$country = $row["country"];
						$about = $row["about"];
						$joindate = $row["datecreated"];
						echo "<p id=\"mainheading\">".$user."<hr></p><br>";
						
						echo "<h2>".$name."</h2>";

						if ($sex == "m")
							echo 'Male<br>';
						else
							echo 'Female<br>';
						echo "Origin: ".$city.", ".$state.", ".$country."<br>";
						echo "Date Joined: ".$joindate."<br>";
						echo "<br>";
						echo "<h2>About</h2><p>";
						echo $about."</p><br>";
						
						// display list of bands user likes
						echo "<h2>Bands I Like</h2>";
						$bands = mysql_query('select bandid from bandrating where rating = 1 and creatorid='.$userid);
						
						if ($bands != false)
						{
							echo "<ul>";
									
							while ($row = mysql_fetch_array($bands, MYSQL_NUM)) {
								$bandname = mysql_fetch_assoc(  mysql_query("select name from band where bandid = ".$row[0])  );
								echo "<li><a href=\"band.php?band=".$bandname["name"]."\">".$bandname["name"]."</a></li>";
							}
							
							echo "</ul>";
							mysql_free_result($bands);
						}

					}
					else
					{
						echo "User not found.";
					}
				}
				else
				{	
					// get ALL users //
					echo "<div>";
					$result = mysql_query("select username from user");
					
					if ($result != false) {

							echo "<p id=\"mainheading\">User List<hr></p><p>&nbsp;</p>";

							while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
								
								echo "<li><a href=\"user.php?user=".$row[0]."\">".$row[0]."</a></li>";
						
							}	
							echo "</ul>";
							mysql_free_result($result);
						
					}
					echo "</div>";
							
				}
			
			}
		?>

		

	</div>

</body>
</html>