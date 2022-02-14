<?php
/**
 * Plugin Name:       Post Reading Time Block
 * Description:       A block to render the estimated reading time of a post.
 * Plugin URI:        https://richtabor.com/reading-time-block?utm_source=wp-plugins&utm_medium=post-reading-time-block&utm_campaign=plugin-uri
 * Requires at least: 5.7
 * Requires PHP:      7.0
 * Version:           0.0.0
 * Author:            RichTabor.com
 * Author URI:        https://richtabor.com/?utm_source=wp-plugins&utm_medium=post-reading-time-block&utm_campaign=author-uri
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       post-reading-time-block
 *
 * @package           tabor/post-reading-time-block
 */

/**
 * Registers the block using the metadata loaded from the `block.json` file.
 * Behind the scenes, it registers also all assets so they can be enqueued
 * through the block editor in the corresponding context.
 *
 * @see https://developer.wordpress.org/block-editor/tutorials/block-tutorial/writing-your-first-block-type/
 */
function tabor_post_reading_time_block_init() {
	register_block_type_from_metadata( __DIR__, array(
		'render_callback' => 'tabor_post_reading_time_block_render',
	) );
}
add_action( 'init', 'tabor_post_reading_time_block_init' );

/**
 * Renders the `tabor/post-reading-time` block on the server.
 *
 * @param array    $attributes Block attributes.
 * @param string   $content    Block default content.
 * @param WP_Block $block      Block instance.
 * @return string Returns the filtered post date for the current post wrapped inside "time" tags.
 */
function tabor_post_reading_time_block_render( $attributes, $content, $block ) {

	if ( ! isset( $block->context['postId'] ) ) {
		return;
	}

	$post_ID = $block->context['postId'];
	$align_class_name   = empty( $attributes['textAlign'] ) ? '' : "has-text-align-{$attributes['textAlign']}";
	$wrapper_attributes = get_block_wrapper_attributes( array( 'class' => $align_class_name ) );

	$content     = get_post_field( 'post_content', $post_ID );
	$word_count  = str_word_count( strip_tags( $content ) );
	$readingtime = ceil( $word_count / 200 );

	$text = __( 'min read', 'post-reading-time-block' );
	$totalreadingtime = $readingtime . ' ' . $text;

	return sprintf(
		'<div %1$s><span>%2$s</span></div>',
		$wrapper_attributes,
		esc_html( $totalreadingtime ),
	);
}

