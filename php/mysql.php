<?php
/**
 * mbe.ro
 *
 * @author     Ciprian Mocanu <http://www.mbe.ro> <ciprian@mbe.ro>
 * @license    Do whatever you like, just please reference the author
 * @version    1.56
 */
class mysql {
	var $con;
	//This one should not be edited except to change the database information
	function __construct($db=array()) {
		//DB Information
		$default = array(
			'host' => 'localhost',
			'user' => 'ecitize1_admin',
			'pass' => 'macos100',
			'db' => 'ecitize1_proxenos'
		);
		$db = array_merge($default,$db);
		$this->con=mysql_connect($db['host'],$db['user'],$db['pass'],true) or die ('Error connecting to MySQL');
		mysql_select_db($db['db'],$this->con) or die('Database '.$db['db'].' does not exist!');
	}
	
	//Closes the mysql session
	function __destruct() {
		mysql_close($this->con);
	}
	
	// Run a generic query, should not be used by itself - used within the other functions
	// By default rows is false meaning will return all rows. Use limit if you want to limit amount.
	function query($s='',$rows=false,$organize=true) {
		if (!$q=mysql_query($s,$this->con)) return false;
		if ($rows!==false) $rows = intval($rows);
		$rez=array(); $count=0;
		$type = $organize ? MYSQL_NUM : MYSQL_ASSOC;
		while (($rows===false || $count<$rows) && $line=mysql_fetch_array($q,$type)) {
			if ($organize) {
				foreach ($line as $field_id => $value) {
					$table = mysql_field_table($q, $field_id);
					if ($table==='') $table=0;
					$field = mysql_field_name($q,$field_id);
					$rez[$count][$field]=$value;
				}
			} else {
				$rez[$count] = $line;
			}
			++$count;
		}
		if (!mysql_free_result($q)) return false;
		return $rez;
	}
	
	//Execute a query
	function execute($s='') {
		if (!mysql_query($s,$this->con)) {
			return(mysql_error());
		}
		return true;
	}
	
	// Selects data - Used for general queries.
	function select($options) {
		// Example array you can use for the options variable passed into the function.
		// You don't have to use all of the options, you can just define the table (needed) and conditions
		$default = array (
			'table' => '',
			'fields' => '*',
			'condition' => '1',
			'order' => '1',
		);
		// Merge entered array with default, the options will override the default.
		$options = array_merge($default,$options);
		if (isset($options['limit'])) {
			$sql = "SELECT {$options['fields']} FROM {$options['table']} WHERE {$options['condition']} ORDER BY {$options['order']} LIMIT 0,{$options['limit']}";
		} else {
			$sql = "SELECT {$options['fields']} FROM {$options['table']} WHERE {$options['condition']} ORDER BY {$options['order']}";
		}
		return $this->query($sql);
	}
	
	// Custom function to return count of rows
	function num($options) {
		$default = array (
			'table' => '',
			'fields' => '*',
			'condition' => '1',
			'order' => '1',
		);
		$options = array_merge($default,$options);
		$sql = "SELECT count({$options['fields']}) AS count FROM {$options['table']} WHERE {$options['condition']}";
		$q = $this->query($sql,1,false);
		return $q[0]['count'];
	}
	
	//This returns a single row using the ($sql,1,false) options for query to return 1 row unorganized
	function row($options) {
		$default = array (
			'table' => '',
			'fields' => '*',
			'condition' => '1',
			'order' => '1'
		);
		$options = array_merge($default,$options);
		$sql = "SELECT {$options['fields']} FROM {$options['table']} WHERE {$options['condition']} ORDER BY {$options['order']}";
		$result = $this->query($sql,1,false);
		if (empty($result[0])) return false;
		return $result[0];
	}
	
	// Returns only one field rather than a whole row. Therefore field is required.
	function get($table=null,$field=null,$conditions='1') {
		if ($table===null || $field===null) return false;
		$result=$this->row(array(
			'table' => $table,
			'condition' => $conditions,
			'fields' => $field
		));
		if (empty($result[$field])) return false;
		return $result[$field];
	}
	
	/*
		* This will update a specific row within the table. The table must be entered and the array of values should be
		* in the format with the key of the array being the name of the column. Example: If users table had username, 
		* password, email then the array should be 
		* array('username' => $username, 'password' => $password, 'email' => $email) 
	*/
	function update($table=null,$array_of_values=array(),$conditions='FALSE') {
		if ($table===null || empty($array_of_values)) return false;
		$what_to_set = array();
		foreach ($array_of_values as $field => $value) {
			if (is_array($value) && !empty($value[0])) $what_to_set[]="`$field`='{$value[0]}'";
			else $what_to_set []= "`$field`='".mysql_real_escape_string($value,$this->con)."'";
		}
		$what_to_set_string = implode(',',$what_to_set);
		return $this->execute("UPDATE $table SET $what_to_set_string WHERE $conditions");
	}
	
	// Inser new row into table. As with above the key of the array_of_values should be the column name
	function insert($table=null,$array_of_values=array()) {
		if ($table===null || empty($array_of_values) || !is_array($array_of_values)) return false;
		$fields=array(); $values=array();
		foreach ($array_of_values as $id => $value) {
			$fields[]=$id;
			if (is_array($value) && !empty($value[0])) $values[]=$value[0];
			else $values[]="'".mysql_real_escape_string($value,$this->con)."'";
		}
		$s = "INSERT INTO $table (".implode(',',$fields).') VALUES ('.implode(',',$values).')';
		if (mysql_query($s,$this->con)) { return mysql_insert_id($this->con); }		
		return false;
	}

	function replace($table=null,$array_of_values=array()) {
		if ($table===null || empty($array_of_values) || !is_array($array_of_values)) return false;
		$fields=array(); $values=array();
		foreach ($array_of_values as $id => $value) {
			$fields[]=$id;
			if (is_array($value) && !empty($value[0])) $values[]=$value[0];
			else $values[]="'".mysql_real_escape_string($value,$this->con)."'";
		}
		$s = "REPLACE INTO $table (".implode(',',$fields).') VALUES ('.implode(',',$values).')';
		if (mysql_query($s,$this->con)) { return mysql_insert_id($this->con); }		
		return false;
	}
	
	// Deletes from table depending on entered conditions
	function delete($table=null,$conditions='FALSE') {
		if ($table===null) return false;
		$s = "DELETE FROM $table WHERE $conditions";
		if (mysql_query($s,$this->con)) { return mysql_insert_id($this->con); }	
	}
}