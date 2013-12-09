<?php
/**
 	* Post Meta Boxes
 	*
 	* @package     Cool Carousel
 	* @subpackage  section.php
 	* @since       1.3.3
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

add_filter( 'cmb_meta_boxes', 'cool_carousel_metaboxes' );
function cool_carousel_metaboxes( array $meta_boxes ) {

	$mediasizesmanual = array('full');
	$mediasizesauto = get_intermediate_image_sizes();
	$mediasizes = array_merge($mediasizesmanual, $mediasizesauto);
	$mediasizes = array_combine($mediasizes,$mediasizes); //this will change the key index with its value for every item

	$opts = array(
		array(
			'id' => 'coolcarousel_directions',
			'name' => '',
			'type' => 'title',
			'desc'	=> __('<strong style="display:block;font-size:16px;color:#eaeaea;text-shadow:0 1px 0 black;padding:2px 2px 1px;background:#333;margin-top:2px;border-radius:2px;border:1px solid white;letter-spacing:0.1em;box-shadow:inset 0 0 3px black;">HOW TO USE:</strong><br/>&bull; Enter EITHER an image OR a single video field OR custom HTML.<br/>
						&bull; If you enter more than one, they will be in this priority: Image (Manual), Image (Media Library), Video, Custom HTML.<br/>
						&bull; <strong>All images in the same set/category should have the same <em>width</em>.</strong><br/>
						&bull; Consider naming your sets/categories with a <em>width</em> indicator, like "Favorite Images (600w)".<br/>
						&bull; Videos will be responsive.<br/>&bull; Custom HTML will not be filtered or escaped so make sure you only enter safe HTML code. (Note: Does allow shortcodes but DOES NOT allow plain text!)', 'cool_carousel' ),
			'cols'	=> 12
		),
		array(
			'id' => 'coolcarousel_directions_image',
			'name' => __( 'Individual Cool Carousel IMAGE', 'cool_carousel' ),
			'type' => 'title',
			//'cols' => 6,
		),
		array(
			'id' => 'coolcarousel_image',
			'name' => __( 'Individual Cool Carousel IMAGE (Manual Input)', 'cool_carousel' ),
			'desc' => __( 'Full URL to Image File', 'cool_carousel' ),
			'type' => 'text',
			//'cols' => 6,
		),
		array(
			'id' => 'coolcarousel_directions_image_or',
			'name' => __( 'OR', 'cool_carousel' ),
			'type' => 'title',
			//'cols' => 6,
		),
		array(
			'id' => 'coolcarousel_image_media_library',
			'name' => __( 'Individual Cool Carousel IMAGE (Media Library)', 'cool_carousel' ),
			'desc' => __( '<strong>Select from / Upload to Media Library</strong>', 'cool_carousel' ),
			'type' => 'file', // attachment ID
			//'size' => '', // default selection, even after one was previously selected (i.e. accidental override of previous selection)
			'cols' => 7,
		),
		array(
			'id' => 'coolcarousel_image_media_library_size',
			'name' => __( 'Image Size from Media Library. Default: full.', 'cool_carousel' ),
			'type' => 'select',
			'options' => $mediasizes,
			'allow_none' => true,
			'cols' => 5,
   		),
		array(
			'id' => 'coolcarousel_directions_image_options',
			'name' => __( 'Image Options', 'cool_carousel' ),
			'type' => 'title',
			//'cols' => 6,
		),
		array(
			'id' => 'coolcarousel_image_link',
			'name' => __('Cool Carousel Link (Optional)', 'cool_carousel' ),
			'desc' => __( 'Must enter a full URL, including http://<br/>Entire image becomes clickable and goes to this link.', 'cool_carousel' ),
			'type' => 'text',
			//'cols' => 6,
		),
		array(
			'id' => 'coolcarousel_image_target',
			'name' => __( 'Open Link in New Window?', 'cool_carousel' ),
			'type' => 'checkbox',
			//'cols' => 6,
		),
		array(
			'id' => 'coolcarousel_title_text',
			'name' => __( 'Image Caption Text and Image Title Tag (Default: Post Title)', 'cool_carousel' ),
			'desc' => __( 'Caption text may not be displayed (depends on each Cool Carousel\'s desired options).<br/>Image Title Tag will always output so use this and Image Alt Tag (below) for Usability and/or SEO as desired.<br/>Or just name the Post Title appropriately.', 'cool_carousel' ),
			'type' => 'text',
			//'cols' => 6,
		),
		array(
			'id' => 'coolcarousel_alt_text',
			'name' => __( 'Image Alt Tag Text (Default: Post Title)', 'cool_carousel' ),
			'type' => 'text',
			//'cols' => 6,
		),
		array(
			'id' => 'coolcarousel_directions_video',
			'name' => __( 'Individual Cool Carousel VIDEO', 'cool_carousel' ),
			'type' => 'title',
			//'cols' => 6,
		),
		array(
			'id' => 'coolcarousel_video_site',
			'name' => __( 'Video Hosting Site', 'cool_carousel' ),
			'type' => 'select',
			'options' => array(
			    'youtube' => __( 'YouTube', 'cool_carousel' ),
			    'vimeo' => __( 'Vimeo', 'cool_carousel' ),
			    'dailymotion' => __( 'Daily Motion', 'cool_carousel' ),
			),
			'allow_none' => true,
			//'cols' => 6,
   		),
		array(
			'id' => 'coolcarousel_video_id',
			'name' => __('Video ID', 'cool_carousel' ),
			'desc' => sprintf('(e.g. <a href="http://demo.pagelines.me/wp-content/blogs.dir/18/files/2012/06/youtube-id.png" target="_blank">%s</a> <a href="http://demo.pagelines.me/wp-content/blogs.dir/18/files/2012/06/vimeo-id.png" target="_blank">%s</a>)',
							__('YouTube', 'cool_carousel' ),
							__('Vimeo', 'cool_carousel' )
						),
			'type' => 'text_small',
			//'cols' => 6,
		),
		array(
			'id' => 'coolcarousel_plvideorelated',
			'name' => __( 'Show Related Videos (YouTube only)?', 'cool_carousel' ),
			'type' => 'checkbox',
			//'cols' => 6,
		),
		array(
			'id' => 'coolcarousel_directions_custom',
			'name' => __( 'Individual Cool Carousel HTML', 'cool_carousel' ),
			'type' => 'title',
			//'cols' => 6,
		),
		array(
			'id' => 'coolcarousel_code',
			'name' => __( 'Custom HTML', 'cool_carousel' ),
			'desc' => __( '(e.g. <a href="http://www.w3schools.com/tags/tag_iframe.asp" target="_blank">iframe a page</a>, <a href="http://wordpress.org/plugins/google-document-embedder/" target="_blank">embed a PDF</a>, etc.)<br/>Shortcodes work too (e.g. <a href="http://demo.pagelines.me/tools/" target="_blank">PageLines Google Maps</a>)<br/>Warning: phones and tablets won\'t respect iframe height and will display full height of iframed page (i.e. not cool).<br/>Does NOT allow plain text.', 'cool_carousel' ),
			'type' => 'textarea',
			//'cols' => 6,
		),
		array(
			'id' => 'coolcarousel_directions_all',
			'name' => __( 'Options for All Types', 'cool_carousel' ),
			'type' => 'title',
			//'cols' => 6,
		),
		array(
			'id' => 'coolcarousel_item_class',
			'name' => __( 'Individual CSS Class', 'cool_carousel' ),
			'desc' => __( 'Optional', 'cool_carousel' ),
			'type' => 'text',
			//'cols' => 6,
		),

	);

	$meta_boxes[] = array(
		'title' => 'Cool Carousel',
		'pages' => array('cool-carousel'),
		'fields' => $opts,
	);

	return $meta_boxes;

}