<?php 
	get_header();
	add_filter('posts_orderby', 'posts_orderby_lastname');
?>

	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<div class="entry-content">
			<div class="archive-team-member_wrap wrap">
				<?php
					// Get all the categories
					$categories = get_terms('team_member_position');
					// Loop through all the returned terms
					if (is_array($categories)) {
						foreach ($categories as $category) {
						// set up a new query for each category, pulling in related posts.
						$teamMember = new WP_Query(
							array(
								'post_type' => 'clas_team_members',
								'orderby'   => 'title',
								'order'     => 'ASC',
								'showposts' => -1,
								'tax_query' => array(
									array(
										'taxonomy' => 'team_member_position',
										'terms'    => array( $category->slug ),
										'field'    => 'slug'
									)
								)
							)
						);
					?>

					<h2 class="entry-title"><?php echo $category->name; ?></h2>
					<div class="all-team-member-container">
						<?php
							while ($teamMember->have_posts()) : $teamMember->the_post();
								$mainInfo   = get_post_meta($post->ID, 'main_info', true);
								$department = get_post_meta($post->ID, 'member-department', true);
								$phone      = get_post_meta($post->ID, 'member-phone', true);
								$email      = get_post_meta($post->ID, 'member-email', true);
								$website    = get_post_meta($post->ID, 'member-website', true);
								$office     = get_post_meta($post->ID, 'member-office', true);
								$moreInfo   = get_post_meta($post->ID, 'member-moreInfo', true);
								$position   = get_post_meta($post->ID, 'member-position', true);
						?>
						<div class="individual-team-member-container">
							<div class="team-member-image">
								<?php
									if (has_post_thumbnail()) {
										the_post_thumbnail('portrait');
									} else {
										echo "<img src='../wp-content/plugins/ufclas-team-members/images/placeholder.jpg' alt='Placeholder image for a headshot' />";
									}
								?>
							</div>
							<!-- image, team member  -->

							<div class="archive-team-member-name">
								<?php echo "<a aria-label='View bio for ". get_the_title() . "'" . "href='" . get_the_permalink() . "'>". get_the_title() . "</a>"; ?>
							</div>
							<!-- name, team member -->

							<?php
								if (!empty($email)) {

									$strippedEmail = str_replace('@ufl.edu','', $email);?>
									<div class="archive-team-member-email">
										<?php echo "<span class='bold-contact'>Email: </span> <a href='mailto:$email'>$strippedEmail</a>"; ?>
									</div>
								<?php }

								if (!empty($position)) { ?>
									<div class="archive-team-member-position">
										<?php echo "<p>{$position}</p>"; ?>
									</div><?php
								}
							?>
						</div>
						<!-- container individual team members -->
						<?php endwhile; // while posts exists ?>
					</div>
					<?php
					// Reset things, for good measure
					$teamMember = null;
					wp_reset_postdata();
					// end the loop
					}
				} ?>
			</div>
			<!-- .wrap -->
		</div>
		<!-- .entry-content -->
	</article>
	<!-- #post-## -->

<?php
	remove_filter( 'posts_orderby', 'posts_orderby_lastname' );
	get_footer();
?>
