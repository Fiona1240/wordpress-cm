<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<div class="wrap">

		<header class="entry-header">

			<div class="entry-title-wrap">
			<?php
				$postCats = wp_get_post_categories(get_the_ID()); 
				foreach($postCats AS $categoryId){
					echo '<h5>'.get_cat_name($categoryId).'</h5>';
				}
			?>
			<?php tamatebako_entry_title(); ?>
				<span class="entry-author vcard"><?php the_author_posts_link(); ?></span>
			</div><!-- .entry-title-wrap -->

			<div class="entry-byline">
				<?php tamatebako_entry_date(); ?>
				<?php tamatebako_comments_link(); ?>
			</div><!-- .entry-byline -->

		</header><!-- .entry-header -->
<style>
.countColumn{
	width:10%;
}
.titleColumn{
	width:50%;
}
.dlinkColumn{
	width:20%;
}
.timedColumn{
	width:20%;
}
.alertTimedContent{
    color: #8a6d3b;
    background-color: #fcf8e3;
    border-color: #faebcc;	
}
</style>

		<div class="entry-content">
			<?php the_content(); ?>
			
			<?php
			global $post;
			//echo $post->post_name;
			if(is_user_logged_in()){
				echo '<h4><em>Course Content:<em></h4>';
				$sessionCourseDetails = get_the_terms( get_the_ID(), 'category' );
				global $wpdb;
				$resultSet = $wpdb->get_results($wpdb->prepare("SELECT p.`ID`, p.`post_title`, p.`post_name`, p.`post_mime_type` FROM `wp_coursemedia`.`wpcm_posts` p LEFT JOIN wpcm_term_relationships tr ON p.ID = tr.object_id WHERE p.post_type='attachment' AND tr.term_taxonomy_id='%d' GROUP BY p.ID",$sessionCourseDetails[0]->term_id));
				$contentDatas_ADMINISTRATOR = array();
				$contentDatas_FACULTY = array();
				$contentDatas_LEARNER = array();
				$cdCount = 0;
				foreach($resultSet AS $result){
					$courseAudience = array();
					$t = wp_get_post_tags($result->ID);
					foreach($t AS $tg){
						array_push($courseAudience, strtoupper($tg->slug));
					}
					//print_r($courseAudience);exit();
					$awsDetails = get_post_meta( $result->ID, 'amazonS3_info', true );
					if(get_post_meta($result->ID, $post->post_name, true)==1){
						$visibleDate = get_post_meta($result->ID, $post->post_name."_startDate", true);
					}else{
						$visibleDate = '';
					}
					if(in_array('ADMINISTRATOR',$courseAudience)){
						array_push($contentDatas_ADMINISTRATOR, array('title'=>$result->post_title, 'link'=>HRLG_AWS_CF_HOST_URI.$awsDetails['key'], 'visibleDate'=>$visibleDate));
					}
					if(in_array('FACULTY',$courseAudience)){
						array_push($contentDatas_FACULTY, array('title'=>$result->post_title, 'link'=>HRLG_AWS_CF_HOST_URI.$awsDetails['key'], 'visibleDate'=>$visibleDate));
					}
					if(in_array('LEARNER',$courseAudience)){
						array_push($contentDatas_LEARNER, array('title'=>$result->post_title, 'link'=>HRLG_AWS_CF_HOST_URI.$awsDetails['key'], 'visibleDate'=>$visibleDate));
					}
					unset($courseAudience);
				$cdCount++;
				}
				
				if(strtoupper(get_current_user_role()) == 'ADMINISTRATOR'){
					echo '<h5>ADMINISTRATOR</h5>';
					if(count($contentDatas_ADMINISTRATOR)>0){
						echo '<table><thead><tr><th class="countColumn">#</th><th class="titleColumn">Title</th><th class="dlinkColumn">Download Link</th></tr></thead><tbody>';
						$cdCount1 = 1;
						foreach($contentDatas_ADMINISTRATOR AS $contentData){
							if($contentData['visibleDate']=='' || strtotime($contentData['visibleDate']) <= strtotime(date("Y-m-d H:i:s")) ){
								echo '<tr>';
								echo '<td>'.$cdCount1.'.</td>';
								echo '<td>'.$contentData['title'].'</td>';
								echo '<td><a href="'.$contentData['link'].'" href="'.$contentData['link'].'"><i class="fa fa-download"></i></a></td>';
								echo '</tr>';
							}else{
								echo '<tr class="alertTimedContent"><td>'.$cdCount1.'.</td><td colspan="2"><em><small>Scheduled Content: visible on '.date("d M, Y H:i:s", strtotime($contentData['visibleDate'])).'(Current Time'.date("d M, Y H:i:s").')</small></em></td></tr>';
							}
						$cdCount1++;
						}
						echo '</tbody></table>';
					}else{
						echo '<pre><center>No Content...</center></pre>';
					}
					//echo '<pre>';print_r($contentDatas_ADMINISTRATOR);echo '</pre>';
				}

				if(strtoupper(get_current_user_role()) == 'ADMINISTRATOR' || strtoupper(get_current_user_role()) == 'FACULTY'){
					echo '<h5>FACULTY</h5>';
					if(count($contentDatas_FACULTY)>0){
						echo '<table><thead><tr><th class="countColumn">#</th><th class="titleColumn">Title</th><th class="dlinkColumn">Download Link</th></tr></thead><tbody>';
						$cdCount2 = 1;
						foreach($contentDatas_FACULTY AS $contentData){
							if($contentData['visibleDate']=='' || strtotime($contentData['visibleDate']) <= strtotime(date("Y-m-d H:i:s")) ){
								echo '<tr>';
								echo '<td>'.$cdCount2.'.</td>';
								echo '<td>'.$contentData['title'].'</td>';
								echo '<td><a href="'.$contentData['link'].'" href="'.$contentData['link'].'"><i class="fa fa-download"></i></a></td>';
								echo '</tr>';
							}else{
								echo '<tr class="alertTimedContent"><td>'.$cdCount2.'.</td><td colspan="2"><em><small>Scheduled Content: visible on '.date("d M, Y H:i:s", strtotime($contentData['visibleDate'])).' ( Current server Time'.date("d M, Y H:i:s").' )</small></em></td></tr>';
							}
						$cdCount2++;
						}
						echo '</tbody></table>';
					}else{
						echo '<pre><center>No Content...</center></pre>';
					}
					//echo '<pre>';print_r($contentDatas_FACULTY);echo '</pre>';
				}

				echo '<h5>LEARNER</h5>';
				if(count($contentDatas_LEARNER)>0){
					echo '<table><thead><tr><th class="countColumn">#</th><th class="titleColumn">Title</th><th class="dlinkColumn">Download Link</th></tr></thead><tbody>';
					$cdCount3 = 1;
					foreach($contentDatas_LEARNER AS $contentData){
							if($contentData['visibleDate']=='' || strtotime($contentData['visibleDate']) <= strtotime(date("Y-m-d H:i:s")) ){
								echo '<tr>';
								echo '<td>'.$cdCount3.'.</td>';
								echo '<td>'.$contentData['title'].'</td>';
								echo '<td><a href="'.$contentData['link'].'" href="'.$contentData['link'].'"><i class="fa fa-download"></i></a></td>';
								echo '</tr>';
							}else{
								echo '<tr class="alertTimedContent"><td>'.$cdCount3.'.</td><td colspan="2"><em><small>Scheduled Content: visible on '.date("d M, Y H:i:s", strtotime($contentData['visibleDate'])).'(Current Time'.date("d M, Y H:i:s").')</small></em></td></tr>';
							}
					$cdCount3++;
					}
					echo '</tbody></table>';
				}else{
					echo '<pre><center>No Content...</center></pre>';
				}
				//echo '<pre>';print_r($contentDatas_LEARNER);echo '</pre>';
			}else{echo '<pre><center>Please login to access the content!</center></pre>';}
			?>

			<?php wp_link_pages(); ?>
		</div><!-- .entry-content -->

		<footer class="entry-footer">
		<?php edit_post_link(); ?>
			<?php tamatebako_entry_taxonomies(); ?>
		</footer><!-- .entry-footer -->

	</div><!-- .entry > .wrap -->

	<?php get_template_part( 'part/pagination-post' ); ?>

</article><!-- .entry -->

<?php comments_template( '', true ); // Load comments. ?>