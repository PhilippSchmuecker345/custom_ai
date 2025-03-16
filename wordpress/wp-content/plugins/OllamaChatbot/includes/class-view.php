<?php
class AI_Chatbot_View {
    public function __construct() {
        add_shortcode('ai_chatbot', [$this, 'render_chatbox']);
        add_action('wp_enqueue_scripts', [$this, 'load_assets']);
    }

    public function render_chatbox() {
        ob_start();
        include plugin_dir_path(__FILE__) . '../templates/chatbox.php';
        return ob_get_clean();
    }

    public function load_assets() {
        wp_enqueue_style('ai-chatbot-style', plugin_dir_url(__FILE__) . '../assets/style.css');
        wp_enqueue_script('ai-chatbot-script', plugin_dir_url(__FILE__) . '../assets/script.js', ['jquery'], null, true);
        wp_localize_script('ai-chatbot-script', 'aiChatbotAjax', ['ajaxurl' => admin_url('admin-ajax.php')]);
    }
}
new AI_Chatbot_View();

