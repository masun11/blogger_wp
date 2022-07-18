
jQuery(document).ready (function () {  
 jQuery('#blogger_form').submit(function (e) {  
    // e.preventDefault();
    let a = true;
		let login = jQuery("#blogger_login").val();
		let pass = jQuery("#blogger_pass").val();
		let email = jQuery("#blogger_email").val();
		// alert('login: ' + login + ', pass: ' + pass + ', email: ' + email );
  	jQuery(".blogValerror").remove();  

		if (login.length < 1) {  
			a = false;
      jQuery('#blogger_login').after('<span class="blogValerror">Username is required</span>');  
    }  else {
    	var regex_login = /^[a-zA-Z][a-zA-Z0-9._\-@ ]{1,29}$/;
      var validLogin = regex_login.test(login);  
	    if (!validLogin) {  
	    	a = false;
	      jQuery('#blogger_login').after('<span class="blogValerror">Enter a valid username in between 2 to 30 characters</span>');  
	    }  
    }

    if (email.length < 1) {  
    	a = false;
      jQuery('#blogger_email').after('<span class="blogValerror">Email is required</span>');  
    } else {  
      var regEx = /^[a-zA-Z0-9][a-zA-Z0-9._%+-]{0,63}@(?:[a-zA-Z0-9-]{1,63}\.){1,125}[a-zA-Z]{2,63}$/;  
      var validEmail = regEx.test(email);  
	    if (!validEmail) {  
	    	a = false;
	      jQuery('#blogger_email').after('<span class="blogValerror">Enter a valid email</span>');  
	    }  
    }  

    if (pass.length < 1) {  
    	a = false;
      jQuery('#blogger_pass').after('<span class="blogValerror">Password required</span>');  
    }  else {
    	var strongPass = new RegExp("^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#\$%\^&\*])(?=.{8,})");
      var validPass = strongPass.test(pass);  
	    if (!validPass) { 
	    a = false; 
	      jQuery('#blogger_pass').after('<span class="blogValerror">Password must contain at least 1 lowercase alphabetical character,  at least 1 uppercase alphabetical character, at least 1 numeric character, at least one special character !@#$%^&* and must be six characters or longer</span>');  
	    }  
    }
    return a;

  });  
});  
