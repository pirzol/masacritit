<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Pojo_Lightbox_Setting_Page extends Pojo_Settings_Page_Base {

	public function section_lightbox( $sections = array() ) {
		$fields = array();

		$fields[] = array(
			'id' => 'lightbox_script',
			'title' => __( 'LightBox Script', 'pojo-lightbox' ),
			'type' => Pojo_Settings::FIELD_SELECT,
			'options' => array(
				'photoswipe' => __( 'PhotoSwipe', 'pojo-lightbox' ),
				//'magnific' => __( 'Magnific Popup', 'pojo-lightbox' ),
				'prettyPhoto' => __( 'prettyPhoto', 'pojo-lightbox' ),
			),
			'std' => 'photoswipe',
		);

		$fields = array_merge( $fields, $this->_get_pretty_photo_fields() );
		$fields = array_merge( $fields, $this->_get_photoswipe_fields() );

		$fields = array_merge( $fields, $this->_get_global_fields() );

		$sections[] = array(
			'id' => 'section-lightbox',
			'page' => $this->_page_id,
			'title' => __( 'Lightbox', 'pojo-lightbox' ),
			'intro' => '',
			'fields' => $fields,
		);

		return $sections;
	}
	
	protected function _get_pretty_photo_fields() {
		$fields = array();
		$wrapper_classes = 'lightbox-fields script-prettyPhoto';

		$fields[] = array(
			'id' => 'lightbox_theme',
			'title' => __( 'LightBox Theme', 'pojo-lightbox' ),
			'type' => Pojo_Settings::FIELD_SELECT,
			'desc' => __( '6 Themes to choose from', 'pojo-lightbox' ),
			'class' => $wrapper_classes,
			'options' => array(
				'' => __( 'Default', 'pojo-lightbox' ),
				'light_rounded' => __( 'Light Rounded', 'pojo-lightbox' ),
				'dark_rounded' => __( 'Dark Rounded', 'pojo-lightbox' ),
				'dark_square' => __( 'Dark Square', 'pojo-lightbox' ),
				'light_square' => __( 'Light Square', 'pojo-lightbox' ),
				'facebook' => __( 'Facebook', 'pojo-lightbox' ),
			),
			'std' => '',
		);

		$fields[] = array(
			'id' => 'lightbox_animation_speed',
			'title' => __( 'Animation Speed', 'pojo-lightbox' ),
			'type' => Pojo_Settings::FIELD_SELECT,
			'std' => '',
			'class' => $wrapper_classes,
			'options' => array(
				'' => __( 'Fast', 'pojo-lightbox' ),
				'normal' => __( 'Normal', 'pojo-lightbox' ),
				'slow' => __( 'Slow', 'pojo-lightbox' ),
			),
		);

		$fields[] = array(
			'id' => 'lightbox_bg_opacity',
			'title' => __( 'Background Opacity', 'pojo-lightbox' ),
			'std' => '0.80',
			'class' => $wrapper_classes,
			'sanitize_callback' => 'floatval',
		);

		$fields[] = array(
			'id' => 'lightbox_show_title',
			'title' => __( 'Show Title', 'pojo-lightbox' ),
			'type' => Pojo_Settings::FIELD_SELECT,
			'class' => $wrapper_classes,
			'options' => array(
				'show' => __( 'Show', 'pojo-lightbox' ),
				'' => __( 'Hide', 'pojo-lightbox' ),
			),
			'std' => '',
		);

		$fields[] = array(
			'id' => 'lightbox_overlay_gallery',
			'title' => __( 'Gallery Thumbnails', 'pojo-lightbox' ),
			'type' => Pojo_Settings::FIELD_SELECT,
			'class' => $wrapper_classes,
			'options' => array(
				'' => __( 'Show', 'pojo-lightbox' ),
				'hide' => __( 'Hide', 'pojo-lightbox' ),
			),
			'std' => '',
		);

		$fields[] = array(
			'id' => 'lightbox_slideshow',
			'title' => __( 'Autoplay Gallery Speed', 'pojo-lightbox' ),
			'desc' => __( 'Default: 5000, 1000 ms = 1 second', 'pojo-lightbox' ),
			'class' => $wrapper_classes,
			'std' => '5000',
			'sanitize_callback' => array( 'Pojo_Settings_Validations', 'field_number' ),
		);

		$fields[] = array(
			'id' => 'lightbox_social_icons',
			'title' => __( 'Social Icons', 'pojo-lightbox' ),
			'type' => Pojo_Settings::FIELD_SELECT,
			'desc' => __( 'Show social sharing buttons on lightbox', 'pojo-lightbox' ),
			'class' => $wrapper_classes,
			'options' => array(
				'' => __( 'Show', 'pojo-lightbox' ),
				'hide' => __( 'Hide', 'pojo-lightbox' ),
			),
			'std' => '',
		);
		
		return $fields;
	}

	protected function _get_photoswipe_fields() {
		$fields = array();
		$wrapper_classes = 'lightbox-fields script-photoswipe';
		
		$fields[] = array(
			'id' => 'photoswipe_loop',
			'title' => __( 'Loop', 'pojo-lightbox' ),
			'type' => Pojo_Settings::FIELD_SELECT,
			'desc' => __( 'Loop slides when using swipe gesture. If set to `enable` you\'ll be able to swipe from last to first image. Option is always `disable` when there are less than 3 slides.', 'pojo-lightbox' ),
			'class' => $wrapper_classes,
			'options' => array(
				'' => __( 'Enable', 'pojo-lightbox' ),
				'disable' => __( 'Disable', 'pojo-lightbox' ),
			),
			'std' => '',
		);
		
		$fields[] = array(
			'id' => 'photoswipe_close_on_scroll',
			'title' => __( 'Close on Scroll', 'pojo-lightbox' ),
			'type' => Pojo_Settings::FIELD_SELECT,
			'desc' => __( 'Close gallery on page scroll. Option works just for devices without hardware touch support.', 'pojo-lightbox' ),
			'class' => $wrapper_classes,
			'options' => array(
				'' => __( 'Enable', 'pojo-lightbox' ),
				'disable' => __( 'Disable', 'pojo-lightbox' ),
			),
			'std' => '',
		);
		
		$fields[] = array(
			'id' => 'photoswipe_close_on_vertical_drag',
			'title' => __( 'Close on Vertical Drag', 'pojo-lightbox' ),
			'type' => Pojo_Settings::FIELD_SELECT,
			'desc' => __( 'Close gallery when dragging vertically and when image is not zoomed. Always `disable` when mouse is used.', 'pojo-lightbox' ),
			'class' => $wrapper_classes,
			'options' => array(
				'' => __( 'Enable', 'pojo-lightbox' ),
				'disable' => __( 'Disable', 'pojo-lightbox' ),
			),
			'std' => '',
		);
		
		$fields[] = array(
			'id' => 'photoswipe_esc_key',
			'title' => __( 'Esc Key', 'pojo-lightbox' ),
			'type' => Pojo_Settings::FIELD_SELECT,
			'desc' => __( 'Esc keyboard key to close Lightbox', 'pojo-lightbox' ),
			'class' => $wrapper_classes,
			'options' => array(
				'' => __( 'Enable', 'pojo-lightbox' ),
				'disable' => __( 'Disable', 'pojo-lightbox' ),
			),
			'std' => '',
		);
		
		$fields[] = array(
			'id' => 'photoswipe_arrow_keys',
			'title' => __( 'Arrow Key', 'pojo-lightbox' ),
			'type' => Pojo_Settings::FIELD_SELECT,
			'desc' => __( 'Keyboard left or right arrow key navigation.', 'pojo-lightbox' ),
			'class' => $wrapper_classes,
			'options' => array(
				'' => __( 'Enable', 'pojo-lightbox' ),
				'disable' => __( 'Disable', 'pojo-lightbox' ),
			),
			'std' => '',
		);
		
		$fields[] = array(
			'id' => 'photoswipe_history',
			'title' => __( 'History', 'pojo-lightbox' ),
			'type' => Pojo_Settings::FIELD_SELECT,
			'desc' => __( 'If set to `disable` disables history module (back button to close gallery, unique URL for each slide).', 'pojo-lightbox' ),
			'class' => $wrapper_classes,
			'options' => array(
				'' => __( 'Enable', 'pojo-lightbox' ),
				'disable' => __( 'Disable', 'pojo-lightbox' ),
			),
			'std' => '',
		);

		$fields[] = array(
			'id' => 'photoswipe_show_caption',
			'title' => __( 'Caption', 'pojo-lightbox' ),
			'type' => Pojo_Settings::FIELD_SELECT,
			'class' => $wrapper_classes,
			'options' => array(
				'' => __( 'Show', 'pojo-lightbox' ),
				'hide' => __( 'Hide', 'pojo-lightbox' ),
			),
			'std' => '',
		);

		$fields[] = array(
			'id' => 'photoswipe_show_close_button',
			'title' => __( 'Close Button', 'pojo-lightbox' ),
			'type' => Pojo_Settings::FIELD_SELECT,
			'class' => $wrapper_classes,
			'options' => array(
				'' => __( 'Show', 'pojo-lightbox' ),
				'hide' => __( 'Hide', 'pojo-lightbox' ),
			),
			'std' => '',
		);

		$fields[] = array(
			'id' => 'photoswipe_show_fullscreen_button',
			'title' => __( 'FullScreen Button', 'pojo-lightbox' ),
			'type' => Pojo_Settings::FIELD_SELECT,
			'class' => $wrapper_classes,
			'options' => array(
				'' => __( 'Show', 'pojo-lightbox' ),
				'hide' => __( 'Hide', 'pojo-lightbox' ),
			),
			'std' => '',
		);

		$fields[] = array(
			'id' => 'photoswipe_show_zoom_button',
			'title' => __( 'Zoom Button', 'pojo-lightbox' ),
			'type' => Pojo_Settings::FIELD_SELECT,
			'class' => $wrapper_classes,
			'options' => array(
				'' => __( 'Show', 'pojo-lightbox' ),
				'hide' => __( 'Hide', 'pojo-lightbox' ),
			),
			'std' => '',
		);

		$fields[] = array(
			'id' => 'photoswipe_show_image_counter',
			'title' => __( 'Image Counter', 'pojo-lightbox' ),
			'type' => Pojo_Settings::FIELD_SELECT,
			'class' => $wrapper_classes,
			'options' => array(
				'' => __( 'Show', 'pojo-lightbox' ),
				'hide' => __( 'Hide', 'pojo-lightbox' ),
			),
			'std' => '',
		);
		
		$fields[] = array(
			'id' => 'photoswipe_show_arrows',
			'title' => __( 'Arrow Navigation', 'pojo-lightbox' ),
			'type' => Pojo_Settings::FIELD_SELECT,
			'class' => $wrapper_classes,
			'options' => array(
				'' => __( 'Show', 'pojo-lightbox' ),
				'hide' => __( 'Hide', 'pojo-lightbox' ),
			),
			'std' => '',
		);
		
		$fields[] = array(
			'id' => 'photoswipe_show_share_button',
			'title' => __( 'Share Button', 'pojo-lightbox' ),
			'type' => Pojo_Settings::FIELD_SELECT,
			'class' => $wrapper_classes,
			'options' => array(
				'' => __( 'Show', 'pojo-lightbox' ),
				'hide' => __( 'Hide', 'pojo-lightbox' ),
			),
			'std' => '',
		);
		
		return $fields;
	}

	protected function _get_global_fields() {
		$fields = array();

		$fields[] = array(
			'id' => 'lightbox_smartphone',
			'title' => __( 'LightBox on Smartphone', 'pojo-lightbox' ),
			'type' => Pojo_Settings::FIELD_SELECT,
			'options' => array(
				'' => __( 'Enable', 'pojo-lightbox' ),
				'disable' => __( 'Disable', 'pojo-lightbox' ),
			),
			'std' => '',
		);

		if ( Pojo_Compatibility::is_woocommerce_installed() ) {
			$fields[] = array(
				'id' => 'lightbox_woocommerce',
				'title' => __( 'LightBox on WooCommerce', 'pojo-lightbox' ),
				'type' => Pojo_Settings::FIELD_SELECT,
				'options' => array(
					'' => __( 'Enable', 'pojo-lightbox' ),
					'disable' => __( 'Disable', 'pojo-lightbox' ),
				),
				'std' => 'disable',
			);
		}
		
		return $fields;
	}

	public function print_js() {
		// TODO: Move to other file
		?>
		<script>
			jQuery( document ).ready( function( $ ) {
				var $lightboxScript = $( 'table.form-table #lightbox_script' );
				$lightboxScript.on( 'change', function() {
					$( 'tr.lightbox-fields' ).hide();
					$( 'tr.script-' + $( this ).val() ).fadeIn( 'fast' );
				} );
				$lightboxScript.trigger( 'change' );
			} );
		</script>
		<?php
	}

	public function __construct( $priority = 10 ) {
		$this->_page_id = 'pojo-lightbox';
		$this->_page_title = __( 'Lightbox Settings', 'pojo-lightbox' );
		$this->_page_menu_title = __( 'Lightbox', 'pojo-lightbox' );
		$this->_page_type = 'submenu';
		$this->_page_parent = 'pojo-home';

		add_filter( 'pojo_register_settings_sections', array( &$this, 'section_lightbox' ), 100 );

		add_action( 'admin_footer', array( &$this, 'print_js' ) );

		parent::__construct( $priority );
	}
	
}
