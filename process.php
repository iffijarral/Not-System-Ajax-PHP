<?php
	
	/*
	* Author: Iftikhar Ahmed
	* 
	* Date: 06/06/2018
	*
	* @desc: This is the backbone or controller, it receives requests and performs operation accordingly.  
	*
	* @required: useroperations.php, noteoperations.php. 
	*
	*/
	
	require_once('includes/useroperations.php');
	require_once('includes/noteoperations.php');	
	
	$objOpr = new Useroperations();
	
	$objOprNote = new Noteoperations();
	
	// $_POST['action'] holds the request, request can be just a service (like logIn, logOut) or it can be to fetch resource   . 
	if(isset($_POST['action'])) {
		
		switch($_POST['action']) {
			// This is request comes on creation of new user.
			case 'register':

				$userData = array(
					
					'userName' => strip_tags($_POST['userName']),
			
					'userEmail' => strip_tags($_POST['userEmail']),
					
					'userPassword' => md5(strip_tags($_POST['userPassword'])),
					
					'status'	=> 1,
					
					'created' => date("Y-m-d H:i:s")	
							
				);
				// If user has been created successfully, it returns true otherwise false. 			
				if($objOpr->saveUser($userData)) {
					echo "true";
				} else {
					echo "false";
				}	
			break;
			
			case 'login':
			
				$username = $_POST['username'];
				$password = $_POST['password'];						
				
				if($objOpr->logIn($username, $password)) {
					//Get all notes of this user after authentication.
					if($objOprNote->getNotes($_SESSION['userID']))
						echo json_encode($objOprNote->getNotes($_SESSION['userID']));
					else
						echo "Record not available";
					
				} else {
					echo "false";
				}
			
			break;
			
			case 'saveNote':

				$noteData = array(
					
					'userid' => $_SESSION['userID'],
			
					'note' => strip_tags($_POST['note']),
					
					'created' => date("Y-m-d H:i:s")	
							
				);
				
				if($objOprNote->saveNote($noteData)) {
					echo "true";
				} else {
					echo "false";
				}
			break;						
			
			case 'getLastNote':
			
				echo json_encode($objOprNote->getLastNote($_SESSION['userID']));
			
			break;
			
			case 'deleteNote':
			
				if($objOprNote->deleteNote($_POST['id'])) 
					echo 'true';
				else
					echo 'false';
			break;
			
			default:
			
				$objOpr->logOut();	
			
			break;
		}
		
		
	}
	
	