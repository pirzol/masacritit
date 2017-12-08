<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

final class Pojo_Lightbox_Frontend {

	public function print_photoswipe_js_markup() {
		?>
		<div class="pswp" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="pswp__bg"></div>
			<div class="pswp__scroll-wrap">
				<div class="pswp__container">
					<div class="pswp__item"></div>
					<div class="pswp__item"></div>
					<div class="pswp__item"></div>
				</div>
				<div class="pswp__ui pswp__ui--hidden">
					<div class="pswp__top-bar">
						<div class="pswp__counter"></div>
						<button class="pswp__button pswp__button--close" title="Close (Esc)"></button>
						<button class="pswp__button pswp__button--share" title="Share"></button>
						<button class="pswp__button pswp__button--fs" title="Toggle fullscreen"></button>
						<button class="pswp__button pswp__button--zoom" title="Zoom in/out"></button>
						<div class="pswp__preloader">
							<div class="pswp__preloader__icn">
								<div class="pswp__preloader__cut">
									<div class="pswp__preloader__donut"></div>
								</div>
							</div>
						</div>
					</div>

					<div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
						<div class="pswp__share-tooltip"></div>
					</div>

					<button class="pswp__button pswp__button--arrow--left" title="Previous (arrow left)">
					</button>
					<button class="pswp__button pswp__button--arrow--right" title="Next (arrow right)">
					</button>
					<div class="pswp__caption">
						<div class="pswp__caption__center"></div>
					</div>
				</div>
			</div>
		</div>
		<?php
	}

	public function add_data_size_in_attachments( $link, $id, $size, $permalink, $icon, $text ) {
		if ( false === $permalink && ! $text && 'none' !== $size ) {
			$_post = get_post( $id );

			$image_attributes = wp_get_attachment_image_src( $_post->ID, 'original' );

			if ( $image_attributes ) {
				$link = str_replace( '<a ', '<a data-size="' . $image_attributes[1] . 'x' . $image_attributes[2] . '" ', $link );
			}
		}
		return $link;
	}
	
	public function add_ref_attribute_in_attachments( $content, $id, $size, $permalink, $icon, $text ) {
		if ( ! $text && $size && 'none' !== $size && ! $permalink )
			$content = preg_replace( '/<a/', '<a rel="lightbox[gallery]"', $content, 1 );

		return $content;
	}

	public function register_actions() {
		$lightbox_script = pojo_get_option( 'lightbox_script' );
		
		if ( 'photoswipe' === $lightbox_script ) {
			add_action( 'wp_footer', array( &$this, 'print_photoswipe_js_markup' ) );
			add_filter( 'wp_get_attachment_link', array( &$this, 'add_data_size_in_attachments' ), 10, 6 );
		}
		
		if ( 'prettyPhoto' === $lightbox_script ) {
			add_filter( 'wp_get_attachment_link', array( &$this, 'add_ref_attribute_in_attachments' ), 10, 6 );
		}
	}

	public function __construct() {
		add_action( 'init', array( &$this, 'register_actions' ), 300 );
	}
	
}