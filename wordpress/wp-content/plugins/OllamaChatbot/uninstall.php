<?php
// Sicherheitscheck: Wird das Skript direkt aufgerufen?
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

global $wpdb;
$table_name = $wpdb->prefix . 'ai_chatbot_messages';

// **1️⃣ Datenbank-Tabelle löschen**
$wpdb->query("DROP TABLE IF EXISTS {$table_name}");

// **2️⃣ Optionen löschen (falls du welche speicherst)**
delete_option('ai_chatbot_settings');

