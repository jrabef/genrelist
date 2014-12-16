<?php
	require_once("./connect.php");
	require_once("./include/membersite_config.php");

	if(!empty($_POST['unique_id']))
	{
		$like_id = $_POST['unique_id'];
		$userid = 3; // $_POST['user_id'];
		
		$uid_sql=mysql_query("select * from bandrating where bandid=".$like_id." and creatorid=".$userid);
		$count=mysql_num_rows($uid_sql);
	
		// handle up button clicked 
		if ($_POST['vote'] == "up")
		{
			if($count==0)
			{
				$sql_in=mysql_query("INSERT into bandrating values (".$like_id.",".$userid.",1)");
			}
			else
			{
				$sql_update=mysql_query("update bandrating set rating=1 where bandid=".$like_id." and creatorid=".$userid);
			}
		}
		
		// handle down button clicked 
		if ($_POST['vote'] == "down")
		{
			if($count==0)
			{
				$sql_in=mysql_query("INSERT into bandrating values (".$like_id.",".$userid.",0)");
			}
			else
			{
				$sql_update=mysql_query("update bandrating set rating=0 where bandid=".$like_id." and creatorid=".$userid);
			}
		}
	}
?>