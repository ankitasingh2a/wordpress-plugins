<?php

use \Firebase\JWT\JWT;

/**
 * Helper methods & functions
 *
 * @link       https://unaibamir.com
 * @since      1.0.0
 *
 * @package    Life_Mastery_Group_Management
 * @subpackage Life_Mastery_Group_Management/includes
 */

/**
 *
 * This class defines all code necessary to run during the plugin's process
 *
 * @since      1.0.0
 * @package    Life_Mastery_Group_Management
 * @subpackage Life_Mastery_Group_Management/includes
 * @author     Unaib Amir <unaibamiraziz@gmail.com>
 */
class LM_Helper {

	public static $_instance;

	private $api_url = 'https://api.zoom.us/v2/';

	private $return_object = null;

	private $data_list = array();

	/**
	 * Create only one instance so that it may not Repeat
	 *
	 * @since 2.0.0
	 */
	public static function instance() {
		if ( ! isset( self::$_instance ) && ! ( self::$_instance instanceof Zoom_Video_Conferencing_Api ) ) {
			self::$_instance = new self();
		}

		//self::$_instance->setup_vars();

		return self::$_instance;

	}

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function get_group_course( $group_id ) {

		$group_courses 	=	learndash_group_enrolled_courses( $group_id );
		$course_id 		= 	$group_courses[0];
		return $course_id;
	}


	public static function get_course_lessons( $course_id ) {
		$course_lessons = 	learndash_get_course_lessons_list( $course_id, array( 'sfwd-lessons' ) );
		return $course_lessons;
	}


	public static function generate_lesson_dates_old( $group_id, $weeks, $start_date ) {
		//dd($weeks);

		$dates 		= array();
		$dates[] 	= $start_date;
		$date 		= '';

		for ($i = 0; $i < $weeks['total_weeks'] + 1; $i++) {

			$date 	 = date( 'Y-m-d', strtotime( "+1 week", strtotime( !empty($date) ? $date : $start_date ) ) );

			$dates[] = $date;
			continue;
		}

		unset($dates[8]);
		$dates = array_values($dates);
		
		return $dates;
	}

	public static function generate_lesson_discuss_dates_old( $group_id, $weeks, $start_date ) {
		
		$lesson_dates 	= LM_Helper::generate_lesson_dates( $group_id, $weeks, $start_date );

		$dates 		= array();
		$date 		= '';

		foreach ($lesson_dates as $key => $lesson_date) {
			$date 	 = date( 'Y-m-d', strtotime( "-3 week monday", strtotime( $lesson_date ) ) );
			$dates[] 	= $date;
			continue;
		}
		
		$dates[0] = $dates[1] = '';
		$dates = array_values($dates);
		return $dates;
	}

	public static function generate_lesson_dates( $group_id, $weeks, $start_date ) {
		
		$discuss_dates 	= LM_Helper::generate_lesson_discuss_dates( $group_id, $weeks, $start_date );
		$dates 		= array();
		
		$date 		= '';

		foreach ($discuss_dates as $key => $discuss_date) {
			$date 	 = date( 'Y-m-d', strtotime( "-3 week monday", strtotime( $discuss_date ) ) );
			$dates[] = $date;
			continue;
		}

		
		$dates = array_values($dates);
		
		return $dates;
	}

	public static function generate_lesson_discuss_dates( $group_id, $weeks, $start_date ) {
		
		//$lesson_dates 	= LM_Helper::generate_lesson_dates( $group_id, $weeks, $start_date );

		$dates 		= array();
		$dates[] 	= $start_date;
		$date 		= '';


		for ($i = 0; $i < $weeks['total_weeks'] + 1; $i++) {
			$date 	 = date( 'Y-m-d', strtotime( "+1 week", strtotime( !empty($date) ? $date : $start_date ) ) );
			$dates[] 	= $date;
			continue;
		}
		
		//unset($dates[8]);
		
		$dates = array_values($dates);
		return $dates;
	}


	public static function get_group_course_weeks( $group_id )
	{

		$course_id 		= 	LM_Helper::get_group_course( $group_id );
		$course_lessons = 	LM_Helper::get_course_lessons( $course_id );

		$total_lessons 	= 	count($course_lessons);
		// $total_weeks 	= 	ceil($total_lessons / 2) + 1; // one additional week to get all lessons divided equally
		$weeks_array	= 	$data = array();
		
		
	        $excluded_courses 	= get_field( 'enable_manage_courses', 'option');
	        if(in_array($course_id, $excluded_courses))
	        {
		      $total_weeks = get_post_meta( $course_id, 'many_weeks', true );
	        }
	        else
	        {
              $total_weeks = ceil($total_lessons / 2) + 1;
	        }

		for ($i = 0; $i < $total_weeks+1; $i++) {
			$weeks_array[ $i ] = array();
		}

		$data['total_weeks'] = $total_weeks;
		$data['weeks_array'] = $weeks_array;
		
		return $data;
	}

	public static function get_group_course_lesson_weeks( $group_id )
	{
		$course_id 		= 	LM_Helper::get_group_course( $group_id );
		$course_lessons = 	LM_Helper::get_course_lessons( $course_id );
		$weeks 			= 	LM_Helper::get_group_course_weeks( $group_id );
		$total_weeks 	= 	$weeks['total_weeks'];
				
		$filtered_lessons = $data = array();
		foreach ($course_lessons as $key => $lesson) {
			$filtered_lessons[$key] = $lesson['post']->ID;
		}

		$lessons = array_slice( $filtered_lessons, 0, 1 );
		$data[0] = $lessons;
		array_shift($filtered_lessons);

		$slice_num  = 0;
		foreach ( $weeks['weeks_array'] as $week_num => $week_array) {

			if( $week_num == 0 ) {
				continue;
			}

			$lessons = array_slice( $filtered_lessons, $slice_num * 2, $slice_num == 0 ? 2 : 2 );
			$data[$week_num] = $lessons;

			$slice_num++;
		}

		$last_index = array_key_last($data);
		$last_lesson_id = $data[$last_index][0];

		array_push( $data[$last_index - 1], $last_lesson_id);
		unset($data[$last_index]);

		return $data;
	}


	public static function drip_admin_group_lessons($group_id)
	{
		$weeks 			= 	LM_Helper::get_group_course_weeks( $group_id );
		$start_date 	=	get_post_meta( $group_id, 'lm_course_start_date', true );
		$lesson_dates 	= 	LM_Helper::generate_lesson_dates( $group_id, $weeks, $start_date );
        $discuss_dates 	= 	LM_Helper::generate_lesson_discuss_dates( $group_id, $weeks, $start_date );
        $course_lesson_weeks = LM_Helper::get_group_course_lesson_weeks( $group_id );
        array_unshift($course_lesson_weeks, array(9999999));

        $gmt_offset  	= get_option( 'gmt_offset' );
		if ( empty( $gmt_offset ) ) {
			$gmt_offset = 0;
		}
		$offset      	= ( $gmt_offset * ( 60 * 60 ) ) * - 1;

        $data 			=	array();
        $format 		= 'Y-m-d H:01:s';

        foreach ($course_lesson_weeks as $week_num => $lesson) {

        	$data[ $week_num ] = array(
        		'lesson_date'	=>	$lesson_dates[$week_num],
        		'lesson_data'	=>	$lesson
        	);
        }

        if( empty($data) ) {
        	return;
        }

        $meta_key = 'uncanny_pro_toolkitUncannyDripLessonsByGroup-' . $group_id;
        
        foreach ($data as $week_num => $lesson_info) {
        	
        	$date 	 	= date( $format, strtotime( $lesson_info['lesson_date'] ) );
        	$drip_date 	= strtotime($date);
        	$drip_date = (int) $drip_date + $offset;

        	foreach ( $lesson_info['lesson_data'] as $lesson_id) {
        		update_post_meta( $lesson_id, $meta_key, $drip_date, '' );
        	}

        	if( $week_num == 1 ) {
        		delete_post_meta( $lesson_info['lesson_data'][0], $meta_key );
        	}
        }
	}


	public static function drip_public_group_lessons( $group_id, $posted_data ) {

		//dd($posted_data, false);

		$gmt_offset  	= get_option( 'gmt_offset' );
		if ( empty( $gmt_offset ) ) {
			$gmt_offset = 0;
		}
		$offset      	= ( $gmt_offset * ( 60 * 60 ) ) * - 1;

        $data 			=	array();
        $format 		= 'Y-m-d H:01:s';

        $meta_key = 'uncanny_pro_toolkitUncannyDripLessonsByGroup-' . $group_id;

        foreach ($posted_data['lm_lessons'] as $week_num => $lesson_arr) {

        	$data[ $week_num ] = array(
        		'lesson_date'	=>	$posted_data['lesson_dates'][$week_num],
        		'lesson_data'	=>	$lesson_arr
        	);
        }

        foreach ($data as $week_num => $lesson_info) {
        	
        	$date 	 	= date( $format, strtotime( $lesson_info['lesson_date'] ) );
        	$drip_date 	= strtotime($date);
        	$drip_date = (int) $drip_date + $offset;

        	foreach ( $lesson_info['lesson_data'] as $lesson_id) {
        		update_post_meta( $lesson_id, $meta_key, $drip_date, '' );
        	}
        }

	}


	public static function get_group_attendance_view( $group_id, $attendance_dates = array(), $students = array() )
	{
		global $wpdb;

		if( empty($attendance_dates) ) {
			$attendance_dates 	= 	get_post_meta( $group_id, 'lm_group_attendance_dates', true );
		}

		if( empty($attendance_dates) ) {
			return __( 'No data available' );
		}

		$user = wp_get_current_user();
		
		if( empty($students) ) {
			$students 			= 	learndash_get_groups_users( $group_id );
		}

		if( !learndash_is_group_leader_user( $user ) ) {
			$students = array();
			$students[] = $user;
		}

		$table 				= $wpdb->prefix . 'lm_attendance_logs';
		$current_date 		= 	new DateTime();
		
		ob_start();
		?>
		<div style="overflow-x:auto;  width: 100%;">
			<table class="profile-fields group-attendance">
				<thead>
					<tr>
						<th><?php echo __('Name');?></th>
						<?php 
						if( !empty($attendance_dates) ) {
							foreach ($attendance_dates as $date) {
								?>
								<th><?php echo date( 'm/d', strtotime($date) );?></th>
								<?php
							}
						}
						?>
					</tr>
				</thead>

				<tbody>

					<?php foreach ($students as $user) {
						?>
						<tr>
							<td id="user-<?php echo $user->ID; ?>">
								<span style="min-width: 140px;display: block;"><?php echo $user->display_name; ?></span>
							</td>
							<?php 
							if( !empty($attendance_dates) ) {
								foreach ($attendance_dates as $date) {
									
									$date = new DateTime( $date );
									
									$query = $wpdb->prepare( "SELECT * FROM $table WHERE user_id = %d AND group_id = %d AND `date` = %s", $user->ID, $group_id, $date->format('Y-m-d') );

									$has_row = $wpdb->get_row( $query );

									/*if( $current_date <= $date ) {
										$status = '';
									} else {
										$status = $has_row !== null && $has_row->attendance_type == '1' ? __('X') : __('-');
									}*/

									if( $current_date <= $date ) {
										$status = '';
									} else {
										if( $has_row !== null ) {
											if( $has_row->attendance_type == '2' ) {
												$status = 'X';
											} else if( $has_row->attendance_type == '1' ) {
												$status = '-';
											} else if( $has_row->attendance_type == '3' ) {
												$status = '/';
											} else {
												$status = '';
											}
										}
										else {
											$status = '';
										}
									}

									?>
									<td>
										<?php echo $status;  ?>
									</td>
									<?php
								}
							}
							?>
						</tr>
						<?php
					} ?>
				</tbody>
			</table>
		</div>

		<?php

		$output = ob_get_contents();
        ob_end_clean();
        return $output;
	}

