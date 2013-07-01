<?php
/*
	Section: Cool Carousel
	Author: Clifford Paulick
	Author URI: http://www.pagelines.com
	Description: coolcarousel description
	Class Name: CoolCarousel
	Workswith: templates, main, header, morefoot
	Cloning: true
	V3: true
	Filter: slider
*/

class CoolCarousel extends PageLinesSection {

	var $ptID = 'coolcarousel'; // post type
	var $taxID = 'coolcarousel-sets'; // category

	function section_persistent() {

		$this->video_hosts = array(
			'youtube' => array('name' => __( 'YouTube', $this->id )),
			'vimeo'   => array('name' => __( 'Vimeo', $this->id )),
			'dailymotion'   => array('name' => __( 'Daily Motion', $this->id )),
		);
		$this->post_type_setup();
		$this->post_meta_setup();
	}


	function section_styles() {}


    function section_scripts() {
		wp_enqueue_script('coolcarousel', $this->base_url.'/js/coolcarousel.js', array( 'jquery' ), '4.1.1', true);
		wp_enqueue_script('coolcarousel', $this->base_url.'/js/coolcarousel.min.js', array( 'jquery' ), '4.1.1', true);
		wp_enqueue_script('pagelines-easing');
    }


	function section_head(){

        global $pagelines_ID;
        $oset = array('post_id' => $pagelines_ID);
		$clone_id = $this->oset['clone_id'];

		?>
		<script type="text/javascript">
		/*<![CDATA[*/
			jQuery(document).ready(function( $ ){
				$("#coolcarousel-<?php echo $clone_id ?>").bxSlider({
					slideWidth: 500
				});
			});
		/*]]>*/
		</script>
		<?php
	}


	function post_type_setup(){
		$args = array(
				'label' 			=> __( 'Cool Carousel', $this->id ),
				'singular_label' 	=> __( 'Cool Carousel', $this->id ),
				'description' 		=> __( 'For creating items in Cool Carousel layouts.', $this->id ),
				'menu_icon'			=> $this->icon,
				'supports'			=> array( 'title' )
			);
		$taxonomies = array(
			$this->taxID => array(
					'label' => __( 'Cool Carousel Sets', $this->id ),
					'singular_label' => __( 'Cool Carousel Set', $this->id ),
				)
		);
		$columns = array(
			'cb'	 		=> "<input type=\"checkbox\" />",
			'title' 		=> 'Post Title',
			'ccmediatype'	=> 'Media Type',
			'ccimage' 		=> 'Image',
			//'ccimagewidth'	=> 'Width',
			//'ccimageheight'	=> 'Height',
			'cclink'		=> 'Image Link',
			$this->taxID 	=> 'Cool Carousel Sets'
		);

		$this->post_type = new PageLinesPostType( $this->ptID, $args, $taxonomies, $columns, array(&$this, 'column_display'));
	}



