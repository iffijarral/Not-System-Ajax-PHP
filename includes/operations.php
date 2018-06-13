<?php
	
	/*
	* Author: Iftikhar Ahmed
	* 
	* Date: 25/05/2018
	*
	* @desc: This class is a middle layer and performs different operations with the help of Database class. This class is called or required in front layer or pages
	*
	* @required: database.php. This class deals directly with database to fetch and send data. It represents third layer.
	*
	* @methods: getNote(), getNotes(), updateNote(), saveNote(), deleteNote(), logIn(), logOut() .
	
	*/
	
	require_once('database.php');	

class NoteOperations{
	
	private $objDB; // Database class object 
	
	// Constructor 
	public function __construct() {
		
		$this->objDB = new Database();	// Object instantiation of Database class. 
	
	}	
	
	/*
	* Name: getNotes()
	*
	* @desc: This function doesn't take any parameter, but it sends two parameters to getRows() database class method. First parameter is an empty array...
			 which means there is no condition and fetch all available Notes. The second parameter is the database table name.
	*		 
	* Parameters: NULL
	* 			  
	* Returns: This function returns an array of all available Notes.	
	*/	
	public function getNotes() {
		
		return $this->objDB->getRows(array(''),'notes');
				
	}
	
	/*
	* Name: getNote()
	*
	* @desc: It gets NoteId as parameter and returns information of that specific Note.
	*
	* Parameters: NoteId
	*
	* Returns: This function returns info of given NoteId.	
	*/
	
	public function getNote($id) {
		return $this->objDB->getRows(array('id'=>$id),'notes');	
	}
	
	/*
	* Name: updateNote()
	*
	* @desc: This function updates Note of given NoteId. It takes two parameters an array of fields to be updated and NoteId. It calls updateRow method of database... 
	*        class and passes 3 parameters, 1.array of fields to update 2. NoteId 3. database table name. Depending on return value, redirects to respective page.
	*
	* Parameters: Array of NoteFields and NoteId
	*
	* Returns: It returns true on successful operation and false otherwise.	
	*/
	
	public function updateNote($NoteData, $id) {
		
		if($this->objDB->updateRow($NoteData, $id, 'notes')) 				
			return true;				
		else
			return false;
	}
	
	/*
	* Name: saveNote()
	*
	* @desc: This function saves new Note. It takes an array of fields and returns true after successful operation otherwise false. It calls saveRow method of database... 
	*        class and passes 2 parameters, 1.array of fields 2. database table name. Depending on return value, redirects to respective page.
	*
	* Parameters: Array of NoteFields
	*
	* Returns: It returns true on successful operation and false otherwise.	
	*/
	
	public function saveNote($NoteData) {
		
		if($this->objDB->saveRow($NoteData,'notes')) 				
			return true;				
		else
			return false;
	}
	
	
	/*
	* Name: deleteNote()
	*
	* @desc: This function deletes Note of given NoteId. It takes NoteId as parameter and calls deleteRow method of database class.
	*	     Depending on return value, redirects to respective page.
	*
	* Parameters: NoteId
	*
	* Returns: It returns true on successful operation and false otherwise.	
	*/
	
	public function deleteNote($id) {
		
		if($this->objDB->deleteRow($id, 'notes')) 
			return true;	
		else
			return false;
		
	}
	
	/*
	* Name: logIn()
	*
	* @desc: This function calls getRow method of database class to authenticate given user. Its takes two parameters 'username' and 'password' and passes them... 
	*        ... to getRow method. And if there is valid user, then it sets session variables and redirects to respective page. If the user is not valid ...
	*		 ... it redires to login page with an error message.
 	*
	* Parameters: 'username' and 'password'
	*
	* Returns: Redirects to respective pages depending on the result it receives.	
	*/
	
	public function logIn($username, $password) {
		
		$con['conditions'] = array(
						
			'email' => $username,
		
			//'password' => md5($this->input->Note('password')),
			'password' => $password					
								
		);
		
		$checkLogin = $this->objDB->getRows($con, 'users');
		
		if($checkLogin) {
			
			$_SESSION['isLoggedIn'] = true;
		
			$_SESSION['username'] = $username;	
			
			return true;
		} else {
			
			return false;
		}
	}
	
	/*
	* Name: logOut()
	*
	* @desc: This function logs the user out, unsets the session variables and destroys the session.	
	*
	* Parameters: NULL
	*
	* Returns: Nothing
	*/
	
	public function logOut() {
		unset($_SESSION['isLoggedIn']);
		unset($_SESSION['username']);
		session_destroy();
		header("Location: ../");
	}
}

