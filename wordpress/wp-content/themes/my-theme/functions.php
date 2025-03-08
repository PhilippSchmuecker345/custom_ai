<?php
include_once 'mvc/models/OllamaModel.php';
include_once 'mvc/views/PostView.php';
include_once 'mvc/controllers/PostController.php';

// Beispiel für das Abrufen der Posts:
$postController = new PostController();

// Display All Posts
add_shortcode('all_posts', function() use ($postController) {
    ob_start();
    $postController->displayPosts();
    return ob_get_clean();
});

// Display Single Post
add_shortcode('single_post', function($atts) use ($postController) {
    $atts = shortcode_atts(array(
        'id' => 1,
    ), $atts);
    ob_start();
    $postController->displaySinglePost($atts['id']);
    return ob_get_clean();
});

?>
