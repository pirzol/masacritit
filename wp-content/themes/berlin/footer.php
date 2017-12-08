<?php
/**
 * The template for displaying the footer.
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>
			</div><!-- #content -->
		</div><!-- .container -->
	</div><!-- #primary -->

	<?php
		if ( is_singular( 'post' ) ) :
			comments_template( '', true );
		endif;
	?>
	<?php po_change_loop_to_parent( 'change' ); ?>
	<?php if ( ! pojo_is_blank_page() ) : ?>
		<footer id="footer">
			<?php get_sidebar( 'footer' ); ?>
		</footer>
		<section id="copyright" role="contentinfo">
			<div class="<?php echo WRAP_CLASSES; ?>">
				<div class="pull-left-copyright">
					<?php echo nl2br( pojo_get_option( 'txt_copyright_left' ) ); ?>
				</div>
				<div class="pull-right-copyright">
					<?php echo nl2br( pojo_get_option( 'txt_copyright_right' ) ); ?>
				</div>
			</div><!-- .container -->
		</section>
	<?php endif; // end blank page ?>
	<?php po_change_loop_to_parent(); ?>

	</div><!-- .container-wrapper -->
</div><!-- #container -->
<?php wp_footer(); ?>
</body>
</html>