	public static function get_group_details_ajax( $group_id )
	{
		ob_start();

		$tab_ajax_roster_url = add_query_arg(array(
			'action'	=>	'lm_load_group_data',
			'group_id'	=>	$group_id,
			'data'		=>	'roster',
			'_wpnonce'	=>	wp_create_nonce( 'lm_ajax_tab_nonce' )
		), admin_url( 'admin-ajax.php' ));

		$tab_ajax_attendance_url = add_query_arg(array(
			'action'	=>	'lm_load_group_data',
			'group_id'	=>	$group_id,
			'data'		=>	'attendance',
			'_wpnonce'	=>	wp_create_nonce( 'lm_ajax_tab_nonce' )
		), admin_url( 'admin-ajax.php' ));

		$tab_ajax_schedule_url = add_query_arg(array(
			'action'	=>	'lm_load_group_data',
			'group_id'	=>	$group_id,
			'data'		=>	'schedule',
			'_wpnonce'	=>	wp_create_nonce( 'lm_ajax_tab_nonce' )
		), admin_url( 'admin-ajax.php' ));

		$tab_ajax_zoom_url = add_query_arg(array(
			'action'	=>	'lm_load_group_data',
			'group_id'	=>	$group_id,
			'data'		=>	'zoom',
			'_wpnonce'	=>	wp_create_nonce( 'lm_ajax_tab_nonce' )
		), admin_url( 'admin-ajax.php' ));

		$tab_ajax_instructions_url = add_query_arg(array(
			'action'	=>	'lm_load_group_data',
			'group_id'	=>	$group_id,
			'data'		=>	'instructions',
			'_wpnonce'	=>	wp_create_nonce( 'lm_ajax_tab_nonce' )
		), admin_url( 'admin-ajax.php' ));

		$tab_ajax_form_url = add_query_arg(array(
			'action'	=>	'lm_load_group_data',
			'group_id'	=>	$group_id,
			'data'		=>	'form',
			'_wpnonce'	=>	wp_create_nonce( 'lm_ajax_tab_nonce' )
		), admin_url( 'admin-ajax.php' ));

		$tab_leader_instructions_url = add_query_arg(array(
			'action'	=>	'lm_load_group_data',
			'group_id'	=>	$group_id,
			'data'		=>	'leader_instructions_1',
			'_wpnonce'	=>	wp_create_nonce( 'lm_ajax_tab_nonce' )
		), admin_url( 'admin-ajax.php' ));

		$tab_leader_instructions_two_url = add_query_arg(array(
			'action'	=>	'lm_load_group_data',
			'group_id'	=>	$group_id,
			'data'		=>	'leader_instructions_2',
			'_wpnonce'	=>	wp_create_nonce( 'lm_ajax_tab_nonce' )
		), admin_url( 'admin-ajax.php' ));

		$tab_leader_facilitator_instructions_url = add_query_arg(array(
			'action'	=>	'lm_load_group_data',
			'group_id'	=>	$group_id,
			'data'		=>	'facilitator_instructions',
			'_wpnonce'	=>	wp_create_nonce( 'lm_ajax_tab_nonce' )
		), admin_url( 'admin-ajax.php' ));
    
		//  tab code put here


		$show_user_tab		= false;
		$user_id 			= get_current_user_id();
		$user 				= wp_get_current_user();
		$group_data 		= get_post_meta( $group_id, 'lm_group_data', true );
		$user_lesson_data 	= get_user_meta( $user_id, "lm_lesson_group_{$group_id}_info", true );
		$date 				= 'now';
		//$date 				= '2021-02-09 23:59:59';
		//$date 				= '2021-03-01 00:00:00';
		$current_date 		= new DateTime( $date, new DateTimeZone( "America/Los_Angeles" ));
        // echo $current_date->format('Y-m-d H:i:s');
		
		if( !empty( $user_lesson_data ) && is_array( $user_lesson_data ) ) {
			
			foreach ($user_lesson_data as $lesson_info ) {

				$lesson_date 		= new DateTime( date('Y-m-d 23:59:59', $lesson_info['date']), new DateTimeZone( "America/Los_Angeles" ) );
				 // echo $lesson_date->format('Y-m-d H:i:s');
				$date_interval 		= $current_date->diff( $lesson_date );
				// calculate days difference
				$date_diff 			= $date_interval->format('%a');
			
				// dd(var_export(strpos($date_diff, '-'), true), false);
				
				/*if( strpos($date_diff, '-') != 0 || strpos($date_diff, '-') === false && ($date_diff >= 0) && ($date_diff < 7) ) {
					$show_user_tab 	= true;
					break;
				} else {
					continue;
				}*/
				// the logic of days to either show the lead tab or not
				// the leab tab should appear for 8 days, 
				if($date_diff == "-0" || ($date_diff >= 0) && ($date_diff < 14) ) {
					$show_user_tab 	= true;
					break;
				} else {
					continue;
				}
			}
		}

		?>
			<div id="lm-group-tab-<?php echo $group_id; ?>" data-instructions="<?php echo $show_user_tab === true ? 'displayed' : 'hidden'; ?>"  class="lm-group_member-details tabs ui-tabs ui-widget ui-widget-content ui-corner-all" >
			<ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">

		   <?php
			$course_id = LM_Helper::get_group_course( $group_id );
	        $excluded_courses 	= get_field( 'enable_manage_courses', 'option');
	        if(in_array($course_id, $excluded_courses))
	        {
			  if(get_post_meta( $course_id, 'class_tab', true )) {
    	    	 $checkval = get_post_meta( $course_id, 'class_tab', true );
    	  	     $checkBoxs = explode(',', $checkval);
	      
			    if (in_array("Roster", $checkBoxs)) { 
				 	?>
				 	 <li class="ui-state-default ui-corner-top" id="litab_<?php echo $course_id; ?>"><a class="ui-tabs-anchor" href="<?php echo $tab_ajax_roster_url; ?>">Roster</a></li>
				 	<?php
				 }  
				 if (in_array("Attendance", $checkBoxs)) { 
				 	?>
				 	<li class="ui-state-default ui-corner-top" id="litab_<?php echo $course_id; ?>"><a class="ui-tabs-anchor" href="<?php echo $tab_ajax_attendance_url; ?>">Attendance</a></li>
				 	<?php
				 }  
				 if (in_array("Zoom", $checkBoxs)) { 
				 	?>
				 	<li class="ui-state-default ui-corner-top" id="litab_<?php echo $course_id; ?>"><a class="ui-tabs-anchor" href="<?php echo $tab_ajax_zoom_url; ?>">Zoom</a></li>
				 	<?php
				 }  
				 if (in_array("Class Schedule", $checkBoxs)) { 
				 	?>
				 	<li class="ui-state-default ui-corner-top" id="litab_<?php echo $course_id; ?>"><a class="ui-tabs-anchor" href="<?php echo $tab_ajax_schedule_url; ?>">Class Schedule</a></li>
				 	<?php
				 }  
				 if (in_array("Promise", $checkBoxs)) { 
				 	?>
				 	<li class="ui-state-default ui-corner-top" id="litab_<?php echo $course_id; ?>"><a class="ui-tabs-anchor" href="<?php echo $tab_ajax_form_url; ?>">Promise</a></li>
				 	<?php
				 } 
				 // Lead Instructions


			    if( $show_user_tab && !learndash_is_group_leader_user( $user ) ) {
			    	if (in_array("Lead Instructions", $checkBoxs)) { 
					?>
					<li class="ui-state-default ui-corner-top"><a class="ui-tabs-anchor" href="<?php echo $tab_ajax_instructions_url; ?>">Lead Instructions</a></li>
					<?php
				    }
				}
				if( !empty($group_data) && learndash_is_group_leader_user( $user ) || current_user_can( 'manage_options' ) ) {
					
					$week_num = $next_week = '';
					
					foreach ($group_data['lesson_review_dates'] as $key => $lesson_date ) {
						$lesson_date 		= new DateTime( date('Y-m-d 23:59:59', strtotime($lesson_date)), new DateTimeZone( "America/Los_Angeles" ) );
						$date_interval 		= $current_date->diff( $lesson_date );
						$date_diff 			= $date_interval->format('%R%a');
						
						if( strpos($date_diff, '-') != 0 || strpos($date_diff, '-') === false && ($date_diff >= 0) && ($date_diff < 7) ) {
							$week_num 	= $key - 1;
							$next_week 	= $key;
							break;
						}
					}

					if( $week_num == '-1' || empty($week_num) ) {
						$week_num = 0;
					}

					if( $next_week == 0  ) {
						$next_week = 1;
					}

					if( $week_num > 12 ) {
						$week_num = 12;
					}

					if( $week_num == 12 ) {
						$next_week = '';
					}

						if (in_array("Lead Instructions", $checkBoxs)) { 
					?>
					<li class="ui-state-default ui-corner-top">
						<?php
						$tab_leader_instructions_url = add_query_arg('week', $week_num, $tab_leader_instructions_url);
						?>
						<a class="ui-tabs-anchor" href="<?php echo $tab_leader_instructions_url; ?>">
							Week <?php echo $week_num; ?> Lead Instructions
						</a>
					</li>
					<?php if( !empty($next_week) ): ?>
						<li class="ui-state-default ui-corner-top">
							<?php
							$tab_leader_instructions_two_url = add_query_arg('week', $next_week, $tab_leader_instructions_two_url);
							?>
							<a class="ui-tabs-anchor" href="<?php echo $tab_leader_instructions_two_url; ?>">
								Week <?php echo $next_week; ?> Lead Instructions
							</a>
						</li>
					<?php
					 endif; 
			      	}
				  if (in_array("Facilitator Instructions", $checkBoxs)) { 
				 	?>
				 	<li class="ui-state-default ui-corner-top" id="litab_<?php echo $course_id; ?>">
						<?php
						$tab_leader_facilitator_instructions_url = add_query_arg('week', $week_num, $tab_leader_facilitator_instructions_url);
						?>
						<a class="ui-tabs-anchor" href="<?php echo $tab_leader_facilitator_instructions_url; ?>">
							Facilitator Instructions
						</a>
					</li>
				 	<?php
				 }  
				}

			  }
			}
	  	    else
	  	    {
	  	      ?>


				<li class="ui-state-default ui-corner-top" id="litab_<?php echo $group_id; ?>"><a class="ui-tabs-anchor" href="<?php echo $tab_ajax_roster_url; ?>">Roster</a></li>
				<li class="ui-state-default ui-corner-top" id="litab_<?php echo $group_id; ?>"><a class="ui-tabs-anchor" href="<?php echo $tab_ajax_attendance_url; ?>">Attendance</a></li>
				<li class="ui-state-default ui-corner-top" id="litab_<?php echo $group_id; ?>"><a class="ui-tabs-anchor" href="<?php echo $tab_ajax_schedule_url; ?>">Class Schedule</a></li>
				<li class="ui-state-default ui-corner-top" id="litab_<?php echo $group_id; ?>"><a class="ui-tabs-anchor" href="<?php echo $tab_ajax_zoom_url; ?>">Zoom</a></li>
				<li class="ui-state-default ui-corner-top" id="litab_<?php echo $group_id; ?>"><a class="ui-tabs-anchor" href="<?php echo $tab_ajax_form_url; ?>">Promise</a></li>

				<?php

			    if( $show_user_tab && !learndash_is_group_leader_user( $user ) ) {
					?>
					<li class="ui-state-default ui-corner-top"><a class="ui-tabs-anchor" href="<?php echo $tab_ajax_instructions_url; ?>">Lead Instructions</a></li>
					<?php
				}
				if( !empty($group_data) && learndash_is_group_leader_user( $user ) || current_user_can( 'manage_options' ) ) {
					
					$week_num = $next_week = '';
					
					foreach ($group_data['lesson_review_dates'] as $key => $lesson_date ) {
						$lesson_date 		= new DateTime( date('Y-m-d 23:59:59', strtotime($lesson_date)), new DateTimeZone( "America/Los_Angeles" ) );
						$date_interval 		= $current_date->diff( $lesson_date );
						$date_diff 			= $date_interval->format('%R%a');
						
						if( strpos($date_diff, '-') != 0 || strpos($date_diff, '-') === false && ($date_diff >= 0) && ($date_diff < 7) ) {
							$week_num 	= $key - 1;
							$next_week 	= $key;
							break;
						}
					}

					if( $week_num == '-1' || empty($week_num) ) {
						$week_num = 0;
					}

					if( $next_week == 0  ) {
						$next_week = 1;
					}

					if( $week_num > 12 ) {
						$week_num = 12;
					}

					if( $week_num == 12 ) {
						$next_week = '';
					}


					?>
					<li class="ui-state-default ui-corner-top">
						<?php
						$tab_leader_instructions_url = add_query_arg('week', $week_num, $tab_leader_instructions_url);
						?>
						<a class="ui-tabs-anchor" href="<?php echo $tab_leader_instructions_url; ?>">
							Week <?php echo $week_num; ?> Lead Instructions
						</a>
					</li>
					<?php if( !empty($next_week) ): ?>
						<li class="ui-state-default ui-corner-top">
							<?php
							$tab_leader_instructions_two_url = add_query_arg('week', $next_week, $tab_leader_instructions_two_url);
							?>
							<a class="ui-tabs-anchor" href="<?php echo $tab_leader_instructions_two_url; ?>">
								Week <?php echo $next_week; ?> Lead Instructions
							</a>
						</li>
					<?php endif; ?>

					<li class="ui-state-default ui-corner-top">
						<?php
						$tab_leader_facilitator_instructions_url = add_query_arg('week', $week_num, $tab_leader_facilitator_instructions_url);
						?>
						<a class="ui-tabs-anchor" href="<?php echo $tab_leader_facilitator_instructions_url; ?>">
							Facilitator Instructions
						</a>
					</li>

					<?php
				  }
	  	       }
				 ?>

			</ul>
		</div>

		<?php
		$output = ob_get_contents();
        ob_end_clean();
        return $output;
	}


