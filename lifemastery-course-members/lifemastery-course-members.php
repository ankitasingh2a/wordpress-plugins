<?php
/**
 * Plugin Name: Lifemastery Course Members
 * Description: Description
 * Plugin URI: http://unaibamir.com
 * Author: Ankita
 * Author URI: http://unaibamir.com
 * Version: 1.0.0
 * License: GPL2
 * Text Domain: lifemastery-course-members
 */

if (!function_exists("dd")) {
    function dd($data, $exit_data = true)
    {
        echo '<pre>' . print_r($data, true) . '</pre>';
        if ($exit_data == false) {
            echo '';
        } else {
            exit;
        }
    }
}

// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

if( !class_exists( 'LifeMastery_Course_Members' ) ) {

    /**
     * Main LifeMastery_Course_Members class
     *
     * @since       1.0.0
     */
    class LifeMastery_Course_Members {

        /**
         * @var         LifeMastery_Course_Members $instance The one true LifeMastery_Course_Members
         * @since       1.0.0
         */
        private static $instance;


        /**
         * Get active instance
         *
         * @access      public
         * @since       1.0.0
         * @return      object self::$instance The one true LifeMastery_Course_Members
         */
        public static function instance() {
            if( !self::$instance ) {
                self::$instance = new LifeMastery_Course_Members();
                self::$instance->setup_constants();
                self::$instance->includes();
                self::$instance->hooks();
            }

            return self::$instance;
        }


        /**
         * Setup plugin constants
         *
         * @access      private
         * @since       1.0.0
         * @return      void
         */
        private function setup_constants() {
            // Plugin version
            define( 'LM_Course_Members_VER', '1.0.0' );

            // Plugin path
            define( 'LM_Course_Members_DIR', plugin_dir_path( __FILE__ ) );

            // Plugin URL
            define( 'LM_Course_Members_URL', plugin_dir_url( __FILE__ ) );
        }


        /**
         * Include necessary files
         *
         * @access      private
         * @since       1.0.0
         * @return      void
         */
        private function includes() {
            // Include scripts
            // require_once LM_Course_Members_DIR . 'includes/shortcodes.php';
            // require_once LM_Course_Members_DIR . 'includes/widgets.php';
        }


        /**
         * Run action and filter hooks
         *
         */
        private function hooks() {

            add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );          

            // create the custom shortcode
            add_shortcode( 'lifemastery_course_members', array( $this, 'lifemastery_course_members_shortcode_callback' ) );

            //hook for bp profile edit page
            add_action( 'bp_after_profile_field_content', array( $this, 'lifemastery_group_selection_callback' ) );
            add_action( "xprofile_updated_profile", array( $this, "lifemastery_update_user_profile" ), 15, 5);
        }

        public function enqueue_scripts() {

            wp_enqueue_style( 'rwmb-select2', RWMB_CSS_URL . 'select2/select2.css', array(), '4.0.10' );
            wp_enqueue_style( 'lifemastery-datatable-css', LM_Course_Members_URL . 'assets/css/datatables.min.css', array(), false, 'all' );
            wp_enqueue_style( 'lifemastery-css', LM_Course_Members_URL . 'assets/css/style.css', array(), false, 'all' );

            wp_enqueue_script( 'rwmb-select2', RWMB_JS_URL . 'select2/select2.min.js', array( 'jquery' ), '4.0.10', true );
            wp_enqueue_script( 'lifemastery-datatable-js', LM_Course_Members_URL . 'assets/js/datatables.min.js', array( 'jquery' ), false, true );
            wp_enqueue_script( 'lifemastery-custom-js', LM_Course_Members_URL . 'assets/js/custom.js', array( 'jquery' ), false, true );
        }

        public function lifemastery_course_members_shortcode_callback( $atts ) {

            $user = wp_get_current_user();

            $atts = shortcode_atts( array(
                'pagelength'    =>  10,
                'user_id'       =>  $user->ID,
                'show_title'    =>  'yes',
                'number'        =>  'all'
            ), $atts, 'lifemastery_course_members' );

            $user_group_ids = array();

            if( learndash_is_admin_user($user->ID) ) {
                $user_group_ids     = learndash_get_administrators_group_ids( $user->ID );
            } else {
                $user_admin_groups  = learndash_get_administrators_group_ids( $user->ID );
                $user_group_ids     = learndash_get_users_group_ids( $user->ID );
                $user_group_ids     = !empty($user_admin_groups) ? array_merge($user_admin_groups, $user_group_ids) : $user_group_ids;
            }
            
            $common_group_ids   = !empty($user_group_ids) ? $user_group_ids : array() ;
            $hidden_groups      = get_user_meta( $user->ID, 'lifemastery_hidden_groups', true);
            if( !empty( $hidden_groups ) ) {
                $common_group_ids = array_diff( $common_group_ids, $hidden_groups );
            }

            if( !empty( $common_group_ids ) ) {
            	$common_group_ids = array_unique( $common_group_ids );
            }

            if( $atts['number'] != 'all' && is_integer( $atts['number'] ) ) {
                $common_group_ids = array_slice( $common_group_ids, 0, $atts['number']);
            }

            ob_start();
            
            if( !empty( $common_group_ids ) ) {
                ?>
                <div class="user-groups-area">
                <?php
                foreach ($common_group_ids as $group_id) {
                    $this->output_group_members( $group_id, $atts );
                }
                ?>
                </div>
                <?php
            }

            $output = ob_get_contents();
            ob_end_clean();
            return $output;
        }

        public function output_group_members( $group_id, $atts ) {
            
            $users = learndash_get_groups_users( $group_id );

            if( empty( $users ) ) {
                return;
            }

            ?>

            
                <h5 class="widgettitle group-title"><?php echo get_the_title($group_id); ?></h5>
                <div class="user-groups-table">
                    <table id="life-group-members-<?php echo $group_id;?>" class="ld-group-members display">
                        <thead>
                            <tr>
                                <th><?php echo __('Image', 'lifemastery-course-members'); ?></th>
                                <th><?php echo __('Name', 'lifemastery-course-members'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $user) {
                                $user_avata_img = get_avatar_url( $user->ID, array( 'size' => 48 ) );
                                ?>
                                <tr id="member-<?php echo $user->ID; ?>">
                                    <td><img src="<?php echo $user_avata_img ?>" alt="<?php echo $user->display_name; ?>"></td>
                                    <td><?php echo $user->display_name; ?></td>
                                </tr>
                                <?php
                            } ?>
                        </tbody>
                    </table>
                    <script>
                        jQuery(document).ready(function(){
                        	if( ! jQuery.fn.DataTable.isDataTable( '#life-group-members-<?php echo $group_id;?>' ) ) {
                        		jQuery('#life-group-members-<?php echo $group_id;?>').DataTable({
                        			pageLength: <?php echo $atts['pagelength']; ?>, 
                        			order: [[0, 'asc']], 
                        			columnDefs: [{targets: [0], orderable: false}],
                        			dom:'Bfrtip',
                        			columns:[ {data: 'avatar'}, {data: 'Firstname'},]
                        		});
                        	}
                        });
                    </script>
                </div>
            
            <?php
        }
        

        public function lifemastery_group_selection_callback()
        {

            if( bp_current_action() != 'edit' ) {
                return;
            }

            $user_id            = bp_displayed_user_id();
            if( learndash_is_admin_user($user_id) ) {
                $user_group_ids     = learndash_get_administrators_group_ids( $user_id );
            } else {
                $user_admin_groups  = learndash_get_administrators_group_ids( $user_id );
                $user_group_ids     = learndash_get_users_group_ids( $user_id );
                $user_group_ids     = !empty($user_admin_groups) ? array_unique(array_merge($user_admin_groups,$user_group_ids), SORT_REGULAR) : $user_group_ids;
            }
            if( empty( $user_group_ids ) ) {
                return;
            }
            
            $lifemastery_hidden_groups = get_user_meta($user_id, 'lifemastery_hidden_groups', true);

            ?>
            <div class="editfield required-field visibility-public field_type_textbox">
                <fieldset>
                    <legend>
                        Select Groups to hide
                    </legend>
                    <p class="description">
                        Selected groups would be hidden on the list of groups when viewing course pages. 
                    </p>
                    <select name="lifemastery_hidden_groups[]" id="lifemastery_hidden_groups" class="lifemastery_hidden_groups" multiple="multiple">
                        <?php foreach ($user_group_ids as $group_id) {
                            $selected = in_array( $group_id, $lifemastery_hidden_groups ) ? ' selected="selected" ' : '';
                            echo '<option value="' . $group_id .'" '. $selected .' >' . get_the_title($group_id) . '</option>';
                        } ?>
                    </select>
                </fieldset>
            </div>
            <?php

            
        }


        public function lifemastery_update_user_profile( $user_id, $posted_field_ids, $errors, $old_values, $new_values )
        {
            update_user_meta( $user_id, 'lifemastery_hidden_groups', $_POST["lifemastery_hidden_groups"], '' );
        }

    } // End of class LifeMastery_Course_Members

} // End if class_exists check


/**
 * The main function responsible for returning the one true LifeMastery_Course_Members
 * instance to functions everywhere
 *
 * @since       1.0.0
 * @return      \LifeMastery_Course_Members The one true LifeMastery_Course_Members
 *
 */
function LifeMastery_Course_Members_load() {
    if( class_exists('infusionWP') && defined('LEARNDASH_VERSION') ) {

        return LifeMastery_Course_Members::instance();
    }
}
add_action( 'plugins_loaded', 'LifeMastery_Course_Members_load', 12 );

/**
 * The activation hook is called outside of the singleton because WordPress doesn't
 * register the call from within the class, since we are preferring the plugins_loaded
 * hook for compatibility, we also can't reference a function inside the plugin class
 * for the activation function. If you need an activation function, put it here.
 *
 * @since       1.0.0
 * @return      void
 */
function LifeMastery_Course_Members_activation() {
    /* Activation functions here */
}
register_activation_hook( __FILE__, 'LifeMastery_Course_Members_activation' );
