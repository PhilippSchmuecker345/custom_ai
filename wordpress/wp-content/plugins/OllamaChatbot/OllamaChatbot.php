<?php
/*
Plugin Name: AI Chatbot mit Ollama
Plugin URI: https://example.com
Description: Ein WordPress-Chatbot mit LLaMA 3 (Ollama) im Docker-Container.
Version: 1.0
Author: Dein Name
Author URI: https://example.com
License: GPL2
*/

if (!defined('ABSPATH')) {
    exit; // Sicherheitscheck
}

// MVC-Klassen laden
require_once plugin_dir_path(__FILE__) . 'includes/class-controller.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-model.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-view.php';

// Plugin initialisieren
function ai_chatbot_init() {
    new AI_Chatbot_Controller();
}
add_action('plugins_loaded', 'ai_chatbot_init');

