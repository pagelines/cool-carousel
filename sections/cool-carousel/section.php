<?php
/*
Section: Cool Carousel
Author: TourKick (Clifford P)
Author URI: http://tourkick.com/?utm_source=pagelines&utm_medium=section&utm_content=authoruri&utm_campaign=coolcarousel_section
Plugin URI: http://www.pagelinestheme.com/coolcarousel-section?utm_source=pagelines&utm_medium=section&utm_content=pluginuri&utm_campaign=coolcarousel_section
Description: A responsive carousel/slider with left, right, up, down, or fade transition, customizable number of slides displayed at once, customizable number of slides to advance, auto play option, timing intervals, and many more carousel-by-carousel options. Utilizes custom post types so you can easily modify the order, add a single slide to multiple carousels, store drafts, and more.
Demo: http://www.pagelinestheme.com/coolcarousel-section?utm_source=pagelines&utm_medium=section&utm_content=demolink&utm_campaign=coolcarousel_section
Class Name: CoolCarousel
Workswith: templates, main, header, morefoot
Cloning: true
v3: true
Filter: slider, dual-width
*/

class CoolCarousel extends PageLinesSection {
/*
Thanks To / Included Licenses:
	- bxSlider: http://bxslider.com
		- released under the WTFPL license: http://sam.zoy.org/wtfpl/
	- HM Custom Meta Boxes for WordPress: https://github.com/humanmade/Custom-Meta-Boxes
*/

	var $ptID = 'cool-carousel'; // post type
	var $taxID = 'cool-carousel-sets'; // category

	function section_persistent() {

		$themeversionnumber = get_theme_mod( 'pagelines_version' ); // Works in both DMS and Framework
		$dmsnometapanelversion = '1.1'; // The first DMS Version that does not have post_meta_setup() as a deprecated function

		if( function_exists('pl_has_editor')
			&& pl_has_editor()
			&& $themeversionnumber >= $dmsnometapanelversion) {

	    		if( !class_exists( 'CMB_Meta_Box' ) ) {
		    		require_once('custom-meta-boxes/custom-meta-boxes.php' );
		    	}
	    		include('cool-carousel-meta.php' );

		} else {
    		$this->post_meta_setup();
    	}

		$this->post_type_setup();

		add_filter( 'pless_vars', array($this,'coolcarousel_less_vars'));

		$this->video_hosts = array(
			'youtube' => array('name' => __( 'YouTube', $this->id )),
			'vimeo'   => array('name' => __( 'Vimeo', $this->id )),
			'dailymotion'   => array('name' => __( 'Daily Motion', $this->id )),
		);

	}

	function coolcarousel_less_vars($less){

		if(function_exists('pl_has_editor') && pl_has_editor()){
			$coolcarouselpath = plugins_url() . '/cool-carousel/sections/cool-carousel';
		} else {
			$coolcarouselpath = plugins_url( 'pagelines-sections' ) . '/cool-carousel';
		}

		// ensure no mixed content warnings
		$coolcarouselpath = str_replace( 'http://', '//', $coolcarouselpath );
		$coolcarouselpath = str_replace( 'https://', '//', $coolcarouselpath );

		$less['coolcarouselpath']  = '"'.$coolcarouselpath.'"'; //LESS Path must be wrapped in quotes

		return $less;
	}

    function section_scripts() {
		global $pagelines_ID;
        $oset = array('post_id' => $pagelines_ID);

		if(
				$this->opt('coolcarousel_mode') !== 'fade'
			&&	$this->opt('coolcarousel_easing')
		){
			wp_enqueue_script('pagelines-easing'); // easing must be before coolcarousel
		}

		wp_enqueue_script('cool-carousel', $this->base_url.'/js/coolcarousel.min.js', array( 'jquery' ), '4.1.1', false);
    }


