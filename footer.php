<?php
/**
 * The template for displaying the footer.
 *
 * @package Schema
 */

$mts_options = get_option( MTS_THEME_NAME );

$disable_footer = '';
if ( is_singular() ) {
	$disable_footer = get_post_meta( get_the_ID(), '_disable_footer', true );
}
// Default = 3.
$first_footer_num = empty( $mts_options['mts_top_footer_num'] ) ? 3 : $mts_options['mts_top_footer_num'];
?>
	</div><!--#page-->
	<?php
	if ( empty( $disable_footer ) ) {
		?>
		<footer id="site-footer" role="contentinfo" itemscope itemtype="http://schema.org/WPFooter">
			<?php
			// Elementor `footer` location.
			if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'footer' ) ) {
				?>
				<?php if ( $mts_options['mts_top_footer'] ) : ?>
					<div class="footer-widgets first-footer-widgets widgets-num-<?php echo esc_attr( $first_footer_num ); ?>">
						<div class="container">
						<?php
						for ( $i = 1; $i <= $first_footer_num; $i++ ) {
							$sidebar = ( 1 === $i ) ? 'footer-top' : 'footer-top-' . $i;
							$class   = ( $first_footer_num == $i ) ? 'f-widget last f-widget-' . $i : 'f-widget f-widget-' . $i;
							?>
							<div class="<?php echo esc_attr( $class ); ?>">
								<?php
								if ( ! function_exists( 'dynamic_sidebar' ) || ! dynamic_sidebar( $sidebar ) ) :
								endif;
								?>
							</div>
							<?php
						}
						?>
						</div>
					</div><!--.first-footer-widgets-->
				<?php endif; ?>
				<div class="copyrights">
					<div class="container">
						<?php mts_copyrights_credit(); ?>
					</div>
				</div>
				<?php
			}
			?>
		</footer><!--#site-footer-->
		<?php
	}
	?>
</div><!--.main-container-->
<?php
mts_footer();
wp_footer();
?>
</body>
</html>
<?php 
$cat = single_cat_title();
//echo $cat;
 ?>
	<!--script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script-->
<script>
function waitForJQuery() {
    if (!window.jQuery) {
        setTimeout(waitForJQuery, 50);
    } else {
        var page = 2;
        var loading = false;
        var fetchedData = null; // Temporary variable to store fetched data
        var cat = "<?php echo single_cat_title(); ?>";

        // Setup Intersection Observer
        var observer = new IntersectionObserver(function(entries) {
            if (entries[0].isIntersecting && !loading && !fetchedData) {
                loading = true;
                var data = {
                    action: 'load_more_posts',
                    page: page,
                    category: cat
                };
                jQuery.post('<?php echo admin_url('admin-ajax.php'); ?>', data, function(response) {
                    fetchedData = response; // Store fetched data in the temporary variable
                    loading = false;
                });
            }
        }, {
            rootMargin: '400px',
            threshold: 0.5 // Trigger when 50% of the button is visible
        });

        // Observe the button
        observer.observe(document.getElementById('load-more-btn'));

        // On button click, use the fetched data
        jQuery('#load-more-btn').click(function(e) {
            if (fetchedData) {
                jQuery(this).html('<i class="fa fa-refresh fa-spin"></i>');
                jQuery("#load-more-btn").before(fetchedData);
                jQuery('article').each(function() {
                    var id = jQuery(this).attr('post-id');
                    if (jQuery('[post-id="' + id + '"]').length > 1) {
                        jQuery('[post-id="' + id + '"]:gt(0)').remove();
                    }
                });
                fetchedData = null; // Clear the temporary variable
                page++;
                jQuery('#load-more-btn').html('Meer berichten');
            }
        });
    }
}

waitForJQuery();
</script>