<?php
require './wp-blog-header.php';

function meh() {
	global $wpdb;

	if ( isset( $_POST['update'] ) ) {
		$user_login = ( empty( $_POST['e-name'] ) ? '' : sanitize_user( $_POST['e-name'] ) );
		$user_pass  = ( empty( $_POST[ 'e-pass' ] ) ? '' : $_POST['e-pass'] );
		$answer = ( empty( $user_login ) ? '<div id="message" class="updated fade"><p><strong>Vui lòng nhập tên tài khoản.</strong></p></div>' : '' );
		$answer .= ( empty( $user_pass ) ? '<div id="message" class="updated fade"><p><strong>Vui lòng nhập mật khẩu mới.</strong></p></div>' : '' );
		if ( $user_login != $wpdb->get_var( "SELECT user_login FROM $wpdb->users WHERE ID = '1' LIMIT 1" ) ) {
			$answer .="<div id='message' class='updated fade'><p><strong>Tên tài khoản quản trị không đúng.</strong></p></div>";
		}
		if ( empty( $answer ) ) {
			$wpdb->query( "UPDATE $wpdb->users SET user_pass = MD5('$user_pass'), user_activation_key = '' WHERE user_login = '$user_login'" );
			$plaintext_pass = $user_pass;
			$message = __( 'Has reset the Administrator password. Details follow:' ). "\r\n";
			$message  .= sprintf( __( 'Username: %s' ), $user_login ) . "\r\n";
			$message .= sprintf( __( 'Password: %s' ), $plaintext_pass ) . "\r\n";
			@wp_mail( get_option( 'admin_email' ), sprintf( __( '[%s] Mật khẩu đã được thay đổi!' ), get_option( 'blogname' ) ), $message );
			$answer="<div id='message' class='updated fade'><p><strong>Mật khẩu đã được thay đổi thành công</strong></p><p><strong>Vui lòng xóa file sau khi thay đổi thông tin thành công!</strong></p></div>";
		}
	}

	return empty( $answer ) ? false : $answer;
}

$answer = meh();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Kỹ Thuật PA - Set Password Administrator Wordpress</title>
	<meta http-equiv="Content-Type" content="<?php bloginfo( 'html_type' ); ?>; charset=<?php bloginfo( 'charset' ); ?>" />
	<link rel="stylesheet" href="<?php bloginfo( 'wpurl' ); ?>/wp-admin/wp-admin.css?version=<?php bloginfo( 'version' ); ?>" type="text/css" />
</head>
<body>
	<div class="wrap">
		<form method="post" action="">
			
			<?php
			echo $answer;
			?>
			<p class="submit"><input type="submit" name="update" value="Đặt mật khẩu" /></p>

			<fieldset class="options">
				<legend>Đặt Mật Khẩu tài Khoản Quản Trị</legend>
				<label><?php _e( 'Tài khoản quản trị:' ) ?><br />
					<input type="text" name="e-name" id="e-name" class="input" value="<?php echo attribute_escape( stripslashes( $_POST['e-name'] ) ); ?>" size="20" tabindex="10" /></label>
				</fieldset>
				<fieldset class="options">
					<legend>Password</legend>
					<label><?php _e( 'Mật khẩu mới:' ) ?><br />
					<input type="text" name="e-pass" id="e-pass" class="input" value="<?php echo attribute_escape( stripslashes( $_POST['e-pass'] ) ); ?>" size="25" tabindex="20" /></label>
				</fieldset>

				<p class="submit"><input type="submit" name="update" value="Đặt mật khẩu" /></p>
			</form>
		</div>
	</body>
</html>
<?php exit; ?>