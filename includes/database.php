<?php
	/*
	* Author: Iftikhar Ahmed
	* 
	* Date: 06/06/2018
	*
	* @desc: This class is backend or third layer. It talks directly with database and performs respective CRUD operations.
	*
	* @required: Nothing
	*
	* @methods: dbConnect(), getRows(), saveRow(), updateRow(), deleteRow().
	
	*/
// start session	
session_start();

class Database{
	
	/*
	* Name: dbConnect()
	*
	* @desc: This function establishes a connection with database. It passes required parameters to connect with database. If there comes an error and connection...
	*        couldn't be established, it quits the script and prints an error message. And if connection is successful it returns an object which represents...
	*		 ...the connection to a MySQL Server. 	
	*		 
	* Parameters: NULL
	* 			  
	* Returns: connection object.	
	*/	
	
	private function dbConnect(){
		
		// Following are database credentials that are used to connect with database.
		$servername = "localhost";
		$username = "root";
		$password = "";
		$dbname = "dbnotes";
		
		// Create connection with database
		$conn = new mysqli($servername, $username, $password, $dbname);
		
		// Check connection
		if ($conn->connect_error) { 
			die("Connection failed: " . $conn->connect_error);
		}  
		
		return $conn;
	}	
	
	/*
	* Name: getRows()
	*
	* @desc: This function fetches record or records from database. Triggers select query depending upon criteria it receives as parameter. 	
	*		 
	* Parameters: An array of conditions and table name.
	* 			  
	* Returns: An array or false.	
	*/
	
	public function getRows($params = array(), $tblName){
		
		$result ='';    	
		
		//Call of member function to establish a connection with database
		$connection = $this->dbConnect();
		
		if($connection) { // If it connects with database
		
			$sql = "SELECT * FROM ".$tblName;			
			
			// Following code snippet builds a select statement or query. It Traverses through given array of conditions and makes select statement.
			
			if(array_key_exists("conditions",$params)){// If there exists conditions array in parameter.
				
				$a = 0;
				
				foreach ($params['conditions'] as $key => $value) { // Iterate through all conditions.
                	
					if($a == 0) // In first iteration we use 'WHERE' clause and after this 'AND'
						$sql .= " WHERE ".$key." = '".$value."'" ;
					else
						$sql .= " AND ".$key." = '".$value."'" ;
					
					$a++;
				}
			}
			
			if(array_key_exists("id",$params)){
			
				$sql .= " WHERE id =".$params['id'];
			}
			
			$sql.= " ORDER BY created DESC";
			
			$result = $connection->query($sql); // Execute the build select statement/
			
			$connection->close();
			
			if(!$result){
				return false;
			}						
			//Fetches all result rows as an associative array and return them
			return mysqli_fetch_all($result,MYSQLI_ASSOC); // MYSQLI_ASSOC specifies what type of array should be produced from the current row data. In this case its Associative
			
		}				
  	}
	
	public function getLastRow($params = array(), $tblName) {
		
		//Call of member function to establish a connection with database
		$connection = $this->dbConnect();
		
		if($connection) {
			
			$sql = "SELECT * FROM ".$tblName."  WHERE id = (SELECT MAX(id) FROM ".$tblName;
			
			if(array_key_exists("conditions",$params)){// If there exists conditions array in parameter.
				
				$a = 0;
				
				foreach ($params['conditions'] as $key => $value) { // Iterate through all conditions.
                	
					if($a == 0) // In first iteration we use 'WHERE' clause and after this 'AND'
						$sql .= " WHERE ".$key." = '".$value."'" ;
					else
						$sql .= " AND ".$key." = '".$value."'" ;
					
					$a++;
				}
			}
			
			$sql .=" )";
			
			$result = $connection->query($sql); // Execute the build select statement/
			
			$connection->close();
			
			if(!$result){
				return false;
			}						
			//Fetches all result rows as an associative array and return them
			return mysqli_fetch_all($result,MYSQLI_ASSOC); // MYSQLI_ASSOC specifies what type of array should be produced from the current row data. In this case its Associative
				
		}				
		
	}
	
	/*
	* Name: saveRows()
	*
	* @desc: This function inserts record into database table. It takes values to be saved and table name as parameter.
	*		 
	* Parameters: An array of values to be inserted and table name.
	* 			  
	* Returns: Returns FALSE on failure and TRUE otherwise.	
	*/
	
	public function saveRow($postData = array(), $tblName) {
		
		$result ='';    	
		$connection = $this->dbConnect();
		
		$sql = " insert into ".$tblName." values(0, "; // Here 0 is for autoincrement field, It will automatically get incremented value
		
		$last_key = key( array_slice( $postData, -1, 1, TRUE ) ); // This will be used to insert ',' as ',' is not used after last value.
		
		foreach($postData as $key => $value) {
			
			$sql .= "'".$value."'";
			
			if ($key != $last_key) {
				// not last element
				$sql .=" , ";
			}
		}
		
		$sql .= " )";
		
		$result = $connection->query($sql);
		
		$connection->close();
		
		return $result;
		
	}
	
	/*
	* Name: updateRows()
	*
	* @desc: This function updates record having given 'ID'. It takes three parameters, values to be updated, id and table name. 
	*		 
	* Parameters: An array of values to be inserted, id and table name.
	* 			  
	* Returns: Returns FALSE on failure and TRUE otherwise.	
	*/
	
	public function updateRow($postData, $id, $tblName) {
		
		$result ='';    	
		$connection = $this->dbConnect();
		
		$sql = "update ".$tblName." set ";
		
		$last_key = key( array_slice( $postData, -1, 1, TRUE ) );		
		
		foreach($postData as $key => $value) {
			
			$sql .= $key." = '".$value."'"; 
			
			if ($key != $last_key) {
				// not last element
				$sql .=" , ";
			}
		}
		
		$sql .= " where id =".$id;
		
		$result = $connection->query($sql);
		
		$connection->close();
		
		return $result;
		
	}
	
	/*
	* Name: deleteRow()
	*
	* @desc: This function deletes record from database table. It takes id and table name as parameters.
	*		 
	* Parameters: ID and table name.
	* 			  
	* Returns: Returns FALSE on failure and TRUE otherwise.	
	*/
	
	public function deleteRow($id, $tblName) {
		
		$result ='';    	
		$connection = $this->dbConnect();
		
		$sql = "delete from ".$tblName." where id = ".$id;
		
		$result = $connection->query($sql);
		
		$connection->close();
		
		return $result;
	}
	
}


