<?php
class AI_Chatbot_Model {
    private $table_name;

    public function __construct() {
        global $wpdb;
        $this->table_name = $wpdb->prefix . 'ai_chatbot_messages';

        // Datenbanktabelle erstellen, falls sie nicht existiert
        $this->create_table();
    }

    private function create_table() {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE IF NOT EXISTS {$this->table_name} (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_message TEXT NOT NULL,
            bot_response TEXT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }

    public function save_message($user_message, $bot_response) {
        global $wpdb;
        $wpdb->insert($this->table_name, [
            'user_message' => sanitize_text_field($user_message),
            'bot_response' => sanitize_text_field($bot_response)
        ]);
    }

    public function get_messages() {
        global $wpdb;
        return $wpdb->get_results("SELECT * FROM {$this->table_name} ORDER BY created_at DESC");
    }
}