	public static function get_group_roster( $group_id )
	{
		global $wpdb;
		$table 				= 	'_isContact';
		$curr_user 			= 	wp_get_current_user();
		$students 			= 	learndash_get_groups_users( $group_id );
		$leaders 			= 	learndash_get_groups_administrators( $group_id );

		ob_start();
		?>

		<div style="overflow-x:auto; width: 100%;">
			<table class="profile-fields group-attendance">
				<thead>
					<tr>
						<!-- <th>First Name</th>
						<th>Last Name</th> -->
						<th>Name</th>
						<th>Email</th>
						<?php if( learndash_is_group_leader_user( $curr_user ) ): ?>
							<th>Phone 1</th>
						<?php endif; ?>
						<th>State</th>
					</tr>
				</thead>

				<tbody>
					<?php
					if( !empty($students) ) {
						foreach ($students as $user) {
							$contact_id = get_user_meta( $user->ID, 'infusion4wp_user_id', true );
							if( !empty($contact_id) ) {
								$phone = $wpdb->get_col( "SELECT meta_value FROM $table WHERE id = $contact_id AND meta_field = 'Phone1'" );
								$state = $wpdb->get_col( "SELECT meta_value FROM $table WHERE id = $contact_id AND meta_field = 'State'" );
							}
							?>
							<tr>
								<!-- <td><?php echo $user->first_name; ?></td>
								<td><?php echo $user->last_name; ?></td> -->
								<td><?php echo $user->display_name; ?></td>
								<td><?php echo $user->user_email; ?></td>
								<?php if( learndash_is_group_leader_user( $curr_user ) ): ?>
									<td><?php echo !empty($contact_id) && isset($phone[0]) ? $phone[0] : ""; ?></td>
								<?php endif; ?>
								<td><?php echo !empty($contact_id) && isset($state[0]) ? $state[0] : ""; ?></td>
							</tr>
							<?php
						}
					}
					?>
				</tbody>
			</table>
		</div>
		<?php
		$output = ob_get_contents();
        ob_end_clean();
        return $output;
	}


	public static function get_group_zoom_info( $group_id )
	{
		$zoom_meeting_id 		= get_post_meta( $group_id, 'lm_meeting_id', true );
		
		$zoom_meeting_page_id 	= get_field('zoom_meeting_page', 'option');
		$zoom_meeting_page 		= add_query_arg(
			array(
				'lm_group_id'	=>	$group_id,
				'lm_meeting_id'	=>	$zoom_meeting_id,
			),
			get_permalink( $zoom_meeting_page_id )
		);

		$zoom_info = 'Please <a href="'.$zoom_meeting_page.'" target="_blank">click</a> here for your zoom meeting.';
		
		return $zoom_info;
	}

	public static function get_group_lead_instructions( $group_id )
	{
		$user_id 				= get_current_user_id();
		$user_lesson_data 		= get_user_meta( $user_id, "lm_lesson_group_{$group_id}_info", true );	
		//$week_content 			= get_field( "questions_week_" . $user_lesson_week_num, "option" );
		$lead_instructions 		= get_field( "lead_instructions" , "option" );
		$defult_instructions 	= get_field( "default_questions", "option" );
		$content 				= '';
		
		//$content 				.= sprintf('<h4>%s</h4>', __('Lead Instructions'));
		$content 				.= $lead_instructions;
		$content 				.= '<br>';


		if( learndash_is_group_leader_user( $user_id ) || current_user_can( 'manage_options' ) ) {

			$group_data = get_post_meta( $group_id, 'lm_group_data', true );
			
			if( !empty($group_data) && isset($group_data['lesson_review_dates']) && !empty( $group_data['lesson_review_dates'] ) ) {
				$current_date 		= new DateTime();
				$week_num = '';

				foreach ($group_data['lesson_review_dates'] as $key => $lesson_date ) {
					
					if( $key == 0 ) {
						//continue;
					}

					$lesson_date 		= new DateTime( date('Y-m-d', strtotime($lesson_date)) );
					$date_interval 		= $current_date->diff( $lesson_date );
					$interval 			= $date_interval->format('%R%a');
					
					if( ( $interval > -1 ) && ( $interval < ($key == 8 ? 14 : 7) ) ) {
						$week_num 	= $key - 1;
						break;
					}
					
				}
				
				if( $week_num || $week_num == 0 ) {
					
					$week_content 	= get_field( "questions_week_" . $week_num, "option" );
					$content 		.= $week_content;
				}
			}

		} else {

			if( !empty($user_lesson_data) && is_array($user_lesson_data) ) {
				$week_num = '';
				$date 				= 'now';
				//$date 				= '2021-02-09 23:59:59';
				$current_date 		= new DateTime($date, new DateTimeZone( "America/Los_Angeles" ));
				
				foreach ($user_lesson_data as $key => $lesson_info ) {
					$lesson_date 		= new DateTime( date('Y-m-d 23:59:59', $lesson_info['date']), new DateTimeZone( "America/Los_Angeles" ) );
					$date_interval 		= $current_date->diff( $lesson_date );
					$date_diff 			= $date_interval->format('%R%a');
					//dd($date_diff, false);
					if( strpos($date_diff, '-') != 0 || strpos($date_diff, '-') === false && ($date_diff >= 0) && ($date_diff < 7) ) {
						$week_num 	= $lesson_info['week'];
						break;
					}
				}

				if( $week_num ) {
					$week_content 	= get_field( "questions_week_" . $week_num, "option" );
					$content 		.= $week_content;
				}
				
			} else {
				$content 			.= $defult_instructions;
				$content 			.= $week_content;
			}

		}

		if( strpos($content, 'resp-container') === false ) {
			$content 			= str_replace(['<iframe', '</iframe>'], ['<div class="resp-container"><iframe', '</iframe></div>'], $content);
		}
		
		$output 			= wpautop( $content );

		return $output;
	}


