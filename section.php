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
/*
TODO
-easing CSS
-vertical
-fade
-additional images
-description
-same slide sizes
-test all options
-conditional options
-regex
-default limit
-add grayscale option
-condense option boxes
*/
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
		global $pagelines_ID;
        $oset = array('post_id' => $pagelines_ID);
		$clone_id = $this->oset['clone_id'];

		$easing = $this->opt('coolcarousel_easing');
			if($easing){
				wp_enqueue_script('pagelines-easing'); // easing must be before coolcarousel
			}

		wp_enqueue_script('coolcarousel', $this->base_url.'/js/coolcarousel.min.js', array( 'jquery' ), '4.1.1', true);
    }


	function section_head(){

        global $pagelines_ID;
        $oset = array('post_id' => $pagelines_ID);
		$clone_id = $this->oset['clone_id'];

		//$value = $this->opt('option_key'); // http://docs.pagelines.com/developer/dms-option-engine

		//General
		$mode = $this->opt('coolcarousel_mode');
		$speed = $this->opt('coolcarousel_speed');
		$slidemargin = $this->opt('coolcarousel_slidemargin');
		$startslide = $this->opt('coolcarousel_startslide');
		$randomstart = $this->opt('coolcarousel_randomstart');
		$slideselector = $this->opt('coolcarousel_slideselector');
		$infiniteloop = $this->opt('coolcarousel_infiniteloop');
		$hidecontrolonend = $this->opt('coolcarousel_hidecontrolonend');
		$easing = $this->opt('coolcarousel_easing');
		$captions = $this->opt('coolcarousel_captions');
		$ticker = $this->opt('coolcarousel_ticker');
		$tickerhover = $this->opt('coolcarousel_tickerhover');
		$adaptiveheight = $this->opt('coolcarousel_adaptiveheight');
		$adaptiveheightspeed = $this->opt('coolcarousel_adaptiveheightspeed');
		$video = $this->opt('coolcarousel_video');
		//$responsive = $this->opt('coolcarousel_responsive');
		$usecss = $this->opt('coolcarousel_usecss');
		$preloadimages = $this->opt('coolcarousel_preloadimages');
		$touchenabled = $this->opt('coolcarousel_touchenabled');
		$swipethreshold = $this->opt('coolcarousel_swipethreshold');
		$onetoonetouch = $this->opt('coolcarousel_onetoonetouch');
		$preventdefaultswipex = $this->opt('coolcarousel_preventdefaultswipex');
		$preventdefaultswipey = $this->opt('coolcarousel_preventdefaultswipey');
		//Pager
		$pager = $this->opt('coolcarousel_pager');
		$pagertype = $this->opt('coolcarousel_pagertype');
		$pagershortseparator = $this->opt('coolcarousel_pagershortseparator');
		$pagerselector = $this->opt('coolcarousel_pagerselector');
		$pagercustom = $this->opt('coolcarousel_pagercustom');
		$buildpager = $this->opt('coolcarousel_buildpager');
		//Controls
		$controls = $this->opt('coolcarousel_controls');
		$nexttext = $this->opt('coolcarousel_nexttext');
		$prevtext = $this->opt('coolcarousel_prevtext');
		$nextselector = $this->opt('coolcarousel_nextselector');
		$prevselector = $this->opt('coolcarousel_prevselector');
		$autocontrols = $this->opt('coolcarousel_autocontrols');
		$starttext = $this->opt('coolcarousel_starttext');
		$stoptext = $this->opt('coolcarousel_stoptext');
		$autocontrolscombine = $this->opt('coolcarousel_autocontrolscombine');
		$autocontrolsselector = $this->opt('coolcarousel_autocontrolsselector');
		//Auto
		$auto = $this->opt('coolcarousel_auto');
		$pause = $this->opt('coolcarousel_pause');
		$autostart = $this->opt('coolcarousel_autostart');
		$autodirection = $this->opt('coolcarousel_autodirection');
		$autohover = $this->opt('coolcarousel_autohover');
		$autodelay = $this->opt('coolcarousel_autodelay');
		//Carousel
		$minslides = $this->opt('coolcarousel_minslides');
		$maxslides = $this->opt('coolcarousel_maxslides');
		$moveslides = $this->opt('coolcarousel_moveslides');
		$slidewidth = $this->opt('coolcarousel_slidewidth');
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
		?>
		<script type="text/javascript">
		/*<![CDATA[*/
			jQuery(document).ready(function(){
				jQuery("#coolcarousel-<?php echo $clone_id ?>").bxSlider({
					<?php
					if(!empty($mode)){ echo 'mode: '. $mode .','; }
					if(!empty($speed)){ echo 'speed: '. $speed .','; }
					if(!empty($slideMargin)){ echo 'slideMargin: '. $slideMargin .','; }
					if(!empty($startSlide)){ echo 'startSlide: '. $startSlide .','; }
					if(!empty($randomStart)){ echo 'randomStart: '. $randomStart .','; }
					if(!empty($slideSelector)){ echo 'slideSelector: '. $slideSelector .','; }
					if(!empty($infiniteLoop)){ echo 'infiniteLoop: '. $infiniteLoop .','; }
					if(!empty($hideControlOnEnd)){ echo 'hideControlOnEnd: '. $hideControlOnEnd .','; }
					if(!empty($easing)){ echo 'easing: '. $easing .','; }
					if(!empty($captions)){ echo 'captions: '. $captions .','; }
					if(!empty($ticker)){ echo 'ticker: '. $ticker .','; }
					if(!empty($tickerHover)){ echo 'tickerHover: '. $tickerHover .','; }
					if(!empty($adaptiveHeight)){ echo 'adaptiveHeight: '. $adaptiveHeight .',';
						if(!empty($adaptiveHeightSpeed)){ echo 'adaptiveHeightSpeed: '. $adaptiveHeightSpeed .','; }
						}
					if(!empty($video)){ echo 'video: '. $video .','; }
					//if(!empty($responsive)){ echo 'responsive: '. $responsive .','; }
					if(!empty($useCSS)){ echo 'useCSS: '. $useCSS .','; }
					if(!empty($preloadImages)){ echo 'preloadImages: '. $preloadImages .','; }
					if(!empty($touchEnabled)){ echo 'touchEnabled: '. $touchEnabled .','; }
					if(!empty($swipeThreshold)){ echo 'swipeThreshold: '. $swipeThreshold .','; }
					if(!empty($oneToOneTouch)){ echo 'oneToOneTouch: '. $oneToOneTouch .','; }
					if(!empty($preventDefaultSwipeX)){ echo 'preventDefaultSwipeX: '. $preventDefaultSwipeX .','; }
					if(!empty($preventDefaultSwipeY)){ echo 'preventDefaultSwipeY: '. $preventDefaultSwipeY .','; }

					if(!empty($pager)){ echo 'pager: '. $pager .','; }
					if(!empty($pagerType)){ echo 'pagerType: '. $pagerType .','; }
					if(!empty($pagerShortSeparator)){ echo 'pagerShortSeparator: '. $pagerShortSeparator .','; }
					if(!empty($pagerSelector)){ echo 'pagerSelector: '. $pagerSelector .','; }
					if(!empty($pagerCustom)){ echo 'pagerCustom: '. $pagerCustom .','; }
					if(!empty($buildPager)){ echo 'buildPager: '. $buildPager .','; }

					if(!empty($controls)){ echo 'controls: '. $controls .','; }
					if(!empty($nextText)){ echo 'nextText: bob'. $nextText .','; }
					if(!empty($prevText)){ echo 'prevText: Sally'. $prevText .','; }
					if(!empty($nextSelector)){ echo 'nextSelector: '. $nextSelector .','; }
					if(!empty($prevSelector)){ echo 'prevSelector: '. $prevSelector .','; }
					if(!empty($autoControls)){ echo 'autoControls: '. $autoControls .','; }
					if(!empty($startText)){ echo 'startText: '. $startText .','; }
					if(!empty($stopText)){ echo 'stopText: '. $stopText .','; }
					if(!empty($autoControlsCombine)){ echo 'autoControlsCombine: '. $autoControlsCombine .','; }
					if(!empty($autoControlsSelector)){ echo 'autoControlsSelector: '. $autoControlsSelector .','; }

					if(!empty($auto)){ echo 'auto: '. $auto .','; }
					if(!empty($pause)){ echo 'pause: '. $pause .','; }
					if(!empty($autoStart)){ echo 'autoStart: '. $autoStart .','; }
					if(!empty($autoDirection)){ echo 'autoDirection: '. $autoDirection .','; }
					if(!empty($autoHover)){ echo 'autoHover: '. $autoHover .','; }
					if(!empty($autoDelay)){ echo 'autoDelay: '. $autoDelay .','; }

					if(!empty($minSlides)){ echo 'minSlides: '. $minSlides .','; }
					if(!empty($maxSlides)){ echo 'maxSlides: '. $maxSlides .','; }
					if(!empty($moveSlides)){ echo 'moveSlides: '. $moveSlides .','; }
					if(!empty($slideWidth)){ echo 'slideWidth: '. $slideWidth .','; }

					if(!empty($onSliderLoad)){ echo 'onSliderLoad: '. $onSliderLoad .','; }
					if(!empty($onSlideBefore)){ echo 'onSlideBefore: '. $onSlideBefore .','; }
					if(!empty($onSlideAfter)){ echo 'onSlideAfter: '. $onSlideAfter .','; }
					if(!empty($onSlideNext)){ echo 'onSlideNext: '. $onSlideNext .','; }
					if(!empty($onSlidePrev)){ echo 'onSlidePrev: '. $onSlidePrev .','; }

					if(!empty($goToSlide)){ echo 'goToSlide: '. $goToSlide .','; }
					if(!empty($goToNextSlide)){ echo 'goToNextSlide: '. $goToNextSlide .','; }
					if(!empty($goToPrevSlide)){ echo 'goToPrevSlide: '. $goToPrevSlide .','; }
					if(!empty($startAuto)){ echo 'startAuto: '. $startAuto .','; }
					if(!empty($stopAuto)){ echo 'stopAuto: '. $stopAuto .','; }
					if(!empty($getCurrentSlide)){ echo 'getCurrentSlide: '. $getCurrentSlide .','; }
					if(!empty($getSlideCount)){ echo 'getSlideCount: '. $getSlideCount .','; }
					if(!empty($reloadSlider)){ echo 'reloadSlider: '. $reloadSlider .','; }
					if(!empty($destroySlider)){ echo 'destroySlider: '. $destroySlider .','; }
					?>
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
				'description' 		=> __( 'For creating items in Cool Carousel layouts', $this->id ),
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
						'inputlabel' 	=> __( 'Image Caption and Title Text<br/>Default:Post Title)', $this->id ),
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
				'shortexp'	=> __( 'Options for displaying Cool Carousel', $this->id ),
				'selectvalues'	=> array(
					'coolcarousel_set' => array(
						'type' 			=> 'select_taxonomy',
						'taxonomy_id'	=> $this->taxID,
						'inputlabel'	=> __( 'Cool Carousel Set To Show', $this->id ),
					),
					'coolcarousel_totalslides' => array(
						'type' 			=> 'text_small',
						'size'			=> 'small',
						'inputlabel' 	=> __( 'Max # of slides to include.<br/>Default: unlimited<br/>If there are 20 slides in your chosen Set, you can enter a number like 7 and only 7 of the 20 slides will be in the slider.', $this->id ),
					),


// Start of Slider Script Options

					'coolcarousel_mode' => array(
						'type' => 'select',
						'selectvalues' => array(
							'fade' => array('name' => __( 'Fade', $this->id )),
							'vertical' => array('name' => __( 'Vertical', $this->id )),
						),
						'inputlabel' => __( 'Mode<br/>Default: Horizontal', $this->id )
					),
					'coolcarousel_speed' => array(
						'type' 			=> 'text_small',
						'size'			=> 'small',
						'inputlabel' 	=> __( 'Slide transition duration (milliseconds).<br/>Default: 500 (0.5 seconds)', $this->id ),
					),
					'coolcarousel_slideMargin' => array(
						'type' 			=> 'text_small',
						'size'			=> 'small',
						'inputlabel' 	=> __( 'Margin between each slide (pixels).<br/>Default: 0', $this->id ),
					),
//coolcarousel_startSlide
//coolcarousel_randomStart
//coolcarousel_slideSelector
					'coolcarousel_infiniteLoop' => array(
						'type' 		=> 'select',
						'selectvalues'	=> array(
							'false'		=> array('name' => __( 'Infinite Loop OFF', $this->id ) )
						),
						'inputlabel' => __( 'Infinite Loop<br/>Default: ON', $this->id ),
					),
					'coolcarousel_hideControlOnEnd' => array(
						'type' 		=> 'select',
						'selectvalues'	=> array(
							'true'		=> array('name' => __( 'Hide Control On End YES', $this->id ) )
						),
						'inputlabel' => __( 'Hide Control On End<br/>Default: NO', $this->id ),
					),
					'coolcarousel_easing' => array(
						'type' 		=> 'select',
						'selectvalues'	=> array(
							'linear'		=> array('name' => __( 'Linear', $this->id ) ),
							'ease'		=> array('name' => __( 'Ease', $this->id ) ),
							'ease-in'		=> array('name' => __( 'Ease-In', $this->id ) ),
							'ease-out'		=> array('name' => __( 'Ease-Out', $this->id ) ),
							'ease-in-out'		=> array('name' => __( 'Ease-In-Out', $this->id ) ),
							//'cubic-bezier(n,n,n,n)'		=> array('name' => __( 'cubic-bezier(n,n,n,n)', $this->id ) ),
						),
						'inputlabel' => __( 'Easing<br/>Default: n/a', $this->id ),
					),
					'coolcarousel_captions' => array(
						'type' 		=> 'select',
						'selectvalues'	=> array(
							'true'		=> array('name' => __( 'Captions ON', $this->id ) )
						),
						'inputlabel' => __( 'Captions<br/>Default: OFF', $this->id ),
					),
					'coolcarousel_ticker' => array(
						'type' 		=> 'select',
						'selectvalues'	=> array(
							'true'		=> array('name' => __( 'Ticker Mode ON', $this->id ) )
						),
						'inputlabel' => __( 'Ticker / Scrolling News Mode<br/>Default: OFF', $this->id ),
					),
/*
// does not work if using CSS Transitions
					'coolcarousel_tickerHover' => array(
						'type' 		=> 'select',
						'selectvalues'	=> array(
							'true'		=> array('name' => __( 'Ticker Pause on Hover ON', $this->id ) )
						),
						'inputlabel' => __( 'Ticker Hover<br/>Default: OFF', $this->id ),
					),
*/
					'coolcarousel_adaptiveHeight' => array(
						'type' 		=> 'select',
						'selectvalues'	=> array(
							'true'		=> array('name' => __( 'Adaptive Height ON', $this->id ) )
						),
						'inputlabel' => __( 'Adaptive / Dynamic Slider Height<br/>Default: OFF', $this->id ),
					),
					'coolcarousel_adaptiveHeightSpeed' => array(
						'type' 			=> 'text_small',
						'size'			=> 'small',
						'inputlabel' 	=> __( 'Adaptive Height Transition Duration (milliseconds).<br/>Default: 500 (0.5 seconds)', $this->id ),
					),
/*
					'coolcarousel_video' => array(
						'type' 		=> 'select',
						'selectvalues'	=> array(
							'false'		=> array('name' => __( 'False', $this->id ) ),
							'true'		=> array('name' => __( 'True', $this->id ) )
						),
						'inputlabel' => __( 'Does this Cool Carousel Set have one or more videos?<br/>Default: False', $this->id ), // auto-detect video?
					),
*/
/*
// no longer an option
					'coolcarousel_responsive' => array(
						'type' 		=> 'select',
						'selectvalues'	=> array(
							'false'		=> array('name' => __( 'Responsive OFF', $this->id ) )
						),
						'inputlabel' => __( 'Responsive Slider<br/>Default: ON', $this->id ),
					),
*/

//coolcarousel_useCSS
					'coolcarousel_preloadImages' => array(
						'type' 		=> 'select',
						'selectvalues'	=> array(
							'all'		=> array('name' => __( 'Preload ALL', $this->id ) )
						),
						'inputlabel' => __( 'Preload Images Before Starting Slider<br/>Default: VISIBLE.<br/>Tip: use VISIBLE if all slides are identical dimensions.', $this->id ),
					),
					'coolcarousel_touchEnabled' => array(
						'type' 		=> 'select',
						'selectvalues'	=> array(
							'false'		=> array('name' => __( 'Touch Swipe OFF', $this->id ) )
						),
						'inputlabel' => __( 'Touch Swipe Transitions<br/>Default: ON', $this->id ),
					),
					'coolcarousel_swipeThreshold' => array(
						'type' 			=> 'text_small',
						'size'			=> 'small',
						'inputlabel' 	=> __( 'Swipe Threshold (pixels). # of pixels a touch swipe needs to exceed to execute a slide transition. Only if Touch Enabled is on.<br/>Default: 50', $this->id ),
					),
					'coolcarousel_oneToOneTouch' => array(
						'type' 		=> 'select',
						'selectvalues'	=> array(
							'false'		=> array('name' => __( 'One-to-One Touch OFF', $this->id ) )
						),
						'inputlabel' => __( 'One-to-One Touch<br/>Default: ON.<br/>Non-fade slides follow the finger as it swipes.', $this->id ),
					),
					'coolcarousel_preventDefaultSwipeX' => array(
						'type' 		=> 'select',
						'selectvalues'	=> array(
							'false'		=> array('name' => __( 'x-axis Swipe OFF', $this->id ) )
						),
						'inputlabel' => __( 'x-axis Swipe<br/>Default: ON', $this->id ),
					),
					'coolcarousel_preventDefaultSwipeY' => array(
						'type' 		=> 'select',
						'selectvalues'	=> array(
							'true'		=> array('name' => __( 'y-axis Swipe ON', $this->id ) )
						),
						'inputlabel' => __( 'y-axis Swipe<br/>Default: OFF', $this->id ),
					),
//Pager
					'coolcarousel_pager' => array(
						'type' 		=> 'select',
						'selectvalues'	=> array(
							'false'		=> array('name' => __( 'Pager OFF', $this->id ) )
						),
						'inputlabel' => __( 'Pager<br/>Default: ON', $this->id ),
					),
					'coolcarousel_pagerType' => array(
						'type' 		=> 'select',
						'selectvalues'	=> array(
							'short'		=> array('name' => __( 'Pager Type x of y (e.g. 1 / 5)', $this->id ) )
						),
						'inputlabel' => __( 'Pager Type<br/>Default: FULL (i.e. Pager Link)', $this->id ),
					),
					'coolcarousel_pagerShortSeparator' => array(
						'type' 			=> 'text_small',
						'size'			=> 'small',
						'inputlabel' 	=> __( 'Pager Separator (only when Pager Type is "short")<br/>Default: " / " (without quotes)<br/>Do not forget spaces on each side if desired.', $this->id ),
					),
//coolcarousel_pagerSelector
//coolcarousel_pagerCustom
//coolcarousel_buildPager
//Controls
					'coolcarousel_controls' => array(
						'type' 		=> 'select',
						'selectvalues'	=> array(
							'false'		=> array('name' => __( 'Next/Prev Controls OFF', $this->id ) )
						),
						'inputlabel' => __( 'Next/Prev Controls<br/>Default: ON', $this->id ),
					),
/*
					'coolcarousel_nextText' => array(
						'type' 			=> 'text_small',
						'size'			=> 'small',
						'inputlabel' 	=> __( 'Next Text<br/>Default: "Next" (without quotes)', $this->id ),
					),
					'coolcarousel_prevText' => array(
						'type' 			=> 'text_small',
						'size'			=> 'small',
						'inputlabel' 	=> __( 'Prev Text<br/>Default: "Prev" (without quotes)', $this->id ),
					),
*/
//coolcarousel_nextSelector
//coolcarousel_prevSelector
					'coolcarousel_autoControls' => array(
						'type' 		=> 'select',
						'selectvalues'	=> array(
							'true'		=> array('name' => __( 'Auto Controls ON', $this->id ) )
						),
						'inputlabel' => __( 'Start/Stop Controls<br/>Default: OFF', $this->id ),
					),
					'coolcarousel_startText' => array(
						'type' 			=> 'text_small',
						'size'			=> 'small',
						'inputlabel' 	=> __( 'Start Text<br/>Default: "Start" (without quotes)', $this->id ),
					),
					'coolcarousel_stopText' => array(
						'type' 			=> 'text_small',
						'size'			=> 'small',
						'inputlabel' 	=> __( 'Stop Text<br/>Default: "Stop" (without quotes)', $this->id ),
					),
					'coolcarousel_autoControlsCombine' => array(
						'type' 		=> 'select',
						'selectvalues'	=> array(
							'true'		=> array('name' => __( 'Combine Start/Stop Controls YES', $this->id ) )
						),
						'inputlabel' => __( 'Combine Start/Stop Controls<br/>Default: NO<br/>When slideshow is playing, only "Stop" control is displayed and vice-versa.', $this->id ),
					),
//coolcarousel_autoControlsSelector
//Auto
					'coolcarousel_auto' => array(
						'type' 		=> 'select',
						'selectvalues'	=> array(
							'true'		=> array('name' => __( 'Auto Transition ON', $this->id ) )
						),
						'inputlabel' => __( 'Auto/Continual Transition<br/>Default: OFF', $this->id ),
					),
					'coolcarousel_pause' => array(
						'type' 			=> 'text_small',
						'size'			=> 'small',
						'inputlabel' 	=> __( 'Amount of time between each auto transition (milliseconds).<br/>Default: 4000 (4.0 seconds)', $this->id ),
					),
					'coolcarousel_autoStart' => array(
						'type' 		=> 'select',
						'selectvalues'	=> array(
							'false'		=> array('name' => __( 'Auto Start OFF', $this->id ) )
						),
						'inputlabel' => __( 'Auto Start<br/>Default: ON<br/>ON = Starts playing on load.<br/>OFF = starts when the "Start" control is clicked.', $this->id ),
					),
					'coolcarousel_autoDirection' => array(
						'type' 		=> 'select',
						'selectvalues'	=> array(
							'prev'		=> array('name' => __( 'Prev', $this->id ) )
						),
						'inputlabel' => __( 'Auto Direction<br/>Default: Next<br/>The direction of auto show slide transitions', $this->id ),
					),
					'coolcarousel_autoHover' => array(
						'type' 		=> 'select',
						'selectvalues'	=> array(
							'true'		=> array('name' => __( 'Auto Hover ON', $this->id ) )
						),
						'inputlabel' => __( 'Auto Hover<br/>Default: OFF<br/>ON = Transitions will pause when mouse hovers over slider.', $this->id ),
					),
					'coolcarousel_autoDelay' => array(
						'type' 			=> 'text_small',
						'size'			=> 'small',
						'inputlabel' 	=> __( 'Amount of time auto show should wait before starting (milliseconds).<br/>Default: 0', $this->id ),
					),
//Carousel
					'coolcarousel_minslides' => array(
						'type' 			=> 'text_small',
						'size'			=> 'small',
						'inputlabel' 	=> __( 'Min # of slides to be shown.<br/>Default: 1<br/>Slides will be sized down if carousel becomes smaller than the original size.', $this->id ),
					),
					'coolcarousel_maxslides' => array(
						'type' 			=> 'text_small',
						'size'			=> 'small',
						'inputlabel' 	=> __( 'Max # of slides to be shown.<br/>Default: 1<br/>Slides will be sized up if carousel becomes larger than the original size.', $this->id ), //vertical carousels can omit: http://bxslider.com/faqs
					),
					'coolcarousel_moveSlides' => array(
						'type' 			=> 'text_small',
						'size'			=> 'small',
						'inputlabel' 	=> __( 'Slides to Move each Transition<br/>Default: The # of fully-visible slides.<br/>Must be >= minSlides and <= maxSlides.', $this->id ),
					),
					'coolcarousel_slidewidth' => array(
						'type' 			=> 'text_small',
						'size'			=> 'small',
						'inputlabel' 	=> __( 'The width of each slide.<br/>Default: full-width', $this->id ), //vertical carousels can omit: http://bxslider.com/faqs
					),
				),
			),
// End of Slider Script Options



				'coolcarousel_ordering' => array(
					'type'		=> 'multi_option',
					'title'		=> __( 'Cool Carousel Ordering Options', $this->id ),
					'shortexp'	=> __( 'Optionally control the ordering of the Cool Carousel', $this->id ),
					'exp'		=> __( 'You may want to order Cool Carousel slides using a post type order plugin for WordPress. However, if you would like to do it algorithmically, we have provided these options for you.', $this->id ),
					'selectvalues'	=> array(
						'coolcarousel_orderby' => array(
							'type'			=> 'select',
							'default'		=> 'ID',
							'inputlabel'	=> 'Order Cool Carousel By (If Not With Post Type Order Plugin)',
							'selectvalues' => array(
								'ID' 		=> array('name' => __( 'Post ID (Default)', $this->id ) ),
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