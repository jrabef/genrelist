<?php
	{ // Connect and Test MySQL and specific DB (return $dbSuccess = T/F)
	
					$hostname = "localhost";
					$username = "root";
					$password = "asw38913266";
					
					$databaseName = "drdb2";

					$dbConnected = @mysql_connect($hostname, $username, $password);
					$dbSelected = @mysql_select_db($databaseName,$dbConnected);

					$dbSuccess = true;
					if ($dbConnected) {
						if (!$dbSelected) {
							echo "DB connection FAILED<br /><br />";
							$dbSuccess = false;
						}		
					} else {
						echo "MySQL connection FAILED<br /><br />";
						$dbSuccess = false;
					}
	}  
?>