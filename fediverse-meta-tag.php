<?php
/*
Plugin Name: Fediverse Meta Tag
Description: Adds a custom “fediverse:creator” metatag.
Version: 1.0.0
Author: Michał Stankiewicz
Author URI: https://www.stankiewiczm.eu
*/

function add_fediverse_creator_meta_box() {
    add_meta_box(
        'fediverse_creator_meta_box',
        'Fediverse Creator Tag',
        'fediverse_creator_meta_box_callback',
        'post',
        'side'
    );
}
add_action('add_meta_boxes', 'add_fediverse_creator_meta_box');

function fediverse_creator_meta_box_callback($post) {
    $fediverse_creator = get_post_meta($post->ID, '_fediverse_creator', true);
    ?>
    <label for="fediverse_creator_field">Fediverse Creator Tag:</label>
    <input type="text" id="fediverse_creator_field" name="fediverse_creator_field" value="<?php echo esc_attr($fediverse_creator); ?>" placeholder="user@example.com" style="width:100%;" />
    <?php
}

function save_fediverse_creator_meta_box_data($post_id) {
    if (array_key_exists('fediverse_creator_field', $_POST)) {
        update_post_meta(
            $post_id,
            '_fediverse_creator',
            sanitize_text_field($_POST['fediverse_creator_field'])
        );
    }
}
add_action('save_post', 'save_fediverse_creator_meta_box_data');

function add_fediverse_creator_meta_tag() {
    global $post;

    if (is_single()) {
        $fediverse_creator = get_post_meta($post->ID, '_fediverse_creator', true);

        // FOR BLOG POSTS
        if (empty($fediverse_creator)) {
            $author_id = $post->post_author;
            $author_username = get_the_author_meta('user_login', $author_id);
            
            if ($author_username === 'user1') { // Change this to author's username
                $fediverse_creator = 'user1@example.com'; // Change this to author's Fediverse username
            } elseif ($author_username === 'user2') { // Change this to author's username
                $fediverse_creator = 'user2@example.com'; // Change this to author's Fediverse username
            } else {
                $fediverse_creator = 'blog@example.com'; // Change this to default Fediverse username (for example, your blog account)
            }
        }
    } else {
        // FOR PAGES AND OTHER POST TYPES
        $fediverse_creator = 'company@example.com'; // Change this to default Fediverse username (for example, your blog account)
    }

    if ($fediverse_creator) {
        echo '<meta name="fediverse:creator" content="' . esc_attr($fediverse_creator) . '">';
    }
}
add_action('wp_head', 'add_fediverse_creator_meta_tag');
