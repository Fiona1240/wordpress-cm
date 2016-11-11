<!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" /> -->
<header role="banner" id="header">

	<div id="branding">

		<?php if( is_front_page() && is_home() ){ ?>

			<h1 id="site-title"><a rel="home" href="<?php echo esc_url( user_trailingslashit( home_url() ) ); ?>"><?php bloginfo( 'name' ); ?></a></h1>

		<?php } else { ?>

			<p id="site-title"><a rel="home" href="<?php echo esc_url( user_trailingslashit( home_url() ) ); ?>"><?php bloginfo( 'name' ); ?></a></p>

		<?php } ?>

	</div><!-- #branding -->

	<div id="sidebar-toggle">
		<a href="#"><span class="screen-reader-text"><?php _ex( 'Sidebar Toggle', 'sidebar toggle (accessibility)', 'explorer' );?></span></a>
	</div><!-- #sidebar-toggle-->

</header><!-- #header-->