	function post_meta_setup(){
		$type_meta_array = array(
			'coolcarousel_directions' => array(
				'type'     => '',
				'title'    => '<strong style="display:block;font-size:16px;color:#eaeaea;text-shadow:0 1px 0 black;padding:7px 7px 5px;background:#333;margin-top:5px;border-radius:3px;border:1px solid white;letter-spacing:0.1em;box-shadow:inset 0 0 3px black;">HOW TO USE:</strong>',
				'shortexp' => '&bull; Enter EITHER an image OR a single video field OR custom HTML.<br/>
						&bull; If you enter more than one, they will be in this priority: Image, YouTube, Vimeo, Custom Embed Code, Custom HTML.<br/>
						&bull; <strong>All images in the same set/category must have the same <em>width</em>.</strong><br/>
						&bull; Consider naming your sets/categories with a <em>width</em> indicator, like "Favorite Images (600w)".<br/>
						&bull; Videos will be responsive.<br/>&bull; Custom HTML will not be filtered or escaped so make sure you only enter safe HTML code.',
			),
			/*'coolcarousel_media' => array(
				'type'         => 'select',
				'title'        => 'Media Type',
				'shortexp'     => 'Determines what content to use for this carousel item.',
				'exp'          => 'Choose what kind of media you would like this item to display.<p>Default: Image</p>',
				'selectvalues' => array(
					'image'   => array('name' => 'Image'),
					'video'   => array('name' => 'Video'),
					'code'    => array('name' => 'Custom Code / HTML'),
				)
			),*/
			'image_multi' => array(
				'type'         => 'multi_option',
				'title'        => __( 'Individual Cool Carousel IMAGE', $this->id ),
				'shortexp'     => __( 'An Image', $this->id ),
				'selectvalues' => array(
					'coolcarousel_image' => array(
							'type'       => 'image_upload',
							'inputlabel' => __( 'Cool Carousel Image', $this->id ),
						),
					'coolcarousel_image_link' => array(
							'type'       => 'text',
							'inputlabel' => __( 'Cool Carousel Link (Optional)', $this->id ),
						),
					'coolcarousel_image_target' => array(
							'type'       => 'check',
							'inputlabel' => __( 'Open link in New Window?', $this->id ),
						),
					'coolcarousel_title_text' => array(
						'default'		=> '',
						'type' 			=> 'text',
						'size'			=> 'small',
						'inputlabel' 	=> __( 'Image Caption and Title Text (Post Title will be used by default)', $this->id ),
					),
					'coolcarousel_alt_text' => array(
						'default'		=> '',
						'type' 			=> 'text',
						'size'			=> 'small',
						'inputlabel' 	=> __( 'Image Alt Text (Post Title will be used by default)', $this->id ),
					),
				),
			),
			'video_multi' => array(
				'type'		=> 'multi_option',
				'title'		=> __( 'Individual Cool Carousel VIDEO', $this->id ),
				'shortexp'	=> __( 'A Video', $this->id ),
				'selectvalues'	=> array(
					'coolcarousel_video_site' => array(
						'type'         => 'select',
						'inputlabel'   => __( 'Video Hosting Site', $this->id ),
						'selectvalues' => $this->video_hosts,
					),
					'coolcarousel_video_id' => array(
						'type'       => 'text',
						'inputlabel' => sprintf('%s (<a href="http://demo.pagelines.me/wp-content/blogs.dir/18/files/2012/06/youtube-id.png" target="_blank">%s</a> <a href="http://demo.pagelines.me/wp-content/blogs.dir/18/files/2012/06/vimeo-id.png" target="_blank">%s</a>)',
							__('Video ID', $this->id),
							__('YouTube', $this->id),
							__('Vimeo', $this->id)
						),
					),
					'coolcarousel_video_embed' => array(
						'type' 			=> 'textarea',
						'inputlabel' 	=> __( 'Custom Video Embed Code (Note: <a href="https://github.com/davatron5000/FitVids.js/blob/master/README.md#currently-supported-players" target="_blank">fitvid.js only supports some players</a>)', $this->id ),
					),
				),
			),
			'coolcarousel_code' => array(
				'title'      => __( 'Individual Cool Carousel HTML', $this->id ),
				'shortexp'   => __( 'Custom HTML', $this->id ),
				'inputlabel' => __( 'You can enter custom HTML (e.g. iframe webpage, static content, Google Map, etc.)', $this->id ),
				'type'       => 'textarea',
			),

		);


		$type_metapanel_settings = array(
				'id' 		=> 'coolcarousel-metapanel',
				'name' 		=> 'Cool Carousel Options',
				'posttype' 	=> array( $this->id ),
			);

		$coolcarousel_meta_panel =  new PageLinesMetaPanel( $type_metapanel_settings );

		$type_metatab_settings = array(
			'id' 		=> 'coolcarousel-type-metatab',
			'name' 		=> 'Cool Carousel Post',
			'icon' 		=> $this->icon,
		);

		$coolcarousel_meta_panel->register_tab( $type_metatab_settings, $type_meta_array );
	}



