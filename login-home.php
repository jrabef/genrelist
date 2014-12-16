<?PHP
require_once("./include/membersite_config.php");

if(!$fgmembersite->CheckLogin())
{
    $fgmembersite->RedirectToURL("login.php");
    exit;
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US" lang="en-US">
<head>
      <meta http-equiv='Content-Type' content='text/html; charset=utf-8'/>
      <title>User Homepage</title>
	  
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

		<div id='fg_membersite_content'>
		<h2>Home Page</h2>
		Welcome back <?= $fgmembersite->UserFullName(); ?>!
		<br>
		<p><a href='user-update.php'>Update Profile</a></p>
		<p><a href='change-pwd.php'>Change password</a></p>
		<br><br>

		<?php 
		if ( $dbSuccess && $fgmembersite->CheckLogin() )
		{
			$result = mysql_query('select userid from user where username = "'.$fgmembersite->getUsername().'"');
			$row = mysql_fetch_assoc($result);
			$userid = $row["userid"];
			
			// display list of playlists
			echo 'Your Playlists<hr>';
			$lists = mysql_query("select playlistid, name from playlist where userid = " . $userid);

			echo '<form action="login-home.php" name="myform" method="POST">';
			
			if ($lists != false)
			{
				while ($row = mysql_fetch_array($lists, MYSQL_NUM)) {;
					echo "<a href=\"playlist.php?id=".$row[0]."\">".$row[1]."</a>&nbsp;";
					
					echo '<input type="hidden" name="id" value="'.$row[0].'">';
					echo '<input id="submit" name="delete" type="submit" value="Delete"><br />';		
				}
				mysql_free_result($lists);
			}
			
			echo '</form>';			
					
			// button clicked, delete playlist
			if (!empty ($_POST["delete"]))
			{
				if (!empty($_POST["id"])) {
					$id = $_POST["id"];

					$result = mysql_query('delete from playlisttag where playlistid='.$id);
					$result2 = mysql_query('delete from playlistsong where playlistid='.$id);
					$result3 = mysql_query('delete from playlist where playlistid='.$id);

						
					if (!$result)
						die('Invalid query: ' . mysql_error());
					elseif (!$result2)
						die('Invalid query: ' . mysql_error());
					elseif (!$result3)
						die('Invalid query: ' . mysql_error());
					else {
						echo "<div class='alert'>Playlist deleted.</div>";
					}
				}
			}
			
			// display add playlist form
			echo '<form action="login-home.php" name="myform" method="POST">
			
				<label><h2>Create playlist: </h2></label>
				<label>Name</label>
				<input name="name" type="text" required>

				<label>About</label>
				<textarea name="about"></textarea>
				
				<label>Private?</label>
				<input id="private" name="private" type="checkbox" value="1">

				<input id="submit" name="create" type="submit" value="Create">

				</form>';
		
			// Button clicked, Add a playlist
			if (!empty ($_POST["create"]))
			{
				if (!empty($_POST["name"])) {
					$name = trim($_POST["name"]);
					$name = mysql_escape_string($name);
					$abouttext = mysql_escape_string($_POST["about"]);
					
					$private = 0;
					if (isset($_POST["private"]))
						$private = 1;

					$result = mysql_query('insert into playlist (userid, name, postdate, private, about) values ('.$userid.', "'.$name.'", now(), '.$private.',"'.$abouttext.'")');
					
					if (!$result)
						die('Invalid query: ' . mysql_error());
					else {
						echo "<div class='alert'>Playlist created.</div>";
					}
				}
			}
			
			echo "<br><br>";

			// display song tags //
			echo 'Your Song Tags<hr>';
			echo "<div class=\"tags\">";
			$result = mysql_query("select count(*) as total from songtag where creatorid = " . $userid);

			if ($result != false)
			{
				$row = mysql_fetch_assoc($result);
				$total = $row["total"];
					
				mysql_free_result($result);
					
				$tags = mysql_query("select name, count(*) as tagcount from songtag where creatorid = ".$userid." group by name");
				
					if ($tags != false) {
						echo "<ul>";
						while ($row = mysql_fetch_array($tags, MYSQL_NUM)) {
							
							if ( ($row[1] / $total ) < .02 )
								$class = 'smallest';

							else if ( ($row[1] / $total ) < .07 )
								$class = 'smaller';

							else if ( ($row[1] / $total ) < .10 )
								$class = 'small';

							else if ( ($row[1] / $total ) < .18 )
								$class = 'medium';

							else if ( ($row[1] / $total ) < .23 )
								$class = 'large';

							else 
								$class = 'largest';
								
								echo '<li class="'.$class.'"><a href="songtag.php?tag='.$row[0].'&userid='.$userid.'">'.$row[0].'</a></li>';
					
						}	
						echo "</ul>";
					mysql_free_result($tags);
				}
			}
			echo "</div>";
		
			echo "<br>";
			
			echo 'Your Band Tags<hr>';
			echo "<div class=\"tags\">";
			$result = mysql_query("select count(*) as total from bandtag where creatorid = " . $userid);

			if ($result != false)
			{
				$row = mysql_fetch_assoc($result);
				$total = $row["total"];
					
				mysql_free_result($result);
					
				$tags = mysql_query("select name, count(*) as tagcount from bandtag where creatorid = ".$userid." group by name");
				
					if ($tags != false) {
						echo "<ul>";
						while ($row = mysql_fetch_array($tags, MYSQL_NUM)) {
							
							if ( ($row[1] / $total ) < .02 )
								$class = 'smallest';

							else if ( ($row[1] / $total ) < .07 )
								$class = 'smaller';

							else if ( ($row[1] / $total ) < .10 )
								$class = 'small';

							else if ( ($row[1] / $total ) < .18 )
								$class = 'medium';

							else if ( ($row[1] / $total ) < .23 )
								$class = 'large';

							else 
								$class = 'largest';
								
								echo '<li class="'.$class.'"><a href="bandtag.php?tag='.$row[0].'&userid='.$userid.'">'.$row[0].'</a></li>';
					
						}	
						echo "</ul>";
					mysql_free_result($tags);
				}
			}
			echo "</div>";
		
			echo "<br>";
			
			// display list of bands user likes
			echo 'Your Liked Bands<hr>';
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

			echo "<br>";
			
			// display list of bands user dislikes
			echo 'Your Disliked Bands<hr>';
			$bands = mysql_query('select bandid from bandrating where rating = 0 and creatorid='.$userid);
			
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

			echo "<br>";
			
			// display list of songs user likes
			echo 'Your Liked Songs<hr>';
			$songs = mysql_query('select songid from songrating where rating = 1 and creatorid='.$userid);
			
			if ($songs != false)
			{
				echo "<ul>";
						
				while ($row = mysql_fetch_array($songs, MYSQL_NUM)) {
					$songname = mysql_fetch_assoc(  mysql_query("select name from song where songid = ".$row[0])  );
					echo "<li><a href=\"song.php?id=".$row[0]."\">".$songname["name"]."</a></li>";
				}
				
				echo "</ul>";
				mysql_free_result($songs);
			}	

			echo "<br>";
			
			// display list of songs user dislikes
			echo 'Your Disliked Songs<hr>';
			$songs = mysql_query('select songid from songrating where rating = 0 and creatorid='.$userid);
			
			if ($songs != false)
			{
				echo "<ul>";
						
				while ($row = mysql_fetch_array($songs, MYSQL_NUM)) {
					$songname = mysql_fetch_assoc(  mysql_query("select name from song where songid = ".$row[0])  );
					echo "<li><a href=\"song.php?id=".$row[0]."\">".$songname["name"]."</a></li>";
				}
				
				echo "</ul>";
				mysql_free_result($songs);
			}				

		}
		?>


		</div>

	</div>
		
</body>
</html>
