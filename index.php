<?php
	
	/*
	* Author: Iftikhar Ahmed
	* 
	* Date: 06/06/2018
	*
	* @desc: This is entry point and contains different forms that are controlled and displayed through jquery.
	*
	* @required: noteoperations.php. 
	*
	
	*/
	require_once('includes/noteoperations.php');
	
	$objOprNote = new Noteoperations();
	
	include('template/header.php');
					
?>

<header>
	<h2> Welcome to Jarral's Note System </h2>
</header>
<main style='text-align: center'>
	<?php 
	
		if(!isset($_SESSION['isLoggedIn'])) { 
		$strStyle = 'display: none';
	?>	
		
		<section id='authentication'>
			<!-- Registration Section Starts here -->
			<section id='registration'>
				<form id='formRegister' action='index.php' method='post' >
				<h2> User Registration Form </h2>
				
				<div>
					<input type="text" name="name" placeholder="Name" required="required" value="">			  
				</div>
			
				<div>
					<input type="email" name="email" placeholder="Email" required="required" value="">			  
				</div>
							
				<div>
				  <input type="password" name="password" placeholder="Password" required="">				  
				</div>
			
				<div>
				  <input type="password" name="confirmPassword" placeholder="Confirm password" required="required">				  
				</div>
				<div>
					<input type="submit" id="btnregister" value="Submit"/>
				</div>
				</form>
			</section>
			
			<!-- Login Section Starts here -->
			<section id='login'>
				<p name="lblmessage"></p>
				<form id='formLogin' method='post' action='index.php'>
				<h2> User Login </h2>
				<div>
					<input type="email" name="loginEmail" placeholder="Email" value="" required="required">			  
				</div>								
				<div>
				  <input type="password" name="loginPassword" placeholder="Password" required="required">				  
				</div>
				<div>
					<input type="submit" id="btnlogin"  value="Login"/>
				</div>
				<label id="loginmsg"></label>
				</form>
			</section>
		</section>
		
	<?php
		} else 
			$strStyle = 'display: block;';	
	?>	
	<!-- Notes Section Starts here -->	
	<section id='notes' style='<?php echo $strStyle; ?>'>
		<section id='addNotes'>
			<form method='post'>
			<div>
				<textarea name='note' rows="10" cols="50" placeholder="Type your message here..."></textarea>
			</div>
			
			<div>
				<input type="submit" id="btnnotes"  value="Send"/>
			</div>
			<a href='#' id='logout' style='color:red'> Logout </a>
			</form>
		</section>
		
		<!-- Previous Notes -->
		
		<section id='prevNotes'>
		
		<?php 
			if(isset($_SESSION['isLoggedIn'])) {
				$notes = $objOprNote->getNotes($_SESSION['userID']);
				if($notes) {
					foreach($notes as $note) {			
						echo "<div class='dvNote' data-date='".$note['created']."'>";
						echo "<p>Created on ".$note['created']."</p>";
						echo "<label>".$note['note']."</label><br /><a href='#' id='".$note['id']."' class='deleteNote'>Delete</a>";
						echo "</div>";
						
					}	
				}	
			}						
		
		?>
		
		</section>
	</section>
		
	
</main>

<?php
	
	include('template/footer.php');