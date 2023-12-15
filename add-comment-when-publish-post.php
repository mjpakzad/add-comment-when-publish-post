<?php
/**
 * Plugin Name: add-comment-when-publish-post
 * Plugin URI: https://github.com/mjpakzad/add-comment
 * Description: This plugin add a comment to every new published post.
 * Version: 0.1
 * Author: Mojtaba Pakzad
 * Author URI: https://mjpakzad.com/
 * License: GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

add_action('publish_post', 'add_comment_when_publish_post');

function add_comment_when_publish_post($post_id) {

    if (wp_is_post_revision($post_id) || wp_is_post_autosave($post_id)) {
        return;
    }
    $comment_contents = plugin_dir_path(__FILE__) . 'comment_contents.json';
    $comment_emails = plugin_dir_path(__FILE__) . 'comment_emails.json';
    $comment_first_names = plugin_dir_path(__FILE__) . 'comment_first_names.json';
    $comment_last_names = plugin_dir_path(__FILE__) . 'comment_last_names.json';

    if (!file_exists($comment_contents)) {
    	return;
    }
    if (!file_exists($comment_emails)) {
    	return;
    }
    if (!file_exists($comment_first_names)) {
    	return;
    }
    if (!file_exists($comment_last_names)) {
    	return;
    }
	$content_json = file_get_contents($comment_contents);
	$emails_json = file_get_contents($comment_emails);
	$first_name_json = file_get_contents($comment_first_names);
	$last_name_json = file_get_contents($comment_last_names);

	$contents = json_decode($content_json, true);
	$emails = json_decode($emails_json, true);
	$first_names = json_decode($first_name_json, true);
	$last_names = json_decode($last_name_json, true);

    if (!is_array($contents)) {
        return;
    }
    if (!is_array($emails)) {
        return;
    }
    if (!is_array($first_names)) {
        return;
    }
    if (!is_array($last_names)) {
        return;
    }

    $random_content = $contents[array_rand($contents)];
    $random_email = $emails[array_rand($emails)];
    $random_first_name = $first_names[array_rand($first_names)];
    $random_last_name = $last_names[array_rand($last_names)];

    $comment_data = [
        'comment_post_ID' => $post_id,
        'comment_author' => $random_first_name . ' ' . $random_last_name,
        'comment_author_email' => $random_email,
        'comment_content' => $random_content,
        'comment_type' => '',
        'comment_parent' => 0,
    ];

    wp_insert_comment($comment_data);
}
