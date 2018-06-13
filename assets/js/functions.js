$(document).ready(function(){
	
	$('.dvNote:even').css("background-color","#4697e426"); // Took help from https://stackoverflow.com/questions/17091513/even-odd-table-row-styles-not-working-jquery
	$('.dvNote:odd').css("background-color","#7ac9b71a");
	
	/*
	*	Following event triggers on logout link click.
	*	Logout request goes to process.php through ajax call.
	*	On successful operation redirects to home. 
	*/
	$('#logout').on('click', function(e) {
		
		e.preventDefault();
		
		var mydata = {																		
					
			action: 'logout',
			
			is_ajax: 1	
		
		};

		$.ajax({
					
			type: "POST",			
			
			url: "process.php",
			
			data: mydata,						
			
			success: function(response) { 	
				
				window.location.href = "index.php";
				
			},
			error: function (err) { 
				
				alert(err);
				
				return false;
			}
		});
		
	});
	
	/*
	*	Following event triggers on form submission.
	*	There are three forms, user registration form, login form and note form. 
	*	After detecting event source, relevant function is called.
	*/
	
	$('form').on('submit', function(e) {
		
		e.preventDefault();
		
		var clickedForm = $(this).closest("form").attr('id'); // Find the source of event.				
		
		if(clickedForm === 'formRegister') { // If this is triggered by create new user form submit
			
			getRegister();
			
		} else if(clickedForm === 'formLogin') { // If this is triggered by login form
			
			getLogin();
			
		} else { // If this is triggered by save note form
			
			saveNote();
		}
				
	});
	
	/*
	*	Following event triggers on delete note link click.
	*	Delete note request along with noteID goes to process.php through ajax call. 
	*	After successful deletion from database, respective note is removed from front-end as well. 
	*/
	
	$(document).on('click', '.deleteNote', function(){ //https://stackoverflow.com/questions/39608567/why-jquery-does-not-work-for-new-element-created-in-ajax-success
		
		if(confirm("Do you really want to delete this note") == false) {
			
			return false;
		}	
		
		// https://stackoverflow.com/questions/19114688/remove-closest-li-after-ajax-success	
		var $clickedElement = $(this); // <- save the clicked button to a variable
		
		var mydata = {																		
					
			id: $(this).attr('id'),
			
			action: 'deleteNote',
			
			is_ajax: 1	
		
		};
		
		$.ajax({
					
			type: "POST",			
			
			url: "process.php",
			
			data: mydata,						
			
			success: function(response) { 	
				
				if(response == 'true') {
					
					$clickedElement.closest('div').fadeOut("10000", function() {
						$(this).remove();
					});
							
				}
				
			},
			error: function (err) { 
				
				alert(err);
				
				return false;
			}
		});
		
		
	});
	
}); // End of ready function

/*
*	Following function is called on the request of new user creation.
*	It gathers user information from registration form and sends to process.php with 'register' request through ajax call.
*	process.php performs operation accordingly and returns true on successful operation and false otherwise.
*	On successful operation login form is prompted with a successful user creation message. User needs to get login after creation. 
*/
function getRegister() {
	
	var name = $("input[name=name]").val().trim();
	var email = $("input[name=email]").val().trim();
	var pas = $("input[name=password]").val().trim();
	var confirmPas = $("input[name=confirmPassword]").val().trim();
	
	if(pas != confirmPas) { // If password doesn't match with confirm password
		
		alert("Password dosn't match with Confirm Password");
		
		$("input[name=password]").val('').focus();
		$("input[name=confirmPassword]").val('');
		
		return false;
	}
	
	var mydata = {												
			
		userName: name,
		userEmail: email,
		userPassword: pas,		
		
		action: 'register',
		
		is_ajax: 1	
	
	};
	
	$.ajax({
				
		type: "POST",			
		
		url: "process.php",
		
		data: mydata,						
		
		success: function(response) { 	
			
			if(response === 'true') {
				
				$('#registration').hide();
				
				$('#loginmsg').html('');
				
				$("input[name=loginEmail]").val('').focus();
				
				$("input[name=loginPassword]").val('');
				
				$('#login').css("float", "none"); // Remove float from login section to display in the middle of page.
				
				$('p[name=lblmessage]').html('User created successfully. Get logged in to get access to note system');
					
			} else {
				
				alert('A problem accored, please try again later');
				return false;
			}
				
			
		},
		error: function (err) { 
			
			alert(err);
			
			return false;
		}
	});
	
}

/*
*	Following function is called on the login.
*	It takes username and password from user and sends to process.php with 'login' request through ajax call.
*	process.php authenticates the user and if its valid user it fetches and returns user notes.
*	On successful operation loadNotes function is called. 
*/

function getLogin() {
	
	var mydata = {												
			
		username: $("input[name=loginEmail]").val().trim(),
		password: $("input[name=loginPassword]").val().trim(),
		
		action: 'login',
		
		is_ajax: 1	
	
	};

	$.ajax({
				
		type: "POST",			
		
		url: "process.php",
		
		data: mydata,						
		
		success: loadNotes,
		error: function (err) { 
			
			alert(err);
			
			return false;
		}
	});
	
}