	function section_head(){
		if(function_exists('pl_has_editor') && pl_has_editor()){
			$clone_id = $this->get_the_id();
		} else {
	        global $pagelines_ID;
	        $oset = array('post_id' => $pagelines_ID);
			$clone_id = $this->oset['clone_id'];
		}

		//$value = $this->opt('option_key'); // http://docs.pagelines.com/developer/dms-option-engine

		//General
		$mode = $this->opt('coolcarousel_mode');
		$speed = $this->opt('coolcarousel_speed');
			$speed = preg_replace("/[^0-9]/","",$speed);
		$slidemargin = $this->opt('coolcarousel_slidemargin');
			$slidemargin = preg_replace("/[^0-9]/","",$slidemargin);
		$startslide = $this->opt('coolcarousel_startslide');
			$startslide = preg_replace("/[^0-9]/","",$startslide);
		$randomstart = $this->opt('coolcarousel_randomstart');
		//$slideselector = $this->opt('coolcarousel_slideselector');
		$infiniteloop = $this->opt('coolcarousel_infiniteloop');
		$hidecontrolonend = $this->opt('coolcarousel_hidecontrolonend');
		$easing = $this->opt('coolcarousel_easing');
		$captions = $this->opt('coolcarousel_captions');
		$ticker = $this->opt('coolcarousel_ticker');
			if($ticker == 'cctrue'){
				$tickeron = 1;
			} else {
				$tickeron = 0;
			}
		$tickerhover = $this->opt('coolcarousel_tickerhover');
		$adaptiveheight = $this->opt('coolcarousel_adaptiveheight');
		$adaptiveheightspeed = $this->opt('coolcarousel_adaptiveheightspeed');
			$adaptiveheightspeed = preg_replace("/[^0-9]/","",$adaptiveheightspeed);
		$video = $this->opt('coolcarousel_video');
		//$responsive = $this->opt('coolcarousel_responsive');
		$usecss = $this->opt('coolcarousel_usecss');
		$preloadimages = $this->opt('coolcarousel_preloadimages');
		$touchenabled = $this->opt('coolcarousel_touchenabled');
		$swipethreshold = $this->opt('coolcarousel_swipethreshold');
			$swipethreshold = preg_replace("/[^0-9]/","",$swipethreshold);
		$onetoonetouch = $this->opt('coolcarousel_onetoonetouch');
		$preventdefaultswipex = $this->opt('coolcarousel_preventdefaultswipex');
		$preventdefaultswipey = $this->opt('coolcarousel_preventdefaultswipey');
		//Pager
		$pager = $this->opt('coolcarousel_pager');
		$pagertype = $this->opt('coolcarousel_pagertype');
		$pagershortseparator = $this->opt('coolcarousel_pagershortseparator');
			//$pagershortseparator = esc_html($pagershortseparator);
			$pagershortseparator = do_shortcode($pagershortseparator);
/*
		$pagerselector = $this->opt('coolcarousel_pagerselector');
		$pagercustom = $this->opt('coolcarousel_pagercustom');
		$buildpager = $this->opt('coolcarousel_buildpager');
*/
		//Controls
		$controls = $this->opt('coolcarousel_controls');
		$nexttext = $this->opt('coolcarousel_nexttext');
			//$nexttext = esc_html($nexttext);
			$nexttext = do_shortcode($nexttext);
		$prevtext = $this->opt('coolcarousel_prevtext');
			//$prevtext = esc_html($prevtext);
			$prevtext = do_shortcode($prevtext);
		if(!empty($nexttext) || !empty($prevtext) ){ //they are supposed to default but do not
			if(empty($nexttext)){ $nexttext = 'Next'; }
			if(empty($prevtext)){ $prevtext = 'Prev'; }
		}
		//$nextselector = $this->opt('coolcarousel_nextselector');
		//$prevselector = $this->opt('coolcarousel_prevselector');
		$autocontrols = $this->opt('coolcarousel_autocontrols');
		$starttext = $this->opt('coolcarousel_starttext');
			//$starttext = esc_html($starttext);
			$starttext = do_shortcode($starttext);
		$stoptext = $this->opt('coolcarousel_stoptext');
			//$stoptext = esc_html($stoptext);
			$stoptext = do_shortcode($stoptext);
		if(!empty($starttext) || !empty($stoptext) ){ //they are supposed to default but do not
			if(empty($starttext)){ $starttext = 'Start'; }
			if(empty($stoptext)){ $stoptext = 'Stop'; }
		}
		$autocontrolscombine = $this->opt('coolcarousel_autocontrolscombine');
		//$autocontrolsselector = $this->opt('coolcarousel_autocontrolsselector');
		//Auto
		$auto = $this->opt('coolcarousel_auto');
		$pause = $this->opt('coolcarousel_pause');
			$pause = preg_replace("/[^0-9]/","",$pause);
		$autostart = $this->opt('coolcarousel_autostart');
		$autodirection = $this->opt('coolcarousel_autodirection');
		$autohover = $this->opt('coolcarousel_autohover');
		$autodelay = $this->opt('coolcarousel_autodelay');
			$autodelay = preg_replace("/[^0-9]/","",$autodelay);
		if($mode !== 'fade'){
			//Carousel
			$minslides = $this->opt('coolcarousel_minslides');
				$minslides = preg_replace("/[^0-9]/","",$minslides);
			$maxslides = $this->opt('coolcarousel_maxslides');
				$maxslides = preg_replace("/[^0-9]/","",$maxslides);
			$moveslides = $this->opt('coolcarousel_moveslides');
				$moveslides = preg_replace("/[^0-9]/","",$moveslides);

			if(!empty($minslides) && empty($maxslides)){ // do not need the inverse
				$maxslides = $minslides;
			}

			if(isset($moveslides) && ($moveslides < 1) ){ // isset instead of !empty because trying to get rid of zero value
				unset($moveslides);
			}
			if(isset($moveslides) && ($maxslides < $moveslides) ){ // Move must be <= Max
				unset($moveslides);
			}
		}
		$slidewidth = $this->opt('coolcarousel_slidewidth');
			$slidewidth = preg_replace("/[^0-9]/","",$slidewidth);
			if($mode !== 'fade' && empty($slidewidth)) { $slidewidth = '2000'; }
/*
		//Callbacks
		$onsliderload = $this->opt('coolcarousel_onsliderload');
		$onslidebefore = $this->opt('coolcarousel_onslidebefore');
		$onslideafter = $this->opt('coolcarousel_onslideafter');
		$onslidenext = $this->opt('coolcarousel_onslidenext');
		$onslideprev = $this->opt('coolcarousel_onslideprev');
		//Public Methods
		$gotoslide = $this->opt('coolcarousel_gotoslide');
		$gotonextslide = $this->opt('coolcarousel_gotonextslide');
		$gotoprevslide = $this->opt('coolcarousel_gotoprevslide');
		$startauto = $this->opt('coolcarousel_startauto');
		$stopauto = $this->opt('coolcarousel_stopauto');
		$getcurrentslide = $this->opt('coolcarousel_getcurrentslide');
		$getslidecount = $this->opt('coolcarousel_getslidecount');
		$reloadslider = $this->opt('coolcarousel_reloadslider');
		$destroyslider = $this->opt('coolcarousel_destroyslider');
*/
		?>
		<script type="text/javascript">
			jQuery(document).ready(function(){
				jQuery("#cool-carousel-<?php echo $clone_id ?>").ccSlider({
					<?php
					if(!empty($mode)){ echo "mode: '$mode',"; }
					if(!empty($speed)){ echo "speed: $speed,"; }
					if(!empty($slidemargin)){ echo "slideMargin: $slidemargin,"; }
					if(!empty($startslide)){ echo "startSlide: $startslide,"; }
					if(!empty($randomstart)){ echo "randomStart: $randomstart,"; }
					if(!empty($slideselector)){ echo "slideSelector: $slideselector,"; }
					if($infiniteloop == 'ccfalse'){ echo 'infiniteLoop: false,'; }
					if($hidecontrolonend == 'cctrue'){ echo 'hideControlOnEnd: true,'; }
					if($mode !== 'fade' && !empty($easing)){ echo "easing: '$easing',"; }
					if($captions == 'cctrue'){ echo 'captions: true,'; }
					if($ticker == 'cctrue'){
						echo 'ticker: true,';
						if($tickerhover == 'cctrue'){ echo 'tickerHover: true,'; }
					}

					if($adaptiveheight == 'cctrue'){
						echo 'adaptiveHeight: true,';
						if(!empty($adaptiveheightspeed) || $adaptiveheightspeed === '0' ){
							echo "adaptiveHeightSpeed: $adaptiveheightspeed,"; }
					}

					//if($video == 'cctrue'){ echo 'video: true,'; }
					//if($responsive == 'ccfalse'){ echo 'responsive: false,'; }
/*
YouTube videos are not clickable to play in the carousel for Firefox if useCSS is true
					if(
						$usecss == 'ccfalse'
						|| !empty($easing)
						|| ($ticker == 'cctrue' && $tickerhover == 'cctrue')
					)
*/{
						echo 'useCSS: false,';
					}
					if(!empty($preloadimages)){ echo "preloadImages: '$preloadimages',"; }

					if($touchenabled == 'ccfalse'){ echo 'touchEnabled: false,'; }
					if(!empty($swipethreshold)){ //if swipe false
						echo "swipeThreshold: $swipethreshold,";
					} else { //if swipe true
						if($onetoonetouch == 'ccfalse'){ echo 'oneToOneTouch: false,'; }
						if($preventdefaultswipex == 'ccfalse'){ echo 'preventDefaultSwipeX: false,'; }
						if($preventdefaultswipey == 'cctrue'){ echo 'preventDefaultSwipeY: true,'; }
					}

				if($tickeron == 0){ //if ticker is on, pager and controls are off
					if($pager == 'ccfalse'){ echo 'pager: false,'; }
					if(!empty($pagertype)){
						echo "pagerType: '$pagertype',";
						if(!empty($pagershortseparator)){ echo "pagerShortSeparator: ' $pagershortseparator ',"; }
					}
					if(!empty($pagerselector)){ echo "pagerSelector: $pagerselector,"; }
					if(!empty($pagercustom)){ echo "pagerCustom: $pagercustom,"; }
					if(!empty($buildpager)){ echo "buildPager: $buildpager,"; }

					if($controls == 'ccfalse'){ echo 'controls: false,'; }
					if(!empty($nexttext)){ echo "nextSelector: '.cc-next-custom',nextText: '$nexttext',"; }
					if(!empty($prevtext)){ echo "prevSelector: '.cc-prev-custom',prevText: '$prevtext',"; }
					if($autocontrols == 'cctrue'){ echo 'autoControls: true,'; }
					if(!empty($starttext) || !empty($stoptext) ){
						echo "autoControlsSelector: '.cc-startstop',startText: '$starttext',stopText: '$stoptext',";
					}
					if($autocontrolscombine == 'cctrue'){ echo 'autoControlsCombine: true,'; }
				} //end of ticker check for pager and controls

					if($auto == 'cctrue'){ echo 'auto: true,'; }
			if(!empty($pause)){ echo "pause: $pause,"; }
			if($autostart == 'ccfalse'){ echo 'autoStart: false,'; }
			if(!empty($autodirection)){ echo "autoDirection: '$autodirection',"; }
			if($autohover == 'cctrue'){ echo 'autoHover: true,'; }
			if(!empty($autodelay)){ echo "autoDelay: $autodelay ,"; }

					if(!empty($minslides)){ echo "minSlides: $minslides,"; }
					if(!empty($maxslides)){ echo "maxSlides: $maxslides,"; }
					if(!empty($moveslides)){ echo "moveSlides: $moveslides ,"; }
					if(!empty($slidewidth)){ echo "slideWidth: $slidewidth,"; }

/*
					if(!empty($onsliderload)){ echo "onSliderLoad: $onsliderload,"; }
					if(!empty($onslidebefore)){ echo "onSlideBefore: $onslidebefore,"; }
					if(!empty($onslideafter)){ echo "onSlideAfter: $onslideafter,"; }
					if(!empty($onslidenext)){ echo "onSlideNext: $onslidenext,"; }
					if(!empty($onslideprev)){ echo "onSlidePrev: $onslideprev,"; }

					if(!empty($gotoslide)){ echo "goToSlide: $gotoslide,"; }
					if(!empty($gotonextslide)){ echo "goToNextSlide: $gotonextslide,"; }
					if(!empty($gotoprevslide)){ echo "goToPrevSlide: $gotoprevslide,"; }
					if(!empty($startauto)){ echo "startAuto: $startauto,"; }
					if(!empty($stopauto)){ echo "stopAuto: $stopauto,"; }
					if(!empty($getcurrentslide)){ echo "getCurrentSlide: $getcurrentslide,"; }
					if(!empty($getslidecount)){ echo "getSlideCount: $getslidecount,"; }
					if(!empty($reloadslider)){ echo "reloadSlider: $reloadslider,"; }
					if(!empty($destroyslider)){ echo "destroySlider: $destroyslider,"; }
*/
					?>
				});
			});
		</script>
		<?php
	}


