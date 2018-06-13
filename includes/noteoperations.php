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
	* @methods: getNote(), getNotes(), updateNote(), saveNote(), deleteNote().
	
	*/
	
	require_once('database.php');	

class Noteoperations{
	
	private $objDB; // Database class object 
	
	// Constructor 
	public function __construct() {
		
		$this->objDB = new Database();	// Object instantiation of Database class. 
	
	}	
	
	/*
	* Name: getNotes()
	*
	* @desc: This function takes userID as parameter, and it sends two parameters to getRows() database class method. First parameter is userID...
	*		 and second parameter is the database table name.
	*		 
	* Parameters: userID
	* 			  
	* Returns: This function returns an array of all available notes of given user.	
	*/	
	
	public function getNotes($userID) {
		
		$con['conditions'] = array(
				
			'userid' => $userID
			
		);
		return $this->objDB->getRows($con,'notes');
				
	}
	
	/*
	* Name: getNote()
	*
	* @desc: It gets noteID as parameter and returns information of that specific note.
	*
	* Parameters: noteID
	*
	* Returns: This function returns info of given noteId.	
	*/
	
	public function getNote($id) {
		
		return $this->objDB->getRows(array('id'=>$id),'notes');	
		
	}
	
	/*
	* Name: getLastNote()
	*
	* @desc: This function fetches the last inserted note of given user. 
	*
	* Parameters: userID
	*
	* Returns: This function returns last inserted record of given user.	
	*/
	
	public function getLastNote($userID) {
		
		$con['conditions'] = array(
				
			'userid' => $userID
			
		);
		return $this->objDB->getLastRow($con,'notes');
	}
	
	/*
	* Name: updateNote()
	*
	* @desc: This function updates note of given noteId. It takes two parameters an array of fields to be updated and noteId. It calls updateRow method of database... 
	*        class and passes 3 parameters, 1.array of fields to update 2. noteId 3. database table name. 
	*
	* Parameters: Array of noteFields and noteId
	*
	* Returns: It returns true on successful operation and false otherwise.	
	*/
	
	public function updateNote($noteData, $id) {
		
		if($this->objDB->updateRow($noteData, $id, 'notes')) 				
			return true;				
		else
			return false;
	}
	
	/*
	* Name: saveNote()
	*
	* @desc: This function saves new note. It takes an array of fields and returns true after successful operation otherwise false. It calls saveRow method of database... 
	*        class and passes 2 parameters, 1.array of fields 2. database table name. 
	*
	* Parameters: Array of noteFields
	*
	* Returns: It returns true on successful operation and false otherwise.	
	*/
	
	public function saveNote($noteData) {
		
		if($this->objDB->saveRow($noteData,'notes')) 				
			return true;				
		else
			return false;
	}	
	
	/*
	* Name: deleteNote()
	*
	* @desc: This function deletes note of given noteId. It takes noteId as parameter and calls deleteRow method of database class.
	*
	* Parameters: noteId
	*
	* Returns: It returns true on successful operation and false otherwise.	
	*/
	
	public function deleteNote($id) {
		
		if($this->objDB->deleteRow($id, 'notes')) 
			return true;	
		else
			return false;
		
	}
	
	
	
	
	
}

