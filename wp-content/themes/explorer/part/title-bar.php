<div style="padding:20px; text-align:center;"><h3><i class="fa fa-search"></i> Search for content</h3>

<?php  echo do_shortcode('[search] ');?>

<form>
	<!--
	<select class="js-example-basic-single">
		<option value="AL">Alabama</option>
		<option value="WY">Wyoming</option>
	</select>
	-->
	
</form>

</div>
<?php
/**
 * Title Bar
 * Archive: Display archive title
 * 404 : "404 Error"
 * Singular: Post type.
 * @since 1.0.0
**/
if( is_front_page() && is_home() ){
	?>
	<header class="archive-header">
		<div class="archive-title"><?php _ex( 'Blog', 'archive title', 'explorer' ); ?></div>
	</header>
	<?php
}
elseif( is_singular() ){
	if( is_page() || is_attachment() ){ ?>
		<header class="archive-header">
			<?php the_title( '<h1 class="archive-title">', '</h1>' ); ?>
		</header>
	<?php } else { ?>
		<header class="archive-header">
			<h1 class="archive-title"><?php echo 'Course Session Details:' //echo explorer_get_post_type_name( get_queried_object_id() ); ?></h1>
		</header>
	<?php }
}
elseif ( get_the_archive_title() ){
	?>
	<header class="archive-header">
		<?php the_archive_title( '<h1 class="archive-title">', '</h1>'); ?>
	</header>
	<?php
}
else{
	?>
	<header class="archive-header">
		<h1 class="archive-title"><?php echo get_bloginfo( 'name' ); ?></h1>
	</header>
	<?php
}