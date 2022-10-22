<?php
if (!defined('WPINC')) { die; } // Abort if accessed directly

/**
 * Hooks affecting Admin pages
 */
add_action('admin_enqueue_scripts', 'h_api_admin_enqueue');

/**
 * @action admin_enqueue_scripts
 */
function h_api_admin_enqueue() {
  wp_enqueue_style('h-api-admin' , H_API_ASSETS . '/admin.css', [], H_API_VERSION);
  wp_enqueue_script('h-api-admin', H_API_ASSETS . '/admin.js', [], H_API_VERSION, true);
}