	public static function get_group_lead_instructions_one( $group_id ) {

		$user_id 				= get_current_user_id();
		$user_lesson_data 		= get_user_meta( $user_id, "lm_lesson_group_{$group_id}_info", true );	
		//$week_content 			= get_field( "questions_week_" . $user_lesson_week_num, "option" );
		$lead_instructions 		= get_field( "lead_instructions" , "option" );
		$defult_instructions 	= get_field( "default_questions", "option" );
		$content 				= '';
		
		//$content 				.= sprintf('<h4>%s</h4>', __('Lead Instructions'));
		$content 				.= $lead_instructions;
		$content 				.= '<br>';

		$group_data = get_post_meta( $group_id, 'lm_group_data', true );
		
		if( !empty($group_data) && isset($group_data['lesson_review_dates']) && !empty( $group_data['lesson_review_dates'] ) ) {
			$date 				= 'now';
			//$date 				= '2021-03-01 23:59:59';
			$current_date 		= new DateTime($date, new DateTimeZone( "America/Los_Angeles" ));
			$week_num = '';

			if( isset($_GET["week"]) && !empty($_GET["week"]) ) {
				$week_num = $_GET["week"];
			} else {
				foreach ($group_data['lesson_review_dates'] as $key => $lesson_date ) {
					
					if( $key == 0 ) {
						//continue;
					}

					$lesson_date 		= new DateTime( date('Y-m-d 23:59:59', strtotime($lesson_date)), new DateTimeZone( "America/Los_Angeles" ) );
					$date_interval 		= $current_date->diff( $lesson_date );
					$date_diff 			= $date_interval->format('%R%a');
					if( strpos($date_diff, '-') != 0 || strpos($date_diff, '-') === false && ($date_diff >= 0) && ($date_diff < 7) ) {
						$week_num 	= $key - 1;
						break;
					}
				}
			}
			
			if( $week_num || $week_num == 0 ) {
				$week_content 	= get_field( "questions_week_" . $week_num, "option" );
				$content 		.= $week_content;
			}

			if( $week_num == '-1' ) {
				$week_content 	= get_field( "tech_check_questions", "option" );
				$content 		.= $week_content;
			}
		}

		if( strpos($content, 'resp-container') === false ) {
			$content 			= str_replace(['<iframe', '</iframe>'], ['<div class="resp-container"><iframe', '</iframe></div>'], $content);
		}

		$output 			= wpautop( $content );
		return $output;

	}

	public static function get_group_lead_instructions_two( $group_id ) {
		$user_id 				= get_current_user_id();
		$user_lesson_data 		= get_user_meta( $user_id, "lm_lesson_group_{$group_id}_info", true );	
		//$week_content 			= get_field( "questions_week_" . $user_lesson_week_num, "option" );
		$lead_instructions 		= get_field( "lead_instructions" , "option" );
		$defult_instructions 	= get_field( "default_questions", "option" );
		$content 				= '';
		
		//$content 				.= sprintf('<h4>%s</h4>', __('Lead Instructions'));
		$content 				.= $lead_instructions;
		$content 				.= '<br>';

		$group_data = get_post_meta( $group_id, 'lm_group_data', true );
		
		if( !empty($group_data) && isset($group_data['lesson_review_dates']) && !empty( $group_data['lesson_review_dates'] ) ) {
			$date 				= 'now';
			//$date 				= '2021-03-01 23:59:59';
			$current_date 		= new DateTime($date, new DateTimeZone( "America/Los_Angeles" ));
			$week_num = '';

			if( isset($_GET["week"]) && !empty($_GET["week"]) ) {
				$week_num = $_GET["week"];
			} else {

				foreach ($group_data['lesson_review_dates'] as $key => $lesson_date ) {
					
					if( $key == 0 ) {
						//continue;
					}

					$lesson_date 		= new DateTime( date('Y-m-d 23:59:59', strtotime($lesson_date)), new DateTimeZone( "America/Los_Angeles" ) );
					$date_interval 		= $current_date->diff( $lesson_date );
					$date_diff 			= $date_interval->format('%R%a');
					if( strpos($date_diff, '-') != 0 || strpos($date_diff, '-') === false && ($date_diff >= 0) && ($date_diff < 7) ) {
						$week_num 	= $key;
						break;
					}
				}
			}
			
			if( $week_num || $week_num == 0 ) {
				$week_content 	= get_field( "questions_week_" . $week_num, "option" );
				$content 		.= $week_content;
			}

			if( $week_num == '-1' ) {
				$week_content 	= get_field( "tech_check_questions", "option" );
				$content 		.= $week_content;
			}
		}

		if( strpos($content, 'resp-container') === false ) {
			$content 			= str_replace(['<iframe', '</iframe>'], ['<div class="resp-container"><iframe', '</iframe></div>'], $content);
		}

		$output 			= wpautop( $content );
		return $output;
	}

	public static function get_group_lead_facilitator_instructions( $group_id ) {

		$user_id 				= get_current_user_id();
		$user_lesson_data 		= get_user_meta( $user_id, "lm_lesson_group_{$group_id}_info", true );	
		//$week_content 			= get_field( "questions_week_" . $user_lesson_week_num, "option" );
		
		$defult_instructions 	= get_field( "default_facilitator_instructions", "option" );
		$content 				= '';
		
		//$content 				.= sprintf('<h4>%s</h4>', __('Lead Instructions'));
		/*$content 				.= $lead_instructions;
		$content 				.= '<br>';*/

		$group_data = get_post_meta( $group_id, 'lm_group_data', true );
		
		$weeks = array(
			'teck_check'	=>	__('Tech Check'),
			'week_0'		=>	__('Week 0'),
		);
	   $course_id = LM_Helper::get_group_course( $group_id );
	        $excluded_courses 	= get_field( 'enable_manage_courses', 'option');
	        if(in_array($course_id, $excluded_courses))
	        {
		      $get_week_count = get_post_meta( $course_id, 'many_weeks', true );
	        }
	        else
	        {
              $get_week_count = '13';
	        }

			for ($i = 0; $i < $get_week_count+1; $i++) {
				$weeks[ 'week_' . $i ] = 'Week ' . $i;
			}

		if( current_user_can( 'manage_options' ) ) {
			ob_start();
			?>
			<form action="<?php echo admin_url( 'admin-ajax.php' ) ?>" id="signup_form" class="standard-form base" method="POST" autocomplete="off">
				<table class="profile-fields attendance-form student-form-details">
					<tbody>
						<tr>
							<th><label for="week_facilitator_instructions"><strong>Select Week</strong></label></th>
							<td>
								<select name="week_facilitator_instructions" id="week_facilitator_instructions" class="week_facilitator_instructions lm-user-select" data-group_id="<?php echo $group_id; ?>">
									<option value="">Please Select</option>
									<?php
									foreach ($weeks as $week_key => $week_label) {
										echo '<option value="'.$week_key.'">'.$week_label.'</option>';
									}
									?>
								</select>
								<p class="description">Please select week to view facilitator instructions of the selected week</p>
							</td>
						</tr>
						<tr class="load-student-form-wrapper">
							<td colspan="2">
								<div class="load-week-facilitator-instructions"></div>
							</td>
						</tr>
					</tbody>
				</table>
			</form>
			<?php
			$output = ob_get_contents();
	        ob_end_clean();
	        $output = wpautop( $output );
	        return $output;
		}
		
		if( !empty($group_data) && isset($group_data['lesson_review_dates']) && !empty( $group_data['lesson_review_dates'] ) ) {
			$date 				= 'now';
			//$date 				= '2021-03-01 23:59:59';
			$current_date 		= new DateTime($date, new DateTimeZone( "America/Los_Angeles" ));
			$week_num = '';

			if( isset($_GET["week"]) && !empty($_GET["week"]) ) {
				$week_num = $_GET["week"];
			} else {
				foreach ($group_data['lesson_review_dates'] as $key => $lesson_date ) {

					$lesson_date 		= new DateTime( date('Y-m-d 23:59:59', strtotime($lesson_date)), new DateTimeZone( "America/Los_Angeles" ) );
					$date_interval 		= $current_date->diff( $lesson_date );
					$date_diff 			= $date_interval->format('%R%a');

					if( strpos($date_diff, '-') != 0 || strpos($date_diff, '-') === false && ($date_diff >= 0) && ($date_diff < 7) ) {
						$week_num 	= $key - 1;
						break;
					}
				}
			}
			
			if( $week_num || $week_num == 0 ) {
				$week_content 	= get_field( "instructions_week_" . $week_num, "option" );
				if( empty($week_content) ) {
					$week_content = $defult_instructions;
				}
				$content 		.= $week_content;
			}

			if( $week_num == '-1' ) {
				$week_content 	= get_field( "tech_check_instructions", "option" );
				$content 		.= $week_content;
			}

			ob_start();
			?>
			<form action="<?php echo admin_url( 'admin-ajax.php' ) ?>" id="signup_form" class="standard-form base" method="POST" autocomplete="off">
				<table class="profile-fields attendance-form student-form-details">
					<tbody>
						<tr>
							<th><label for="week_facilitator_instructions"><strong>Select Week</strong></label></th>
							<td>
								<select name="week_facilitator_instructions" id="week_facilitator_instructions" class="week_facilitator_instructions lm-user-select" data-group_id="<?php echo $group_id; ?>">
									<option value="">Please Select</option>
									<?php
									foreach ($weeks as $week_key => $week_label) {
										$selected = strpos($week_key, $week_num) !== false ? 'selected' : '';
										echo '<option  value="'.$week_key.'" '.$selected.' >'.$week_label.'</option>';
									}
									?>
								</select>
								<p class="description">Please select week to view facilitator instructions of the selected week</p>
							</td>
						</tr>
						<tr class="load-student-form-wrapper">
							<td colspan="2">
								<div class="load-week-facilitator-instructions"><?php echo $content; ?></div>
							</td>
						</tr>
					</tbody>
				</table>
			</form>
			<?php
			$output = ob_get_contents();
	        ob_end_clean();
	        $content = wpautop( $output );
		}

		if( strpos($content, 'resp-container') === false ) {
			$content 			= str_replace(['<iframe', '</iframe>'], ['<div class="resp-container"><iframe', '</iframe></div>'], $content);
		}

		
		$output 			= wpautop( $content );
		return $output;
	}

