<?php
/*
<style>
.listCategoryContainer{
	padding:20px;
}
.categoryList{
	list-style:none;
	margin:0px;
}
.categoryList li{
	background:#fbfbfb;
	padding:10px 5px;
	border-bottom:1px solid #cccccc;
    font-size: 1.7rem;
}
.categoryList li i{
	margin-right:20px;
}
</style>
<article class="listCategoryContainer">
<h4>Courses</h4>
<?php

	$allCourses = get_categories();
	echo '<ul class="categoryList">';
	foreach($allCourses AS $course){
		echo '<pre>';
		print_r($course);
		echo '</pre>';
		echo '<li><a href="'.site_url().'/category/'.$course->slug.'/"><i class="fa fa-book"></i>'.$course->name.'<span class="pull-right">('.$course->count.')</span></a></li>';
	}
	echo '</ul>';
	
?>
</article>
*/?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<div class="wrap">

		<div class="entry-title-wrap">
			<?php
				$postCats = wp_get_post_categories(get_the_ID()); 
				foreach($postCats AS $categoryId){
					echo '<h5>'.get_cat_name($categoryId).'</h5>';
				}
			?>
			<?php explorer_thumbnail_html(); ?>
			<?php tamatebako_entry_title(); ?>
			<?php /*<div class="entry-type"><?php _ex( 'Type:', 'entry post type prefix', 'explorer' ); ?> <?php echo explorer_get_post_type_name( get_the_ID() ); ?></div>*/ ?>
		</div><!-- .entry-title-wrap -->

		<div class="entry-byline">
			<?php tamatebako_entry_date(); ?>
			<?php tamatebako_comments_link(); ?>
		</div><!-- .entry-byline -->

	</div><!-- .entry > .wrap -->

</article><!-- .entry -->
