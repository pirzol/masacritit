<?php
/**
 * Default Single
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( have_posts() ) :
	while ( have_posts() ) : the_post(); ?>
		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<?php if ( has_post_format( array( 'audio', 'image' ) ) ) :
				$image_args = array( 'width' => '1170', 'height' => '660' );
				if ( has_post_format( 'image' ) )
					$image_args['placeholder'] = true;
				$image_url = Pojo_Thumbnails::get_post_thumbnail_url( $image_args );
				if ( $image_url ) : ?>
					<img src="<?php echo $image_url; ?>" alt="<?php echo esc_attr( get_the_title() ); ?>" class="media-object" />
				<?php endif; ?>
			<?php endif; ?>
			<?php if ( has_post_format( 'video' ) ) : ?>
				<?php if ( $video_link = atmb_get_field( 'format_video_link' ) ) : ?>
					<div class="custom-embed" data-save_ratio="<?php echo atmb_get_field( 'format_aspect_ratio' ); ?>"><?php echo wp_oembed_get( $video_link, wp_embed_defaults() ); ?></div>
				<?php endif; ?>
			<?php endif; ?>
			<?php if ( has_post_format( 'audio' ) ) : ?>
				<?php echo wp_audio_shortcode( array( 'mp3' => atmb_get_field( 'format_mp3_url' ), 'ogg' => atmb_get_field( 'format_oga_url' ) ) ); ?>
				<div class="custom-embed"><?php echo wp_oembed_get( atmb_get_field( 'format_embed_url' ), wp_embed_defaults() ); ?></div>
			<?php endif; ?>
			<?php if ( has_post_format( 'gallery' ) ) :
				$gallery_items = explode( ',', atmb_get_field( 'format_gallery' ) );
				$slides = array();
				if ( ! empty( $gallery_items ) ) :
					foreach ( $gallery_items as $item_id ) :
						$attachment     = get_post( $item_id );
						$attachment_url = Pojo_Thumbnails::get_attachment_image_src( $item_id, array( 'width' => '1170', 'height' => '660' ) );
						if ( ! empty( $attachment_url ) )
							$slides[] = sprintf(
								'<li><img src="%2$s" title="%3$s" alt="%3$s" /></li>',
								esc_attr( get_permalink() ),
								esc_attr( $attachment_url ),
								esc_attr( $attachment->post_excerpt )
							);
					endforeach;
					if ( ! empty( $slides ) ) :
						echo '<ul class="pojo-simple-gallery">' . implode( '', $slides ) . '</ul>';
					endif;
				endif; ?>
			<?php endif; ?>
			<div class="entry-post">
				<header class="entry-header">
					<?php if ( po_breadcrumbs_need_to_show() ) : ?>
						<?php pojo_breadcrumbs(); ?>
					<?php endif; ?>
					<?php if ( pojo_is_show_page_title() ) : ?>
						<div class="page-title">
							<h1 class="entry-title">
								<?php the_title(); ?>
							</h1>
						</div>
					<?php endif; ?>
					<div class="entry-meta">
						<?php if ( po_single_metadata_show( 'date' ) ) : ?>
							<span><time datetime="<?php the_time('o-m-d'); ?>" class="entry-date date published updated"><a href="<?php echo get_month_link( get_the_time('Y'), get_the_time('m') ); ?>"><?php echo get_the_date(); ?></a></time></span>
						<?php endif; ?>
						<?php if ( po_single_metadata_show( 'time' ) ) : ?>
							<span class="entry-time"><?php echo get_the_time(); ?></span>
						<?php endif; ?>
						<?php if ( po_single_metadata_show( 'comments' ) ) : ?>
							<span class="entry-comment"><?php comments_popup_link( __( 'No Comments', 'pojo' ), __( 'One Comment', 'pojo' ), __( '% Comments', 'pojo' ), 'comments' ); ?></span>
						<?php endif; ?>
						<?php if ( po_single_metadata_show( 'author' ) ) : ?>
							<span class="entry-user vcard author"><a class="fn" href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>" rel="author"><?php echo get_the_author(); ?></a></span>
						<?php endif; ?>
					</div>
				</header>
				<div class="entry-content">
					<?php if ( ! Pojo_Core::instance()->builder->display_builder() ) : ?>
						<?php the_content(); ?>
						<?php pojo_link_pages(); ?>
					<?php endif; ?>
					<?php if ( has_post_format( 'quote' ) ) : ?>
						<div><?php echo atmb_get_field( 'format_the_quote' ); ?></div>
						<div><?php echo atmb_get_field( 'format_quote_author' ); ?></div>
					<?php endif; ?>

				</div>
				<footer class="entry-footer">
					<?php $category = get_the_category(); if ( $category ) : ?>
						<span class="entry-categories"><?php _e( 'Posted in:', 'pojo' ); ?> <?php the_category( ', ' ); ?></span>
					<?php endif; ?>
					<?php $tags = get_the_tags(); if ( $tags ) : ?>
						<span class="entry-tags"><?php _e( 'Tagged:', 'pojo' ); ?> <?php the_tags( '', ', ' ); ?></span>
					<?php endif; ?>
					<div class="entry-edit">
						<?php pojo_button_post_edit(); ?>
					</div>
				</footer>
			</div>

			<?php
				// Previous/next post navigation.
				echo pojo_get_post_navigation(
					array(
						'prev_text' => __( '&laquo; Previous', 'pojo' ),
						'next_text' => __( 'Next &raquo;', 'pojo' ),
					)
				);
			?>

			<?php if ( pojo_is_show_about_author() ) : ?>
				<div class="author-info media">
					<div class="author-info-inner">
						<div class="author-avatar pull-left">
							<a href="<?php the_author_meta( 'user_url' ); ?>"><?php echo get_avatar( get_the_author_meta( 'email' ), '90' ); ?></a>
						</div>
						<div class="author-content media-body">
							<h4 class="author-title">
								<?php _e( 'About', 'pojo' ); ?> <?php the_author_meta( 'user_firstname' ); ?> <?php the_author_meta( 'user_lastname' ); ?>
							</h4>
							<p class="author-bio">
								<?php echo nl2br( get_the_author_meta( 'description' ) ); ?><br />
								<a class="author-link" href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>" rel="author" class="fn"><?php _e( 'View all posts by', 'pojo' ); ?> <?php echo get_the_author(); ?></a>
							</p>
						</div>
					</div>
				</div>
			<?php endif; ?>
		</article>
	<?php endwhile;
else :
	pojo_get_content_template_part( 'content', 'none' );
endif;