	function section_optionator( $settings ){

		$settings = wp_parse_args($settings, $this->optionator_default);

		$tab = array(
			'coolcarousel_setup' => array(
				'type'		=> 'multi_option',
				'title'		=> __( 'Cool Carousel Setup Options', $this->id ),
				'shortexp'	=> __( 'Options for displaying Cool Carousel.', $this->id ),
				'selectvalues'	=> array(
					'coolcarousel_set' => array(
						'type' 			=> 'select_taxonomy',
						'taxonomy_id'	=> $this->taxID,
						'inputlabel'	=> __( 'Cool Carousel Set To Show', $this->id ),
					),
					'coolcarousel_totalslides' => array(
						'type' 			=> 'text_small',
						'size'			=> 'small',
						'inputlabel' 	=> __( 'Maximum number of slides to include. (Default: unlimited)<br/>If there are 20 slides in your chosen Set, you can enter a number like 7 and only 7 of the 20 slides will be in the slider.', $this->id ),
					),
/*
					'coolcarousel_video' => array(
						'type' 		=> 'select',
						'selectvalues'	=> array(
							'false'		=> array('name' => __( 'False', $this->id ) ),
							'true'		=> array('name' => __( 'True', $this->id ) )
						),
						'inputlabel' => __( 'Does this Cool Carousel Set have one or more videos? (default: False)', $this->id ), // auto-detect video?
					),
*/
					'coolcarousel_mode' => array(
						'type' 		=> 'select',
						'selectvalues'	=> array(
							'horizontal'=> array('name' => __( 'Horizontal', $this->id ) ),
							'fade'		=> array('name' => __( 'Fade', $this->id ) ),
							'vertical'	=> array('name' => __( 'Vertical', $this->id ) )
						),
						'inputlabel' => __( 'Mode (default: Horizontal)', $this->id ),
					),
					'coolcarousel_captions' => array(
						'type' 		=> 'select',
						'selectvalues'	=> array(
							'false'		=> array('name' => __( 'False', $this->id ) ),
							'true'		=> array('name' => __( 'True', $this->id ) )
						),
						'inputlabel' => __( 'Captions (default: False)', $this->id ),
					),

					'coolcarousel_slidewidth' => array(
						'type' 			=> 'text_small',
						'size'			=> 'small',
						'inputlabel' 	=> __( 'The width of each slide. (Default: 600px)', $this->id ), //vertical carousels can omit: http://bxslider.com/faqs --- but is it really necessary???
					),
					'coolcarousel_minslides' => array(
						'type' 			=> 'text_small',
						'size'			=> 'small',
						'inputlabel' 	=> __( 'Minimum number of slides to be shown. (Default: 1)<br/>Slides will be sized down if carousel becomes smaller than the original size.', $this->id ),
					),
					'coolcarousel_maxslides' => array(
						'type' 			=> 'text_small',
						'size'			=> 'small',
						'inputlabel' 	=> __( 'Maximum number of slides to be shown. (Default: 1)<br/>Slides will be sized up if carousel becomes larger than the original size.', $this->id ), //vertical carousels can omit: http://bxslider.com/faqs
					),

				),
			),

				'coolcarousel_ordering' => array(
					'type'		=> 'multi_option',
					'title'		=> __( 'Cool Carousel Ordering Options', $this->id ),
					'shortexp'	=> __( 'Optionally control the ordering of the Cool Carousel', $this->id ),
					'exp'		=> __( 'The easiest way to order Cool Carousel is using a post type order plugin for WordPress. However, if you would like to do it algorithmically, we have provided these options for you.', $this->id ),
					'selectvalues'	=> array(
						'coolcarousel_orderby' => array(
							'type'			=> 'select',
							'default'		=> 'ID',
							'inputlabel'	=> 'Order Cool Carousel By (If Not With Post Type Order Plugin)',
							'selectvalues' => array(
								'ID' 		=> array('name' => __( 'Post ID (default)', $this->id ) ),
								'title' 	=> array('name' => __( 'Title', $this->id ) ),
								'date' 		=> array('name' => __( 'Date', $this->id ) ),
								'modified' 	=> array('name' => __( 'Last Modified', $this->id ) ),
								'rand' 		=> array('name' => __( 'Random', $this->id ) ),
							)
						),
						'coolcarousel_order' => array(
								'default' => 'DESC',
								'type' => 'select',
								'selectvalues' => array(
									'DESC' 		=> array('name' => __( 'Descending', $this->id ) ),
									'ASC' 		=> array('name' => __( 'Ascending', $this->id ) ),
								),
								'inputlabel'=> __( 'Select sort order', $this->id ),
						),
					),
				),

		);

		$tab_settings = array(
				'id' 		=> 'coolcarousel_meta',
				'name' 		=> 'Cool Carousel',
				'icon' 		=> $this->icon,
				'clone_id'	=> $settings['clone_id'],
				'active'	=> $settings['active']
			);

		register_metatab($tab_settings, $tab);
	}



   function section_template() {
	   $clone_id = $this->oset['clone_id'];

	   	$items = $this->get_items();
	   	// check to see if there's antying to display first..
	   	if ( empty( $items ) ) {
	   		echo setup_section_notify( 'Message' );
	   		return; // stop display here.
	   	}

		?>
		<ul id="coolcarousel-<?php echo $clone_id; ?>">
			<?php
			foreach ( $items as $item )
				$this->do_the_item( $item );
			?>
		</ul>
		<?php
	} // section_template


	function get_items() {

		$args = array(
			'post_type'      => $this->ptID,
			'orderby'        => ploption('coolcarousel_orderby', $this->oset) ? ploption('coolcarousel_orderby', $this->oset) : 'ID',
			'order'          => ploption('coolcarousel_order', $this->oset) ? ploption('coolcarousel_order', $this->oset) : 'DESC',
			$this->taxID     => ploption('coolcarousel_set', $this->oset ) ? ploption('coolcarousel_set', $this->oset ) : null,
			'posts_per_page' => ploption('coolcarousel_totalslides', $this->oset ) ? ploption('coolcarousel_totalslides', $this->oset ) : -1
		);

		$found = new WP_Query( $args );
		return $found->posts;
	}

