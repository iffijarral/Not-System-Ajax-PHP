<?php
	
	/*
	* Author: Iftikhar Ahmed
	* 
	* Date: 06/06/2018
	*
	* @desc: This class is a middle layer and performs different operations with the help of Database class. This class is called or required in front layer or pages
	*
	* @required: database.php. 
	*
	* @methods: saveUser(), logIn(), logOut() .
	
	*/
	
	require_once('database.php');	

class Useroperations{
	
	private $objDB; // Database class object 
	
	// Constructor 
	public function __construct() {
		
		$this->objDB = new Database();	// Object instantiation of Database class. 
	
	}	
	
	/*
	* Name: saveUser()
	*
	* @desc: This function saves new user. It takes an array of fields and returns true after successful operation otherwise false. It calls saveRow method of database... 
	*        class and passes 2 parameters, 1.array of fields 2. database table name.
	*
	* Parameters: Array of userFields
	*
	* Returns: It returns true on successful operation and false otherwise.	
	*/
	
	public function saveUser($userData) {
		
		if($this->objDB->saveRow($userData,'users')) 				
			return true;				
		else
			return false;
	}
	
	/*
	* Name: logIn()
	*
	* @desc: This function calls getRow method of database class to authenticate given user. Its takes two parameters 'username' and 'password' and passes them... 
	*        ... to getRow method. And if there is valid user, then it sets session variables and returns true. If the user is not valid ...
	*		 ... it just returns fals.
 	*
	* Parameters: 'username' and 'password'
	*
	* Returns: true or false.	
	*/
	
	public function logIn($username, $password) {
		
		$con['conditions'] = array(
						
			'userEmail' => $username,
		
			'userPassword' => md5($password),
			
								
		);
		
		$checkLogin = $this->objDB->getRows($con, 'users');
		
		if($checkLogin) {
			
			$_SESSION['isLoggedIn'] = true;
		
			$_SESSION['userName'] = $username;

			$_SESSION['userID'] = $checkLogin[0]['id'];	
			
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
		unset($_SESSION['userName']);
		unset($_SESSION['userID']);
		session_destroy();
		header("Location: ../");
	}
}

