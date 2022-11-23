<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://unaibamir.com
 * @since      1.0.0
 *
 * @package    Life_Mastery_Group_Management
 * @subpackage Life_Mastery_Group_Management/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Life_Mastery_Group_Management
 * @subpackage Life_Mastery_Group_Management/public
 * @author     Unaib Amir <unaibamiraziz@gmail.com>
 */
class Life_Mastery_Group_Management_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		global $post;

		if( !empty($post->post_content) && !has_shortcode( $post->post_content, 'lm_group_management' ) ) {
			//return;
		}
		if( !empty($post->post_content) && !has_shortcode( $post->post_content, 'lm_course_management' ) ) {
			//return;
		}

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Life_Mastery_Group_Management_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Life_Mastery_Group_Management_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( 'lm-jquery-ui-style' , plugin_dir_url( __FILE__ ) . 'css/jquery-ui.css');
		wp_enqueue_style( 'lm-select2-css' , plugin_dir_url( __FILE__ ) . 'css/select2.min.css', array(), $this->version, 'all');
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/life-mastery-group-management-public.css', array('lm-select2-css'), time(), 'all' );
		wp_enqueue_style( 'fancybox' , 'https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css');

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		global $post;

		if( !empty($post->post_content) && !has_shortcode( $post->post_content, 'lm_group_management' ) ) {
			//return;
		}
		if( !empty($post->post_content) && !has_shortcode( $post->post_content, 'lm_course_management' ) ) {
			//return;
		}

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Life_Mastery_Group_Management_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Life_Mastery_Group_Management_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		$form_id = get_field('student_form', 'option');
		if( !empty( $form_id ) ) {
			gravity_form_enqueue_scripts( $form_id, true );
		}
		

		$dependecy = array( 'jquery', 'jquery-ui-datepicker', 'lm-select2', 'jquery-fancybox' );
		wp_enqueue_script( 'jquery-ui-datepicker' );
		wp_enqueue_script( 'jquery-ui-tabs' );
		wp_enqueue_script( 'jQuery', 'https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js', null, null, true );
		wp_enqueue_script( 'lm-select2', plugin_dir_url( __FILE__ ) . 'js/select2.min.js', array(), $this->version, true );
	    wp_enqueue_script( 'lmgm-js', plugin_dir_url( __FILE__ ) . 'js/life-mastery-group-management-public.js', array(), $this->version, true );
		

	}

	public function init() {

		if ( ! wp_next_scheduled( 'lm_zoom_sync_user_meetings_recordings' ) ) {
			wp_schedule_event( time() + ( MINUTE_IN_SECONDS * 2 ), 'hourly', 'lm_zoom_sync_user_meetings_recordings' );
		}

		add_action( 'lm_zoom_sync_user_meetings_recordings', array( $this, 'get_upload_user_meetings' ) );		

		if ( ! wp_next_scheduled( 'lm_zoom_sync_user_current_meetings_recordings' ) ) {
			wp_schedule_event( time() + ( MINUTE_IN_SECONDS * 2 ), 'hourly', 'lm_zoom_sync_user_current_meetings_recordings' );
		}

		add_action( 'lm_zoom_sync_user_current_meetings_recordings', array( $this, 'get_upload_user_meetings_by_current_user' ) );

		if( isset($_GET["user_meetings"]) ) {

			$this->get_upload_user_meetings();
			$this->get_upload_user_meetings_by_current_user();
		}
	}


	public function lm_group_management_shortcode_callback() {

		if( !is_user_logged_in() ) {
			$data = $this->get_login_form();
			return $data;
		}
		
		$user = wp_get_current_user();

		ob_start();
		
		if( learndash_is_group_leader_user( $user ) ) {
			echo $this->lm_group_leader_management( $user );
		} else {
			echo $this->lm_group_member_management( $user );
		}

		$output = ob_get_contents();
        ob_end_clean();
        return $output;
	}

	public function lm_group_leader_management( $user ) {
		global $post;

		  if( learndash_is_admin_user($user->ID) ) {
                $user_group_ids     = learndash_get_administrators_group_ids( $user->ID );
            } else {
                $user_admin_groups  = learndash_get_administrators_group_ids( $user->ID );
                $user_group_ids     = learndash_get_users_group_ids( $user->ID );
                $user_group_ids     = !empty($user_admin_groups) ? array_unique(array_merge($user_admin_groups,$user_group_ids), SORT_REGULAR) : $user_group_ids;
            }


		// $excluded_groups 	= get_field( 'enable_manage_classes', 'option');
		if( isset($_GET['lm_group_id'], $_GET['lm_action']) 
			&& !empty($_GET['lm_group_id']) 
			&& $_GET['lm_action'] == 'edit' 
			&& in_array($_GET['lm_group_id'], $user_group_ids
		) 
		) {

			$this->getGroupEditPages( $user );

		} else {

			// $user_group_ids     = learndash_get_administrators_group_ids( $user->ID );
			$hidden_groups      = get_user_meta( $user->ID, 'lifemastery_hidden_groups', true);

				// echo'<pre>';print_r($hidden_groups);
	        if( !empty( $hidden_groups ) ) {
	            $user_group_ids = array_diff( $user_group_ids, $hidden_groups );
	        }

	        $user_group_ids = array_unique($user_group_ids);
			arsort( $user_group_ids );

			// if( !empty($user_group_ids) && !empty( $excluded_groups ) ) {
	  //       	$user_group_ids = array_intersect( $user_group_ids, $excluded_groups );
	  //       }

			if( !empty( $user_group_ids ) ) {

				?>
			    <div id="buddypress" class="user-groups-area 1">

			    <?php

			    foreach ($user_group_ids as $group_id) {

			    	$group_manage_link = add_query_arg(array(
			    		'lm_group_id'	=>	$group_id,
			    		'lm_action'		=>	'edit',
			    		'lm_view'		=>	'attendance'
			    	), get_permalink( $post ));

			    	?>
			    	<h5 class="widgettitle group-title lm-group-title">
			    		<?php echo get_the_title($group_id); ?>
			    	</h5>
			    	<div class="user-groups-table" id="group-<?php echo $group_id; ?>">
			    		<span class="alignright lm-group-manage-link">
			    			<a href="<?php echo esc_url( $group_manage_link ) ?>"  id="litab_<?php echo $group_id; ?>" data-id="<?php echo $group_id; ?>"><i class="fa fa-edit"></i> <?php _e('Manage Group'); ?></a>
			    		</span>

			    		<?php echo LM_Helper::get_group_details_ajax($group_id); ?>
			    		
                	</div>
			    	<?php
			    }

			    ?>
		        </div>
		        <?php
		    }

    	}
	}
	public function lm_group_member_management( $user ) {

		global $post;

		// $excluded_groups 	= get_field( 'enable_manage_classes', 'option');
		if( learndash_is_admin_user($user->ID) ) {
                $user_group_ids     = learndash_get_administrators_group_ids( $user->ID );
            } else {
                $user_admin_groups  = learndash_get_administrators_group_ids( $user->ID );
                $user_group_ids     = learndash_get_users_group_ids( $user->ID );
                $user_group_ids     = !empty($user_admin_groups) ? array_unique(array_merge($user_admin_groups,$user_group_ids), SORT_REGULAR) : $user_group_ids;
            }


		if( isset($_GET['lm_group_id'], $_GET['lm_action']) 
			&& !empty($_GET['lm_group_id']) 
			&& $_GET['lm_action'] == 'edit' 
			&& in_array($_GET['lm_group_id'], $user_group_ids)
		) {

			$this->getGroupEditPages( $user );
			return;
		}

		$user_admin_groups  = learndash_get_administrators_group_ids( $user->ID );
		// echo'<pre>';print_r($user_group_ids);
		$user_group_ids     = learndash_get_users_group_ids( $user->ID );
        $user_group_ids     = !empty($user_admin_groups) ? array_merge($user_admin_groups, $user_group_ids) : $user_group_ids;

        $common_group_ids   = !empty($user_group_ids) ? $user_group_ids : array() ;
        $hidden_groups      = get_user_meta( $user->ID, 'lifemastery_hidden_groups', true);
        if( !empty( $hidden_groups ) ) {
            $common_group_ids = array_diff( $common_group_ids, $hidden_groups );
        }

        $common_group_ids = array_unique($common_group_ids);
        $common_group_ids = array_reverse( $common_group_ids );

        // if( !empty($common_group_ids) && !empty( $excluded_groups ) ) {
        // 	$common_group_ids = array_intersect( $common_group_ids, $excluded_groups );
        // }

        if( !empty( $common_group_ids ) ) {

			?>
		    <div id="buddypress" class="user-groups-area ">

		    <?php

		    foreach ($common_group_ids as $group_id) {

		    	$is_leader = false;
		    	$has_group_leader = learndash_get_groups_administrators( $group_id, true );
				if ( ! empty( $has_group_leader ) ) {
					foreach ( $has_group_leader as $leader ) {
						if ( learndash_is_group_leader_of_user( $leader->ID, $user->ID ) ) {
							$is_leader = true;
							break;
						}
					}
				}

		    	$group_manage_link = add_query_arg(array(
		    		'lm_group_id'	=>	$group_id,
		    		'lm_action'		=>	'edit',
		    		'lm_view'		=>	'attendance'
		    	), get_permalink( $post ));
		    	?>
		    	<h5 class="widgettitle group-title lm-group-title">
		    		<?php echo get_the_title($group_id); ?>
		    	</h5>
		    	<div class="user-groups-table" id="group-<?php echo $group_id; ?>">

			    	<?php
			    	if( $is_leader ) {
			    		?>
			    		 <span class="alignright lm-group-manage-link">
			    			<a href="<?php echo esc_url( $group_manage_link ) ?>"><i class="fa fa-edit"></i> <?php _e('Manage Group'); ?></a>
			    		</span>
			    		<?php
			    	}
			    	?>

		    		<?php echo LM_Helper::get_group_details_ajax($group_id); ?>
		    		
            	</div>
		    	<?php
		    }

		    ?>
	        </div>
	        <?php
	    }

	}	

	public function getGroupEditPages( $user ) {

		global $post;

		$tabs = array(
			/*'dates'			=>	array(
				'name' 		=>	__('Lesson Dates'),
				'callback'	=>	'get_group_dates_manage'
			),*/
			'attendance'	=>	array(
				'name' 		=>	__('Attendance'),
				'callback'	=>	'get_group_attendance_manage'
			),
			'schedule'		=>	array(
				'name' 		=>	__('Class Schedule'),
				'callback'	=>	'get_group_schedule_manage'
			),
			'zoom'			=>	array(
				'name' 		=>	__('Zoom'),
				'callback'	=>	'get_group_zoom_manage'
			),
		);

		$current_tab 	=	isset($_GET['lm_view']) ? $_GET['lm_view'] : 'attendance';
		$group_id 		=  	isset($_GET['lm_group_id']) ? $_GET['lm_group_id'] : 0;

		$url 			= 	add_query_arg( array(
			'lm_group_id'	=>	$group_id,
			'lm_action'		=>	'edit'
		), get_permalink( $post ) );

		$back_link 		= get_permalink( $post );

		?>
		<div id="buddypress" class="lm-group-manage-area">
			<h5 class="widgettitle  lm-group-title">
		    		<?php echo get_the_title($group_id); ?>
		    	</h5>
			<div id="item-nav" class="lm-group-manage-nav">
				<div class="item-list-tabs no-ajax">
					<ul class="horizontal-responsive-menu" id="nav-bar-filter" style="padding-left: 0">
						<?php

						foreach ($tabs as $key => $tab) {

							$url = add_query_arg( 'lm_view', $key, $url );

							?>
							<li class="<?php echo $key == $current_tab ? "current selected" : ""; ?>">
								<a href="<?php echo $url; ?>"><?php echo $tab['name']; ?></a>
							</li>
							<?php
						}
						?>
						<li class="right" style="float: right;">
							<a href="<?php echo $back_link; ?>">Back</a>
						</li>
					</ul>
				</div>
			</div>

			<div id="item-body" role="main" class="clearfix lm-group-manage-content" style="padding: 0">
				<?php
				$this->show_notifications();
				call_user_func( array( $this, $tabs[$current_tab]['callback'] ) );
				?>
			</div>
		</div>
		<?php
	}


	public function get_group_schedule_manage() {
		global $post;

		$group_id 		=  	isset($_GET['lm_group_id']) ? $_GET['lm_group_id'] : 0;
		$group_courses 	=	learndash_group_enrolled_courses( $group_id );
		$course_id 		= 	$group_courses[0];
		$group_data 	= 	get_post_meta( $group_id, 'lm_group_data', true );
		$group_start_date 	= 	get_post_meta( $group_id, 'lm_course_start_date', true );

		$ld_course_steps_object = LDLMS_Factory_Post::course_steps( $course_id );
		$lesson_ids = $ld_course_steps_object->get_children_steps( $course_id, 'sfwd-lessons' );
		/*dd($lesson_ids);
		$course_lessons = learndash_get_course_lessons_list( $course );
		dd($course_lessons);*/
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
		
		foreach ($lesson_ids as $lesson_id) {
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
		
		
		reset($sections);
		$key = key($sections);
		unset($sections[$key]);

		?>

		<form action="<?php echo admin_url( 'admin-ajax.php' ) ?>" class="standard-form base" method="POST" autocomplete="off">

			<input type="hidden" name="action" value="lm_group_schedule_callback">
			
			<div style="overflow-x:auto;">
				<table class="profile-fields">
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
								if(!empty($group_data))
								{
							    	$group_data['lesson_dates'][$counter] = '';
								}
								//$group_data['lesson_review_dates'][$counter] = '';
							}
		

							?>
							<tr>
								<td style="width: 40px;"><?php echo $call; ?></td>
								<td style="width: 70px;"><?php echo $call_text; ?></td>
								<td>
									<input type="text" name="lesson_date[<?php echo $counter; ?>]" class="<?php echo $call == 1 ? "": ""; ?>" value="<?php echo $group_data['lesson_dates'][$counter]; ?>" <?php echo $call == 1 ? "readonly": "readonly"; ?> >
								</td>
								<td>
									<input type="text" name="lesson_review_date[<?php echo $counter; ?>]" id="date_show_review_<?php echo $counter; ?>" class="<?php echo $call <= 1 ? "": "lesson_review_date"; ?>" value="<?php echo $group_data['lesson_review_dates'][$counter]; ?>" >
									
								</td>
								<script type="text/javascript">
									$(document).ready(function() {
										var count = "<?php echo $counter; ?>";
									  $( "#date_show_review_"+count ).datepicker({
									       dateFormat: 'mm/dd/yy' 
									  });
									});
								</script>
								<td>
									<select name="lm_lessons[<?php echo $counter; ?>][]" id="lessons-<?php echo $counter; ?>" class="lesson_select" multiple="multiple" >
										<?php
										foreach ($lessons as $lesson) {
											$selected = '';
											if( isset($group_data['lm_lessons'][$counter]) && in_array($lesson['post']->ID, $group_data['lm_lessons'][$counter]) ) {
												$selected = 'selected="selected"';
											}
											?>
											<option value="<?php echo $lesson['post']->ID; ?>" <?php echo $selected; ?>><?php echo $lesson['post']->post_title; ?></option>
											<?php
										}
										?>
									</select>
								</td>
								<td>
									<select name="users[<?php echo $counter; ?>][]" class="student_select" multiple="multiple">
										<?php if( $counter > 1 ): ?>
											<optgroup label="Students">
												<?php
												foreach ($members['students'] as $member) {
													$selected = '';
													if( isset($group_data['users'][$counter]) && in_array($member['ID'], $group_data['users'][$counter]) ) {
														$selected = 'selected="selected"';
													}
													?>
													<option value="<?php echo $member['ID']; ?>" <?php echo $selected; ?>><?php echo $member['name']; ?></option>
													<?php
												}
												?>
											</optgroup>
										<?php endif; ?>

										<optgroup label="Leaders">
											<?php
											foreach ($members['leaders'] as $member) {
												$selected = '';
												if( isset($group_data['users'][$counter]) && in_array($member['ID'], $group_data['users'][$counter]) ) {
													$selected = 'selected="selected"';
												}
												?>
												<option value="<?php echo $member['ID']; ?>" <?php echo $selected; ?>><?php echo $member['name']; ?></option>
												<?php
											}
											?>
										</optgroup>
									</select>
								</td>
							</tr>

							<?php

							if( $call == 2 ) {
								?>
								<tr>
									<td colspan="5">Phase 1</td>
									<td>
										<input type="hidden" name="section[]" value="0">
										<select name="s_users[0][]" class="student_select" multiple="multiple" >
											

											<optgroup label="Leaders">
												<?php
												foreach ($members['leaders'] as $member) {
													$selected = '';
													if( isset($group_data['s_users'][0]) && in_array($member['ID'], $group_data['s_users'][0]) ) {
														$selected = 'selected="selected"';
													}
													?>
													<option value="<?php echo $member['ID']; ?>" <?php echo $selected; ?>><?php echo $member['name']; ?></option>
													<?php
												}
												?>
											</optgroup>
										</select>
									</td>
								</tr>
								<?php
							}

							if( $call == 8 ) {
								?>
								<tr>
									<td colspan="5">Phase 2</td>
									<td>
										<input type="hidden" name="section[]" value="<?php echo 1; ?>">
										<select name="s_users[<?php echo 1; ?>][]" class="student_select" multiple="multiple" >
											

											<optgroup label="Leaders">
												<?php
												foreach ($members['leaders'] as $member) {
													$selected = '';
													if( isset($group_data['s_users'][1]) && in_array($member['ID'], $group_data['s_users'][1]) ) {
														$selected = 'selected="selected"';
													}
													?>
													<option value="<?php echo $member['ID']; ?>" <?php echo $selected; ?>><?php echo $member['name']; ?></option>
													<?php
												}
												?>
											</optgroup>
										</select>
									</td>
								</tr>
								<?php
							}

							if( $call == 14 ) {
								?>
								<tr>
									<td colspan="5">Phase 3</td>
									<td>
										<input type="hidden" name="section[]" value="<?php echo 2; ?>">
										<select name="s_users[<?php echo 2; ?>][]" class="student_select" multiple="multiple" >
											

											<optgroup label="Leaders">
												<?php
												foreach ($members['leaders'] as $member) {
													$selected = '';
													if( isset($group_data['s_users'][2]) && in_array($member['ID'], $group_data['s_users'][2]) ) {
														$selected = 'selected="selected"';
													}
													?>
													<option value="<?php echo $member['ID']; ?>" <?php echo $selected; ?>><?php echo $member['name']; ?></option>
													<?php
												}
												?>
											</optgroup>
										</select>
									</td>
								</tr>
								<?php
							}

							if( $call == 20 ) {
								?>
								<tr>
									<td colspan="5">Phase 4</td>
									<td>
										<input type="hidden" name="section[]" value="<?php echo 3; ?>">
										<select name="s_users[<?php echo 3; ?>][]" class="student_select" multiple="multiple" >
											

											<optgroup label="Leaders">
												<?php
												foreach ($members['leaders'] as $member) {
													$selected = '';
													if( isset($group_data['s_users'][3]) && in_array($member['ID'], $group_data['s_users'][3]) ) {
														$selected = 'selected="selected"';
													}
													?>
													<option value="<?php echo $member['ID']; ?>" <?php echo $selected; ?>><?php echo $member['name']; ?></option>
													<?php
												}
												?>
											</optgroup>
										</select>
									</td>
								</tr>
								<?php
							}

							if( $call == 26 ) {
								?>
								<tr>
									<td colspan="5">Phase 5</td>
									<td>
										<input type="hidden" name="section[]" value="<?php echo 4; ?>">
										<select name="s_users[<?php echo 4; ?>][]" class="student_select" multiple="multiple" >
											

											<optgroup label="Leaders">
												<?php
												foreach ($members['leaders'] as $member) {
													$selected = '';
													if( isset($group_data['s_users'][4]) && in_array($member['ID'], $group_data['s_users'][4]) ) {
														$selected = 'selected="selected"';
													}
													?>
													<option value="<?php echo $member['ID']; ?>" <?php echo $selected; ?>><?php echo $member['name']; ?></option>
													<?php
												}
												?>
											</optgroup>
										</select>
									</td>
								</tr>
								<?php
							}

							if( $call == 32 ) {
								?>
								<tr>
									<td colspan="5">Phase 6</td>
									<td>
										<input type="hidden" name="section[]" value="<?php echo 5; ?>">
										<select name="s_users[<?php echo 5; ?>][]" class="student_select" multiple="multiple" >
											

											<optgroup label="Leaders">
												<?php
												foreach ($members['leaders'] as $member) {
													$selected = '';
													if( isset($group_data['s_users'][5]) && in_array($member['ID'], $group_data['s_users'][5]) ) {
														$selected = 'selected="selected"';
													}
													?>
													<option value="<?php echo $member['ID']; ?>" <?php echo $selected; ?>><?php echo $member['name']; ?></option>
													<?php
												}
												?>
											</optgroup>
										</select>
									</td>
								</tr>
								<?php
							}

							if( $call == 38 ) {
								?>
								<tr>
									<td colspan="5">Phase 7</td>
									<td>
										<input type="hidden" name="section[]" value="<?php echo 6; ?>">
										<select name="s_users[<?php echo 6; ?>][]" class="student_select" multiple="multiple" >
											

											<optgroup label="Leaders">
												<?php
												foreach ($members['leaders'] as $member) {
													$selected = '';
													if( isset($group_data['s_users'][6]) && in_array($member['ID'], $group_data['s_users'][6]) ) {
														$selected = 'selected="selected"';
													}
													?>
													<option value="<?php echo $member['ID']; ?>" <?php echo $selected; ?>><?php echo $member['name']; ?></option>
													<?php
												}
												?>
											</optgroup>
										</select>
									</td>
								</tr>
								<?php
							}

							if( $call == 44 ) {
								?>
								<tr>
									<td colspan="5">Phase 8</td>
									<td>
										<input type="hidden" name="section[]" value="<?php echo 7; ?>">
										<select name="s_users[<?php echo 7; ?>][]" class="student_select" multiple="multiple" >
											

											<optgroup label="Leaders">
												<?php
												foreach ($members['leaders'] as $member) {
													$selected = '';
													if( isset($group_data['s_users'][7]) && in_array($member['ID'], $group_data['s_users'][7]) ) {
														$selected = 'selected="selected"';
													}
													?>
													<option value="<?php echo $member['ID']; ?>" <?php echo $selected; ?>><?php echo $member['name']; ?></option>
													<?php
												}
												?>
											</optgroup>
										</select>
									</td>
								</tr>
								<?php
							}

							if( $call == 50 ) {
								?>
								<tr>
									<td colspan="5">Phase 9</td>
									<td>
										<input type="hidden" name="section[]" value="<?php echo 8; ?>">
										<select name="s_users[<?php echo 8; ?>][]" class="student_select" multiple="multiple" >
											

											<optgroup label="Leaders">
												<?php
												foreach ($members['leaders'] as $member) {
													$selected = '';
													if( isset($group_data['s_users'][8]) && in_array($member['ID'], $group_data['s_users'][8]) ) {
														$selected = 'selected="selected"';
													}
													?>
													<option value="<?php echo $member['ID']; ?>" <?php echo $selected; ?>><?php echo $member['name']; ?></option>
													<?php
												}
												?>
											</optgroup>
										</select>
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

				<div class="submit">
					<input type="submit" name="lm_group_schedule_save" id="lm_group_schedule_save" value="Save Changes ">
				</div>
			</div>
			<input type="hidden" name="lm_group_id" value="<?php echo $group_id ?>">
			<?php wp_nonce_field( 'lm_group_schedule_save', 'lm_group_schedule_save_wpnonce' ); ?>

		</form>
		<?php
	}


	public function lm_group_schedule_save_callback() {
		global $wpdb;

		if ( ! isset( $_POST['lm_group_schedule_save_wpnonce'] )  || ! wp_verify_nonce( $_POST['lm_group_schedule_save_wpnonce'], 'lm_group_schedule_save' ) ) {
			wp_die( __('Oops, something went wrong with your submission. Please try again.'), __('something went wrong!') );
		}

		$table = _get_meta_table( 'user' );

		$lesson_drip_dates = $group_attendance_dates = $lesson_review_dates = array();

		if( isset($_POST['lesson_review_date']) && !empty($_POST['lesson_review_date']) ) {
			foreach ($_POST['lesson_review_date'] as $lesson_date ) {
				if( empty($lesson_date) ) continue;
				$date 		= date( 'Y-m-d H:i:s', strtotime($lesson_date));
				$group_attendance_dates[] = $date;
			}
		}

		$lessons = array();

		$gmt_offset  	= get_option( 'gmt_offset' );
		if ( empty( $gmt_offset ) ) {
			$gmt_offset = 0;
		}
		$offset      	= ( $gmt_offset * ( 60 * 60 ) ) * - 1;

        $data 			=	array();
        $format 		= 'Y-m-d H:01:s';

		$group_data = array();
		$group_data['group_id'] 		= $_POST['lm_group_id'];
		$group_data['lesson_dates'] 	= $_POST['lesson_date'];
		$group_data['lesson_review_dates'] = $_POST['lesson_review_date'];
		$group_data['lm_lessons'] 		= $_POST['lm_lessons'];
		$group_data['users'] 			= isset($_POST['users']) ? $_POST['users'] : array();
		$group_data['s_users'] 			= isset($_POST['s_users']) ? $_POST['s_users'] : array();
		$group_data['users_data'] 		= array();

		/*foreach ($group_data['lesson_dates'] as $key => $lesson_date) {
			$meta_key = 'lm-lesson-date-' . $_POST['lm_group_id'] . '-'  ;
			dd( $meta_key, false );
		}*/
		//dd($group_data['lesson_dates'], false);
		foreach ( $group_data['lesson_review_dates'] as $discuss_date) {
			//$date 		= date( 'Y-m-d H:i:s', strtotime($lesson_date));
			$date 	 	= date( 'm/d/Y', strtotime( "-3 week monday", strtotime( $discuss_date ) ) );
			
			$dates[] 	= $date;
			continue;			
			
		}

		$dates = array_values($dates);
		$group_data['lesson_dates'] = $dates;
		//dd($group_data['lesson_dates']);
		$query = "DELETE FROM $table WHERE meta_key LIKE 'lm_lesson_group_".$group_data['group_id']."%'";
		$count = $wpdb->query( $query );

		if( !empty($group_data['users']) ){
			$user_data = array();
			foreach ($group_data['users'] as $key => $users) {

				

				$date 	 	= date( $format, strtotime( $group_data['lesson_review_dates'][$key] ) );
	        	$drip_date 	= strtotime($date);
	        	$drip_date 	= (int) $drip_date + $offset;

	        	$week_num 	= $key - 1;
	        	
	        	if( $week_num < 1 ) {
	        		//continue;
	        	}

				$data = array(
					'date'	=>	$drip_date,
					'week'	=>	$week_num
				);

				if( !empty($users) ) {
					foreach ( $users as $user_num => $user_id ) {
						if( !isset( $user_data[$user_id] ) ) {
							$user_data[$user_id] = array();
						}
						array_push( $user_data[$user_id], $data );
					}
				}				
			}

			if( !empty( $user_data ) ) {
				foreach ( $user_data as $user_id => $data ) {
					$user_meta_key = 'lm_lesson_group_' . $group_data['group_id'].'_info';
					update_user_meta( $user_id, $user_meta_key, $data, '' );
				}
			}

		} else {
			
		}
		
		/*dd( $group_data );

		dd('finish user settings first');*/

		LM_Helper::drip_public_group_lessons( $_POST['lm_group_id'], $group_data );

		//dd($group_data);

		update_post_meta( $_POST['lm_group_id'], 'lm_group_attendance_dates', $group_attendance_dates, '' );
		update_post_meta( $_POST['lm_group_id'], 'lm_group_data', $group_data, '' );

		$redirect_url = $_POST['_wp_http_referer'];
		//$redirect_url = add_query_arg( '' );
		wp_safe_redirect( $redirect_url );
		exit;

	}


	public function get_group_zoom_manage() {
		global $post;
		$group_id 		=  	isset($_GET['lm_group_id']) ? $_GET['lm_group_id'] : 0;

		$group_zoom 	= 	get_post_meta( $group_id, 'lm_group_zoom_info', true );
		$meeting_id 	= 	get_post_meta( $group_id, 'lm_meeting_id', true );
		$settings = array(
			'media_buttons'	=>	false,
			'quicktags'		=>	false,
			'teeny'			=>	true

		);


		
		$recordings = lm_helper()->get_user_meeting_recordings( get_current_user_id(), $meeting_id );
		//dd($recordings, false);

		$groups = learndash_get_administrators_group_ids( get_current_user_id() );


		?>
		<form action="<?php echo admin_url( 'admin-ajax.php' ) ?>" class="standard-form base" method="POST" autocomplete="off">
			<input type="hidden" name="action" value="lm_group_zoom_callback">

			<!-- <textarea name="lm_group_zoom_info" id="message_content" cols="30" rows="30"><?php echo $group_zoom; ?></textarea> -->
			<?php wp_editor( $group_zoom, 'lm_group_zoom_info', $settings ) ?>

			<hr>

			<label for="lm_zoom_meeting_id"><?php _e( 'Zoom Meeting ID', 'buddypress' ); ?></label>
			<input type="text" name="lm_zoom_meeting_id" id="lm_zoom_meeting_id" value="<?php echo $meeting_id; ?>" class="settings-input" />

			<hr>

			<div class="submit">
				<input type="submit" name="lm_group_attendance_save" id="lm_group_attendance_save" value="Save Changes ">
			</div>

			<input type="hidden" name="lm_group_id" value="<?php echo $group_id ?>">
			<?php wp_nonce_field( 'lm_group_zoom_save', 'lm_group_zoom_save_wpnonce' ); ?>
		</form>
		<?php

	}

	public function lm_group_zoom_save_callback()
	{
		
		if ( ! isset( $_POST['lm_group_zoom_save_wpnonce'] )  || ! wp_verify_nonce( $_POST['lm_group_zoom_save_wpnonce'], 'lm_group_zoom_save' ) ) {
			wp_die( __('Oops, something went wrong with your submission. Please try again.'), __('something went wrong!') );
		}
		
		update_post_meta( $_POST['lm_group_id'], 'lm_group_zoom_info', $_POST['lm_group_zoom_info'], '' );

		update_post_meta( $_POST['lm_group_id'], 'lm_meeting_id', sanitize_text_field( preg_replace('/\s+/', '', $_POST['lm_zoom_meeting_id'] ) ), '' );

		$redirect_url = $_POST['_wp_http_referer'];
		//$redirect_url = add_query_arg( array( 'lm-message' => 'saved', 'lm-status' => 'success' ), $redirect_url );
		wp_safe_redirect( $redirect_url );
		exit;
	}

	public function get_group_attendance_manage_old()
	{
		global $post;
		$group_id 			=  	isset($_GET['lm_group_id']) ? $_GET['lm_group_id'] : 0;
		$attendance_dates 	= 	get_post_meta( $group_id, 'lm_group_attendance_dates', true );
		$students 			= 	learndash_get_groups_users( $group_id );
		$current_date 		= 	new DateTime();
		
		?>

		<form action="<?php echo admin_url( 'admin-ajax.php' ) ?>" class="standard-form base" method="POST" autocomplete="off">

			<input type="hidden" name="action" value="lm_group_attendance_callback">
			
			<div style="overflow-x:auto;">
				<table class="profile-fields">
					<thead>
						<tr>
							<th><?php echo __('Name');?></th>
							<?php foreach ($attendance_dates as $date) {
								?>
								<th><?php echo date( 'm/d', strtotime($date) );?></th>
								<?php
							} ?>
						</tr>
					</thead>

					<tbody>

						<?php foreach ($students as $user) {
							?>
							<tr>
								<td><?php echo $user->display_name; ?></td>
								<?php foreach ($attendance_dates as $date) {
									$date    = new DateTime($date);
									?>
									<td>
									<?php
										if( $current_date < $date ) {
											echo '';
										} else {
											?>
											<div class="checkbox" style="width: 80px;">
												<label >
													<input type="checkbox"  name="sdas">
													Present
												</label>
												<label >
													<input type="checkbox"  name="sdas">
													Absent
												</label>
											</div>
											
											<?php
										}
										?>
									</td>
									<?php
								} ?>
							</tr>
							<?php
						} ?>
					</tbody>
				</table>
			</div>

		</form>

		<?php
	}


	public function get_group_attendance_manage(){
		global $post;
		$group_id 			=  	isset($_GET['lm_group_id']) ? $_GET['lm_group_id'] : 0;
		$attendance_dates 	= 	get_post_meta( $group_id, 'lm_group_attendance_dates', true );
		$students 			= learndash_get_groups_users( $group_id );
		$current_date 		= new DateTime();

		?>

		<form action="<?php echo admin_url( 'admin-ajax.php' ) ?>" id="signup_form" class="standard-form base" method="POST" autocomplete="off">

			<input type="hidden" name="action" value="lm_group_attendance_callback">

			<table class="profile-fields attendance-form">
				<tr>
					<th>Users</th>
					<td>
						<select name="users[]" class="lm-user-select" multiple="multiple" style="width: 550px;" required="required">
							<?php
							foreach ($students as $user) {
								echo '<option value="'.$user->ID.'">' . $user->display_name . '</option>';
							}
							?>
						</select>
					</td>
				</tr>
				<tr>
					<th>Date</th>
					<td>
						<select name="date" class="lm-user-select" style="width: 550px;" required="required">
							<option value="">Please Select</option>
							<?php
							foreach ($attendance_dates as $date) {
								$date = new DateTime( $date );
								echo '<option value="'.$date->format('Y-m-d').'">' . $date->format('Y-m-d') . '</option>';
							}
							?>
						</select>
					</td>
				</tr>
				<tr>
					<th>Status</th>
					<td>
						<div class="input-options radio-button-options">
							<label class="option-label"><input type="radio" name="attendance" value="1" required="required"><strong>Missed</strong></label>
							<label class="option-label"><input type="radio" name="attendance" value="2" required="required"><strong>Present</strong></label>
							<label class="option-label"><input type="radio" name="attendance" value="3" required="required"><strong>Missed but completed the work</strong></label>
						</div>

					</td>
				</tr>

				<tr>
					<th>Comment</th>
					<td>
						<textarea name="comment" placeholder="<?php echo __('Your Comment'); ?>"></textarea>
					</td>
				</tr>
			</table>

			<div class="submit" style="float: left;">
				<input type="submit" name="lm_group_attendance_save" id="lm_group_attendance_save" value="Save Attendance ">
			</div>

			<input type="hidden" name="lm_group_id" value="<?php echo $group_id ?>">
			<?php wp_nonce_field( 'lm_group_attendance_save', 'lm_group_attendance_save_wpnonce' ); ?>

		</form>

		<hr style="float: left; width: 100%;">

		<h4>Attendance Details</h4>
		<?php echo LM_Helper::get_group_attendance_view( $group_id, $attendance_dates, $students ); ?>

		<?php
		
	}

	public function lm_group_attendance_save_callback()
	{
		global $wpdb;

		if ( ! isset( $_POST['lm_group_attendance_save_wpnonce'] )  || ! wp_verify_nonce( $_POST['lm_group_attendance_save_wpnonce'], 'lm_group_attendance_save' ) ) {
			wp_die( __('Oops, something went wrong with your submission. Please try again.'), __('something went wrong!') );
		}

		$table 				= $wpdb->prefix . 'lm_attendance_logs';
		$group_id 			= $_POST['lm_group_id'];
		$date 				= $_POST['date'];
		$attendance_type 	= $_POST['attendance'];
		$current_user_id 	= get_current_user_id();
		$gmt_offset  		= get_option( 'gmt_offset' );
		
		if ( empty( $gmt_offset ) ) {
			$gmt_offset = 0;
		}

		foreach ($_POST['users'] as $user_id) {

			// lets check if there is any already record for the user and the date posted.
			// if yes, we either need to update the existing record with updated info or skip it.
			$query = $wpdb->prepare( "SELECT * FROM $table WHERE user_id = %d AND group_id = %d AND `date` = %s", $user_id, $group_id, $date );
			$has_row = $wpdb->get_row( $query );

			if( null !== $has_row ) {
				$wpdb->update( 
					$table,
					array( 
						'attendance_type'	=>	$attendance_type,
						'comment'			=>	$_POST['comment'],
					),
					array(
						'user_id' 			=> 	$user_id,
						'group_id' 			=> 	$group_id,
						'date'				=>	$date,
					), 
				);
			} else {
				$wpdb->insert( 
					$table,
					array( 
						'user_id' 			=> 	$user_id,
						'group_id' 			=> 	$group_id,
						'attendance_type'	=>	$attendance_type,
						'date'				=>	$date,
						'log_by_user_id'	=>	$current_user_id,
						'comment'			=>	$_POST['comment'],
						'created_at'		=>	current_time( 'mysql', $gmt_offset )
					),
				);
			}

		}

		$redirect_url = $_POST['_wp_http_referer'];
		//$redirect_url = add_query_arg( '' );
		wp_safe_redirect( $redirect_url );
		exit;

	}


	public function lm_group_details_ajax_callback()
	{
		$group_id 	= $_GET['group_id'];
		$data_load 	= $_GET['data'];

		switch( $data_load ) {
			case 'roster':
				echo LM_Helper::get_group_roster( $group_id );
				break;

			case 'attendance':
				echo LM_Helper::get_group_attendance_view( $group_id );
				break;

			case 'schedule':
				echo LM_Helper::get_group_schedule_view( $group_id );
				break;

			case 'zoom':
				echo LM_Helper::get_group_zoom_info( $group_id );
				break;

			case 'instructions':
				echo LM_Helper::get_group_lead_instructions( $group_id );
				break;

			case 'form':
				echo LM_Helper::get_group_form( $group_id );
				break;

			case 'leader_instructions_1':
				echo LM_Helper::get_group_lead_instructions_one( $group_id );
				break;

			case 'leader_instructions_2':
				echo LM_Helper::get_group_lead_instructions_two( $group_id );
				break;

			case 'facilitator_instructions':
				echo LM_Helper::get_group_lead_facilitator_instructions( $group_id );
				break;
		}

		wp_die();
	}

	public function get_login_form()
	{
		ob_start();

		?>
<h3 class="login-text">Please login to get started.</h3>
<div id="login-page-form">
[i4w_login_form label_username='Email' redirect='#use_last_page#']
[i4w_is_logged_in]
  Hi [i4w_db_FirstName], You already logged in.
[/i4w_is_logged_in]
<a href="<?php echo home_url(''); ?>/wp-login.php?action=lostpassword">Lost Your Password?</a>
</div>		
		<?php

		$output = ob_get_contents();
        ob_end_clean();
        return do_shortcode( $output );
	}


	public function lm_infusionsoft_listner_callback() {
		global $lm_logs;
	
		if( !function_exists('lm_debug_log') ) {
			return;
		}

		if( !isset($_GET['lm-listner']) ) {
			return;
		}


		$arr_rh = json_decode( file_get_contents( 'php://input' ), TRUE );

		if ( ! isset( $arr_rh['event_key'] ) OR ! $arr_rh['event_key'] OR ! isset( $arr_rh['object_type'] ) OR ! $arr_rh['object_type'] OR ! isset( $arr_rh['object_keys'] ) OR ! $arr_rh['object_keys'] OR ! isset( $arr_rh['api_url'] ) ) :
			exit;
		endif;

		lm_debug_log( sprintf('LM Note: %s', maybe_serialize( $arr_rh ) )  );

		if( $arr_rh['event_key'] == 'contactGroup.applied' ) {

			foreach ($arr_rh['object_keys'] as $object) {
				$tag_id = $object['tag_id'];
				$contact_ids = array();
				
				foreach ($object['contact_details'] as $contact ) {
					if( !in_array($contact['id'], $contact_ids) ) {
						$contact_ids[] = $contact['id'];
					}
				}

				if( !empty( $tag_id ) && !empty($contact_ids) ) {
					LM_Helper::find_tag_group_assign_user( $tag_id, $contact_ids );
				} else {
					lm_debug_log( sprintf('LM Note: empty data %s', '' )  );
				}
				
			}
		}

	}

	public function show_notifications()
	{
		if( !isset($_GET['lm-message']) || isset($_GET['lm-message']) && empty($_GET['lm-message']) ) {
			return;
		}

		$type 		= $_GET['lm-message'];
		$message 	=	'';

		switch( $type ) {
			case 'saved': 
				$message = __('Successfully Updated!');

			default:
				break;
		}

		?>
		<div class="lm-message success"><?php echo wpautop( $message ); ?></div>
		<?php

	}


	public function load_student_form_details() {
		
		$group_id 	= $_POST['group_id'];
		$user_id 	= $_POST['student_id'];

		$form_id 	= get_field('student_form', 'option');
		$form 		= GFFormsModel::get_form_meta( absint( $form_id ) );
		$form    	= apply_filters( 'gform_admin_pre_render', $form );
		$form    	= apply_filters( 'gform_admin_pre_render_' . $form_id, $form );

		$search_criteria = array(
			'status'        => 'active',
			'field_filters' => array(
				array(
					'key'   => 'created_by',
					'value' => $user_id
				),
				array(
					'key'   => '20',
					'value' => $group_id,
					'operator'	=>	'is'
				)
			)
		);

		$entries    = GFAPI::get_entries( $form_id, $search_criteria );
		
		ob_start();

		if( empty( $entries ) ) {
			$output = 'The student has not submitted the information yet.';
			echo wpautop( $output );
			wp_die();
		}
		
		$lead 	 	= $entry = $entries[0];
		?>
		
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
	                                    <th class="entry-view-field-name"><strong>' . esc_html( GFCommon::get_label( $field ) ) . '</strong></th>
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
        echo $output;

        wp_die();
	}


	public function load_week_facilitator_instructions() {

		
		$group_id 	=  $_POST['group_id'];
		$week_id 	= $_POST['week_id'];


		$defult_instructions 	= get_field( "default_facilitator_instructions", "option" );
		$content 				= '';

		$group_data = get_post_meta( $group_id, 'lm_group_data', true );

		if( strpos( $week_id, 'week_' ) !== false ) {
			$week = explode('week_', $week_id);
			$week_num = $week[1];
			if( $week_num || $week_num == 0 ) {
				$week_content 	= get_field( "instructions_week_" . $week_num, "option" );
			}
		} else if( $week_id == 'teck_check' ) {
			$week_content 	= get_field( "tech_check_instructions", "option" );
		} else {
			$week_content 	= '';
		}

		if( empty($week_content) ) {
			$week_content = $defult_instructions;
		}
		$content 		.= $week_content;

		if( strpos($content, 'resp-container') === false ) {
			$content 			= str_replace(['<iframe', '</iframe>'], ['<div class="resp-container"><iframe', '</iframe></div>'], $content);
		}

		
		$output 			= wpautop( $content );

		echo $output;

		wp_die();
	}

	public function lm_group_meeting_shortcode_callback() {
		
		if( !is_user_logged_in() ) {
			return;
		}

		if( !isset($_GET['lm_group_id'], $_GET['lm_meeting_id']) ) {
			return;
		}

		if( empty($_GET['lm_group_id']) || empty($_GET['lm_meeting_id']) ) {
			return;
		}

		$user 				= wp_get_current_user();
		$group_id 			= $_GET['lm_group_id'];
		$zoom_meeting_id 	= $_GET['lm_meeting_id'];

		if( !is_admin() || !learndash_is_group_leader_user( $user ) || !learndash_is_user_in_group( $user->ID, $group_id ) ) {
			//return;
		}

		$meeting_data 		= !empty($zoom_meeting_id) ? lm_helper()->get_user_meeting_info( $zoom_meeting_id ) : false;

		if( !empty($meeting_data->code) ) {
			return;
		}

		ob_start();
		
		echo do_shortcode( '[lm_zoom_api_link meeting_id="'.$zoom_meeting_id.'" hide_join_before_time="1"]' );

		echo do_shortcode( '[lm_meeting_recordings meeting_id="'.$zoom_meeting_id.'"]' );

		$output = ob_get_contents();
        ob_end_clean();
        return $output;
		
	}

	public function lm_meeting_recordings_shortcode_callback( $atts, $content ) {
		$args = shortcode_atts(
			array(
				'meeting_id' => '',
				'is_webinar' => '',
				'play_url'   => '',
				'start_time' => '',
				'end_time'   => '',
			),
			$atts
		);

		$args['zoom_not_show_recordings'] = get_option( 'zoom_hide_recordings' );
		$args['zoom_window_size']         = get_option( 'zoom_window_size' );
		$args['meeting_id']               = (float) str_replace( '-', '', str_replace( ' ', '', $args['meeting_id'] ) );
		$args['is_only_recording']        = 1;
		$args['is_meeting_view']          = 0;

		if ( $args['is_webinar'] ) {
			$args['option'] = 'zoom_api_webinar_options';
		} else {
			$args['option'] = 'zoom_api_meeting_options';
		}

		if ( $args['meeting_id'] ) {
			$args['zoom_map_array'] = get_post_meta( $args['meeting_id'], $args['option'], true );
			if ( ! $args['zoom_map_array'] ) {
				$args['zoom_map_array'] = array();
			}
		}

		$past_recordings 	= $args['zoom_map_array']['past_recordings'];
		$meeging_id 		= $args['meeting_id'];
		$meeting_title 		= $args['zoom_map_array']['topic'];
		$meeting_title 		= get_the_title( $_GET["lm_group_id"] );
		 // $meeting_title 		= empty( $meeting_title ) ? get_the_title( $_GET["lm_group_id"] ) : $meeting_title;

		//add_thickbox();

		ob_start();

        $array_date = array();
		$group_data = 	get_post_meta( $_GET["lm_group_id"], 'lm_group_data', true );
		foreach ($group_data['lesson_review_dates'] as $key => $value) {
	         $array_date[] = date( 'Y-m-d', strtotime($value));
		}
		usort($past_recordings, function($a, $b) {
		  return new DateTime($a['recording_end']) <=> new DateTime($b['recording_end']);
		});

		// echo "<pre>";print_r($array_date);

		if( !empty( $past_recordings ) ) {
			?>
			<ul>
			<?php
			foreach (array_reverse($past_recordings) as $past_recording) {
				$recording_date 	= $past_recording['recording_end'];
				$recording_date 	= explode('T', $recording_date);
				$recording_title 	= $meeting_title . ' - ' . $recording_date[0];
				if( in_array( $recording_date[0] ,$array_date ) ){
				?>
				<li>
					<div id="lm-zoom-recording-<?php echo $past_recording['vimeo_id']; ?>" style="display:none;" class="lm-zoom-recording">
						<div class="recording-container">
							<?php echo do_shortcode( '[dgv_vimeo_video id="' . esc_attr( $past_recording['vimeo_id'] ) . '"]' ); ?>
						</div>
						
					</div>
					<!-- <a href="#TB_inline?&width=800&height=450&inlineId=lm-zoom-recording-<?php echo $past_recording['vimeo_id']; ?>" class="thickbox">
						<?php echo $recording_title; ?>
					</a> <br> -->

					<a href="#lm-zoom-recording-<?php echo $past_recording['vimeo_id']; ?>"  class="various">
						<?php echo $recording_title;
						 ?>
					</a>

				</li>
				<?php
			   }
			   else if(in_array( date('Y-m-d', strtotime('-1 day', strtotime($recording_date[0]))) ,$array_date ))
			   {
			   	?>
			   	<li>
					<div id="lm-zoom-recording-<?php echo $past_recording['vimeo_id']; ?>" style="display:none;" class="lm-zoom-recording">
						<div class="recording-container">
							<?php echo do_shortcode( '[dgv_vimeo_video id="' . esc_attr( $past_recording['vimeo_id'] ) . '"]' ); ?>
						</div>
						
					</div>

					<a href="#lm-zoom-recording-<?php echo $past_recording['vimeo_id']; ?>"  class="various">
						<?php echo $recording_title;
						 ?>
					</a>

				</li>
			   	<?php

			   }
			   else
			   {

			   }
			}
			?>
			</ul>
			<?php
		}


		$output = ob_get_contents();
        ob_end_clean();
        return $output;
		
	}

	public function add_member_settings_field() {

		if( bp_current_action() != 'edit' ) {
            return;
        }

		$user_id 				= bp_displayed_user_id();
		$lm_zoom_api_key 		= bp_get_user_meta( $user_id, 'lm_zoom_api_key', true );
		$lm_zoom_api_secret_key = bp_get_user_meta( $user_id, 'lm_zoom_api_secret_key', true );
		
		?>

		<div class="editfield required-field visibility-public field_type_textbox">
           <fieldset>
            	<legend>
            		<label for="lm_zoom_api_key"><?php _e( 'Zoom API Key', 'buddypress' ); ?></label>
            	</legend>
            	<input type="text" name="lm_zoom_api_key" id="lm_zoom_api_key" value="<?php echo $lm_zoom_api_key; ?>" class="settings-input" />
            </fieldset>

            <fieldset>
            	<legend>
            		<label for="lm_zoom_api_secret_key"><?php _e( 'Zoom API Secret Key', 'buddypress' ); ?></label>
            	</legend>
            	<input type="text" name="lm_zoom_api_secret_key" id="lm_zoom_api_secret_key" value="<?php echo $lm_zoom_api_secret_key; ?>" class="settings-input" />
            </fieldset>
        </div>	
		 <?php //echo $this->get_upload_user_meetings_by_current_user(); ?> 
		<?php
	}

	public function save_member_settings_field() {
		
		$user_id = bp_displayed_user_id();

		bp_update_user_meta( $user_id, 'lm_zoom_api_key', sanitize_text_field( preg_replace('/\s+/', '', $_POST["lm_zoom_api_key"] ) ) );
		bp_update_user_meta( $user_id, 'lm_zoom_api_secret_key', sanitize_text_field( preg_replace('/\s+/', '', $_POST["lm_zoom_api_secret_key"] ) ) );

		$feedback[]    = __( 'Your settings have been saved.', 'buddypress' );
		$feedback_type = 'success';

		bp_core_add_message( implode( "\n", $feedback ), $feedback_type );
	}

	public function get_upload_user_meetings() {
        video_conferencing_zoom_log_error( "cron worked" );
		$users = get_users( array(
			'meta_query'	=> array(
				'relation'		=>	'AND',
				array(
					'key'		=>	'lm_zoom_api_key',
				),
				array(
					'key'		=>	'lm_zoom_api_secret_key',
				)
			)
		) );

		$zoom_map_array = array();

		if( !empty($users) ) {

			foreach ($users as $user) {

				$user_id 				= $user->ID;
				$user_zoom_api_key 		= get_user_meta( $user_id, 'lm_zoom_api_key', true );
				$user_zoom_api_secret_key = get_user_meta( $user_id, 'lm_zoom_api_secret_key', true );
				
				$user_groups 			= learndash_get_administrators_group_ids( get_current_user_id() );

				// bail if we do not have user LD groups, zoom api key and zoom api secret key
				if( empty( $user_groups ) && empty( $user_zoom_api_key ) && empty( $user_zoom_api_secret_key ) ) {
					continue;
				}

				foreach( $user_groups as $group_key => $group_id ) {
					
					$meeting_id 		= 	get_post_meta( $group_id, 'lm_meeting_id', true );
					
					// bail if we do not have zoom meeting id for the group
					if( empty( $meeting_id ) ) {
						continue;
					}

					// lets get user's zoom meeting and recordings
					$meeting_recordings = lm_helper()->get_user_meeting_recordings( $user_id, $meeting_id );
					
					if( empty($meeting_recordings) ) {
						continue;
					}

						 // echo "<pre>";print_r($meeting_recordings);
					// loop through available meeting recordinds and process futher
					foreach( $meeting_recordings as $meeting_key => $meeting_recording ) {

						if( !isset( $meeting_recording->recording_files ) || empty( $meeting_recording->recording_files ) ) {
							continue;
						}

						$recordings = array();

						if ( in_array( $meeting_recording->type, array( 5, 6, 9 ) ) ) {
							$type = 'zoom_api_webinar_options';
						} else {
							$type = 'zoom_api_meeting_options';
						}

						$meeting_data = get_post_meta( $meeting_recording->id, $type, true ) ?: array();
						// No record found for meeting or webinar in WP so skip it
						if ( ! $zoom_map_array[ $meeting_recording->id ] ) {
							//continue;
						}

						if( !isset($zoom_map_array[ $meeting_recording->id ]['past_recordings_all']) ) {
							//$zoom_map_array[ $meeting_recording->id ]['past_recordings_all'] = array();
						}

						$zoom_map_array[ $meeting_recording->id ]['type'] = $type;
						// $zoom_map_array[ $meeting_recording->id ]['topic'] = $meeting_recording->topic;
						$zoom_map_array[ $meeting_recording->id ]['topic'] = get_the_title( $group_id );
						$zoom_map_array[ $meeting_recording->id ]['host_id'] = $meeting_recording->host_id;
						
						$meeting_recording->recording_files = array_values($meeting_recording->recording_files);
						
						$this_recording = video_conferencing_recording_get_params_by_sequence( (array) $meeting_recording->recording_files, array() );
						

						// if we have only recording_url
						if ( isset( $this_recording['recording_url'] ) && $this_recording['recording_url'] ) {

							// Associate Vimeo ID if video exists
							$recording_key = array_search( $this_recording['recording_id'], array_column( $meeting_data['past_recordings'], 'recording_id' ) );

							if ( false !== $recording_key ) {
								
								$vimeo_record = $meeting_data['past_recordings'][ $recording_key ];
								
								if ( isset( $vimeo_record['vimeo_id'] ) ) {
									$exist = video_conferencing_zoom_check_vimeo_exist( $vimeo_record['vimeo_id'] );

									// Video deleted from Vimeo so remove from WP as well
									if ( ! $exist ) {
										$this_recording['vimeo_removed'] = 1;
									} else {
										$this_recording['vimeo_id'] = $vimeo_record['vimeo_id'];
									}
								}
							}

							//dd($this_recording, false);

							$recording_date = $this_recording['recording_end'];
							$recording_date = explode('T', $recording_date);

							$this_recording = lm_helper()->upload_user_recording_to_vimeo( $this_recording, get_the_title( $group_id ) . ' - ' . $recording_date[0], false, true, $user_id);

							$recording = $this_recording;
							//dd($recording, false);

							$zoom_map_array[ $meeting_recording->id ]['past_recordings_all'][] = $recording;
							//array_push( $zoom_map_array[ $meeting_recording->id ]['past_recordings_all'], $recordings);
							
							
						}

					}
					// Update DB will all past recordings for a meeting
					if ( $zoom_map_array ) {
						foreach ( $zoom_map_array as $meeting_id => $options ) {
							$type = $options['type'];
							if ( isset( $options['past_recordings_all'] ) && $options['past_recordings_all'] ) {
								$options['past_recordings'] = $options['past_recordings_all'];
							}
							if ( isset( $options['type'] ) ) {
								unset( $options['type'] );
							}
							if ( isset( $options['past_recordings_all'] ) ) {
								unset( $options['past_recordings_all'] );
							}
							update_post_meta( $meeting_id, $type, $options );
						}
					}

				}  //end user groups foreach


			} //end users foreach
		}  //end users ifelse

		exit;
	}


	public function get_upload_user_meetings_by_current_user() {
        $user_id = get_current_user_id(); 
		$zoom_map_array = array();

		if( !empty($user_id) ) {

				$user_zoom_api_key 		= get_user_meta( $user_id, 'lm_zoom_api_key', true );
				$user_zoom_api_secret_key = get_user_meta( $user_id, 'lm_zoom_api_secret_key', true );
				
				$user_groups 			= learndash_get_administrators_group_ids( $user_id );
				foreach( $user_groups as $group_key => $group_id ) {
					
					$meeting_id 		= 	get_post_meta( $group_id, 'lm_meeting_id', true );
					
					// bail if we do not have zoom meeting id for the group
					if( empty( $meeting_id ) ) {
						continue;
					}

					// lets get user's zoom meeting and recordings
					$meeting_recordings = lm_helper()->get_user_meeting_recordings( $user_id, $meeting_id );
					
					if( empty($meeting_recordings) ) {
						continue;
					}

					 
					// loop through available meeting recordinds and process futher
					foreach( $meeting_recordings as $meeting_key => $meeting_recording ) {

						if( !isset( $meeting_recording->recording_files ) || empty( $meeting_recording->recording_files ) ) {
							continue;
						}

						$recordings = array();

						if ( in_array( $meeting_recording->type, array( 5, 6, 9 ) ) ) {
							$type = 'zoom_api_webinar_options';
						} else {
							$type = 'zoom_api_meeting_options';
						}

						$meeting_data = get_post_meta( $meeting_recording->id, $type, true ) ?: array();
						// No record found for meeting or webinar in WP so skip it
						if ( ! $zoom_map_array[ $meeting_recording->id ] ) {
							//continue;
						}

						if( !isset($zoom_map_array[ $meeting_recording->id ]['past_recordings_all']) ) {
							//$zoom_map_array[ $meeting_recording->id ]['past_recordings_all'] = array();
						}

						$zoom_map_array[ $meeting_recording->id ]['type'] = $type;
						// $zoom_map_array[ $meeting_recording->id ]['topic'] = $meeting_recording->topic;
						$zoom_map_array[ $meeting_recording->id ]['topic'] = get_the_title( $group_id );
						$zoom_map_array[ $meeting_recording->id ]['host_id'] = $meeting_recording->host_id;
						
						$meeting_recording->recording_files = array_values($meeting_recording->recording_files);
						
						$this_recording = video_conferencing_recording_get_params_by_sequence( (array) $meeting_recording->recording_files, array() );
						

						// if we have only recording_url
						if ( isset( $this_recording['recording_url'] ) && $this_recording['recording_url'] ) {

							// Associate Vimeo ID if video exists
							$recording_key = array_search( $this_recording['recording_id'], array_column( $meeting_data['past_recordings'], 'recording_id' ) );

							if ( false !== $recording_key ) {
								
								$vimeo_record = $meeting_data['past_recordings'][ $recording_key ];
								
								if ( isset( $vimeo_record['vimeo_id'] ) ) {
									$exist = video_conferencing_zoom_check_vimeo_exist( $vimeo_record['vimeo_id'] );

									// Video deleted from Vimeo so remove from WP as well
									if ( ! $exist ) {
										$this_recording['vimeo_removed'] = 1;
									} else {
										$this_recording['vimeo_id'] = $vimeo_record['vimeo_id'];
									}
								}
							}

							//dd($this_recording, false);

							$recording_date = $this_recording['recording_end'];
							$recording_date = explode('T', $recording_date);

							$this_recording = lm_helper()->upload_user_recording_to_vimeo( $this_recording, get_the_title( $group_id ) . ' - ' . $recording_date[0], false, true, $user_id);

							$recording = $this_recording;
							//dd($recording, false);

							$zoom_map_array[ $meeting_recording->id ]['past_recordings_all'][] = $recording;
							//array_push( $zoom_map_array[ $meeting_recording->id ]['past_recordings_all'], $recordings);
							
							
						}

					}
					// Update DB will all past recordings for a meeting
					if ( $zoom_map_array ) {
						foreach ( $zoom_map_array as $meeting_id => $options ) {
							$type = $options['type'];
							if ( isset( $options['past_recordings_all'] ) && $options['past_recordings_all'] ) {
								$options['past_recordings'] = $options['past_recordings_all'];
							}
							if ( isset( $options['type'] ) ) {
								unset( $options['type'] );
							}
							if ( isset( $options['past_recordings_all'] ) ) {
								unset( $options['past_recordings_all'] );
							}
							update_post_meta( $meeting_id, $type, $options );
						}
					}

				}  //end user groups foreach

		}  //end users ifelse

		exit;
	}


	public function lm_zoom_api_link_shortcode_callback( $atts, $content = null ) {

		if ( ! video_conferencing_zoom_is_valid_request() ) {
			return;
		}
		ob_start();

		/* Forced redirect disabled in in v4.11.4
		// Serve a non cached version of the shortcode page
		if ( video_conferencing_zoom_redirect_nocache() ) {
			wp_enqueue_script( 'video-conferencing-with-zoom-api-nocache' );
		}
		*/

		// If styles fail to enqueue earlier then add them with the shortcode
		if ( ! wp_style_is( 'video-conferencing-with-zoom-api-iframe' ) ) {
			wp_enqueue_style( 'video-conferencing-with-zoom-api-iframe' );

			if ( is_rtl() ) {
				wp_add_inline_style( 'video-conferencing-with-zoom-api-iframe', 'body ul.zoom-meeting-countdown{ direction: ltr; }' );
			}
		}

		$args = shortcode_atts(
			array(
				'meeting_id'             => '',
				'is_webinar'             => '',
				'title'                  => '',
				'countdown_title'        => '',
				'join_via_app'           => '',
				'display_name'           => '',
				'user_email'             => '',
				'cloud_recording_button' => '',
				'recording_url'          => '',
				'display_back_btn'       => '',
				'id'                     => 'zoom-meeting-window',
				'class'                  => 'zoom-meeting-window',
				'hide_form'              => 0,
				'meeting_role'           => 0,
				'show_countdown'         => 0,
				'join_meeting'           => 0,
				'auto_join'              => 0,
				'is_meeting_view'        => 0,
				'show_join_web'          => 0,
				'show_join_app'          => 0,
			),
			$atts
		);

		$args['type_id'] = (float) str_replace( '-', '', str_replace( ' ', '', $args['meeting_id'] ) );

		if ( ! $args['type_id'] ) {
			$content = '<h4 class="no-meeting-id"><strong style="color:red;">' . __( 'ERROR: ', 'video-conferencing-with-zoom-api' ) . '</strong>' . __( 'Missing meeting or webinar id', 'video-conferencing-with-zoom-api' ) . '</h4>';
			return;
		}

		$content = '<div class="zoom-window-wrap"><div style="display:none;" class="loader"></div>';

		if ( $args['is_webinar'] ) {
			$args['option'] = 'zoom_api_webinar_options';
		} else {
			$args['option'] = 'zoom_api_meeting_options';
		}

		$args['zoom_map_array'] = get_post_meta( $args['type_id'], $args['option'], true );
		if ( ! $args['zoom_map_array'] ) {
			$args['zoom_map_array'] = array();
		}

		$args = lm_helper()->video_conferencing_zoom_prepare_args( $args );

		if ( isset( $args['zoom_map_array']['enforce_login'] ) && ! is_user_logged_in() ) {
			$content .= video_conferencing_zoom_show_is_login();
		} elseif ( video_conferencing_zoom_is_countdown( $args ) ) {
			$content .= video_conferencing_zoom_show_countdown( $args );
		} else {
			$content .= video_conferencing_zoom_load_meeting( $args );
		}

		$content .= '</div>'; // Close zoom-window-wrap

		// Allow addon devs to perform filter before window rendering
		echo apply_filters( 'zoom_wp_before_window_content', $content );

		$display = ob_get_clean();

		return $display;
	}

	public function lm_zoom_main_cron_sync_user_meeting_data() {
		/*if ( ! defined( 'DOING_CRON' ) ) {
			return;
		}*/

		$this->get_upload_user_meetings();
	}

	
}
