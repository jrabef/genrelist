<html land="en">
<head>
    <meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="style/style.css" />
	<link rel="STYLESHEET" type="text/css" href="style/fg_membersite.css" />
	<script type="text/javascript" src="jquery.min.js"></script>
	
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
			<li><a href='playlist.php'><span>Playlists</span></a></li>
			<li><a href='band.php'><span>Bands</span></a></li>
				<ul>
					<li><a href='bandtag.php'><span>Band Tags</span></a></li>
					<li class='active'><a href='songtag.php'><span>Song Tags</span></a></li>
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
	*list song tag page*
	*/

		//Execute code ONLY if connections were successful 	
		if ($dbSuccess) {
			
			if (isset($_GET["tag"]))
			{
				if (!empty ($_GET["tag"]))
				{
					$tag = trim($_GET["tag"]);
					
					if (isset($_GET["userid"]))
					{
						$userid = $_GET["userid"];
						// display list of songs with $tag
						$songids = mysql_query('select songid, count(*) as tagcount from songtag where name like "'.$tag.'" and creatorid = '.$userid.' group by songid order by tagcount desc');
						
						if ($songids != false) {
						
							echo "<p id=\"mainheading\">".$tag."<hr></p><p>&nbsp;</p>";
							echo "<ul>";
									
							while ($row = mysql_fetch_array($songids, MYSQL_NUM)) {
							
								$songname = mysql_fetch_assoc(  mysql_query("select name from song where songid = ".$row[0])  );
								//find band by songid
								$result2 = mysql_query("select albumid from albumsong where songid = ".$row[0]);
								$row2 = mysql_fetch_assoc($result2);
								$albumid = $row2["albumid"];
								$result3 = mysql_query("select bandid from album where albumid = ".$albumid);
								$row3 = mysql_fetch_assoc($result3);
								$bandid = $row3["bandid"];
								$row4 = mysql_fetch_assoc( mysql_query("select name from band where bandid = ".$bandid) );
								$bandname = $row4["name"];
								
								echo "<li><a href=\"song.php?id=".$row[0]."\">".$bandname." - ".$songname["name"]."</a> - Tagged ".$row[1]." times</li>";
							}
							
							echo "</ul>";
							mysql_free_result($songids);
						}
						
					}
					else
					{
						// display list of songs with $tag
						$songids = mysql_query('select songid, count(*) as tagcount from songtag where name like "'.$tag.'" group by songid order by tagcount desc');
						
						if ($songids != false) {
						
							echo "<p id=\"mainheading\">".$tag."<hr></p><p>&nbsp;</p>";
							echo "<ul>";
									
							while ($row = mysql_fetch_array($songids, MYSQL_NUM)) {
							
								$songname = mysql_fetch_assoc(  mysql_query("select name from song where songid = ".$row[0])  );
								//find band by songid
								$result2 = mysql_query("select albumid from albumsong where songid = ".$row[0]);
								$row2 = mysql_fetch_assoc($result2);
								$albumid = $row2["albumid"];
								$result3 = mysql_query("select bandid from album where albumid = ".$albumid);
								$row3 = mysql_fetch_assoc($result3);
								$bandid = $row3["bandid"];
								$row4 = mysql_fetch_assoc( mysql_query("select name from band where bandid = ".$bandid) );
								$bandname = $row4["name"];
								
								echo "<li><a href=\"song.php?id=".$row[0]."\">".$bandname." - ".$songname["name"]."</a> - Tagged ".$row[1]." times</li>";
							}
							
							echo "</ul>";
							mysql_free_result($songids);
						}
					}
				}
				else
				{
					echo "Tag not found.";
				}
			}
			else
			{	
				// get all tags
				echo "<div class=\"tags\">";
				$result = mysql_query("select count(*) as total from songtag");
				
				if ($result != false) {
					$row = mysql_fetch_assoc($result);
					$total = $row["total"];
					
					echo "<p id=\"mainheading\">Song Tags<hr></p><br>";
						
					mysql_free_result($result);
						
					$tags = mysql_query("select name, count(*) as tagcount from songtag group by name");
					
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
								
								echo '<li class="'.$class.'"><a href="songtag.php?tag='.$row[0].'">'.$row[0].'</a></li>';
					
						}	
						echo "</ul>";
						mysql_free_result($tags);
					}
					
					
				}
				echo "</div>";
						
			}
		
		}
	?>

	

</div>

</body>
</html>