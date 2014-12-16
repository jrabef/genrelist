<html land="en">
<head>
    <meta charset="utf-8">

	<?php include 'includes.php';
		  include 'connect.php';
	?>
</head>

<body>

	<h1>Defiant Rock</h1>

	<div id="cssmenu">
		<ul>
			<li><a href='index.php'><span>Home</span></a></li>
			<li><a href='user.php'><span>Users</span></a></li>
			<li class='active'><a href='article.php'><span>Articles</span></a></li>
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
	*list article data by id*
	*/
		//Execute code ONLY if connections were successful 	
		if ($dbSuccess) {
			
			if (isset($_GET["id"]))
			{
				if (!empty ($_GET["id"]))
				{
					$id = $_GET["id"];
					
					// article with specific id
					$result = mysql_query("select * from article where articleid = ".$id);
					$row = mysql_fetch_assoc($result);
					
					echo "<p id=\"mainheading\">".$row["heading"]."<hr></p><br>";
					echo "Posted ".$row["postdate"]."<br>";
					
					$user = mysql_fetch_assoc( mysql_query("select username from user where userid = ".$row["creatorid"] ) );
					
					echo "by ".$user["username"]."<br>&nbsp;<br>";
					echo $row["body"];
				}
				else
				{
					echo "Article not found.";
				}
			}
			else
			{	
				// get ALL articles //
				echo "<div class=\"articlelist\">";
				$result = mysql_query("select * from article");
				
				if ($result != false) {

						echo "<p id=\"mainheading\">Articles<hr></p><p>&nbsp;</p>";

						while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
							
							echo "<li><a href=\"article.php?id=".$row[0]."\">".$row[1]."</a></li>";
					
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