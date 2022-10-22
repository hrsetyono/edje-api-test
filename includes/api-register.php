<?php
add_action('rest_api_init', 'h_init_api_register');

function h_init_api_register() {
  $namespace = 'h/v1';

  register_rest_route($namespace, '/register/nonce', [
    'methods' => 'GET',
    'permission_callback' => '__return_true',
    'callback' => function($request) {
      $timestamp = current_time('U');
      $nonce = wp_create_nonce("my_register_{$timestamp}");
      return [
        'timestamp' => $timestamp,
        'nonce' => $nonce,
      ];
    }
  ]);

  register_rest_route($namespace, '/register', [
    'methods' => 'POST',
    'permission_callback' => '__return_true',
    'callback' => function($request) {
      $params = $request->get_params();      
      $params = wp_parse_args($params, [
        'first_name' => '',
        'last_name' => '',
        'user_email' => '',
        'user_pass' => '',
        'user_pass_confirm' => null,
        
        'acf_gender' => '', // If you have ACF field, prefix it with "acf_"
        'acf_job' => '',
        'timestamp' => null,
        '_wpnonce' => null,
      ]);

      // If $params has invalid nonce, reject the request
      if (!wp_verify_nonce($params['_wpnonce'], "my_register_{$params['timestamp']}")) {
        return new WP_Error('invalid_request', 'Invalid registration request, please refresh your page.');
      }

      if (!$params['user_email']) {
        return new WP_Error('user_email', 'Email address cannot be empty');
      }
      
      if (email_exists($params['user_email'])) {
        return new WP_Error('user_email', 'Your email is already registered');
      }

      if (strlen($params['user_pass']) < 6) {
        return new WP_Error('user_pass', 'Password should be at least 6 characters');
      }

      if ($params['user_pass'] !== $params['user_pass_confirm']) {
        return new WP_Error('user_pass_confirm', 'Your password confirmation is different');
      }


      // Auto generate username
      $username = sanitize_user($params['first_name']);
      $count = null;
      while (username_exists($username . $count)) {
        $count += 1;
      }
      $params['user_login'] = $username . $count;
      
      // Add other data
      $params['display_name'] = $params['first_name'] . ' ' . $params['last_name'];

      $user_id = wp_insert_user($params);

      if (is_wp_error($user_id)) {
        return $user_id;
      }

      // Update ACF field
      foreach ($params as $key => $value) {
        if (str_contains($key, 'acf')) {
          $field = str_replace('acf_', '', $key);
          update_field($field, $value, "user_{$user_id}");
        }
      }

      return $user_id;
    }
  ]);
}