	public static function get_group_schedule_view( $group_id )
	{
		global $wpdb;
		
		 // $group_courses 	=	learndash_group_enrolled_courses( $group_id );
		$course_ids = array();

	   $group_id = absint( $group_id );
	   if ( ! empty( $group_id ) ) {

		$query_args = array(
			'post_type'      => learndash_get_post_type_slug( 'course' ),
			'fields'         => 'ids',
			'posts_per_page' => -1,
			'author' => -1,
			'meta_query'     => array(
				array(
					'key'     => 'learndash_group_enrolled_' . $group_id,
					'compare' => 'EXISTS',
				),
			),
		);

		$query = new WP_Query( $query_args );
		if ( ( is_a( $query, 'WP_Query' ) ) && ( property_exists( $query, 'posts' ) ) ) {
			$course_ids = $query->posts;
		}
	   }
	   $group_courses = $course_ids;
		 // echo $wpdb->last_query;
		 // echo $group_id;
		if( empty( $group_courses ) ){
			return __( 'No data available' );
		}
	    $course_id 		= 	$group_courses[0];
	    $group_data 	= 	get_post_meta( $group_id, 'lm_group_data', true );
		$group_start_date 	= 	get_post_meta( $group_id, 'lm_course_start_date', true );
	

		if( !isset($group_data['lesson_dates']) || isset($group_data['lesson_dates']) && empty($group_data['lesson_dates']) ){
			return __( 'No data available' );
		}

		$ld_course_steps_object = LDLMS_Factory_Post::course_steps( $course_id );
		$lesson_ids = $ld_course_steps_object->get_children_steps( $course_id, 'sfwd-lessons' );

		//$course_lessons = learndash_get_course_lessons_list( $course_id, array( 'sfwd-lessons' ) );
		// $total_rows 	= ceil( count($lesson_ids) / 2 );
		 $total_row_count = get_post_meta( $course_id, 'many_weeks', true );
		 if($total_row_count)
		 {
		 	$total_rows = $total_row_count+1;
		 }
		 else{
		 	$total_rows 	= ceil( count($lesson_ids) / 2 );
		 }
		//$total_rows 	= $total_rows + count( $sections );

		$lessons 		= array();
		array_unshift($lessons , array(
			'post' => (object) array(
				'ID'			=>	9999999,
				'post_title' 	=>	__('Introductions & Tech Check!')
			)
		));
		
		foreach ($lesson_ids as $lesson) {
			$lessons[] 	= array(
				'post' => (object) array(
					'ID'			=>	$lesson_id,
					//'post_title' 	=>	sprintf(__('Lesson %s'), $lesson['sno'] )
					'post_title' 	=>	get_the_title( $lesson_id )
				)
			);
		}

		//dd($lessons);
		
		$sections 		= learndash_30_get_course_sections( $course_id );
		$students 		= learndash_get_groups_users( $group_id );
		$leaders 		= learndash_get_groups_administrators( $group_id );
		$members 		= array();
		foreach ($students as $student) {
			$members['students'][] = array(
				'ID' 	=> 	$student->ID,
				'name' 	=> 	$student->data->display_name,
			);
		}

		foreach ($leaders as $leader) {
			$members['leaders'][] = array(
				'ID' 	=> 	$leader->ID,
				'name' 	=> 	$leader->data->display_name,
			);
		}		
		
		if( !empty( $sections ) ) {

			reset($sections);
			$key = key($sections);
			unset($sections[$key]);
		}


		?>

		<div style="overflow-x:auto;">
			<table class="profile-fields group-attendance group-schedule">
				<thead>
					<tr>
						<th class="" style="width: 40px;"><?php echo __('Call #');?></th>
						<th class="">&nbsp;</th>
						<th class="" style="width: 110px;"><?php echo __('Availability Date');?></th>
						<th class="" style="width: 110px;"><?php echo __('Review Date');?></th>
						<th class=""><?php echo __('Class Agenda - Review:');?></th>
						<th class=""><?php echo __('Leading the discussion');?></th>
					</tr>
				</thead>

				<tbody>
						<?php
						$counter = 0;
						$call = 1;
						for ($i = 0; $i < $total_rows; $i++) {

							if( $call == 1 ) {
								$call_text = __('Tech Check');
								$i--;
							} else {
								$call_text = sprintf( __('Week %s' ), $i );
							}

							if( $counter == 0 || $counter == 1 ) {
								$group_data['lesson_dates'][$counter] = '';
								//$group_data['lesson_review_dates'][$counter] = '';
							}

							?>
							<tr>
								<td style="width: 40px;"><?php echo $call; ?></td>
								<td style="width: 90px;"><?php echo $call_text; ?></td>
								<td>
									<?php echo isset( $group_data['lesson_dates'][$counter] ) ? $group_data['lesson_dates'][$counter] : '' ; ?>
								</td>
								<td>
									<?php echo isset( $group_data['lesson_review_dates'][$counter] ) ? $group_data['lesson_review_dates'][$counter] : ''; ?>
								</td>
								<td>

									<?php
									$lesson_titles = array();
									if( !empty($group_data['lm_lessons'][$counter]) ) {
										foreach ($group_data['lm_lessons'][$counter] as $lesson_id) {
											if(  $lesson_id == 9999999 ) {
												$lesson_titles[] = __('Introductions & Tech Check!');
											} else {
												$lesson_titles[] = get_the_title( $lesson_id );
											}

											
										}
									}
									echo implode('<br>', $lesson_titles);
									?>
								</td>
								<td>
									<?php
									$users_info = array();
									if( isset($group_data['users'][$counter]) && !empty($group_data['users'][$counter]) ) {
										foreach ($group_data['users'][$counter] as $user_id) {
											$user 			= get_user_by( 'ID', $user_id );
											$users_info[] 	= $user->display_name;
										}
									}
									echo implode('<br>', $users_info);
									?>
								</td>
							</tr>

							<?php

							if( $call == 2 ) {
								?>
								<tr>
									<td colspan="5">Phase 1</td>
									<td>
										<?php
										$users_info = array();
										if( isset($group_data['s_users'][0]) && !empty($group_data['s_users'][0]) ) {
											foreach ($group_data['s_users'][0] as $user_id) {
												$user 			= get_user_by( 'ID', $user_id );
												$users_info[] 	= $user->display_name;
											}
										}
										echo implode('<br>', $users_info);
										?>
									</td>
								</tr>
								<?php
							}

							if( $call == 8) {
								?>
								<tr>
									<td colspan="5">Phase 2</td>
									<td>
										<?php
										$users_info = array();
										if( isset($group_data['s_users'][1]) && !empty($group_data['s_users'][1]) ) {
											foreach ($group_data['s_users'][1] as $user_id) {
												$user 			= get_user_by( 'ID', $user_id );
												$users_info[] 	= $user->display_name;
											}
										}
										echo implode('<br>', $users_info);
										?>
									</td>
								</tr>
								<?php
							}
							if( $call == 14) {
								?>
								<tr>
									<td colspan="5">Phase 3</td>
									<td>
										<?php
										$users_info = array();
										if( isset($group_data['s_users'][3]) && !empty($group_data['s_users'][3]) ) {
											foreach ($group_data['s_users'][3] as $user_id) {
												$user 			= get_user_by( 'ID', $user_id );
												$users_info[] 	= $user->display_name;
											}
										}
										echo implode('<br>', $users_info);
										?>
									</td>
								</tr>
								<?php
							}							
							if( $call == 20) {
								?>
								<tr>
									<td colspan="5">Phase 4</td>
									<td>
										<?php
										$users_info = array();
										if( isset($group_data['s_users'][4]) && !empty($group_data['s_users'][4]) ) {
											foreach ($group_data['s_users'][4] as $user_id) {
												$user 			= get_user_by( 'ID', $user_id );
												$users_info[] 	= $user->display_name;
											}
										}
										echo implode('<br>', $users_info);
										?>
									</td>
								</tr>
								<?php
							}
					
							if( $call == 26) {
								?>
								<tr>
									<td colspan="5">Phase 5</td>
									<td>
										<?php
										$users_info = array();
										if( isset($group_data['s_users'][5]) && !empty($group_data['s_users'][5]) ) {
											foreach ($group_data['s_users'][5] as $user_id) {
												$user 			= get_user_by( 'ID', $user_id );
												$users_info[] 	= $user->display_name;
											}
										}
										echo implode('<br>', $users_info);
										?>
									</td>
								</tr>
								<?php
							}
					
							if( $call == 32) {
								?>
								<tr>
									<td colspan="5">Phase 6</td>
									<td>
										<?php
										$users_info = array();
										if( isset($group_data['s_users'][6]) && !empty($group_data['s_users'][6]) ) {
											foreach ($group_data['s_users'][6] as $user_id) {
												$user 			= get_user_by( 'ID', $user_id );
												$users_info[] 	= $user->display_name;
											}
										}
										echo implode('<br>', $users_info);
										?>
									</td>
								</tr>
								<?php
							}
					
							if( $call == 38) {
								?>
								<tr>
									<td colspan="5">Phase 7</td>
									<td>
										<?php
										$users_info = array();
										if( isset($group_data['s_users'][7]) && !empty($group_data['s_users'][7]) ) {
											foreach ($group_data['s_users'][7] as $user_id) {
												$user 			= get_user_by( 'ID', $user_id );
												$users_info[] 	= $user->display_name;
											}
										}
										echo implode('<br>', $users_info);
										?>
									</td>
								</tr>
								<?php
							}
					
							if( $call == 44) {
								?>
								<tr>
									<td colspan="5">Phase 8</td>
									<td>
										<?php
										$users_info = array();
										if( isset($group_data['s_users'][8]) && !empty($group_data['s_users'][8]) ) {
											foreach ($group_data['s_users'][8] as $user_id) {
												$user 			= get_user_by( 'ID', $user_id );
												$users_info[] 	= $user->display_name;
											}
										}
										echo implode('<br>', $users_info);
										?>
									</td>
								</tr>
								<?php
							}
					
							if( $call == 50) {
								?>
								<tr>
									<td colspan="5">Phase 9</td>
									<td>
										<?php
										$users_info = array();
										if( isset($group_data['s_users'][9]) && !empty($group_data['s_users'][9]) ) {
											foreach ($group_data['s_users'][9] as $user_id) {
												$user 			= get_user_by( 'ID', $user_id );
												$users_info[] 	= $user->display_name;
											}
										}
										echo implode('<br>', $users_info);
										?>
									</td>
								</tr>
								<?php
							}
					

							$counter++;
							$call++;
						}
						?>
					</tbody>
			</table>
		</div>

		<?php

	}


	public static function get_group_form( $group_id )
	{
		$user = wp_get_current_user();

		if( learndash_is_group_leader_user( $user ) || current_user_can( 'manage_options' ) ) {
			echo self::lm_group_leader_form_management( $group_id, $user );
		} else {
			echo self::lm_group_member_form_management( $group_id, $user );
		}
		
	}

	public static function lm_group_leader_form_management( $group_id, $user )
	{
		$form_id 			= get_field('student_form', 'option');
		$students 			= 	learndash_get_groups_users( $group_id );

		ob_start();
		?>
		<form action="<?php echo admin_url( 'admin-ajax.php' ) ?>" id="signup_form" class="standard-form base" method="POST" autocomplete="off">
			<table class="profile-fields attendance-form student-form-details">
				<tbody>
					<tr>
						<th><label for="student_view_form"><strong>Select Student</strong></label></th>
						<td>
							<select name="student_view_form" id="student_view_form" class="student_view_form lm-user-select" data-group_id="<?php echo $group_id; ?>">
								<option value="">Please Select</option>
								<?php
								foreach ($students as $user) {
									echo '<option value="'.$user->ID.'">'.$user->display_name.'</option>';
								}
								?>
							</select>
							<p class="description">Please select student to view form submissions</p>
						</td>
					</tr>
					<tr class="load-student-form-wrapper">
						<td colspan="2">
							<div class="load-student-form-details"></div>
						</td>
					</tr>
				</tbody>
			</table>
		</form>
						
		<?php
		$output = ob_get_contents();
        ob_end_clean();
        return $output;
		
	}

