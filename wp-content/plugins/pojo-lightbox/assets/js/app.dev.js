/*!
 * @author: Pojo Team
 */
/* global jQuery, PojoLightboxOptions */

( function( $, window, document, undefined ) {
	'use strict';

	var Pojo_LightBox_App = {
		cache: {
			$document: $( document ),
			$window: $( window )
		},

		cacheElements: function() {
			var elementorLightbox = false;
			if ( window.elementorFrontend && elementorFrontend.getGeneralSettings ) {
				elementorLightbox = 'yes' === elementorFrontend.getGeneralSettings( 'elementor_global_image_lightbox' );
			}

			this.cache.elementorLightbox = elementorLightbox;
		},

		buildElements: function() {},

		bindEvents: function() {
			switch ( PojoLightboxOptions.script_type ) {
				case 'prettyPhoto' :
					this._bindEventsPrettyPhoto();
					break;
				
				case 'magnific' :
					this._bindEventsMagnificPopup();
					break;
				
				case 'photoswipe' :
					this._bindEventsPhotoSwipe();
					break;
			}
		},

		// Bind for prettyPhoto
		_bindEventsPrettyPhoto: function() {
			var self = this, isMobile = Modernizr.mq( 'only screen and (max-width: 600px)' );
			
			if ( 'disable' !== PojoLightboxOptions.smartphone || ! isMobile ) {
				if ( isMobile ) {
					PojoLightboxOptions.lightbox_args.allow_expand = false;
				}
				var lightbox_single_args = PojoLightboxOptions.lightbox_args, $body, notScopes = '';

				if ( 'disable' === PojoLightboxOptions.woocommerce ) {
					notScopes += ':not(.woocommerce)';
				}

				if ( self.cache.elementorLightbox ) {
					notScopes += ':not(.elementor-page)';
				}

				$body = $( 'body' + notScopes );

				$( 'a', $body )
					.filter( function() {
						return ( /\.(jpg|jpeg|gif|png)$/i.test( $( this ).attr( 'href' ) ) );
					} )
					.has( 'img' )
					.prettyPhoto( lightbox_single_args );

				$( 'a[rel^="lightbox"]', $body )
					.prettyPhoto( PojoLightboxOptions.lightbox_args );
			}
		},

		// Bind for Magnific Popup
		_bindEventsMagnificPopup: function() {
			var self = this, $body, notScopes = '';

			if ( 'disable' === PojoLightboxOptions.woocommerce ) {
				notScopes += ':not(.woocommerce)';
			}

			if ( self.cache.elementorLightbox ) {
				notScopes += ':not(.elementor-page)';
			}

			$body = $( 'body' + notScopes );

			$( 'a', $body )
				.filter( function() {
					return ( /\.(jpg|jpeg|gif|png)$/i.test( $( this ).attr( 'href' ) ) );
				} )
				.filter( function() {
					// Is in WordPress Gallery?
					return ( 0 === $( this ).closest( 'div.gallery' ).length );
				} )
				.has( 'img' )
				.magnificPopup( {
					type: 'image'
				} );
			
			// WordPress Gallery
			$( 'div.gallery' ).magnificPopup( {
				delegate: 'a',
				type: 'image',
				gallery: {
					enabled: true,
					navigateByImgClick: true,
					preload: [0, 1]
				}
			} );
		},

		// Bind for Photo Swipe
		_bindEventsPhotoSwipe: function() {
			var self = this,
				$photosWipeTemplate = $('.pswp')[0],
				globalOptions = PojoLightboxOptions.lightbox_args;
			
			var _parseItemOptionsFromSelector = function( $this ) {
					var imageSize = $this.data( 'size' ),
						imageWidth = 0,
						imageHeight = 0,
						//imageCaption = $this.find( 'img' ).attr( 'alt' ),
						imageCaption = '',

						$captionElement;

					if ( undefined !== imageSize ) {
						imageSize = imageSize.split( 'x' );
						imageWidth = imageSize[0];
						imageHeight = imageSize[1];
					}
					
					// Parse caption from WP Gallery
					var $galleryItem = $this.closest( '.gallery-item' );
					if ( 1 <= $galleryItem.length ) {
						$captionElement = $galleryItem.find( '.wp-caption-text' );

						if ( 1 <= $captionElement.length ) {
							imageCaption = $captionElement.text();
						}
					}
					
					// Parse caption from Single image
					$captionElement = $this.next( '.wp-caption-text' );
					if ( 1 <= $captionElement.length ) {
						imageCaption = $captionElement.text();
					}
					
					return {
						src: $this.attr( 'href' ),
						w: imageWidth,
						h: imageHeight,
						title: imageCaption,
						el: $this[0]
					};
				},

				_getThumbBoundsFn = function( index, items ) {
					var thumbnail = items[index].el.children[0];
					if ( undefined === thumbnail ) {
						return null;
					}
					
					var pageYScroll = window.pageYOffset || document.documentElement.scrollTop,
						rect = thumbnail.getBoundingClientRect();

					return {x: rect.left, y: rect.top + pageYScroll, w: rect.width};
				};
			
			// Single Images
			var $body, notScopes = '';

			if ( 'disable' === PojoLightboxOptions.woocommerce ) {
				notScopes += ':not(.woocommerce)';
			}

			if ( self.cache.elementorLightbox ) {
				notScopes += ':not(.elementor-page)';
			}

			$body = $( 'body' + notScopes );

			var $singleImages = $( 'a', $body )
				.filter( function() {
					return ( /\.(jpg|jpeg|gif|png)$/i.test( $( this ).attr( 'href' ) ) );
				} )
				.filter( function() {
					// Is in WordPress Gallery?
					return ( 0 === $( this ).closest( 'div.gallery' ).length );
				} )
				.has( 'img' );

			$singleImages.on( 'click', function( event ) {
				event.preventDefault();

				var items = [ _parseItemOptionsFromSelector( $( this ) ) ];
				var options = {
					getThumbBoundsFn: function( index ) {
						return _getThumbBoundsFn( index, items );
					}
				};
				options = $.extend( {}, globalOptions, options );
				var gallery = new PhotoSwipe( $photosWipeTemplate, PhotoSwipeUI_Default, items, options );
				gallery.listen( 'gettingData', function( index, item ) {
					if ( item.w < 1 || item.h < 1 ) {
						var img = new Image();
						img.onload = function() {
							item.w = this.width;
							item.h = this.height;
							gallery.invalidateCurrItems();
							gallery.updateSize( true );
						};
						img.src = item.src;
					}
				} );
				gallery.init();
			} );
			
			// WordPress Gallery
			$( 'div.gallery, div.pojo-gallery', $body ).each( function() {
				var $thisGallery = $( this ),
					
					_getItems = function() {
						var items = [],
							itemIndex = 0;
						
						$thisGallery.find( 'a' ).each( function() {
							items.push( _parseItemOptionsFromSelector( $( this ) ) );
							$( this ).data( 'lightbox_index', itemIndex++ );
						} );
						
						return items;
					};

				var items = _getItems();
				
				$thisGallery.on( 'click', 'a', function( event ) {
					event.preventDefault();
					
					var options = {
						index: $( this ).data( 'lightbox_index' ),

						getThumbBoundsFn: function( index ) {
							return _getThumbBoundsFn( index, items );
						}
					};
					options = $.extend( {}, globalOptions, options );
					var gallery = new PhotoSwipe( $photosWipeTemplate, PhotoSwipeUI_Default, items, options );
					gallery.listen( 'gettingData', function( index, item ) {
						if ( item.w < 1 || item.h < 1 ) {
							var img = new Image();
							img.onload = function() {
								item.w = this.width;
								item.h = this.height;
								gallery.invalidateCurrItems();
								gallery.updateSize( true );
							};
							img.src = item.src;
						}
					} );
					gallery.init();
				} );
				
			} );
		},

		init: function() {
			this.cacheElements();
			this.buildElements();
			this.bindEvents();
		}
	};

	$( document ).ready( function( $ ) {
		Pojo_LightBox_App.init();
	} );

}( jQuery, window, document ) );
