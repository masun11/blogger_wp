<?php
  if(is_user_logged_in()) {
		header("Location:" . get_dashboard_url());
		exit();
	}
	$blogger_login = (isset($_POST['blogger_login']))? $_POST['blogger_login'] : '' ;
	$blogger_email = (isset($_POST['blogger_email']))? $_POST['blogger_email'] : '' ;
	$blogger_pass = (isset($_POST['blogger_pass']))? $_POST['blogger_pass'] : '' ;
	get_header(); 
?>

<div class="blogger_bg_div">
	<div class="blogger_inner_div">

		<h2 class="blogger_heading"><?php _e( 'Information needed' ); ?></h2>
		<p class="blogger_para"><?php _e( 'Please provide the following information. Don&#8217;t worry, you can always change these settings later.' ); ?></p>

		<form id="blogger_form" method="post" novalidate="novalidate">
			<table id="blogger-form-table" role="presentation">
				<tr>
					<th scope="row"><label for="user_login">Username</label></th>
					<td>
						<input name="blogger_login" type="text" id="blogger_login" size="25" value="<?=$blogger_login?>"/>
						<p class="blogger_para"><?php _e( 'Usernames can have only alphanumeric characters, spaces, underscores, hyphens, periods, and the @ symbol.' ); ?></p>
					</td>
				</tr>
				<?php if ( ! $user_table ) : ?>
				<tr class="form-field form-required user-pass1-wrap">
					<th scope="row"> <label for="blogger_pass"> <?php _e( 'Password' ); ?> </label> </th>
					<td>
						<div class="wp-pwd">
							<input type="password" name="blogger_pass" id="blogger_pass" autocomplete="off" value="<?=$blogger_pass?>"/>
						</div>
						<p class="blogger_para">
						<strong><?php _e( 'Important:' ); ?></strong>
						<?php _e( 'You will need this password to log&nbsp;in. Please store it in a secure location.' ); ?></p>
					</td>
				</tr>
				<?php endif; ?>
				<tr>
					<th scope="row"><label for="blogger_email"><?php _e( 'Your Email' ); ?></label></th>
					<td><input name="blogger_email" type="email" id="blogger_email" size="25" value="<?=$blogger_email?>"/>
					<p class="blogger_para"><?php _e( 'Double-check your email address before continuing.' ); ?></p></td>
				</tr>
			</table>
			<?php if($userExists != ''):?>
				<span class="blogValerror"><?php echo $userExists;?></span>
			<?php endif;?>
			
			<input type="Submit" name="add_new_blogger" value="Submit" id="blogger_submit_btn" />
		</form>
	</div>
</div>

<?php get_footer(); ?>