	public static function lm_group_member_form_management( $group_id, $user )
	{
		$search_criteria = array(
			'status'        => 'active',
			'field_filters' => array(
				array(
					'key'   => 'created_by',
					'value' => $user->ID
				),
				array(
					'key'   => '20',
					'value' => $group_id,
					'operator'	=>	'is'
				)
			)
		);

		$entries         = GFAPI::get_entries( $form_id, $search_criteria );

		// if user has already submitted the form for the particular group
		if( !empty( $entries ) ) {

			$output = self::get_form_entry_details( $entries[0], $group_id, $user );
			return $output;

		} else {
			
			$form_id = get_field('student_form', 'option');
			$form_shortcode = '[gravityform id="'.$form_id.'" title="false" description="false" ajax="true" field_values="group_id='.$group_id.'"]';
			
			//return apply_filters( 'the_content', do_shortcode( $form_shortcode ) );
			return do_shortcode( $form_shortcode );
		}

	}

	public static function get_form_entry_details( $entry, $group_id, $user )
	{
		$form_id = get_field('student_form', 'option');
		$form = GFFormsModel::get_form_meta( absint( $form_id ) );

		$form_id = absint( $form['id'] );
		$lead 	 = $entry;

		$form    = apply_filters( 'gform_admin_pre_render', $form );
		$form    = apply_filters( 'gform_admin_pre_render_' . $form_id, $form );

		ob_start();
		?>
		<p>You have already submitted the form. Please see below your form submission. </p>
		<table class="widefat fixed entry-detail-view">
			<tbody>
				<?php
				$count = 0;
				$field_count = sizeof( $form['fields'] );
				$has_product_fields = false;

				foreach ( $form['fields'] as $field ) {

					if( $field->id == 20 )
						continue;

					$content = $value = '';

					switch ( $field->get_input_type() ) {
						case 'section' :
							if ( ! GFCommon::is_section_empty( $field, $form, $lead ) || $display_empty_fields ) {
								$count ++;
								$is_last = $count >= $field_count ? ' lastrow' : '';

								$content = '
	                                <tr>
	                                    <td colspan="2" class="entry-view-section-break' . $is_last . '">' . esc_html( GFCommon::get_label( $field ) ) . '</td>
	                                </tr>';
							}
							break;

						case 'captcha':
						case 'html':
						case 'password':
						case 'page':
							// Ignore captcha, html, password, page field.
							break;

						default :
							// Ignore product fields as they will be grouped together at the end of the grid.
							if ( GFCommon::is_product_field( $field->type ) ) {
								$has_product_fields = true;
								break;
							}

							$value = RGFormsModel::get_lead_field_value( $lead, $field );

							if ( is_array( $field->fields ) ) {
								// Ensure the top level repeater has the right nesting level so the label is not duplicated.
								$field->nestingLevel = 0;
							}

							$display_value = GFCommon::get_lead_field_display( $field, $value, $lead['currency'] );

							/**
							 * Filters a field value displayed within an entry.
							 *
							 * @since 1.5
							 *
							 * @param string   $display_value The value to be displayed.
							 * @param GF_Field $field         The Field Object.
							 * @param array    $lead          The Entry Object.
							 * @param array    $form          The Form Object.
							 */
							$display_value = apply_filters( 'gform_entry_field_value', $display_value, $field, $lead, $form );

							if ( $display_empty_fields || ! empty( $display_value ) || $display_value === '0' ) {
								$count ++;
								$is_last  = $count >= $field_count && ! $has_product_fields ? true : false;
								$last_row = $is_last ? ' lastrow' : '';

								$display_value = empty( $display_value ) && $display_value !== '0' ? '&nbsp;' : $display_value;

								$content = '
	                                <tr>
	                                    <th class="entry-view-field-name">' . esc_html( GFCommon::get_label( $field ) ) . '</th>
	                                    <td class="entry-view-field-value' . $last_row . '">' . $display_value . '</td>
	                                </tr>
	                                <tr>';
							}
							break;
					}

					/**
					 * Filters the field content.
					 *
					 * @since 2.1.2.14 Added form and field ID modifiers.
					 *
					 * @param string $content    The field content.
					 * @param array  $field      The Field Object.
					 * @param string $value      The field value.
					 * @param int    $lead['id'] The entry ID.
					 * @param int    $form['id'] The form ID.
					 */
					$content = gf_apply_filters( array( 'gform_field_content', $form['id'], $field->id ), $content, $field, $value, $lead['id'], $form['id'] );

					echo $content;
				}
				?>
			</tbody>
		</table>
		<?php
		$output = ob_get_contents();
        ob_end_clean();
        return $output;
	}


	public static function find_tag_group_assign_user( $tag_id, $contact_ids = array() ) {

		lm_debug_log( sprintf('LM Note: infusionSoft data recieved, tagID: %s contact_ids: %s', $tag_id, var_export($contact_ids, true) )  );

		$args = array(
			'post_type' 		=> 	'groups',
			'post_status' 		=> 	'publish',
			'posts_per_page'    => 	-1,
			'meta_key'			=>	'lm_group_tag',
			'meta_value'		=> 	$tag_id,
		);
		
		$query = new WP_Query( $args );

		if( is_array($query->posts) && !empty($query->posts) ) {
			
			$group_ids = wp_list_pluck( $query->posts, 'ID' );

			if( empty( $group_ids ) ) {
				return;
			}

			$user_args = array(
				'meta_query'	=>	array(
					array(
						'key'		=>	'infusion4wp_user_id',
						'value'		=> 	$contact_ids,
						'compare'	=>	'IN'
					)
				),
				'fields'		=>	'ID'		
			);

			$user_query = new WP_User_Query( $user_args );

			$user_ids  = $user_query->get_results();

			if( empty( $user_ids ) ) {
				lm_debug_log( sprintf('LM Note: no users found against contact_ids: %s', $contact_ids )  );
				return;
			}

			foreach ($user_ids as $user_id) {

				foreach ($group_ids as $group_id) {

					$is_member = learndash_is_user_in_group( $user_id, $group_id );
					
					if( !$is_member ) {
						
						$group_users 	= learndash_get_groups_user_ids( $group_id );

						if( !in_array($user_id, $group_users) ) {
							array_push($group_users, $user_id);
						}
						
						learndash_set_groups_users( $group_id, $group_users );

						lm_debug_log( sprintf('LM Note: user id: %s has been added into to group: %s', $user_id, $group_id )  );
					}
				}
			}

		}

	}



	//function to generate JWT
	public function generateJWTKey( $user_id ) {

		$key    = get_user_meta( $user_id, 'lm_zoom_api_key', true );
		$secret = get_user_meta( $user_id, 'lm_zoom_api_secret_key', true );

		$token = array(
			'iat' => time(),
			'aud' => null,
			'iss' => $key,
			'exp' => time() + strtotime( '+60 minutes' ),
		);
		
		return JWT::encode( $token, $secret );
	}


	public function sendRequest( $user_id, $calledFunction, $data, $request = 'GET', $log = true ) {

		if( empty($user_id) ) {
			return;
		}
		$request_url = $this->api_url . $calledFunction;
		$args        = array(
			'headers' => array(
				'Authorization' => 'Bearer ' . $this->generateJWTKey( $user_id ),
				'Content-Type'  => 'application/json',
			),
			'timeout' => 60,
		);

		try {
			set_time_limit( 0 );
			$response = new stdClass();
			$response = $this->makeRequest( $request_url, $args, $data, $request, $log );
			// check if response contains multiple pages
			if ( $this->data_list && isset( $response->{$this->return_object} ) ) {
				$response->{$this->return_object} = $this->data_list;
			}
			$response = json_encode( $response );
		} catch ( Exception $e ) {
			video_conferencing_zoom_log_error( $e->getMessage() );
		}

		return $response;
	}

	// Send API request to Zoom
	public function makeRequest( $request_url, $args, $data, $request = 'GET', $log = true ) {
		if ( 'GET' == $request ) {
			$args['body'] = ! empty( $data ) ? $data : array();
			$response     = wp_remote_get( $request_url, $args );
		} elseif ( 'DELETE' == $request ) {
			$args['body']   = ! empty( $data ) ? json_encode( $data ) : array();
			$args['method'] = 'DELETE';
			$response       = wp_remote_request( $request_url, $args );
		} elseif ( 'PATCH' == $request ) {
			$args['body']   = ! empty( $data ) ? json_encode( $data ) : array();
			$args['method'] = 'PATCH';
			$response       = wp_remote_request( $request_url, $args );
		} elseif ( 'PUT' == $request ) {
			$args['body']   = ! empty( $data ) ? json_encode( $data ) : array();
			$args['method'] = 'PUT';
			$response       = wp_remote_post( $request_url, $args );
		} else {
			$args['body']   = ! empty( $data ) ? json_encode( $data ) : array();
			$args['method'] = 'POST';
			$response       = wp_remote_post( $request_url, $args );
		}

		if ( is_wp_error( $response ) ) {
			video_conferencing_zoom_log_error( $response->get_error_message() );
			return false;
		}

		$response = wp_remote_retrieve_body( $response );

		if ( ! $response || '' == $response ) {
			return false;
		}

		$check_response = new stdClass();
		$check_response = json_decode( $response );
		if ( isset( $check_response->code ) || isset( $check_response->error ) ) {
			if ( $log ) {
				video_conferencing_zoom_log_error( $check_response->message );
			}
		}

		// Fetch the next page of the request
		if ( isset( $check_response->next_page_token )
			&& $check_response->next_page_token
			&& $this->return_object
		) {
			$data['next_page_token'] = $check_response->next_page_token;

			// If data received then fetch other pages too
			if ( $check_response->{$this->return_object} ) {
				$this->data_list = array_merge( $this->data_list, $check_response->{$this->return_object} );
			}

			return $this->makeRequest( $request_url, $args, $data, $request, $log );
		} elseif ( $this->data_list ) {
			// Get last page data in a multi request mode
			$this->data_list = array_merge( $this->data_list, $check_response->{$this->return_object} );
		}

		return $check_response;
	}


