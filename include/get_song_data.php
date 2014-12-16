<?php

function get_song_by_id($songid) {
	$data = array();
	
	$result = mysql_query("select name, year, length from song where songid = ".$songid);
	$result2 = mysql_query("select albumid, trackno from albumsong where songid = ".$songid);

	if ($result != false && $result2 != false) {

		// display song info //
		$row = mysql_fetch_assoc($result);
		$row2 = mysql_fetch_assoc($result2);
		
		$name = $row["name"];
		$year = $row["year"];
		
		$lengthinsecs = $row["length"];
		$mins = intval($lengthinsecs / 60);
		$secs = str_pad(intval($lengthinsecs % 60),2,"0",STR_PAD_LEFT);
		
		$trackno = $row2["trackno"];
		
		$albumid = $row2["albumid"];
		$result3 = mysql_query("select name, bandid from album where albumid = ".$albumid);
		$row3 = mysql_fetch_assoc($result3);
		
		$albumname = $row3["name"];
		$bandid = $row3["bandid"];
		$row4 = mysql_fetch_assoc( mysql_query("select name from band where bandid = ".$bandid) );
		$bandname = $row4["name"];

		$data[] = $name; //0
		$data[] = $year; //1
		$data[] = $mins; //2
		$data[] = $secs; //3
		$data[] = $trackno; //4
		$data[] = $albumname; //5
		$data[] = $bandname; //6
		
		// return array
		return $data;
	}
}
?>