	/**
	 * Output carousel item
	 * @param  object 		post
	 */
	function do_the_item( $item ) {

		$args = array(
			'type' => $this->item_type_breaker( $item ),
			'post' => $item,
		);

		$output = $this->render_carousel_item( $args );

		if ( $output )
			echo $output;
	}

	/**
	 * Determine item type
	 * @param  object 	$item
	 */
	function item_type_breaker( $item ) {

		if ( !is_object( $item ) )
			return;

		$oset = array('post_id' => $item->ID);

		if ( plmeta('coolcarousel_image', $oset) )
			return 'image';
		if ( (plmeta('coolcarousel_video_site', $oset) && plmeta('coolcarousel_video_id', $oset )) || plmeta('coolcarousel_video_embed', $oset) )
			return 'video';
		if ( plmeta('coolcarousel_code', $oset) )
			return 'code';
	}


	function render_carousel_item( $args ) {

		extract( $args );

		$post_id = $post->ID;
		$oset = array('post_id' => $post_id);

		switch ( $type ) {
			case 'image' :
				$attributes = array(
					'src'    => plmeta('coolcarousel_image', $oset),
					'target' => plmeta('coolcarousel_image_target', $oset),
					'title'  => plmeta('coolcarousel_title_text', $oset) ? plmeta('coolcarousel_title_text', $oset) : get_the_title( $post_id ),
					'alt'    => plmeta('coolcarousel_alt_text', $oset) ? plmeta('coolcarousel_alt_text', $oset) : get_the_title( $post_id ),
				);
				$attributes = array_filter( array_map( 'esc_attr', $attributes ) );

				$image = '<img class="coolcarousel-image" ';

				foreach ( $attributes as $key => $value )
					if ( $value )
						$image .= "$key=\"$value\" ";

				$image .= '/>';

				if ( $link = plmeta('coolcarousel_image_link', $oset) )
					$image = sprintf('<a href="%s">%s</a>', $link, $image);

				$content = $image;
				break;

			case 'video' :
				$host = plmeta('coolcarousel_video_site', $oset);
				$id   = plmeta('coolcarousel_video_id', $oset);
				if ( $host && $id )
					$content = do_shortcode("[pl_video type='$host' id='$id']");
				elseif( $embed = plmeta('coolcarousel_video_embed', $oset) )
					$content = $embed;
				else
					$content = '';
				break;

			case 'code'	:
				$content = plmeta('coolcarousel_code', $oset);
				break;
		}

		if ( ! $content )
			return false;

		$out = sprintf('<li class="coolcarousel-item %s-item slide">%s</li>', $type, do_shortcode( $content ) );

		return $out;
	}



	function column_display( $column ) {
		// get column data
		global $post;

		// Image
		$coolcarousel_image = get_post_meta($post->ID, 'coolcarousel_image', true );
		// Video
		$cc_youtube = get_post_meta($post->ID, 'coolcarousel_youtube', true );
		$cc_vimeo = get_post_meta($post->ID, 'coolcarousel_vimeo', true );
		$cc_video_embed = get_post_meta($post->ID, 'coolcarousel_video_embed', true );
		// HTML
		$cc_code = get_post_meta($post->ID, 'coolcarousel_code', true );

		// Content to Display
		if(!empty($coolcarousel_image)){
			$cctype = 'Image';
		} elseif(!empty($cc_youtube)) {
			$cctype = 'YouTube';
		} elseif(!empty($cc_vimeo)) {
			$cctype = 'Vimeo';
		} elseif(!empty($cc_video_embed)) {
			$cctype = 'Custom Video';
		} elseif(!empty($cc_code)){
			$cctype = 'HTML';
		} else{
			$cctype = '';
		}

		// display columns
		switch ($column){
			case 'ccmediatype':
				echo $cctype;
				break;
			case 'ccimage':
				if($cctype == 'Image')
					echo '<img src="'.$coolcarousel_image.'" style="max-width: 80px; max-height:80px; margin: 0 auto; border: 1px solid #ccc; padding: 5px; background: #fff;" />';
				break;
/*
			case 'ccimagewidth':
				if($cctype == 'Image'){
					list($width, $height) = getimagesize($coolcarousel_image); //doesn't work with relative URLs
					if(is_integer($width) && is_integer($height)){
						echo $width . ' px';
					}
				}
				break;
			case 'ccimageheight':
				if($cctype == 'Image'){
					list($width, $height) = getimagesize($coolcarousel_image);
					if(is_integer($width) && is_integer($height)){
						echo $height . ' px';
					}
				}
				break;
*/
			case 'cclink':
				if($cctype == 'Image'){
					echo get_post_meta($post->ID, 'coolcarousel_image_link', true );
				}
				break;
			case $this->taxID:
				echo get_the_term_list($post->ID, 'coolcarousel-sets', '', ', ','');
				break;
		}
	}
}