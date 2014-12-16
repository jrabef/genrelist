<html land="en">
<head>
	<?php include 'includes.php';
		  include 'connect.php';
	?>
    <meta charset="utf-8">
	<script type="text/javascript" src="scripts/jquery.validate.js"></script>
	<script type="text/javascript" src="scripts/like_message.js"></script>
	<script>
	$(document).ready(function() {
		$("table tr").not(":first").filter(":even").addClass("altrow");
	
		$("#addband").validate({
			rules: {
				name: {
					required: true,
				},
				url: {
					required: true,
				},
				startyear: {
					minlength: 4,
					maxlength: 4,
				},
				endyear: {
					minlength: 4,
					maxlength: 4,
				},
			}
		
		});
		
		$("#addalbum").validate({
			rules: {
				name: {
					required: true,
				},
				year: {
					minlength: 4,
					maxlength: 4,
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
			<li class='active'><a href='band.php'><span>Bands</span></a></li>
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
			
			//search for band
			if (isset($_GET["band"]))
			{
				if (!empty ($_GET["band"]))
				{
					$band = $_GET["band"];
					
					$result = mysql_query("select * from band where name = '" . $band . "'");
					if ($result != false) {

						// display bio //
						$row = mysql_fetch_assoc($result);
						$band = $row["name"];
						$bandid = $row["bandid"];
						$url = $row["url"];
						$city = $row["city"];
						$state = $row["state"];
						$country = $row["country"];
						$label = $row["label"];
						$startyear = $row["startyear"];
						if ($row["endyear"] = "0000")
						{
							$endyear = "Present";
						}
						else
						{
							$endyear = $row["endyear"];
						}
						$about = $row["about"];
						$creatorid = $row["creatorid"];
						$datecreated = $row["datecreated"];
						
						echo "<p id=\"mainheading\">".$band." (".$startyear." - ".$endyear.")";
					}

					// find username of creator
					$user_qry = mysql_query('select username from user where userid = '.$creatorid);
					$username = mysql_fetch_array($user_qry)[0];
					
					// find rating
					$ratingquery = mysql_query("select rating from bandrating where bandid = " . $bandid);
					
					if ($ratingquery != false) {
						$dislikes = 0;
						$likes = 0;
						while ($ratingdata = mysql_fetch_array($ratingquery, MYSQL_NUM)) {
							if ($ratingdata[0] == 0)
								$dislikes = $dislikes + 1;
							if ($ratingdata[0] == 1)
								$likes = $likes + 1;
						}
					}
			
					// **** SIDEBAR ****
					echo "<div id=\"sidebar\">";
					echo "<p><h2>Creator:</h2> ".$username."</p><br />";
					echo "<p><h2>Created:</h2> ".$datecreated."</p><br />";
					echo "</div>";
					// **** END SIDEBAR ****

					if ($fgmembersite->CheckLogin() ) {
						$user_qry = mysql_query('select userid from user where username = "'.$fgmembersite->getUsername().'"');
						$userid = mysql_fetch_assoc($user_qry);
						$userid = $userid["userid"];
						
						$rating_sql=mysql_query("select rating from bandrating where bandid=".$bandid." and creatorid=".$userid);
						$count=mysql_num_rows($rating_sql);

						echo "<div class='voting_wrapper' id='".$bandid."'>";
						
						// if they already voted, disable one of the buttons
						if($count > 0)
						{
							$rating_data = mysql_fetch_array($rating_sql);
							$rating = $rating_data[0];
							// if rating == 0, disable dislike button
							if ($rating == 0) {
								// show like button
								echo "<span class='up_button'><img src='vote_up.png'></span><span class='up_votes'>".$likes."</span>";
								echo "&nbsp;";
								// show dislike button
								echo "<span class='down_button'><img src='vote_down_orange.png'></span><span class='down_votes'>".$dislikes."</span>";
							}
							// if rating == 1, disable like button
							else {
								// show like button
								echo "<span class='up_button'><img src='vote_up_orange.png'></span><span class='up_votes'>".$likes."</span>";
								echo "&nbsp;";
								// show dislike button
								echo "<span class='down_button'><img src='vote_down.png'></span><span class='down_votes'>".$dislikes."</span>";
							}
						}
						// else they haven't voted, enable both buttons
						else
						{
							// show like button
							echo "<span class='voting_btn'>";
							echo "<span class='up_button'><img src='vote_up.png'></span><span class='up_votes'>".$likes."</span>";
							echo "</span>&nbsp;";
						
							// show dislike button
							echo "<span class='voting_btn'>";
							echo "<span class='down_button'><img src='vote_down.png'></span><span class='down_votes'>".$dislikes."</span>";
							echo "</span>";
						}

						echo "</div>";

					}
					
					
					echo "<hr></p><p>&nbsp;</p>";
					
					// display basic band information
					echo "<span class=\"band-infobar\">";
					echo "Label: ".$label."<br>";
					echo "Origin: ".$city.", ".$state.", ".$country."<br>";
					echo "</span><p>";
					echo "<a href='".$url."'>External Link</a><br>&nbsp;<br>";
					echo "<h1>About</h1><p>";
					echo $about."</p><br>";
					
					// display band tags
					echo "<div class=\"tags\">";
					$result = mysql_query("select count(*) as total from bandtag where bandid = " . $bandid);
						
					if ($result != false) {
							$row = mysql_fetch_assoc($result);
							$total = $row["total"];
							
							echo "<h1><span id=\"tag-heading\"><p>Tags</p></span></h1>";
								
							mysql_free_result($result);
								
							$tags = mysql_query("select name, count(*) as tagcount from bandtag where bandid = ".$bandid." group by name");
							
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
										
										echo '<li class="'.$class.'"><a href="bandtag.php?tag='.$row[0].'">'.$row[0].'</a></li>';
							
								}	
								echo "</ul>";
								mysql_free_result($tags);
							}
					}					
					echo "</div>";
					
					// if user logged in, can add a tag
					if( $fgmembersite->CheckLogin() ) {
					
						// display add tag form
						echo '<form action="band.php?band='.$_GET["band"].'" name="myform" method="POST">
						
							<label>Add tag: </label>
							<input name="tag" type="text" required>
				
							<input id="submit" name="submit" type="submit" value="Add">
							 
							</form>';
					
						// Button clicked, Add a tag
						if (!empty ($_POST["submit"]))
						{
							if (!empty($_POST["tag"])) {
								$result = mysql_query('select userid from user where username = "'.$fgmembersite->getUsername().'"');
								$row = mysql_fetch_assoc($result);
								$userid = $row["userid"];
								$tag = trim($_POST["tag"]);
								$tag = mysql_escape_string($tag);
								$result = mysql_query('insert into bandtag (bandid, name, creatorid) values ('.$bandid.',"'.$tag.'",'.$userid.')');
									
								if (!$result)
									die('Invalid query: ' . mysql_error());
								else {
									echo "<div class='alert'>Tag added.</div>";
								}
							}
						}
					}
											
					echo "<br><br>";
					
					// add Album data
					echo "<h1><span id=\"tag-heading\"><p>Albums</p></span></h1>";
					
					echo "<div class=\"albumlist\">";
					
					$result = mysql_query("select albumid, name, year from album where bandid=".$bandid);
				
					if ($result != false) {
							while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
								$albumid = $row[0];
								$name = $row[1];
								$year = $row[2];
								
								echo "<br>&nbsp;<br><h2><span id=\"tag-heading\"><p> <a href='album.php?id=".$albumid."'>".$name." (".$year.")</a></p></span></h2>";
								
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

					// if user logged in, can add an album
					if( $fgmembersite->CheckLogin() ) {
						
						echo 'Add An Album<hr>';
						
						// add album button
						echo '<form action="band.php?band='.$_GET["band"].'" id="addalbum" name="myform" method="POST">
					
						<label>Name</label>
						<input name="name" type="text" required>

						<label>Total Tracks</label>
						<input name="totaltracks">						

						<label>Label</label>
						<input name="label">
					
						<label>Year</label>
						<input name="year">
						
						<label>About</label>
						<textarea name="about"></textarea>
			
						<input id="submit" name="addalbum" type="submit" value="Add">
						 
						</form>';
					
						// Button clicked, Add a album
						if (!empty ($_POST["addalbum"]))
						{
							$query = 'insert into album (name, bandid, totaltracks, label, year, about, creatorid) values (';
						
							$result = mysql_query('select userid from user where username = "'.$fgmembersite->getUsername().'"');
							$row = mysql_fetch_assoc($result);
							$userid = $row["userid"];
							
							if (!empty ($_POST["name"]))
							{
								$name = mysql_escape_string($_POST["name"]);
								$query = $query.'"'.$name.'"';
							}
							
							$query = $query.', '.$bandid;
			
							if (!empty ($_POST["totaltracks"]))
							{
								$query = $query.','.trim($_POST["totaltracks"]);
							}
							else
							{
								$query = $query.",NULL";
							}
							
							if (!empty ($_POST["label"]))
							{
								$query = $query.',"'.trim($_POST["label"]).'"';
							}
							else
							{
								$query = $query.",NULL";
							}
							
							if (!empty ($_POST["year"]))
							{
								$query = $query.",".trim($_POST["year"]);
							}
							else
							{
								$query = $query.",NULL";
							}
							
							if (!empty ($_POST["about"]))
							{
								$about = mysql_escape_string($_POST["about"]);
								$query = $query.',"'.$about.'"';
							}
							else
							{
								$query = $query.",NULL";
							}
							
							$query = $query.','.$userid.')';
							
							$result = mysql_query( $query );
								
							if (!$result)
								die('Invalid query: ' . mysql_error());
							else {
								echo "<div class='alert'>Album added.</div>";
							}
						}	

						echo "<br>";
						
					}
					
					// DISPLAY LIST OF TOP SONGS
					echo "<div class=\"songlist\">";
					echo "<h1><span id=\"tag-heading\"><p>Top Songs</p></span></h1>";
									
					$result = mysql_query("select albumid, name, year from album where bandid=".$bandid);
				
					if ($result != false) {
						echo "<ul>";
							
						while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
							$albumid = $row[0];
							$albumname = $row[1];
							$year = $row[2];
							
							// find all songids
							$result2 = mysql_query('select songid, count(rating) from songrating where rating = 1 and songid in (select song.songid from albumsong, song where albumid='.$albumid.' and song.songid = albumsong.songid) group by songid order by count(rating) desc');
				
							if ($result2 != false) {
							
								echo "<br>&nbsp;<br><h3><span id=\"tag-heading\"><p><strong>".$albumname." (".$year.")</strong></p></span></h3>";
								
								while ($row2 = mysql_fetch_array($result2, MYSQL_NUM)) {
									$songid = $row2[0];
									$rating = $row2[1];
									$result3 = mysql_query("select name from song where songid = ".$songid);
									$row3 = mysql_fetch_array($result3);
									
									echo "<li><a href=\"song.php?id=".$songid."\">".$row3[0]." - Liked ".$rating." Times</a></li>";
									mysql_free_result($result3);
								}
								
							}
							mysql_free_result($result2);
						}
						echo "</ul>";
					}
					mysql_free_result($result);
					echo "</div>";
					
					echo "<br><br>";
					
					//display similar bands
					
					// display posts about band
					echo "<h1><span id=\"tag-heading\"><p>Posts</p></span></h1>";
					
					echo "<div class=\"articlelist\">";
					
					$result = mysql_query("select articleid, heading, postdate, creatorid from article where articleid IN (select articleid from articleband where bandid=".$bandid.")");
				
					if ($result != false) {
							while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
								
								$posterqry = mysql_query("select username from user where userid = ".$row[3]);
								$posterdata = mysql_fetch_array($posterqry, MYSQL_NUM);
								$poster = $posterdata[0];
								$postdate = date("F j, Y, g:i a", strtotime( $row[2] ) );
								
								echo "<li><a href=\"article.php?id=".$row[0]."\">".$row[1]." (".$postdate.") by ".$poster."</a></li>";
						
							}	
							echo "</ul>";
							mysql_free_result($result);
						
					}
					echo "</div>";
					
				}
				else
				{
						echo "Band not found.";
				}
				
			}
			
			else
			{
				// DISPLAY LIST OF TOP BANDS
				echo "<div class=\"bandlist\">";
				
				$result = mysql_query('select bandid, count(rating) from bandrating where rating = 1 group by bandid order by count(rating) desc');
				
					if ($result != false) {
						echo "<p id=\"mainheading\">Top Bands<hr></p><p>&nbsp;</p>";
						
						echo "<ul>";
								
						while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
							$bandid = $row[0];
							$rating = $row[1];
							$result2 = mysql_query("select name from band where bandid = ".$bandid);
							$row2 = mysql_fetch_array($result2);
							echo "<li><a href=\"band.php?band=".$row2[0]."\">".$row2[0]." - Liked ".$rating." Times</a></li>";
						}
						
						echo "</ul>";
						mysql_free_result($result);
						mysql_free_result($result2);
					}
				echo "</div>";
				
				echo "<br><br>";

				// list of all bands
				echo "<div class=\"bandlist\">";
				$result = mysql_query("select name from band order by name");
				
					if ($result != false) {
						echo "<p id=\"mainheading\">List of All Bands<hr></p><p>&nbsp;</p>";
						
						echo "<ul>";
								
						while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
							echo "<li><a href=\"band.php?band=".$row[0]."\">".$row[0]."</a></li>";
						}
						
						echo "</ul>";
						mysql_free_result($result);
					}
				echo "</div>";
				
				echo "<br><br>";
				
				// if user logged in, can add a band
				if( $fgmembersite->CheckLogin() ) {
				
					echo 'Add A Band<hr>';

					// add band button
					echo '<form action="band.php" id="addband" name="myform" method="POST">
				
					<label>Name</label>
					<input name="name" type="text" required>
					
					<label>Website</label>
					<input name="url" type="url" required>

					<label>City</label>
					<input name="city">
							 
					<label>State</label>
					<input name="state">						
							
					<label>Country</label>
					<input name="country">
							
					<label>Label</label>
					<input name="label">
				
					<label>Start Year</label>
					<input name="startyear">
					
					<label>End Year</label>
					<input name="endyear">
					
					<label>About</label>
					<textarea name="about"></textarea>
		
					<input id="submit" name="create" type="submit" value="Add">
					 
					</form>';
				
					// Button clicked, Add a band
					if (!empty ($_POST["create"]))
					{
						$query = 'insert into band (name, url, city, state, country, label, startyear, endyear, about, creatorid) values (';
					
						$result = mysql_query('select userid from user where username = "'.$fgmembersite->getUsername().'"');
						$row = mysql_fetch_assoc($result);
						$userid = $row["userid"];
						
						if (!empty ($_POST["name"]))
						{
							$name = mysql_escape_string($_POST["name"]);
							$query = $query.'"'.$name.'"';
						}
						if (!empty ($_POST["url"]))
						{
							$query = $query.',"'.trim($_POST["url"]).'"';
						}
						else
						{
							$query = $query.",NULL";
						}
						
						if (!empty ($_POST["city"]))
						{
							$query = $query.',"'.trim($_POST["city"]).'"';
						}
						else
						{
							$query = $query.",NULL";
						}
						
						if (!empty ($_POST["state"]))
						{
							$query = $query.',"'.trim($_POST["state"]).'"';
						}
						else
						{
							$query = $query.",NULL";
						}
						
						if (!empty ($_POST["country"]))
						{
							$query = $query.',"'.trim($_POST["country"]).'"';
						}
						else
						{
							$query = $query.",NULL";
						}
						
						if (!empty ($_POST["label"]))
						{
							$query = $query.',"'.trim($_POST["label"]).'"';
						}
						else
						{
							$query = $query.",NULL";
						}
						
						if (!empty ($_POST["startyear"]))
						{
							$query = $query.",".trim($_POST["startyear"]);
						}
						else
						{
							$query = $query.",NULL";
						}
						
						if (!empty ($_POST["endyear"]))
						{
							$query = $query.",".trim($_POST["endyear"]);
						}
						else
						{
							$query = $query.",NULL";
						}
						
						if (!empty ($_POST["about"]))
						{
							$about = mysql_escape_string($_POST["about"]);
							$query = $query.',"'.$about.'"';
						}
						else
						{
							$query = $query.",NULL";
						}
						
						$query = $query.','.$userid.')';
						
						$result = mysql_query( $query );
							
						if (!$result)
							die('Invalid query: ' . mysql_error());
						else {
							echo "<div class='alert'>Band added.</div>";
						}
					}	
				}	
			}
		}
	?>

	

</div>

</body>
</html>


