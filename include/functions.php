<?php
	function fetchAll($sql) {
		$result = mysql_query($sql);
		$out = array();
		while($row = mysql_fetch_assoc($result)) {
			$out[] = $row;
		}
		mysql_free_result($result);
		return $out;
	}

	function fetchOne($sql) {
		$out = $this->fetchAll($sql);
		return array_shift($out);
	}
?>