/*
*	Following function is called on successful login operation.
*	If it receives false from process.php then it just prints error message.
*	If it doesn't receive false that means this user is valid, then it receives all notes of given user.
*	It receives data in json encoded form, which is being parsed here and then print all the fetched notes. 
*/

function loadNotes(response) {
	
	if(response === 'false') {
		
		$('#loginmsg').html('Wrong credentials');				
		
	} else {
		
		$("#notes").show(); // show note form
		
		$("#authentication").hide(); // Hide registration form.
		
		var jsobj = JSON.parse(response, function (key, value) { 
														   
			var type;
			
			if (value && typeof value === 'object') {
				
				type = value.type;
				
				if (typeof type === 'string' && typeof window[type] === 'function') {
					
					return new (window[type])(value);
					
				}
			}
			
			return value;
		});
		
		var str = "";
		var a = 1;
		
		for (var i = 0; i < jsobj.length; i++) {
			
			str += "<div class='dvNote' data-date='"+jsobj[i].created+"'>";
			str += "<p>Created on "+jsobj[i].created+"</p>";	
			str += "<label>"+jsobj[i].note+"</label> <br />";
			str += "<a href='#' class='deleteNote' id='"+jsobj[i].id+"'>Delete</a>";
			str += "</div>";	
			a++;
		}
		
		$("#prevNotes").html(str);
		
		$('.dvNote:even').css("background-color","#4697e426");	
		$('.dvNote:odd').css("background-color","#7ac9b71a");
	}
		
}

/*
*	Following function is called on save note form submission.
*	It takes note data from and passes it along with 'saveNote' request to process.php through ajax call.
*	process.php performs save operation and returns true or false accordingly.
*	On success displayLastNote function is called.
*	On failure error message is alert.
*/

function saveNote() {		
	
	var mydata = {												
			
		note: $("textarea[name=note]").val(),
				
		action: 'saveNote',
		
		is_ajax: 1	
	
	};

	$.ajax({
				
		type: "POST",			
		
		url: "process.php",
		
		data: mydata,						
		
		success: displayLastNote,
		error: function (err) { 
			
			alert(err);
			
			return false;
		}
	});
	
}

/*
*	Following function is called on success of saveNote function.
*	If saveNote function gets 'true', this function clears the note field and sends 'getLastNote' request to process.php through ajax.
*	on success it receives required record in json encoded form. 
*	It parses that and performs sorting to print last inserted note at top.
*	On failure error message is alert.
*/

function displayLastNote(response) {
	
	if(response === 'true') {
		
		$('textarea[name=note]').val('');
		
		var mydata = {												
					
			action: 'getLastNote',
			
			is_ajax: 1	
		
		};

		$.ajax({
					
			type: "POST",			
			
			url: "process.php",
			
			data: mydata,						
			
			success: function(response) {
				
				var jsobj = JSON.parse(response, function (key, value) { 
														   
				var type;
				
				if (value && typeof value === 'object') {
					
					type = value.type;
					
					if (typeof type === 'string' && typeof window[type] === 'function') {
						
						return new (window[type])(value);
						
					}
				}
				
				return value;
			});
			
			var str = "";
			
			for (var i = 0; i < jsobj.length; i++) {
				
				str += "<div class='dvNote' data-date='"+jsobj[i].created+"'>";	
				str += "<p>Created on "+jsobj[i].created+"</p>";	
				str += "<label>"+jsobj[i].note+"</label> <br />";
				str += "<a href='#' class='deleteNote' id='"+jsobj[i].id+"'>Delete</a>";
				str += "</div>";	
				
			}
			
			$("#prevNotes").fadeIn("slow", function() {
				$(this).append(str);
			});
			//$("#prevNotes").append(str);	

			$('.dvNote:even').css("background-color","#4697e426");	
			$('.dvNote:odd').css("background-color","#7ac9b71a");	
			
			// Sorting
			//https://stackoverflow.com/questions/38979585/sort-divs-by-date-time-with-js
			var reverse = true; // ascending/descending flag
			var board = $("#prevNotes");
			var boards = board.children('.dvNote');
			var orderedBoards = boards.slice().sort(function (elem1, elem2) {
			  var value1 = new Date($(elem1).data("date")).getTime(),
				  value2 = new Date($(elem2).data("date")).getTime();
			  if (reverse) {
				// descending
				return -(value1 > value2) || +(value1 < value2) || (isNaN(value1)) - (isNaN(value2));
			  }
			  // ascending
			  return +(value1 > value2) || -(value1 < value2) || (isNaN(value1)) - (isNaN(value2));
			});
			board.empty().append(orderedBoards);
			
			},
			error: function (err) { 
				
				alert(err);
				
				return false;
			}
		});
		
	}
	
}

