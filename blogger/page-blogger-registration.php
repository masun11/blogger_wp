<?php
  if(is_user_logged_in()) {
		header("Location:" . get_dashboard_url());
		exit();
	}
	$blogger_login = (isset($_POST['blogger_login']))? $_POST['blogger_login'] : '' ;
	$blogger_email = (isset($_POST['blogger_email']))? $_POST['blogger_email'] : '' ;
	$blogger_pass = (isset($_POST['blogger_pass']))? $_POST['blogger_pass'] : '' ;
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta name="viewport" content="width=device-width" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="robots" content="noindex,nofollow" />
	<title><?php _e( 'User Registration' ); ?></title>
	<?php wp_head(); ?>
</head>
<body class="wp-core-ui<?php echo $body_classes; ?>">


<h2><?php _e( 'Information needed' ); ?></h2>
<p><?php _e( 'Please provide the following information. Don&#8217;t worry, you can always change these settings later.' ); ?></p>

<form id="blogger_form" method="post" novalidate="novalidate">
	<table class="form-table" role="presentation">
		<tr>
			<th scope="row"><label for="user_login"><?php _e( 'Username' ); ?></label></th>
			<td>
				<input name="blogger_login" type="text" id="blogger_login" size="25" value="<?=$blogger_login?>" />
				<p><?php _e( 'Usernames can have only alphanumeric characters, spaces, underscores, hyphens, periods, and the @ symbol.' ); ?></p>
			</td>
		</tr>
		<?php if ( ! $user_table ) : ?>
		<tr class="form-field form-required user-pass1-wrap">
			<th scope="row"> <label for="blogger_pass"> <?php _e( 'Password' ); ?> </label> </th>
			<td>
				<div class="wp-pwd">
					<input type="password" name="blogger_pass" id="blogger_pass" class="regular-text" autocomplete="off" value="<?=$blogger_pass?>" />
				</div>
				<p><span class="description important hide-if-no-js">
				<strong><?php _e( 'Important:' ); ?></strong>
				<?php _e( 'You will need this password to log&nbsp;in. Please store it in a secure location.' ); ?></span></p>
			</td>
		</tr>
		<?php endif; ?>
		<tr>
			<th scope="row"><label for="blogger_email"><?php _e( 'Your Email' ); ?></label></th>
			<td><input name="blogger_email" type="email" id="blogger_email" size="25" value="<?=$blogger_email?>" />
			<p><?php _e( 'Double-check your email address before continuing.' ); ?></p></td>
		</tr>
	</table>
	<?php if($userExists != ''):?>
		<span class="blogValerror"><?php echo $userExists;?></span>
	<?php endif;?>
	
	<input class="button button-large" type="Submit" name="add_new_blogger" value="Submit" />
</form>
<?php wp_footer(); ?>
<script type="text/javascript">


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



</script>

</body>
</html>