	function post_type_setup(){
		// http://codex.wordpress.org/Function_Reference/register_post_type
		// http://www.paulund.co.uk/creating-a-custom-post-type
		// don't need all custom stuff because PageLinesPostType sets defaults ( https://github.com/pagelines/DMS/blob/7602ed0959184fe4ab30207e2b0327e4cb5542f6/includes/class.types.php )
		$icon = $this->base_url.'/icon.png';
			$icon = str_ireplace('http://', '//', $icon);
		$args = array(
				'label' 			=> __( 'Cool Carousel', $this->id ),
				'singular_label' 	=> __( 'Cool Carousel Slide', $this->id ), // default is 'Post' in WP Admin Bar
				'description' 		=> __( 'Cool Carousel Slides', $this->id ),
				'capability_type'	=> 'page',
				'supports'			=> array( 'title' ),
				'menu_icon'			=> $icon,
		);
		$taxonomies = array(
			$this->taxID => array(
					'label' => __( 'Cool Carousel Sets', $this->id ),
					'singular_label' => __( 'Cool Carousel Set', $this->id ),
				)
		);
		$columns = array(
			'cb'	 		=> '<input type="checkbox" />',
			'title' 		=> 'Post Title',
			'ccmediatype'	=> 'Media Type',
			'ccimage' 		=> 'Image',
			//'ccimagewidth'	=> 'Width',
			//'ccimageheight'	=> 'Height',
			'cclink'		=> 'Image Link',
			'ccclass'		=> 'CSS Class',
			$this->taxID 	=> 'Cool Carousel Sets'
		);

		$this->post_type = new PageLinesPostType( $this->ptID, $args, $taxonomies, $columns, array($this, 'column_display'));
	}



