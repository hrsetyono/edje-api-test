<?php

add_action('rest_api_init', 'h_init_api_forgot_password');

function h_init_api_forgot_password() {
  $namespace = 'custom/v1';

  /**
   * Request forgot password email
   */
  register_rest_route($namespace, '/password/forgot', [
    'methods' => 'POST',
    'permission_callback' => '__return_true',
    'callback' => function($request) {
      $params = $request->get_params();
      $params = wp_parse_args($params, [
        'email' => '',
        'reset_url' => '',
      ]);

      if (!is_email($params['email'])) {
        return new WP_Error('email_invalid', __('Email is invalid'));
      }

      // If URL is invalid
      if (!filter_var($params['reset_url'], FILTER_VALIDATE_URL)) {
        return new WP_Error('reset_url_invalid', __('Please refresh the page and try again'));
      }

      $user = get_user_by('email', $params['email']);

      if (!$user) {
        // @warn - Better if we mask the error code as "If your email is registered, you will get email from us"
        return new WP_Error('user_not_found', __('There is no User with that email'));
      }

      $key = get_password_reset_key($user);
      $email_title = get_bloginfo('name') . ' - Password Reset';
      $email_body = "Hello {$user->display_name},
      
Someone has requested a password reset for the following account:

    Email Address: {$user->user_email}

If this was a mistake, ignore this email and nothing will happen.

To reset your password, visit the following address:
{$params['reset_url']}?key={$key}&username={$user->user_login}

Thank you";
    
      $is_success = wp_mail($user->user_email, $email_title, $email_body);
      
      if (!$is_success) {
        return new WP_Error('mail_not_sent', __('There is a problem with our mailing server. Please try again later'));
      }

      return [
        'message' => __('Thank you. If your email is registered with us, you will receive an email with instruction to reset password.'),
      ];
    }
  ]);

  /**
   * Reset Password
   */
  register_rest_route($namespace, '/password/reset', [
    'methods' => 'POST',
    'permission_callback' => '__return_true',
    'callback' => function($request) {
      $params = $request->get_params();
      $params = wp_parse_args($params, [
        'user_pass' => '',
        'user_pass_confirm' => '',
        'key' => '',
        'username' => '',
      ]);

      if (strlen($params['user_pass']) < 6) {
        return new WP_Error('user_pass', __('Password should be at least 6 characters'));
      }

      if ($params['user_pass'] !== $params['user_pass_confirm']) {
        return new WP_Error('user_pass_confirm', __('Your password confirmation is different'));
      }

      $is_key_valid = check_password_reset_key($params['key'], $params['username']);
      if (is_wp_error($is_key_valid)) {
        return new WP_Error('invalid_key', __('Your Reset request has expired. Please fill the Forgot Password form again'));
      }

      // update password
      $user = get_user_by('login', $params['username']);
      reset_password($user, $params['user_pass']);

      return [
        'message' => __('Success! You can now login with the new password'),
      ];
    },
  ]);
}