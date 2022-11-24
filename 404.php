<?php 
/**
	* The template for displaying 404 pages (not found)
	*
	* @link https://codex.wordpress.org/Creating_an_Error_404_Page
	*/

	get_header();
?>

<div class="page page__error-404">

	<section class="error-404 not-found">

		<a href="<?= esc_url(home_url('/')); ?>" class="error-404__link">
			Home
		</a>

	</section><!-- .error-404 -->

</div><!-- .page__error-404 -->

<?php get_footer(); ?>