    // only used prior to PageLines DMS v1.1 (October 2013)
	function post_meta_setup(){
		$type_meta_array = array(
			'coolcarousel_directions' => array(
				'type'     => '',
				'title'    => '<strong style="display:block;font-size:16px;color:#eaeaea;text-shadow:0 1px 0 black;padding:7px 7px 5px;background:#333;margin-top:5px;border-radius:3px;border:1px solid white;letter-spacing:0.1em;box-shadow:inset 0 0 3px black;">HOW TO USE:</strong>',
				'shortexp' => '&bull; Enter EITHER an image OR a single video field OR custom HTML.<br/>
						&bull; If you enter more than one, they will be in this priority: Image, Video, Custom HTML.<br/>
						&bull; <strong>All images in the same set/category should have the same <em>width</em>.</strong><br/>
						&bull; Consider naming your sets/categories with a <em>width</em> indicator, like "Favorite Images (600w)".<br/>
						&bull; Videos will be responsive.<br/>&bull; Custom HTML will not be filtered or escaped so make sure you only enter safe HTML code. (Note: Does allow shortcodes but DOES NOT allow plain text!)',
			),
			/*'coolcarousel_media' => array(
				'type'         => 'select',
				'title'        => 'Media Type',
				'shortexp'     => 'Determines what content to use for this carousel item',
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
							'inputlabel' => __( 'Cool Carousel Link (Optional)<br/><span style="font-weight:normal;">Must enter a full URL, including http://<br/>Entire image becomes clickable and goes to this link.</span>', $this->id ),
						),
					'coolcarousel_image_target' => array(
							'type'       => 'check',
							'inputlabel' => __( 'Open Link in New Window?', $this->id ),
						),
					'coolcarousel_title_text' => array(
						'default'		=> '',
						'type' 			=> 'text',
						'size'			=> 'small',
						'inputlabel' 	=> __( 'Image Caption Text and Image Title Tag (Default: Post Title)<br/><span style="font-weight:normal;">Caption text may not be displayed (depends on each Cool Carousel\'s desired options).<br/>Image Title Tag will always output so use this and Image Alt Tag (below) for Usability and/or SEO as desired.<br/>Or just name the Post Title appropriately.</span>', $this->id ),
					),
					'coolcarousel_alt_text' => array(
						'default'		=> '',
						'type' 			=> 'text',
						'size'			=> 'small',
						'inputlabel' 	=> __( 'Image Alt Tag Text (Default: Post Title)', $this->id ),
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
					'coolcarousel_plvideorelated' => array(
							'type'       => 'check',
							'inputlabel' => __( 'Show Related Videos (YouTube only)?', $this->id ),
						),
				),
			),
			'coolcarousel_code' => array(
				'title'      => __( 'Individual Cool Carousel HTML', $this->id ),
				'shortexp'   => __( 'Custom HTML', $this->id ),
				'inputlabel' => __( 'Custom HTML (e.g. <a href="http://www.w3schools.com/tags/tag_iframe.asp" target="_blank">iframe a page</a>, <a href="http://wordpress.org/plugins/google-document-embedder/" target="_blank">embed a PDF</a>, etc.)<br/><span style="font-weight:normal;">Shortcodes work too (e.g. <a href="http://demo.pagelines.me/tools/" target="_blank">PageLines Google Maps</a>)<br/>Warning: phones and tablets won\'t respect iframe height and will display full height of iframed page (i.e. not cool).</span><br/>Does NOT allow plain text.', $this->id ),
				'type'       => 'textarea',
			),

		);


		$type_metapanel_settings = array(
				'id' 		=> 'cool-carousel-metapanel',
				'name' 		=> 'Cool Carousel Options',
				'posttype' 	=> array( $this->id ),
			);

		$coolcarousel_meta_panel =  new PageLinesMetaPanel( $type_metapanel_settings );

		$type_metatab_settings = array(
			'id' 		=> 'cool-carousel-type-metatab',
			'name' 		=> 'Cool Carousel Post',
			'icon' 		=> $this->base_url.'/icon.png',
		);

		$coolcarousel_meta_panel->register_tab( $type_metatab_settings, $type_meta_array );
	}



	function section_opts(){

		$options = array();

		$options[] = array(
			'key'		=> 'coolcarousel_setup_a',
			'type'		=> 'multi',
			//'col'		=> 1, //left side
			'title'		=> __('<span style="color:blue;"><i class="fa fa-power-off"></i> Cool Carousel Setup</span><br/>Pick your Cool Carousel Set and # of Slides', $this->id),
			'help'		=> 'You may want to order Cool Carousel Slides using a post type order plugin for WordPress. However, if you would like to do it algorithmically, we have provided these options for you.',
			'opts'	=> array(
				array(
					'key'		=> 'coolcarousel_set',
					'type'		=> 'select_taxonomy',
					//'default'	=> '',
					'taxonomy_id'	=> $this->taxID,
					'label' 	=> __('<span style="color:#800000;"><i class="fa fa-angle-down"></i> Cool Carousel Set To Show</span><br/>Default: All Slides from All Sets', $this->id)
				),
				array(
					'key'		=> 'coolcarousel_totalslides',
					'type'		=> 'text_small',
					'default'	=> '',
					'label' 	=> __('<span style="color:#800000;"><i class="fa fa-angle-down"></i> Max # of Slides to Query For (in chosen C.C. Set)</span><br/>Default: unlimited<br/>', $this->id)
				),
				array(
					'key'		=> 'coolcarousel_orderby',
					'type'		=> 'select',
					//'default'	=> 'ID',
					'label' 	=> __('<span style="color:#800000;"><i class="fa fa-angle-down"></i> Order Cool Carousel By</span> (Leave blank if using Post Type Order Plugin)', $this->id),
					'opts' => array(
						'ID' 		=> array('name' => __( 'Post ID', $this->id ) ),
						'title' 	=> array('name' => __( 'Title', $this->id ) ),
						'date' 		=> array('name' => __( 'Date', $this->id ) ),
						'modified' 	=> array('name' => __( 'Last Modified', $this->id ) ),
						'rand' 		=> array('name' => __( 'Random', $this->id ) ),
					)
				),
				array(
					'key'		=> 'coolcarousel_order',
					'type'		=> 'select',
					//'default' => 'DESC',
					'label' 	=> __('<span style="color:#800000;"><i class="fa fa-angle-down"></i> Select sort order</span><br/>Default: Descending', $this->id),
					'opts' => array(
						//'DESC' 		=> array('name' => __( 'Descending (Default)', $this->id ) ),
						'ASC' 		=> array('name' => __( 'Ascending', $this->id ) ),
					),
				),
				array(
					'key'		=> 'coolcarousel_mode',
					'type'		=> 'select',
					'default'	=> '',
					'label' 	=> __('<span style="color:#800000;"><i class="fa fa-angle-down"></i> Mode</span><br/>Default: Horizontal', $this->id),
					'opts' => array(
						'fade' => array('name' => __( 'Fade', $this->id )),
						'vertical' => array('name' => __( 'Vertical', $this->id )),
					),
				),
				array(
					'key'		=> 'coolcarousel_minslides',
					'type'		=> 'text_small',
					'default'	=> '',
					'label' 	=> __('<span style="color:#800000;"><i class="fa fa-angle-down"></i> Min # of Slides to be Shown (N/A for Fade mode)</span><br/>Default: 1', $this->id)
				),
				array(
					'key'		=> 'coolcarousel_maxslides',
					'type'		=> 'text_small',
					'default'	=> '',
					'label' 	=> __('<span style="color:#800000;"><i class="fa fa-angle-down"></i> Max # of Slides to be Shown (N/A Fade mode)</span><br/>Default: 1<br/>Must be >= minSlides.<br/>Suggestion: <a href="http://forum.pagelines.com/topic/31338-minimum-slides-maximum-slides-slides-to-move/?p=185976" target="_blank">Reduce the slideWidth option for this to take effect.</a>', $this->id)
				),
				array(
					'key'		=> 'coolcarousel_moveslides',
					'type'		=> 'text_small',
					'default'	=> '',
					'label' 	=> __('<span style="color:#800000;"><i class="fa fa-angle-down"></i> Slides to Move each Transition (N/A Fade mode)</span><br/>Default: The # of fully-visible Slides.<br/>Must be <= maxSlides.', $this->id)
				),
				array(
					'key'		=> 'coolcarousel_slidewidth',
					'type'		=> 'text_small',
					'default'	=> '',
					'label' 	=> __('<span style="color:#800000;"><i class="fa fa-angle-down"></i> Width of each slide</span><br/>Default: 2000 (pixels, responsive)', $this->id)
				),
				array(
					'key'		=> 'coolcarousel_speed',
					'type'		=> 'text_small',
					'default'	=> '',
					'label' 	=> __('<span style="color:#800000;"><i class="fa fa-angle-down"></i> Slide transition speed/duration (milliseconds)</span><br/>Default: 500 (0.5 seconds)', $this->id)
				),
				array(
					'key'		=> 'coolcarousel_slidemargin',
					'type'		=> 'text_small',
					'default'	=> '',
					'label' 	=> __('<span style="color:#800000;"><i class="fa fa-angle-down"></i> Margin between each slide (pixels)</span><br/>Default: 0', $this->id)
				),
			)
		);

		$options[] = array(
			'key'	=> 'coolcarousel_setup_b',
			'type'	=> 'multi',
			//'col'		=> 1, //left side
			'title'		=> __('<span style="color:blue;">Cool Carousel Basic Play Options</span><br/>Frequently used options', $this->id),
			'opts'	=> array(
				array(
					'key'		=> 'coolcarousel_infiniteloop',
					'type'		=> 'select',
					'default'	=> '',
					'label' 	=> __('<span style="color:#800000;"><i class="fa fa-angle-down"></i> Infinite Loop</span><br/>Default: ON', $this->id),
					'opts'	=> array(
						'ccfalse'		=> array('name' => __( 'Infinite Loop OFF', $this->id ) )
					),
				),
				array(
					'key'		=> 'coolcarousel_hidecontrolonend',
					'type'		=> 'select',
					'default'	=> '',
					'label' 	=> __('<span style="color:#800000;"><i class="fa fa-angle-down"></i> Hide Control On End</span><br/>Default: NO', $this->id),
					'opts'	=> array(
						'ccfalse'		=> array('name' => __( 'Infinite Loop OFF', $this->id ) )
					),
				),
				array(
					'key'		=> 'coolcarousel_easing',
					'type'		=> 'select',
					'default'	=> '',
					'label' 	=> __('<span style="color:#800000;"><i class="fa fa-angle-down"></i> Easing (N/A for Fade mode)</span><br/>Default: N/A (For a clue on which transition to pick, check out <a href="http://easings.net/" target="_blank">Easings.net</a>.)', $this->id),
					'opts'	=> array(
						'swing'	=> array('name' => __( 'Swing', $this->id ) ),
						'easeInQuad'		=> array('name' => __( 'easeInQuad', $this->id ) ),
						'easeOutQuad'	=> array('name' => __( 'easeOutQuad', $this->id ) ),
						'easeInOutQuad'	=> array('name' => __( 'easeInOutQuad', $this->id ) ),
						'easeInCubic' => array('name' => __( 'easeInCubic', $this->id ) ),
						'easeOutCubic' => array('name' => __( 'easeOutCubic', $this->id ) ),
						'easeInOutCubic' => array('name' => __( 'easeInOutCubic', $this->id ) ),
						'easeInQuart' => array('name' => __( 'easeInQuart', $this->id ) ),
						'easeOutQuart' => array('name' => __( 'easeOutQuart', $this->id ) ),
						'easeInOutQuart' => array('name' => __( 'easeInOutQuart', $this->id ) ),
						'easeInQuint' => array('name' => __( 'easeInQuint', $this->id ) ),
						'easeOutQuint' => array('name' => __( 'easeOutQuint', $this->id ) ),
						'easeInOutQuint' => array('name' => __( 'easeInOutQuint', $this->id ) ),
						'easeInSine' => array('name' => __( 'easeInSine', $this->id ) ),
						'easeOutSine' => array('name' => __( 'easeOutSine', $this->id ) ),
						'easeInOutSine' => array('name' => __( 'easeInOutSine', $this->id ) ),
						'easeInExpo' => array('name' => __( 'easeInExpo', $this->id ) ),
						'easeOutExpo' => array('name' => __( 'easeOutExpo', $this->id ) ),
						'easeInOutExpo' => array('name' => __( 'easeInOutExpo', $this->id ) ),
						'easeInCirc' => array('name' => __( 'easeInCirc', $this->id ) ),
						'easeOutCirc' => array('name' => __( 'easeOutCirc', $this->id ) ),
						'easeInOutCirc' => array('name' => __( 'easeInOutCirc', $this->id ) ),
						'easeInElastic' => array('name' => __( 'easeInElastic', $this->id ) ),
						'easeOutElastic' => array('name' => __( 'easeOutElastic', $this->id ) ),
						'easeInOutElastic' => array('name' => __( 'easeInOutElastic', $this->id ) ),
						'easeInBack' => array('name' => __( 'easeInBack', $this->id ) ),
						'easeOutBack' => array('name' => __( 'easeOutBack', $this->id ) ),
						'easeInOutBack' => array('name' => __( 'easeInOutBack', $this->id ) ),
						'easeInBounce' => array('name' => __( 'easeInBounce', $this->id ) ),
						'easeOutBounce' => array('name' => __( 'easeOutBounce', $this->id ) ),
						'easeInOutBounce' => array('name' => __( 'easeInOutBounce', $this->id ) ),
					),
				),
				array(
					'key'		=> 'coolcarousel_ticker',
					'type'		=> 'select',
					'default'	=> '',
					'label' 	=> __('<span style="color:#800000;"><i class="fa fa-angle-down"></i> Ticker / Scrolling News Mode (N/A Fade mode)</span><br/>Default: OFF<br/>If Ticker is ON, Pager and Controls are OFF.<br/>Note: Ticker Speed = <em>Slide Transition Speed</em> divided by <em>calculated width</em> of all included items (e.g. 600w x 10 images = 6000) --> 500ms / 6000w = .08s (i.e. too fast). FYI: Calculated width is NOT controllable. It\'s dependent upon the actual image dimensions.', $this->id),
					'opts'	=> array(
						'cctrue'		=> array('name' => __( 'Ticker Mode ON', $this->id ) )
					),
				),
				array(
					'key'		=> 'coolcarousel_tickerhover',
					'type'		=> 'select',
					'default'	=> '',
					'label' 	=> __('<span style="color:#800000;"><i class="fa fa-angle-down"></i> Ticker Hover</span><br/>Default: OFF', $this->id),
					'opts'	=> array(
						'cctrue'		=> array('name' => __( 'Ticker Pause on Hover ON', $this->id ) )
					),
				),
				array(
					'key'		=> 'coolcarousel_adaptiveheight',
					'type'		=> 'select',
					'default'	=> '',
					'label' 	=> __('<span style="color:#800000;"><i class="fa fa-angle-down"></i> Adaptive / Dynamic Slider Height</span><br/>Default: OFF<br/>Always ON for sliders in Vertical mode.', $this->id),
					'opts'	=> array(
						'cctrue'		=> array('name' => __( 'Adaptive Height ON', $this->id ) )
					),
				),
				array(
					'key'		=> 'coolcarousel_adaptiveheightspeed',
					'type'		=> 'text_small',
					'default'	=> '',
					'label' 	=> __('<span style="color:#800000;"><i class="fa fa-angle-down"></i> Adaptive Height Transition Speed/Duration (milliseconds)</span><br/>Default: 500 (0.5 seconds)', $this->id)
				),
				array(
					'key'		=> 'coolcarousel_preloadimages',
					'type'		=> 'select',
					'default'	=> '',
					'label' 	=> __('<span style="color:#800000;"><i class="fa fa-angle-down"></i> Preload Images Before Starting Slider</span><br/>Default: VISIBLE.<br/>Tip: use VISIBLE if all Slides are identical dimensions.', $this->id),
					'opts'	=> array(
						'all'		=> array('name' => __( 'Preload ALL', $this->id ) )
					),
				),
			)
		);

		$options[] = array(
			'key'	=> 'coolcarousel_setup_c',
			'type'	=> 'multi',
			//'col'		=> 1, //left side
			'title'		=> __('<span style="color:blue;">Cool Carousel Images-Only Options</span><br/>Disable Touch Swiping or Control Touch Options', $this->id),
			'opts'	=> array(
				array(
					'key'		=> 'coolcarousel_ssl',
					'type'		=> 'select',
					'default'	=> '',
					'label' 	=> __('<span style="color:#800000;"><i class="fa fa-angle-down"></i> Image Source SSL/HTTPS</span><br/>Default: change image source to work with both HTTP and HTTPS URLs<br/>If one or more of your images CANNOT be served via both HTTP and HTTPS, change this option so you do not get "not found" errors.', $this->id),
					'opts'	=> array(
						'nochange'		=> array('name' => __( 'DO NOT Change Image Source', $this->id ) )
					),
				),
				array(
					'key'		=> 'coolcarousel_captions',
					'type'		=> 'select',
					'default'	=> '',
					'label' 	=> __('<span style="color:#800000;"><i class="fa fa-angle-down"></i> Captions</span><br/>Default: OFF<br/>FYI: Captions span entire slide width, not just width of image if image is narrower than the slide.', $this->id),
					'opts'	=> array(
						'cctrue'		=> array('name' => __( 'Captions ON', $this->id ) )
					),
				),
				array(
					'key'		=> 'coolcarousel_grayscale',
					'type'		=> 'select',
					'default'	=> '',
					'label' 	=> __('<span style="color:#800000;"><i class="fa fa-angle-down"></i> Grayscale Mode</span><br/>Default: OFF<br/>FYI: IE10 will display 70% opacity but not grayscale.', $this->id),
					'opts'	=> array(
						'gray'		=> array('name' => __( 'Grayscale ON', $this->id ) ),
						'graynonhover' => array('name' => __( 'Grayscale ON, Color on Hover', $this->id ) )
					),
				),
				array(
					'key'		=> 'coolcarousel_boxstyling',
					'type'		=> 'select',
					'default'	=> '',
					'label' 	=> __('<span style="color:#800000;"><i class="fa fa-angle-down"></i> Box Highlight on Hover</span><br/>Default: OFF<br/>Only use when there are multiple slides displayed at a time (Min # of slides to be shown > 1).', $this->id),
					'opts'	=> array(
						'boxhighlighthover'		=> array('name' => __( 'Box Highlight on Hover', $this->id ) ),
						'plimageframehover'		=> array('name' => __( 'PageLines Image Frame on Hover', $this->id ) ),
						'plimageframe'		=> array('name' => __( 'PageLines Image Frame ON', $this->id ) ),
					),
				),
			)
		);

		$options[] = array(
			'key'	=> 'coolcarousel_setup_d',
			'type'	=> 'multi',
			//'col'		=> 1, //left side
			'title'		=> __('<span style="color:blue;">Cool Carousel Navigation</span><br/>Next, Previous, Start, Stop, etc.', $this->id),
			'opts'	=> array(
				array(
					'key'		=> 'coolcarousel_pager',
					'type'		=> 'select',
					'default'	=> '',
					'label' 	=> __('<span style="color:#800000;"><i class="fa fa-angle-down"></i> Pager</span><br/>Default: ON', $this->id),
					'opts'	=> array(
						'ccfalse'		=> array('name' => __( 'Pager OFF', $this->id ) )
					),
				),
				array(
					'key'		=> 'coolcarousel_pagertype',
					'type'		=> 'select',
					'default'	=> '',
					'label' 	=> __('<span style="color:#800000;"><i class="fa fa-angle-down"></i> Pager Type</span><br/>Example of "short" -> x of y &rarr; 1 / 5<br/>Example of "full" -> circle links below slider<br/>Default: FULL', $this->id),
					'opts'	=> array(
						'short'		=> array('name' => __( 'Pager Type SHORT', $this->id ) )
					),
				),
				array(
					'key'		=> 'coolcarousel_pagershortseparator',
					'type'		=> 'text_small',
					'default'	=> '',
					'label' 	=> __('<span style="color:#800000;"><i class="fa fa-angle-down"></i> Pager Separator (only when Pager Type is "short")</span><br/>Default: "/" (without quotes)<br/>One space on each side of separator will be added.', $this->id)
				),
				array(
					'key'		=> 'coolcarousel_controls',
					'type'		=> 'select',
					'default'	=> '',
					'label' 	=> __('<span style="color:#800000;"><i class="fa fa-angle-down"></i> Next/Prev Controls</span><br/>Default: ON', $this->id),
					'opts'	=> array(
						'ccfalse'		=> array('name' => __( 'Next/Prev Controls OFF', $this->id ) )
					),
				),
				array(
					'key'		=> 'coolcarousel_nextprevlocation',
					'type'		=> 'select',
					'default'	=> '',
					'label' 	=> __('<span style="color:#800000;"><i class="fa fa-angle-down"></i> Location of Custom Next/Prev Text</span><br/>Default: After/Below<br/>Only applies if Custom Next Text and/or Custom Prev Text is added.<br/>If Custom Text is used, navigation arrows are NOT displayed over the slides.', $this->id),
					'opts'	=> array(
						'before'		=> array('name' => __( 'Show Above/Before Slides', $this->id ) )
					),
				),
				array(
					'key'		=> 'coolcarousel_nexttext',
					'type'		=> 'text_small',
					'default'	=> '',
					'label' 	=> __('<span style="color:#800000;"><i class="fa fa-angle-down"></i> Custom Next Text</span><br/>Default: "Next" (without quotes)<br/>Try the "fa fa-chevron-sign-right" <a href="http://fortawesome.github.io/Font-Awesome/icons/" target="_blank">icon</a> or "Right".', $this->id)
				),
				array(
					'key'		=> 'coolcarousel_prevtext',
					'type'		=> 'text_small',
					'default'	=> '',
					'label' 	=> __('<span style="color:#800000;"><i class="fa fa-angle-down"></i> Custom Prev Text</span><br/>Default: "Prev" (without quotes)<br/>Try the "fa fa-chevron-sign-left" <a href="http://fortawesome.github.io/Font-Awesome/icons/" target="_blank">icon</a> or "Left".', $this->id)
				),
				array(
					'key'		=> 'coolcarousel_nextprevdivider',
					'type'		=> 'text_small',
					'default'	=> '',
					'label' 	=> __('<span style="color:#800000;"><i class="fa fa-angle-down"></i> Custom Next/Prev Divider</span><br/>Default: N/A<br/>Try an <a href="http://fortawesome.github.io/Font-Awesome/icons/" target="_blank">icon</a> or "---".', $this->id)
				),
			)
		);

		$options[] = array(
			'key'	=> 'coolcarousel_setup_e',
			'type'	=> 'multi',
			//'col'		=> 1, //left side
			'title'		=> __('<span style="color:blue;">Cool Carousel Slideshow / Auto Play Mode</span><br/>Auto Transition, Auto Start, etc.', $this->id),
			'opts'	=> array(
				array(
					'key'		=> 'coolcarousel_auto',
					'type'		=> 'select',
					'default'	=> '',
					'label' 	=> __('<span style="color:#800000;"><i class="fa fa-angle-down"></i> Auto Play / Continual Transition</span><br/>Default: OFF', $this->id),
					'opts'	=> array(
						'cctrue'		=> array('name' => __( 'YES Auto Play/Transition', $this->id ) )
					),
				),
				array(
					'key'		=> 'coolcarousel_autostart',
					'type'		=> 'select',
					'default'	=> '',
					'label' 	=> __('<span style="color:#800000;"><i class="fa fa-angle-down"></i> Auto Start the Auto Play</span><br/>Default: ON<br/>ON = Starts playing on load.<br/>OFF = starts when the "Start" control is clicked.', $this->id),
					'opts'	=> array(
						'ccfalse'		=> array('name' => __( 'OFF Must first click Play/Start', $this->id ) )
					),
				),
				array(
					'key'		=> 'coolcarousel_autodirection',
					'type'		=> 'select',
					'default'	=> '',
					'label' 	=> __('<span style="color:#800000;"><i class="fa fa-angle-down"></i> Auto Direction</span><br/>Default: Next<br/>The direction of auto show slide transitions', $this->id),
					'opts'	=> array(
						'prev'		=> array('name' => __( 'Prev', $this->id ) )
					),
				),
				array(
					'key'		=> 'coolcarousel_autocontrols',
					'type'		=> 'select',
					'default'	=> '',
					'label' 	=> __('<span style="color:#800000;"><i class="fa fa-angle-down"></i> Start/Stop Controls</span><br/>Default: OFF', $this->id),
					'opts'	=> array(
						'cctrue'		=> array('name' => __( 'Auto Controls ON', $this->id ) )
					),
				),
				array(
					'key'		=> 'coolcarousel_startstoplocation',
					'type'		=> 'select',
					'default'	=> '',
					'label' 	=> __('<span style="color:#800000;"><i class="fa fa-angle-down"></i> Custom Start/Stop Controls Location</span><br/>Default: After/Below', $this->id),
					'opts'	=> array(
						'before'		=> array('name' => __( 'Show Above/Before Slides', $this->id ) )
					),
				),
				array(
					'key'		=> 'coolcarousel_starttext',
					'type'		=> 'text_small',
					'default'	=> '',
					'label' 	=> __('<span style="color:#800000;"><i class="fa fa-angle-down"></i> Start Text</span><br/>Default: "Start" (without quotes)', $this->id)
				),
				array(
					'key'		=> 'coolcarousel_stoptext',
					'type'		=> 'text_small',
					'default'	=> '',
					'label' 	=> __('<span style="color:#800000;"><i class="fa fa-angle-down"></i> Stop Text</span><br/>Default: "Stop" (without quotes)', $this->id)
				),
				array(
					'key'		=> 'coolcarousel_autocontrolscombine',
					'type'		=> 'select',
					'default'	=> '',
					'label' 	=> __('<span style="color:#800000;"><i class="fa fa-angle-down"></i> Combine Start/Stop Controls</span><br/>Default: NO<br/>When slideshow is playing, only "Stop" control is displayed and vice-versa.', $this->id),
					'opts'	=> array(
						'cctrue'		=> array('name' => __( 'Combine Start/Stop Controls YES', $this->id ) )
					),
				),
				array(
					'key'		=> 'coolcarousel_pause',
					'type'		=> 'text_small',
					'default'	=> '',
					'label' 	=> __('<span style="color:#800000;"><i class="fa fa-angle-down"></i> Amount of time between each auto transition (milliseconds)</span><br/>Default: 4000 (4.0 seconds)', $this->id)
				),
				array(
					'key'		=> 'coolcarousel_autohover',
					'type'		=> 'select',
					'default'	=> '',
					'label' 	=> __('<span style="color:#800000;"><i class="fa fa-angle-down"></i> Auto Hover</span><br/>Default: OFF<br/>ON = Transitions will pause when mouse hovers over slider.', $this->id),
					'opts'	=> array(
						'cctrue'		=> array('name' => __( 'Auto Hover ON', $this->id ) )
					),
				),
				array(
					'key'		=> 'coolcarousel_autodelay',
					'type'		=> 'text_small',
					'default'	=> '',
					'label' 	=> __('<span style="color:#800000;"><i class="fa fa-angle-down"></i> Amount of time auto show should wait before starting (milliseconds)</span><br/>Default: 0', $this->id)
				),
			)
		);

		$options[] = array(
			'key'	=> 'coolcarousel_setup_f',
			'type'	=> 'multi',
			//'col'		=> 1, //left side
			'title'		=> __('<span style="color:blue;">Cool Carousel Touch Screen Settings</span><br/>Disable Touch Swiping or Control Touch Options', $this->id),
			'opts'	=> array(
				array(
					'key'		=> 'coolcarousel_touchenabled',
					'type'		=> 'select',
					'default'	=> '',
					'label' 	=> __('<span style="color:#800000;"><i class="fa fa-angle-down"></i> Touch Swipe Transitions</span><br/>Default: ON', $this->id),
					'opts'	=> array(
						'ccfalse'		=> array('name' => __( 'Touch Swipe OFF', $this->id ) )
					),
				),
				array(
					'key'		=> 'coolcarousel_swipethreshold',
					'type'		=> 'text_small',
					'default'	=> '',
					'label' 	=> __('<span style="color:#800000;"><i class="fa fa-angle-down"></i> Swipe Threshold (pixels).</span> # of pixels a touch swipe needs to exceed to execute a slide transition. Only if Touch Enabled is on.<br/>Default: 50', $this->id)
				),
				array(
					'key'		=> 'coolcarousel_onetoonetouch',
					'type'		=> 'select',
					'default'	=> '',
					'label' 	=> __('<span style="color:#800000;"><i class="fa fa-angle-down"></i> One-to-One Touch</span><br/>Non-fade Slides follow the finger as it swipes.<br/>Default: ON.', $this->id),
					'opts'	=> array(
						'ccfalse'		=> array('name' => __( 'One-to-One Touch OFF', $this->id ) )
					),
				),
				array(
					'key'		=> 'coolcarousel_preventdefaultswipex',
					'type'		=> 'select',
					'default'	=> '',
					'label' 	=> __('<span style="color:#800000;"><i class="fa fa-angle-down"></i> x-axis Swipe</span><br/>Default: ON', $this->id),
					'opts'	=> array(
						'ccfalse'		=> array('name' => __( 'x-axis Swipe OFF', $this->id ) )
					),
				),
				array(
					'key'		=> 'coolcarousel_preventdefaultswipey',
					'type'		=> 'select',
					'default'	=> '',
					'label' 	=> __('<span style="color:#800000;"><i class="fa fa-angle-down"></i> y-axis Swipe</span><br/>Default: OFF', $this->id),
					'opts'	=> array(
						'cctrue'		=> array('name' => __( 'y-axis Swipe ON', $this->id ) )
					),
				),
			)
		);


		return $options;

	}




   function section_template() {
		if(function_exists('pl_has_editor') && pl_has_editor()){
			$clone_id = $this->get_the_id();
		} else {
	        global $pagelines_ID;
	        $oset = array('post_id' => $pagelines_ID);
			$clone_id = $this->oset['clone_id'];
		}

		$nexttext = $this->opt('coolcarousel_nexttext');
			//$nexttext = esc_html($nexttext);
			$nexttext = do_shortcode($nexttext);
		$prevtext = $this->opt('coolcarousel_prevtext');
			//$prevtext = esc_html($prevtext);
			$prevtext = do_shortcode($prevtext);
		$nextprevdivider = $this->opt('coolcarousel_nextprevdivider');
			//$nextprevdivider = esc_html($nextprevdivider);
			$nextprevdivider = do_shortcode($nextprevdivider);

		if($this->opt('coolcarousel_ticker') == 'cctrue'){
			$tickeron = 1;
		} else {
			$tickeron = 0;
		}

	if($tickeron == 0){
		if( !empty($nexttext) || !empty($prevtext) ){
			$nextprevstuff = "<div class='cc-outside cc-nextprev'>";
			//$nextprevstuff .= "<h3>This div is outside of the slider</h3>";
			$nextprevstuff .= "<p><span class='cc-prev-custom $clone_id'></span> $nextprevdivider <span class='cc-next-custom $clone_id'></span></p>";
			$nextprevstuff .= "</div>";
		}

		$starttext = $this->opt('coolcarousel_starttext');
			//$starttext = esc_html($starttext);
			$starttext = do_shortcode($starttext);
		$stoptext = $this->opt('coolcarousel_stoptext');
			//$stoptext = esc_html($stoptext);
			$stoptext = do_shortcode($stoptext);

		if( !empty($starttext) || !empty($stoptext) ){
			$startstopstuff = "<div class='cc-outside cc-autocontrols'>";
			//$startstopstuff .= "<h3>This div is outside of the slider</h3>";
			$startstopstuff .= "<p><span class='cc-startstop $clone_id'></span></p>";
			$startstopstuff .= "</div>";
		}

		$nextprevlocation = $this->opt('coolcarousel_nextprevlocation') ? $this->opt('coolcarousel_nextprevlocation') : 'after';
		$startstoplocation = $this->opt('coolcarousel_startstoplocation') ? $this->opt('coolcarousel_startstoplocation') : 'after';

		if(!empty($startstopstuff) && $startstoplocation == 'before'){
			echo $startstopstuff;
		}
		if(!empty($nextprevstuff) && $nextprevlocation == 'before'){
			echo $nextprevstuff;
		}
	}

		$items = $this->get_items();
			// check to see if there's anything to display first..
			if ( empty( $items ) ) {
			echo setup_section_notify( $this );
			return; // stop display here.
		}

		if(function_exists('pl_has_editor') && pl_has_editor()){
			echo "<ul class='dms' id='cool-carousel-$clone_id'>";
		} else {
			echo "<ul class='plv2' id='cool-carousel-$clone_id'>";
		}

			foreach ( $items as $item )
			$this->do_the_item( $item );

		echo '</ul>';

if($tickeron == 0){
	if(!empty($nextprevstuff) && $nextprevlocation == 'after'){
		echo $nextprevstuff;
	}
	if(!empty($startstopstuff) && $startstoplocation == 'after'){
		echo $startstopstuff;
	}
}


	} // section_template


	function get_items() {

		$args = array(
			'post_type'      => $this->ptID,
			'orderby'        => $this->opt('coolcarousel_orderby') ? $this->opt('coolcarousel_orderby') : 'ID',
			'order'          => $this->opt('coolcarousel_order') ? $this->opt('coolcarousel_order') : 'DESC',
			$this->taxID     => $this->opt('coolcarousel_set') ? $this->opt('coolcarousel_set') : null,
			'posts_per_page' => $this->opt('coolcarousel_totalslides') ? $this->opt('coolcarousel_totalslides') : -1
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

		if ( plmeta('coolcarousel_image', $oset)
			|| plmeta('coolcarousel_image_media_library', $oset) )
			return 'image';
		if ( (plmeta('coolcarousel_video_site', $oset) && plmeta('coolcarousel_video_id', $oset )) )
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

				$ccimagemanual = plmeta('coolcarousel_image', $oset);
				$ccimagelibraryid = plmeta('coolcarousel_image_media_library', $oset);
				$ccimagelibrarysize = plmeta('coolcarousel_image_media_library_size', $oset) ? plmeta('coolcarousel_image_media_library_size', $oset) : 'full';

				if( $ccimagemanual ){
					$coolcarousel_image = $ccimagemanual;
				} elseif( $ccimagelibraryid ) {
					$coolcarousel_library_attributes = wp_get_attachment_image_src( $ccimagelibraryid, $ccimagelibrarysize );
					$coolcarousel_image = $coolcarousel_library_attributes[0];
				}

				$imagesource = $coolcarousel_image;
					if ( $this->opt('coolcarousel_ssl') !== 'nochange' ){
						// example: http://imagesource.jpg and https://imagesource.jpg --> //imagesource.jpg
						$imagesource = str_replace('http:', '', $imagesource);
						$imagesource = str_replace('https:', '', $imagesource);
					}

				$attributes = array(
					'src'    => $imagesource,
					'target' => plmeta('coolcarousel_image_target', $oset),
					'title'  => plmeta('coolcarousel_title_text', $oset) ? plmeta('coolcarousel_title_text', $oset) : get_the_title( $post_id ),
					'alt'    => plmeta('coolcarousel_alt_text', $oset) ? plmeta('coolcarousel_alt_text', $oset) : get_the_title( $post_id ),
				);
				$attributes = array_filter( array_map( 'esc_attr', $attributes ) );



				$graymode = $this->opt('coolcarousel_grayscale');
				$boxstyling = $this->opt('coolcarousel_boxstyling');

				$image = '<img class="cool-carousel-image';

					if($graymode == 'gray'){ $image .= ' cc-grayscale'; }
					if($graymode == 'graynonhover'){ $image .= ' cc-grayscale-hover'; }

					if($boxstyling == 'boxhighlighthover'){ $image .= ' cc-boxhighlight-hover'; }
					if($boxstyling == 'plimageframe'){ $image .= ' pl-imageframe'; }
					if($boxstyling == 'plimageframehover'){ $image .= ' pl-imageframe-hover'; }

				$image .= '"';



				foreach ( $attributes as $key => $value )
					if ( $value )
						$image .= "$key=\"$value\" ";

				$image .= '/>';

				if ( $link = plmeta('coolcarousel_image_link', $oset) ){
					if (plmeta('coolcarousel_image_target', $oset)){
						$image = sprintf('<a href="%s" target="_blank">%s</a>', $link, $image);
					} else {
						$image = sprintf('<a href="%s">%s</a>', $link, $image);
					}
				}

				$content = $image;
				break;

			case 'video' :
				$host = plmeta('coolcarousel_video_site', $oset);
				$id   = plmeta('coolcarousel_video_id', $oset);
				$relatedon = plmeta('coolcarousel_plvideorelated', $oset);
					if($relatedon){ $relatedoff = ''; } else { $relatedoff = 'true'; } // https://github.com/pagelines/DMS/blob/9407e44a1553fd27a69d21ce3d0f303f84293d17/includes/class.shortcodes.php
				if ( $host && $id )
					$content = do_shortcode("[pl_video type='$host' id='$id' related='$relatedoff']");
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

		$ccitemclass = plmeta('coolcarousel_item_class', $oset) ? plmeta('coolcarousel_item_class', $oset) : '';

		$out = sprintf('<li class="cool-carousel-item %s %s-item slide %s">%s</li>', $ccitemclass, $type, $post_id, do_shortcode( $content ) );

		return $out;
	}



	function column_display( $column ) {
		// get column data
		global $post;

		$ccitemclass = get_post_meta($post->ID, 'coolcarousel_item_class', true );

		// Image
		$ccimagemanual = get_post_meta($post->ID, 'coolcarousel_image', true );
		$ccimagelibraryid = get_post_meta($post->ID, 'coolcarousel_image_media_library', true );
		$ccimagelibrarysize = get_post_meta($post->ID, 'coolcarousel_image_media_library_size', true );

		if( $ccimagemanual ){
			$coolcarousel_image = $ccimagemanual;
		} elseif( $ccimagelibraryid ) {
			$coolcarousel_library_attributes = wp_get_attachment_image_src( $ccimagelibraryid, $ccimagelibrarysize );
			$coolcarousel_image = $coolcarousel_library_attributes[0];
		}

		// Video
		if(get_post_meta($post->ID, 'coolcarousel_video_site', true )
		   && get_post_meta($post->ID, 'coolcarousel_video_id', true )){
			$cc_video_site = get_post_meta($post->ID, 'coolcarousel_video_site', true );
			$cc_video_id = get_post_meta($post->ID, 'coolcarousel_video_id', true );
		}
		// HTML
		$cc_code = get_post_meta($post->ID, 'coolcarousel_code', true );

		// Content to Display
		if(!empty($coolcarousel_image)){
			$cctype = 'Image';
		} elseif(!empty($cc_video_site)) {
			$cctype = 'Video';
		} elseif(!empty($cc_code)){
			$cctype = 'HTML';
		} else{
			$cctype = '';
		}

		// display columns
		switch ($column){
			case 'ccmediatype':
				echo "<strong>$cctype</strong>";
				if($cctype == 'Image' && $ccimagemanual) {
					echo ' - <em>Manual</em>';
				} elseif($cctype == 'Image' && $ccimagelibraryid) {
					echo " - <em>Library</em><br/>Size: <em>$ccimagelibrarysize</em>";
				} elseif($cctype == 'Video') {
					echo " - <em>$cc_video_site</em> - <em>$cc_video_id</em>";
				} else {
					echo '';
				}
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
			case 'ccclass':
				if(!empty($ccitemclass)){
					echo $ccitemclass;
				}
				break;
			case $this->taxID:
				echo get_the_term_list($post->ID, 'cool-carousel-sets', '', ', ','');
				break;
		}
	}
}