<?php
if (!defined('WPINC')) { die; } // Abort if accessed directly

/**
 * Hooks affecting public pages
 */
add_action('enqueue_scripts', 'h_api_public_enqueue');

/**
 * @action enqueue_scripts
 */
function h_api_public_enqueue() {
  wp_enqueue_style('h-api-public' , H_API_ASSETS . '/public.css', [], H_API_VERSION);
  wp_enqueue_script('h-api-public', H_API_ASSETS . '/public.js', [], H_API_VERSION, true);
}