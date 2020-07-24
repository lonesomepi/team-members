<?php 
	get_header();
	global $post;
	$info = get_post_meta($post->ID);
	$mainInfo   = get_post_meta($post->ID, 'main_info', true);
	$department = get_post_meta($post->ID, 'member-department', true);
	$phone      = get_post_meta($post->ID, 'member-phone', true);
	$email      = get_post_meta($post->ID, 'member-email', true);
	$website    = get_post_meta($post->ID, 'member-website', true);
	$office     = get_post_meta($post->ID, 'member-office', true);
	$moreInfo   = get_post_meta($post->ID, 'member-moreInfo', true);
?>
	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<header class="entry-header">
			<div class="team-member_wrap wrap">
				<h2 class='entry-title'>
					<?php echo get_the_title(); ?>
				</h2>
			</div><!-- .wrap -->
		</header><!-- .entry-header -->
		<div class="entry-content">
			<div class="team-member_wrap wrap">
				<div class="team-member-main-info-container">
					<div class="team-member-image">
						<?php
							if (has_post_thumbnail()) {
								the_post_thumbnail('portrait');
							} else {
								echo "<img src='../../wp-content/plugins/ufclas-team-members/images/placeholder.jpg' alt='Placeholder image for a headshot' />";
							}
						?>
					</div>
					<!-- team member image -->

					<?php if (!empty($mainInfo)) { ?>
						<div class="team-member-main-info">
							<em><?php echo $mainInfo; ?></em>
						</div>
					<?php } ?>

					<h3>Contact Information</h3>

					<?php if (!empty($email)) { ?>
						<div class="team-member-email">
							<?php echo "<span class='bold-contact'>Email: </span> <a href='mailto:$email'>$email</a>"; ?>
						</div>
					<?php } ?>

					<?php if (!empty($office)) { ?>
						<div class="team-member-office">
							<?php echo "<span class='bold-contact'>Office: </span> $office"; ?>
						</div>
					<?php } ?>

					<?php if (!empty($phone)) { ?>
						<div class="team-member-phone">
							<?php echo "<span class='bold-contact'>Phone: </span> <a href='tel:$phone'> $phone</a>"; ?>
						</div>
					<?php } ?>

					<?php if (!empty($website)) { ?>
						<div class="team-member-website">
							<?php echo "<a href='$website' target='_blank'>Website</a>"; ?>
						</div>
					<?php } ?>

					<style media="screen">
						table, tr, td {
							border: 1px solid #c0c0c0;
							border-collapse: collapse;
							padding: 3px;
						}
					</style>

					<?php

					$list_empty            = array();
					$list_teaching_periods = array();

					$days = array(
						"monday",
						"tuesday",
						"wednesday",
						"thursday",
						"friday",
						"saturday"
					);

					foreach ($info as $key_period => $value) {
						if (strpos($key_period, "period_") !== false) {
							$list_teaching_periods[] = $key_period;
						}
					}

					if (!empty($list_teaching_periods)) { ?>
						<div id="schedule_teaching_periods">
							<h4>Teaching Periods</h4>
							<table>
								<tbody>
									<?php

										foreach ($list_teaching_periods as $period) {
											$period = str_replace("period_","",$period);
											$explode_period[] = explode("_", $period);
										}

										$single_days = array();

										foreach ($explode_period as $null_index => $list_dayHour) {
											$day  = $list_dayHour[0];
											$hour = $list_dayHour[1];
											if (!in_array($day, $single_days)) {
												$single_days[] = $day;
											}
										}

										foreach ($single_days as $d) {
											foreach ($explode_period as $index => $dayHour_list) {
												if ($d === $dayHour_list[0]) {
													$teaching_schedule[$d][] = $dayHour_list[1];
												}
											}
										}

									foreach ($days as $master_day_value) {
										foreach ($teaching_schedule as $key_day => $value_periods) {
											if ($master_day_value == $key_day) { ?>
												<tr>
													<td><?php echo ucfirst($key_day); ?></td>
													<td>
														<?php
														foreach ($teaching_schedule as $key_day_inner => $value_periods_inner) {
															if ($key_day === $key_day_inner) {
																foreach ($value_periods_inner as $period) {
																	echo $period;
																	if ($period !== end($value_periods_inner)) {	echo ", ";	}
																}
															}
														}
														?>
													</td>
												</tr>
											<?php
											}
										}
									}
								?>
								</tbody>
							</table>
						</div>
						<!-- schedule teaching periods -->
					<?php } // BIG IF  if $list_teaching_periods

					// $date = '19:24:15 06/13/2013';
					// echo date('h:i:s a m/d/Y', strtotime($date));

					$list_office_hours = array();

					foreach ($info as $key => $value) {
						if (strpos($key, "appt_") !== false) {
							$list_office_hours[$key] = $value[0];
						}
					}

					if (!empty($list_office_hours)) { ?>
						<div id="schedule_office_hours">
							<h4>Office Hours</h4>
							<?php

							include("master_time.php");

							$assoc_days = array();

							foreach ($master_time as $day => $slot_index) {
								foreach ($list_office_hours as $appt_window => $time) {

									$appt_window    = str_replace("appt_","",$appt_window);
									$explode_window = explode("_", $appt_window);
									$weekday        = $explode_window[0]; // day
									$time_slot      = $explode_window[1]; // 1 | 2 | 3
									$open_close     = $explode_window[2]; // start | end

									if ($weekday == $day) {
										foreach ($slot_index as $index_key => $startEnds) {
											if ($index_key == $time_slot) {
												if ($open_close == "0") {
													$master_time[$day][$time_slot]['start'] = $time;
												}
												if ($open_close == "1") {
													$master_time[$day][$time_slot]['end'] = $time;
												}
											}
										}
									}
								}
							}

							foreach ($master_time as $schedule_day => $value) {
							$open_key = $value['1']['start'];
								if (!empty($open_key)) { ?>
									<h4><?php echo ucfirst($schedule_day); ?></h4>
									<ul> <?php
										foreach ($value as $key_index => $startEnds) {
											if (!empty($startEnds['start'])) {
												$officeHour_starts = $startEnds['start'];
												// format from 24 hours
												$officeHour_starts = date('g:i a', strtotime($officeHour_starts));
												$officeHour_ends   = $startEnds['end'];
												// format from 24 hours
												$officeHour_ends   = date('g:i a', strtotime($officeHour_ends));
												?>
												<li><?php echo $officeHour_starts; ?> to <?php echo $officeHour_ends; ?></li>
												<?php
											}
										}
										?>
									</ul> <?php
								}
							}
							// echo "<pre>";
							// 	print_r($master_time);
							// echo "</pre>";
							?>
						</div>
						<!-- schedule office hours -->
					<?php } ?>


					<!--
					// echo "Mastertime";
					// echo "<pre>";
					// 	print_r($master_time);
					// echo "</pre>";
					-->

				</div>
				<!-- team-member-main-info-container -->

				<div class="team-member-additional-info">
					<?php
						echo $moreInfo;
						echo "<br>";
					?>
				</div>
				<!-- team member additional information -->
			</div>
			<!-- .wrap -->
		</div>
		<!-- .entry-content -->

	</article>
	<!-- article -->

<?php
	get_footer();
?>