	/**
	 * User Function to List
	 *
	 * @return array
	 */
	public function list_user_zoom_Users( $user_id = false, $listUsersArray = array() ) {

		$zoom_users 	= array();

		//$listUsersArray['page_size'] = 300;

		$this->return_object = 'users';

		$encoded_users 	= $this->sendRequest( $user_id, 'users', $listUsersArray, 'GET' );
		$decoded_users 	= json_decode( $encoded_users );

		if ( isset( $decoded_users->code ) ) {
			$zoom_users = false;
		} else {
			$zoom_users = end($decoded_users->users);	
		}

		return $zoom_users;
	}


	/**
	 * Get All Recordings By User
	 *
	 * @param 	$user_id        The user identifier
	 * @param 	$zoom_user_obj  The zoom user object
	 * 
	 * @return 	array 			user zoom recordings
	 */
	public function listUserRecordings( $user_id, $zoom_user_obj ) {
		$listMeetingsArray              = array();
		$all_meetings                   = array();
		$listMeetingsArray['page_size'] = 300;

		// Fetch recordings upto 3 months
	 //    $preoneyear = date('Y-m-d', strtotime('-1 year'));
		// $todaydate = date('Y-m-d');
		// $listMeetingsArray['from'] = $preoneyear;
		// $listMeetingsArray['to'] = $todaydate;
		// $recordings_list = json_decode( $this->sendRequest( $user_id, 'users/' . $zoom_user_obj->id . '/recordings', $listMeetingsArray, 'GET' ) );

			// if ( isset( $recordings_list->meetings ) && $recordings_list->meetings ) {
			// 	$all_meetings = array_merge( $all_meetings, $recordings_list->meetings );
			// }
		for ( $month = 0; $month < 12; $month++ ) {
			$prev_month                = $month + 1;
			$listMeetingsArray['from'] = gmdate( 'Y-m-d', strtotime( "-$prev_month months" ) );
			$listMeetingsArray['to']   = gmdate( 'Y-m-d', strtotime( "-$month months" ) );
			$recordings_list = json_decode( $this->sendRequest( $user_id, 'users/' . $zoom_user_obj->id . '/recordings', $listMeetingsArray, 'GET' ) );

			if ( isset( $recordings_list->meetings ) && $recordings_list->meetings ) {
				$all_meetings = array_merge( $all_meetings, $recordings_list->meetings );
			}
		}
		// echo "<pre>";print_r($all_meetings);die;
		return $all_meetings;
	}


	public function filter_user_meeting_recordings( $user_id, $meeting_id ) {

		$groups_zoom_meeting  	 = array();
		$zoom_user_obj 	 = $this->list_user_zoom_Users( $user_id );
		$user_recordings = $this->listUserRecordings( $user_id, $zoom_user_obj );

		if( !empty($user_recordings)) {
			$user_recording_ids = wp_list_pluck( $user_recordings, 'id' );
			
			foreach( $user_recordings as $key => $meeting_recording ) {
				if( $meeting_recording->id == $meeting_id && $meeting_recording->duration > 15 ) {
					$groups_zoom_meeting[] = $meeting_recording;
				}
			}
		}

// echo "<pre>";print_r($groups_zoom_meeting);
		//$groups_zoom_meeting[0]->recording_files[0]->recording_type = 'shared_screen_with_gallery_view';

		if( !empty($groups_zoom_meeting) ) {

			foreach ( $groups_zoom_meeting as $meeting_key => $meeting_recording ) {

				$recording_files = $meeting_recording->recording_files;

				$recording_types = wp_list_pluck( $recording_files, 'recording_type' );

				$shared_screen_exits 	= in_array( 'shared_screen_with_gallery_view', $recording_types );
				$gallery_view_exits 	= in_array( 'gallery_view', $recording_types );

				foreach ( $recording_files as $file_key => $recording_file ) {

					if( $recording_file->recording_type == 'shared_screen_with_gallery_view' ) {
						$groups_zoom_meeting[ $meeting_key ]->recording_files = array( $recording_file );
						break;
					} elseif( $recording_file->recording_type == 'gallery_view' ) {
						$groups_zoom_meeting[ $meeting_key ]->recording_files = array( $recording_file );
						break;
					} else {
						unset( $groups_zoom_meeting[ $meeting_key ]->recording_files[$file_key] );
					}
				}
			}

		}

		return $groups_zoom_meeting;
	}

	public function get_user_meeting_recordings( $user_id, $meeting_id ) {

		$user_recordings = $this->filter_user_meeting_recordings( $user_id, $meeting_id );

		return $user_recordings;

	}


	public function upload_user_recording_to_vimeo( $this_recording, $meeting_topic, $jwt = false, $upload = true, $user_id = false ) {

		// Param only needed for Vimeo API
		if ( isset( $this_recording['file_size'] ) ) {
			$recording_size = $this_recording['file_size'];
			unset( $this_recording['file_size'] );
		}


		# If already uploaded or deleted from vimeo then don't upload
		if ( isset( $this_recording['vimeo_id'] ) || isset( $this_recording['vimeo_removed'] ) ) {
			return $this_recording;
		}

		// Append JWT access token for private recording
		if ( ! $jwt ) {
			$jwt = lm_helper()->generateJWTKey( $user_id );
		}

		# Set dependent Vimeo upload classes
		$api = new WP_DGV_Api_Helper();
		$db  = new WP_DGV_Db_Helper();

		# Upload remote cloud recording to Vimeo if not uploaded already
		$url    = $this_recording['recording_url'] . '?access_token=' . $jwt;
		$params = array(
			'name'        => $meeting_topic,
			'description' => $meeting_topic,
			'size'        => $recording_size,
		);

		// If plus or above vimeo plan then set video privacy
		if ( 'basic' != $api->user_type ) {
			$params['privacy'] = array(
				'add'      => 0, // prevent adding add the video to a showcase, channel, or group
				'view'     => 'disable', // make video unavailable from vimeo
				'download' => false, // prevent downloads
				'embed'    => 'whitelist', // Only allow embed to this site
			);

			$params['embed'] = array(
				'buttons' => array(
					'share'      => 0,
					'embed'      => 0,
					'watchlater' => 0,
					'fullscreen' => 1,
					'hd'         => 1,

				),
				'logos'   => array(
					'vimeo' => 0,
				),
			);
		} else {
			$params['privacy'] = array(
				'add'      => 0, //prevent adding add the video to a showcase, channel, or group
				'comments' => 'nobody',
			);
		}

		if( $upload ) {
			$vimeo_upload = $api->upload_pull( $url, $params );
			
			if ( isset( $vimeo_upload['response']['body']['uri'] )
				&& $vimeo_upload['response']['body']['uri'] ) {
				// If plus or above vimeo plan then set domain whitelist
				if ( 'basic' != $api->user_type ) {
					// Whitelist all domains on this WP install
					if ( function_exists( 'get_sites' ) ) {
						$sites = get_sites( array( 'number' => 0 ) );
						foreach ( $sites as $key => $site ) {
							$sites[ $key ] = $site->domain;
						}
						$sites = array_unique( $sites );
					} else {
						$sites[0] = get_site_url();
					}

					foreach ( $sites as $domain ) {
						$domain = str_replace( array( 'http://', 'https://' ), '', $domain );
						$api->whitelist_domain_add( $vimeo_upload['response']['body']['uri'], $domain );
					}
					$domain = 'schooloflifemastery.com';
					$api->whitelist_domain_add( $vimeo_upload['response']['body']['uri'], $domain );
				}

				$vimeo_id = str_replace( '/videos/', '', $vimeo_upload['response']['body']['uri'] );

				// Add video to Media -> Vimeo
				$this->create_local_video( $params['name'], $params['description'], $vimeo_id );
				$this_recording['vimeo_id'] = $vimeo_id;

				$vimeo_ids = get_option( 'lm_vimeo_ids', '' );
				$vimeo_ids .= ', ' .$vimeo_id;
				update_option( 'lm_vimeo_ids', $vimeo_ids );
				
			} elseif ( isset( $vimeo_upload['response']['body']['error'] ) ) {
				$dev_error = ( isset( $vimeo_upload['response']['body']['developer_message'] ) ? $vimeo_upload['response']['body']['developer_message'] : '' );
				video_conferencing_zoom_log_error( 'Vimeo Error: ' . $vimeo_upload['response']['body']['error'] . ' ' . $dev_error );
			}
		} else {
			$this_recording['vimeo_id'] = rand(564565646, 56456589798);
		}


		return $this_recording;
	}


	/**
     * Returns the local video
     *
     * @param $title
     * @param $description
     * @param $vimeo_id  - (eg. 18281821)
     * @param  string  $context
     *
     * @return int|WP_Error
     */
	public function create_local_video( $title, $description, $vimeo_id, $context = 'admin' ) {

	    $args = array(
            'post_title'   => wp_strip_all_tags( $title ),
            'post_content' => wp_strip_all_tags( $description ),
            'post_status'  => 'publish',
            'post_type'    => WP_DGV_Db_Helper::POST_TYPE_UPLOADS,
            //'post_author'  => is_user_logged_in() ? get_current_user_id() : 0,
            'post_author'  => 3,
        );

	    $args = apply_filters('dgv_insert_video_args', $args, $context);

		$postID = wp_insert_post( $args );

		if ( ! is_wp_error( $postID ) ) {
			update_post_meta( $postID, 'dgv_response', $vimeo_id );
		}

		update_post_meta($postID, 'dgv_context', $context);

		return $postID;
	}

	/**
	 * Get a Meeting Info
	 *
	 * @param  [INT] $id
	 * @param  [STRING] $host_id
	 *
	 * @return array
	 */
	public function get_user_meeting_info( $meeting_id ) {
		if ( ! $meeting_id ) {
			return;
		}

		global $wpdb;

		$group_id 	= $wpdb->get_var( "SELECT post_id FROM $wpdb->postmeta WHERE meta_value = '".$meeting_id."'" );
		$users 		= learndash_get_groups_administrator_ids( $group_id );
		$data 		= false;
		
		$getMeetingInfoArray              = array();
		$getMeetingInfoArray['meetingId'] = $meeting_id;
		

		if( !empty($users) ) {

			foreach ($users as $user_id) {
				
				$user_zoom_api_key 		= get_user_meta( $user_id, 'lm_zoom_api_key', true );
				$user_zoom_api_secret_key = get_user_meta( $user_id, 'lm_zoom_api_secret_key', true );

				// bail if we do not have user LD groups, zoom api key and zoom api secret key
				if( empty( $user_zoom_api_key ) && empty( $user_zoom_api_secret_key ) ) {
					continue;
				}

				$response = json_decode( $this->sendRequest( $user_id, 'meetings/' . $meeting_id, $getMeetingInfoArray, 'GET' ) );

				if( $response && isset($response->id)) {
					$data = $response;
					break;
				}

			}
		}

		return $data;	
	}

