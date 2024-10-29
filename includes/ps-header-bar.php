<header id="#top" class="amp-wp-header" data-post-type="<?php echo get_post_type(); ?>">
	<div>
		<a class="ps-nav-icon" href="#" aria-hidden="true"><span></span><span></span><span></span></a>
		<nav class="ps-nav"><?php wp_nav_menu( array( 'theme_location' => 'amp-menu', ) ); ?></nav>
		<a class="ps-nav-close" href="#" aria-hidden="true">&times;</a>

		<a href="<?php echo esc_url( $this->get( 'home_url' ) ); ?>">
			<?php $site_icon_url = $this->get( 'site_icon_url' );
				if ( $site_icon_url ) : ?>
				<amp-img src="<?php echo esc_url( $site_icon_url ); ?>" width="32" height="32" class="amp-wp-site-icon"></amp-img>
			<?php endif; ?>
			<?php echo esc_html( $this->get( 'blog_name' ) ); ?>
		</a>
	</div>
</header>