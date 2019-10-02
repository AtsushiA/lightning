<?php get_header(); ?>

<?php get_template_part( 'module_pageTit' ); ?>
<?php get_template_part( 'module_panList' ); ?>

<div class="section siteContent">
<?php do_action( 'lightning_siteContent_prepend' ); ?>
<div class="container">
<?php do_action( 'lightning_siteContent_container_prepend' ); ?>
<div class="row">
<div class="<?php lightning_the_class_name( 'mainSection' ); ?>" id="main" role="main">
<?php do_action( 'lightning_mainSection_prepend' ); ?>

<?php
if ( apply_filters( 'is_lightning_extend_single', false ) ) :
	do_action( 'lightning_extend_single' );
else :
	if ( have_posts() ) :
		while ( have_posts() ) :
			the_post();
		?>
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<header>
		<?php get_template_part( 'module_loop_post_meta' ); ?>
		<h1 class="entry-title"><?php the_title(); ?></h1>
		</header>

		<?php do_action( 'ligthning_entry_body_before' ); ?>
		<div class="entry-body">
		<?php the_content(); ?>
		</div>
		<?php do_action( 'ligthning_entry_body_after' ); ?>

		<div class="entry-footer">
		<?php
		$args = array(
			'before'      => '<nav class="page-link"><dl><dt>Pages :</dt><dd>',
			'after'       => '</dd></dl></nav>',
			'link_before' => '<span class="page-numbers">',
			'link_after'  => '</span>',
			'echo'        => 1,
		);
			wp_link_pages( $args );
			?>

				<?php
				/*-------------------------------------------*/
				/*  Category and tax data
				/*-------------------------------------------*/
				$args          = array(
					'template'      => __( '<dl><dt>%s</dt><dd>%l</dd></dl>', 'lightning' ),
					'term_template' => '<a href="%1$s">%2$s</a>',
				);
				$taxonomies    = get_the_taxonomies( $post->ID, $args );
				$taxnomiesHtml = '';
				if ( $taxonomies ) {
					foreach ( $taxonomies as $key => $value ) {
						if ( $key != 'post_tag' ) {
							$taxnomiesHtml .= '<div class="entry-meta-dataList">' . $value . '</div>';
						}
					} // foreach
				} // if ($taxonomies)
				$taxnomiesHtml = apply_filters( 'lightning_taxnomiesHtml', $taxnomiesHtml );
				echo $taxnomiesHtml;
			?>

			<?php
			$tags_list = get_the_tag_list();
			if ( $tags_list ) :
			?>
			<div class="entry-meta-dataList entry-tag">
			<dl>
			<dt><?php _e( 'Tags', 'lightning' ); ?></dt>
	<dd class="tagcloud"><?php echo $tags_list; ?></dd>
	</dl>
	</div><!-- [ /.entry-tag ] -->
	<?php endif; ?>
		</div><!-- [ /.entry-footer ] -->

		<?php comments_template( '', true ); ?>
	</article>


	<?php if ( $bootstrap == '3' ) { ?>
		<nav>
		  <ul class="pager">
			<li class="previous"><?php previous_post_link( '%link', '%title' ); ?></li>
			<li class="next"><?php next_post_link( '%link', '%title' ); ?></li>
		  </ul>
		</nav>
	<?php
} else {
?>

<div class="card-deck postNextPrev">

			<?php
			$post = get_previous_post();
			if ( $post ) {
			?>
				<?php
				$options = array(
					'layout'       => 'card-holizontal',
					'display'      => array(
						'image'       => true,
						'excerpt'     => false,
						'date'        => true,
						'link_button' => false,
						// 'link_text'   => __( 'Read more', 'lightning' ),
						'overlay'     => '',
					),
					'class'        => array(
						'outer' => 'card-sm',
					),
					'body_prepend' => '',
					'body_append'  => '',
				);
				$options['body_prepend'] = '<p class="postNextPrev_label"><i class="fas fa-chevron-left"></i> ' . __( 'Previous article', 'lightning' ) . '</p>';
				VK_Component_Posts::the_view( $post, $options );
				wp_reset_postdata();
				// get_template_part( 'module_loop_post_card' );
			} else {
				echo '<div class="card card-noborder"></div>';
			}
			?>
			<?php
			$post = get_next_post();
			if ( $post ) {
			?>
				<?php
				$options['body_prepend']   = '<p class="postNextPrev_label">' . __( 'Next article', 'lightning' ) . ' <i class="fas fa-chevron-right"></i></p>';
				$options['class']['outer'] = 'card-sm card-holizontal-reverse postNextPrev_next';
				VK_Component_Posts::the_view( $post, $options );
				wp_reset_postdata();
			} else {
				echo '<div class="card card-noborder"></div>';
			}
			?>
	  <!-- </div> -->
	</div>
	<?php } ?>


	<?php
	endwhile;
endif;
endif;
?>

</div><!-- [ /.mainSection ] -->

<div class="<?php lightning_the_class_name( 'sideSection' ); ?>">
<?php get_sidebar( get_post_type() ); ?>
</div><!-- [ /.subSection ] -->

</div><!-- [ /.row ] -->
</div><!-- [ /.container ] -->
</div><!-- [ /.siteContent ] -->
<?php get_footer(); ?>