	public function video_conferencing_zoom_prepare_args( $args ) {

		global $wpdb;

		$meeting_id = $args['type_id'];
		$group_id 	= $wpdb->get_var( "SELECT post_id FROM $wpdb->postmeta WHERE meta_value = '" . $meeting_id . "'" );
		$users 		= learndash_get_groups_administrator_ids( $group_id );
		//$users 		= array_reverse( $users );

		$getMeetingInfoArray              = array();
		$getMeetingInfoArray['meetingId'] = $meeting_id;

		if( !empty($users) ) {
			foreach ($users as $user_id) {
				$user_zoom_api_key 			= get_user_meta( $user_id, 'lm_zoom_api_key', true );
				$user_zoom_api_secret_key 	= get_user_meta( $user_id, 'lm_zoom_api_secret_key', true );

				// bail if we do not have user LD groups, zoom api key and zoom api secret key
				if( !empty( $user_zoom_api_key ) && !empty( $user_zoom_api_secret_key ) ) {

					$response = json_decode( $this->sendRequest( $user_id, 'meetings/' . $meeting_id, $getMeetingInfoArray, 'GET' ) );
					if( $response && isset($response->id)) {
						break;
					}
				}
			}
		}

		$args['join_app_target']                    = get_option( 'zoom_meeting_join_app_target' );
		$args['attendee_name_format']               = get_option( 'zoom_meeting_attendee_name_format' );
		$args['remove_join_via_app_pass']           = get_option( 'zoom_join_via_app_pass' );
		$args['remove_zoom_auto_display_recording'] = get_option( 'zoom_auto_display_recording' );
		$args['hide_join_before_time']              = get_option( 'zoom_join_before_time' );
		$args['hide_register_for_meeting']          = get_option( 'zoom_register_for_meeting' );
		$args['global_web_disable']                 = get_option( 'zoom_join_via_web_disable' );
		$args['global_hide_join_via_app']           = get_option( 'zoom_help_text_disable' );
		$args['zoom_window_size']                   = get_option( 'zoom_window_size' );
		$args['zoom_btn_css_class']                 = get_option( 'zoom_btn_css_class' );
		$args['zoom_not_show_recordings']           = get_option( 'zoom_hide_recordings' );
		$args['zoom_not_show_countdown']            = get_option( 'zoom_hide_countdown_timer' );
		$args['zoom_not_scroll_window']             = get_option( 'zoom_disable_scroll_to_window' );
		$args['zoom_meeting_autojoin']              = get_option( 'zoom_meeting_autojoin' );
		$args['user_data']                          = get_userdata( get_current_user_id() );
		$args['is_admin']                           = video_conferencing_zoom_is_user_admin();
		$args['zoom_meeting_title']                 = ( $args['title'] ? $args['title'] : get_option( 'zoom_meeting_title' ) );
		$args['zoom_lang_select']                   = get_option( 'zoom_meeting_lang_select' );

		// If meeting data missing from WP then pull from Zoom API
		if ( ! isset( $args['zoom_map_array'] )
			|| ! isset( $args['zoom_map_array']['time'] )
			|| ! isset( $args['zoom_map_array']['password'] )
			|| ! isset( $args['zoom_map_array']['host_id'] )
			|| ! isset( $args['zoom_map_array']['join_url'] )
			) {
			try {
				if ( $args['is_webinar'] ) {
					$meeting_data = json_decode( lm_helper()->getWebinarInfo( $user_id, $args['type_id'] ) );
					$type         = 'zoom_api_webinar_options';
				} else {
					$meeting_data = json_decode( lm_helper()->getMeetingInfo( $user_id, $args['type_id'] ) );
					$type         = 'zoom_api_meeting_options';
				}

				if ( ! $meeting_data ) {
					return $args;
				}

				if ( isset( $meeting_data->code ) ) {
					throw new Exception( $meeting_data->message );
				}

				$args['zoom_map_array'] = video_conferencing_zoom_set_wp_cache( $meeting_data, $type, $args['zoom_map_array'] );

			} catch ( Exception $e ) {
				if ( current_user_can( 'manage_options' ) ) {
					echo $e->getMessage();
				}
				video_conferencing_zoom_log_error( $e->getMessage() );
			}
		}

		// Check whether to show the meeting countdown
		if ( isset( $args['zoom_map_array']['time'] ) && $args['zoom_map_array']['time'] ) {
			try {
				$dt                            = new DateTime( $args['zoom_map_array']['time'] );
				$meeting_zone                  = $dt->getTimezone()->getName();
				$args['meeting_timezone_time'] = video_conferencing_zoom_convert_time_to_local( 'now', $meeting_zone );
				$args['meeting_time_check']    = video_conferencing_zoom_convert_time_to_local( $args['zoom_map_array']['time'], $meeting_zone );

				// Show meeting countdown
				if ( $args['meeting_time_check'] > $args['meeting_timezone_time'] ) {
					$args['show_countdown'] = 1;
				}
			} catch ( Exception $e ) {
				if ( current_user_can( 'manage_options' ) ) {
					echo $e->getMessage();
				}
				video_conferencing_zoom_log_error( $e->getMessage() );
			}
		}

		// See if user wants to join meeting
		if ( isset( $_POST['join_iframe'] ) && isset( $_POST['meeting_nonce_field'] )
			&& wp_verify_nonce( $_POST['meeting_nonce_field'], 'meeting_nonce' ) ) {
			$args['join_meeting'] = 1;
		}

		// Set auto join meeting mode if enabled
		if ( video_conferencing_zoom_is_autologin( $args ) ) {
			$args['auto_join'] = 1;

			// Auto fill name & password if set on auto join
			if ( isset( $args['zoom_map_array']['password'] ) ) {
				$_POST['meeting_pwd'] = $args['zoom_map_array']['password'];
			}
		}

		if ( ! isset( $_POST['join_iframe'] ) && isset( $_GET['leave'] ) ) {
			$args['auto_join'] = 0;
		}

		// Set username and email
		if ( isset( $args['user_data']->user_login ) ) {
			switch ( $args['attendee_name_format'] ) {
				case '1':
					$attendee_name_by_format = ( $args['user_data']->first_name ? $args['user_data']->first_name : $args['user_data']->display_name );
					break;
				case '2':
					$attendee_name_by_format = ( $args['user_data']->user_login ? $args['user_data']->user_login : $args['user_data']->display_name );
					break;
				case '3':
					$attendee_name_by_format = ( $args['user_data']->user_email ? $args['user_data']->user_email : $args['user_data']->display_name );
					break;
				default:
					$attendee_name_by_format = ( $args['user_data']->first_name ? $args['user_data']->first_name . ' ' . $args['user_data']->last_name : $args['user_data']->display_name );
			}

			$args['display_name'] = trim( apply_filters( 'video_conferencing_zoom_attendee_name_display', ( isset( $_POST['display_name'] ) ? $_POST['display_name'] : $attendee_name_by_format ) ) );
			$args['user_email']   = trim( apply_filters( 'video_conferencing_zoom_attendee_email_display', $args['user_data']->user_email ) );
		} else {
			$args['display_name'] = trim( apply_filters( 'video_conferencing_zoom_attendee_name_display', ( isset( $_POST['display_name'] ) ? $_POST['display_name'] : $args['display_name'] ) ) );
			$args['user_email']   = trim( apply_filters( 'video_conferencing_zoom_attendee_email_display', ( isset( $_POST['meeting_email'] ) ? $_POST['meeting_email'] : $args['user_email'] ) ) );
		}

		if ( ! filter_var( $args['user_email'], FILTER_VALIDATE_EMAIL ) ) {
			$args['user_email'] = '';
		}

		// Set join via app link
		if ( 1 != $args['global_hide_join_via_app'] || 1 == $args['show_join_web'] && isset( $args['zoom_map_array']['join_url'] ) ) {
			if ( isset( $args['user_data']->ID ) ) {
				// If the logged in user registered meeting join url set
				$registrant_url = get_user_meta( $args['user_data']->ID, 'zoom_registrant_url_' . $args['type_id'], true );
				if ( $registrant_url ) {
					$args['zoom_map_array']['join_url'] = esc_url( $registrant_url );
				}
			}

			if ( isset( $args['zoom_map_array']['join_url'] ) ) {
				if ( 1 == $args['remove_join_via_app_pass'] ) {
					$args['zoom_map_array']['join_url'] = remove_query_arg( 'pwd', $args['zoom_map_array']['join_url'] );
				}

				if ( 1 != $args['join_app_target'] ) {
					$args['join_app_target'] = '_self';
				} else {
					$args['join_app_target'] = '_blank';
				}

				$args['join_via_app'] = '<button type="submit" onclick="event.preventDefault(); window.open(\'' . esc_url( $args['zoom_map_array']['join_url'] ) . '\', \'' . $args['join_app_target'] . '\');" class="zoom-link join-link ' . esc_attr( $args['zoom_btn_css_class'] ) . '">' . __( 'Join via Zoom App', 'video-conferencing-with-zoom-api' ) . '</button>';
			}
		}

		return $args;
	}


	/**
	 * Get a Meeting Info
	 *
	 * @param  [INT] $id
	 * @param  [STRING] $host_id
	 *
	 * @return array
	 */
	public function getMeetingInfo( $user_id, $id ) {
		if ( ! $id ) {
			return;
		}

		$getMeetingInfoArray              = array();
		$getMeetingInfoArray['meetingId'] = $id;

		$response = $this->sendRequest( $user_id, 'meetings/' . $id, $getMeetingInfoArray, 'GET' );

		return apply_filters( 'zoom_wp_after_get_meeting', $response );
	}

	/**
	 * Get a Webinar Info
	 *
	 * @param  [INT] $id
	 * @param  [STRING] $host_id
	 *
	 * @return array
	 */
	public function getWebinarInfo( $user_id, $id ) {
		$getWebinarInfoArray              = array();
		$getWebinarInfoArray['webinarId'] = $id;

		$response = $this->sendRequest( $user_id, 'webinars/' . $id, $getWebinarInfoArray, 'GET' );

		return apply_filters( 'zoom_wp_after_get_webinar', $response );
	}

	public function getMeetingStatus( $user_id, $id ) {
		$status = array();
		return $this->sendRequest( $user_id, 'meetings/' . $id, $status, 'GET' );

	}

	public function endMeetingStatus( $user_id, $meeting_id ) {
		$postData           = array();
		$postData['action'] = 'end';

		return $this->sendRequest( $user_id, 'meetings/' . $meeting_id . '/status', $postData, 'PUT' );
	}

	public function endWebinarStatus( $user_id, $meeting_id ) {
		$postData           = array();
		$postData['action'] = 'end';

		return $this->sendRequest( $user_id, 'webinars/' . $meeting_id . '/status', $postData, 'PUT' );
	}


} //end class LM_Helper



function lm_helper() {
	return LM_Helper::instance();
}