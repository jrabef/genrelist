<html land="en">
<head>
    <meta charset="utf-8">
	<script type="text/javascript" src="scripts/like_message.js"></script>
	<link rel="stylesheet" type="text/css" href="style/style.css" />
	<link rel="STYLESHEET" type="text/css" href="style/fg_membersite.css" />
	<script type="text/javascript" src="jquery.min.js"></script>
	<script type="text/javascript" src="scripts/jquery.tablesorter.js"></script>
	
	<?php 
		  include 'connect.php';
	?>
	
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
			<li class='active'><a href='playlist.php'><span>Playlists</span></a></li>
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

	<!------------------------------>
	<!--- list playlist details  --->
	<!------------------------------>
	<?php
		//Execute code ONLY if connections were successful 	
		if ($dbSuccess) {
			
			//search for list
			if (isset($_GET["id"]))
			{
				if (!empty($_GET["id"]))
				{
					$playlistid = $_GET["id"];
					
					$result2 = mysql_query("select name, postdate, featured, featureddate, about, userid from playlist where playlistid = " . $playlistid);
					$playlistdata = mysql_fetch_array($result2);
					$name = $playlistdata[0];
					$postdate = $playlistdata[1];
					$featured = $playlistdata[2];
					$featureddate = $playlistdata[3];
					$about = $playlistdata[4];
					$userid = $playlistdata[5];

					$result3 = mysql_query('select username from user where userid = '.$userid);
					$user_row = mysql_fetch_assoc($result3);
					$username = $user_row["username"];
					
					// display playlist name and details
					echo "<h2><span id=\"playlist-heading\">".$name."</span></h2>";

					// find rating
					$ratingquery = mysql_query("select rating from playlistrating where playlistid = " . $playlistid);
					
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
					echo "<p>Likes: ".$likes."</p><br />";
					echo "<p>Dislikes: ".$dislikes."</p><br />";
					echo "<p><h2>Creator:</h2> ".$username."</p><br />";
					echo "<p><h2>Created:</h2> ".$postdate."</p><br />";
					if ($featured == 1)
					{
						echo "<h2>Featured</h2>";
						echo "<p>Front page on ".$featureddate.".</p><br />";
					}
					echo "</div>";
					// **** END SIDEBAR ****
					
					$result = mysql_query("select songid, position from playlistsong where playlistid = " . $playlistid . " order by position");
			
					if ($result != false) {
							while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
								$songid = $row[0];
								$num = $row[1];
								
								// display songs
								$songs = mysql_query("select name, length from song where songid = ".$songid);

								if ($songs != false) {
									while ($songrow = mysql_fetch_array($songs, MYSQL_NUM)) {
										$name = $songrow[0];
										$lengthinsecs = $songrow[1];
										
										$mins = intval($lengthinsecs / 60);
										$secs = str_pad(intval($lengthinsecs % 60),2,"0",STR_PAD_LEFT);

										
										echo '<form action="playlist.php?id='.$playlistid.'" method="POST">
										<table id="myTable" class="tablesorter" border="1" width="500">
										<thead>
										<tr>
										<th>Select</th><th>Track</th><th>Title</th><th>Length</th>
										</tr>
										</thead>
										<tbody>
										<tr>
										<td width="5%"><input type="checkbox" name="songid[]" value='.$songid.'></td>
										<td width="10%">'.$num.'</td><td width="80%">  <a href="song.php?id='.$songid.'">'.$name.'</a> </td> <td>'.$mins.':'.$secs.'</td>
										</tr>
										</tbody> 
										</table>';
									}
								}
								
							}	
							echo '<input id="submit" name="delete" type="submit" value="Delete"></form>';
							echo "</ul><br>&nbsp;<br>";
							mysql_free_result($result);
							mysql_free_result($result2);
					}
					mysql_free_result($result3);
							
					// Button clicked, delete song from playlist
					if( $fgmembersite->CheckLogin() ) {
					
						if (!empty($_POST["delete"])) {
							$result = mysql_query('select userid from user where username = "'.$fgmembersite->getUsername().'"');
							$row = mysql_fetch_assoc($result);
							$userid = $row["userid"];

							// check if logged in user is playlist creator
							$playlist_creator = mysql_fetch_array(mysql_query("select userid from playlist where playlistid = ".$playlistid))[0];
							
							if ($playlist_creator == $userid) {
							
								if(!empty($_POST["songid"])) {
									foreach($_POST["songid"] as $songid) {
										$pos = mysql_fetch_array(mysql_query('select position from playlistsong where playlistid = '.$playlistid.' and songid = '.$songid))[0];
									
										$result2 = mysql_query('delete from playlistsong where playlistid = '.$playlistid.' and position = '.$pos );
										
										$reset = mysql_query('update playlistsong set position = position - 1 where position > '.$pos);
										
										if (!$result2)
											die('Invalid query: ' . mysql_error());
										else {
											echo "<div class='alert'>Song deleted.</div>";
										}
									}
								}
							}
							else {
									echo "<div class=\"alert\">Cannot delete from another user's playlist.</div>";
							}
						}
					}
                     
					// display about
					echo "<h2><span id=\"tag-heading\">About</span></h2><p>";
					echo $about;
					echo "</p><br />";
					
                    // display tags //
                    $result = mysql_query("select count(*) as tagcount from playlisttag where playlistid = ".$playlistid." group by name order by tagcount desc limit 1");
                         
                    if ($result != false) {
                            $row = mysql_fetch_assoc($result);
                            $total = $row["tagcount"];
                             
                            echo "<h2><span id=\"tag-heading\">Tags</span></h2>";
                                 
                            mysql_free_result($result);
                                 
                            $tags = mysql_query("select name, count(*) as tagcount from playlisttag where playlistid = ".$playlistid." group by name");
							
							echo "<div class=\"tags\">";
                            if ($tags != false) {
                                echo "<ul>";
                                while ($row = mysql_fetch_array($tags, MYSQL_NUM)) {
                                    if ( ($row[1] / $total ) < .06 )
                                        $class = 'smallest';
 
                                    else if ( ($row[1] / $total ) < .10 )
                                        $class = 'smaller';
 
                                    else if ( ($row[1] / $total ) < .20 )
                                        $class = 'small';
 
                                    else if ( ($row[1] / $total ) < .30 )
                                        $class = 'medium';
 
                                    else if ( ($row[1] / $total ) < .40 )
                                        $class = 'large';
 
                                    else
                                        $class = 'largest';
                                         
                                        echo '<li class="'.$class.'"><a href="playlisttag.php?tag='.$row[0].'">'.$row[0].'</a></li>';
                             
                                }   
                                echo "</ul>";
								echo "</div>";
                                mysql_free_result($tags);
                            }
                    }
					
					// if user logged in, can add a tag
					if( $fgmembersite->CheckLogin() ) {
					
						// display add tag form
						echo '<form action="playlist.php?id='.$_GET["id"].'" name="myform" method="POST">
						
							<label>Add tag: </label>
							<input name="tag" type="text" required>
				
							<input id="submit" name="submit" type="submit" value="Add">
							 
							</form>';
					
						// Button clicked, Add a tag
						if (isset($_POST["submit"]))
						{
							if (isset($_POST["tag"])) {
								$result = mysql_query('select userid from user where username = "'.$fgmembersite->getUsername().'"');
								$row = mysql_fetch_assoc($result);
								$userid = $row["userid"];
								$tag = trim($_POST["tag"]);
								$tag = mysql_escape_string($tag);
								$result = mysql_query('insert into playlisttag (playlistid, name, creatorid) values ('.$playlistid.',"'.$tag.'",'.$userid.')');
									
								if (!$result)
									die('Invalid query: ' . mysql_error());
								else {
									echo '<div class="alert">Tag Added.</div>';
								}
							}
						}
					}
					
				}
				else
					echo "Playlist not found";
			}
			else
			{
				// display list of all playlists
				echo "<span id=\"mainheading\">Playlists<hr></span><br>";
				
				$lists = mysql_query("select playlistid, name, userid from playlist where private = 0");
				
				if ($lists != false)
				{
					echo "<ul>";
					
					while ($row = mysql_fetch_array($lists, MYSQL_NUM)) {
						$result = mysql_query('select username from user where userid = '.$row[2]);
						$row2 = mysql_fetch_assoc($result);
						$user = $row2["username"];

						echo "<li><a href=\"playlist.php?id=".$row[0]."\">".$row[1]."</a> by ".$user."</li>";
					}
					
					echo "</ul>";
					
					mysql_free_result($lists);
					mysql_free_result($result);
				}
			}
			
		}	
	?>

	

</div>

	<script>
		$(document).ready(function() 
			{ 
				$("#myTable").tablesorter( {sortList: [[0,0], [1,0]]} ); 
			} 
		); 
			
    </script>

</body>
</html>