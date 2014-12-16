<html land="en">
<head>
	<?php include 'includes.php';
		  include 'connect.php';
	?>
    <meta charset="utf-8">
	<script type="text/javascript" src="scripts/like_message.js"></script>
	
	<script type="text/javascript" src="scripts/jquery.validate.js"></script>
	<script>
	$(document).ready(function() {
		$("table tr").not(":first").filter(":even").addClass("altrow");
		
		$("#addsong").validate({
			rules: {
				name: {
					required: true,
				},
				length: {
					required: true,
					number: true
				},
				trackno: {
					required: true,
					number: true
				},
				discno: {
					number: true
				},
				year: {
					minlength: 4,
					maxlength: 4,
					number: true
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

	<!-------------------------->
	<!--- list band details  --->
	<!-------------------------->
	<?php
		//Execute code ONLY if connections were successful 	
		if ($dbSuccess) {
			
			//search for album
			if (isset($_GET["id"]))
			{
				if (!empty ($_GET["id"]))
				{
					$albumid = $_GET["id"];
					
					echo "<div class=\"albumlist\">";
					
					$result = mysql_query("select bandid, name, year from album where albumid=".$albumid);
				
					if ($result != false) {
							while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
								$bandid = $row[0];
								$bandname = mysql_fetch_array(mysql_query("select name from band where bandid = ".$bandid))[0];
								
								$name = $row[1];
								$year = $row[2];
								
								echo "<h1>".$bandname."</h1><br />";
								
								echo "<br>&nbsp;<br><h2><span id=\"tag-heading\"><p>".$name." (".$year.")</p></span></h2>";
								
								// add songs
								$songs = mysql_query("select song.songid, name, trackno, length from albumsong, song where albumid=".$albumid." and song.songid = albumsong.songid");

								if ($songs != false) {
										while ($songrow = mysql_fetch_array($songs, MYSQL_NUM)) {
											$songid = $songrow[0];
											$name = $songrow[1];
											$num = $songrow[2];
											$lengthinsecs = $songrow[3];
											
											$mins = intval($lengthinsecs / 60);
											$secs = str_pad(intval($lengthinsecs % 60),2,"0",STR_PAD_LEFT);

											echo '<table border="1" width="500">
											<tr>
											<th>Track</th><th>Title</th><th>Length</th>
											</tr>
											<tr>
											<td width="10%">'.$num.'</td><td width="80%">  <a href="song.php?id='.$songid.'">'.$name.'</a> </td> <td>'.$mins.':'.$secs.'</td>
											</tr>
											</table>';
										}	

								}
								
							}	
							echo "</ul><br>&nbsp;<br>";
							mysql_free_result($result);
					}
					echo "</div>";
					
					// if user logged in, can add a song
					if( $fgmembersite->CheckLogin() ) {
						
						echo 'Add A Song<hr>';
						
						// add song button
						echo '<form action="album.php?id='.$_GET["id"].'" id="addsong" name="myform" method="POST">
					
						<label>Name</label>
						<input name="name" type="text" required>

						<label>Track Number</label>
						<input name="trackno">						

						<label>Disc Number</label>
						<input name="discno">	
						
						<label>Year</label>
						<input name="year">
						
						<label>Length (in seconds)</label>
						<input name="length">
			
						<input id="submit" name="addsong" type="submit" value="Add">
						 
						</form>';
					
						// Button clicked, Add a song!
						if (!empty ($_POST["addsong"]))
						{
							// create song query
							$query = 'insert into song (name, year, length, creatorid) values (';
						
							$result = mysql_query('select userid from user where username = "'.$fgmembersite->getUsername().'"');
							$row = mysql_fetch_assoc($result);
							$userid = $row["userid"];
							
							if (!empty ($_POST["name"]))
							{
								$name = mysql_escape_string($_POST["name"]);
								$query = $query.'"'.$name.'"';
							}

							if (!empty ($_POST["year"]))
							{
								$query = $query.",".trim($_POST["year"]);
							}
							else
							{
								$query = $query.",NULL";
							}
							
							if (!empty ($_POST["length"]))
							{
								$len = mysql_escape_string($_POST["length"]);
								$query = $query.','.$len;
							}
							else
							{
								$query = $query.",NULL";
							}
							
							$query = $query.','.$userid.')';
							
							$result = mysql_query( $query );
							
							// create albumsong query
							$songid = mysql_fetch_array(mysql_query ( "SELECT LAST_INSERT_ID()" ))[0];
							
							if (empty($_POST["discno"]))
								$_POST["discno"] = 1;

							$query2 = 'insert into albumsong (albumid, songid, trackno, discno, creatorid) values ( '.$albumid.','.$songid.','.$_POST["trackno"].','.$_POST["discno"].','.$userid.')';
							
							$result2 = mysql_query( $query2 );
							
							if (!$result || !$result2)
								die('Invalid query: ' . mysql_error());
							else {
								echo "<div class='alert'>Song added.</div>";
							}
							
						}	
						
					}
					
				}
				else
				{
						echo "Album not found.";
				}
				
			}
			
		}
	?>

	

</div>

</body>
</html>