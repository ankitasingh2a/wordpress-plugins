<?php
/**
 * The public-facing functionality of the plugin.
 * @link       http://example.com
 * @since      1.0.0
 * @package    reliable_repair_device
 * @subpackage reliable_repair_device/public
 */

class Reliable_Repair_Device_Public {
    /**
     * The ID of this plugin.
     * @since    1.0.0
     * @access   private
     * @var      string    $reliable_repair_device    The ID of this plugin.
     */
    private $plugin_name;
    /**
     * The version of this plugin.
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;
    /**
     * Initialize the class and set its properties.
     * @since    1.0.0
     * @param      string    $reliable_repair_device       The name of the plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct( $plugin_name, $version ) {

        $this->plugin_name = $plugin_name;
        $this->version = $version;

    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     * @since    1.0.0
     */

    public function enqueue_styles() {

        /**
         * This function is provided for demonstration purposes only.
         * An instance of this class should be passed to the run() function
         * defined in reliable_repair_device_Loader as all of the hooks are defined
         * in that particular class.
         * The reliable_repair_device_Loader will then create the relationship
         * between the defined hooks and the functions defined in this class.
         */

       wp_enqueue_style( 'flat_pickr' , 'https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css');
       wp_enqueue_style( 'bootstrap_css' , 'https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css');
       wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/reliable-repair-device-public.css', array(), time(), 'all' );
    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     * @since    1.0.0
     */

    public function enqueue_scripts() {

        wp_enqueue_script( 'flat_pickr', 'https://cdn.jsdelivr.net/npm/flatpickr', null, time(), false );
        wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/reliable-repair-device-public.js', array( 'jquery' ), time(), false );
        wp_localize_script( $this->plugin_name, 'my_ajax_object',array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
    }

    public function reliable_repair_device_shortcode_callback()
    {

      ob_start();
      $devices = get_field('device', 'option');
        // echo'<pre>';print_r($devices);
        if( $devices ): ?>
          <div class="sc-htJRVC fvHrIV">
            <div class="Device">

              <div class="form-wizard-wrapper">
               <ul class="progress-container ">
                 <li>
                   <div class="">Device</div>
                   <a class="form-wizard-link circle active" href="javascript:;" data-attr="info">
                     <span></span>
                     <div class="progress form-wizardmove-button"></div>
                   </a>
                 </li>
                 <li>
                   <div class="">ZIP code</div>
                   <a class="form-wizard-link circle" id="step_remove-2" href="javascript:;" data-attr="ads">
                     <span></span>
                     <div class="progress form-wizardmove-button"></div>
                   </a>
                 </li>
                 <li>
                   <div class="">Service Details</div>
                   <a class="form-wizard-link circle" id="step_remove-3" href="javascript:;" data-attr="placement">
                     <span></span>
                     <div class="progress form-wizardmove-button"></div>
                   </a>
                 </li>
                 <li>
                   <div class="">Appointment</div>
                   <a class="form-wizard-link circle" id="step_remove-4" href="javascript:;" data-attr="schedule">
                     <span></span>
                     <div class="progress form-wizardmove-button"></div>
                   </a>
                 </li>
               </ul>
              </div>
              
              <div role="form" class="wpcf7" id="wpcf7-f5064-p2-o2" lang="en-US" dir="ltr">
                <div id="overlay">
                  <div class="cv-spinner">
                 <span class="spinner"><img src="https://cutewallpaper.org/21/loading-gif-transparent-background/Tag-For-Transparent-Spinner-Icon-Pehliseedhi-Suitable-.gif"></span>
                  </div>
                </div>
                <div class="screen-reader-response"><p role="status" aria-live="polite" aria-atomic="true">One or more fields have an error. Please check and try again.</p></div>
                    <form action="<?php echo esc_url(get_page_link()); ?>"  method="post" class="wpcf7-form init" id="contactForm" novalidate="novalidate" data-status="init">

                    <div style="display: none;">
                        <input type="hidden" name="_wpcf7" value="5064">
                        <input type="hidden" name="_wpcf7_version" value="5.6.2">
                        <input type="hidden" name="_wpcf7_locale" value="en_US">
                        <input type="hidden" name="_wpcf7_unit_tag" value="wpcf7-f5064-p2-o2">
                        <input type="hidden" name="_wpcf7_container_post" value="2">
                        <input type="hidden" name="_wpcf7_posted_data_hash" value="">
                    </div>

                            <!-- add device 1 step -->

                <div class="Device-hide form-wizard-content show" data-tab-content="info">
                    <h6>What needs a repair?</h6>

                    <p>Schedule a repair in as little as 3 minutes.</p>

                            <div class="Device-sevices">



                                <?php foreach ($devices as $device): ?>

                                <a href="javascript:;" class="btn sc-iCfMLu jSMcTB item btn-nf-cell-phone-repair-cta add_device form-wizard-next-btn">

                                    <div class="image-container"><img class="image" src="<?php echo $device['add_device_image']; ?>" alt=""></div>

                                    <span font-size="3" font-weight="base" class="sc-fKVqWL dxPFUv text" id="get_device_name"><?php echo $device['add_device']; ?></span>

                                    <svg width="10" height="18" viewBox="0 0 10 18" fill="none" xmlns="http://www.w3.org/2000/svg" class="icon">

                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M10 9L1.33333 -3.78832e-07L-2.27393e-07 1.38461L7.33333 9L2.92155e-06 16.6154L1.33334 18L10 9Z" fill="black"></path>

                                    </svg>

                                    <div class="sc-pVTFL gphpNF">

                                        <span class="sc-eCImPb eFEXwx">

                                            <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">

                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M9.00003 7.41L14.0001 12L9.00003 16.59L10.41 18L16.41 12L10.41 6L9.00003 7.41Z" fill="currentColor"></path>

                                            </svg>

                                        </span>

                                    </div>

                                </a>

                                <?php
endforeach; ?>

                              <input type="hidden" name="device_name" id="device_nm">

                            </div>

                        </div>

                          <!-- zip code 2nd step -->

                        <div class="ZIP-code-hide form-wizard-content" data-tab-content="ads">

                            <a href="javascript:;"  class="step-back form-wizard-previous-btn backlock-2">

                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">

                                    <path d="M16 12H3" stroke="black" stroke-width="2"></path>

                                    <path d="M11 20L3 12.0212L10.9577 4" stroke="black" stroke-width="2"></path>

                                </svg>

                            </a>

                            <header class="sc-jObWnj dDZIDd">

                                <div class="bucket"><img class="bucket-media device_img_ap" src="" alt=""><span font-size="3" font-weight="base" class="sc-fKVqWL dxPFUv mob_res">Let’s get started on your <span class="device_name_ap"></span> repair</span></div>

                                <h1 font-size="5" font-weight="feather" class="sc-fKVqWL ggbnRe title">Where are you located?</h1>

                                <p font-size="3" font-weight="base" class="sc-fKVqWL dxPFUv desc">We tailor your options based on where you are.</p>

                            </header>

                            

                                <div class="sc-AjmGg hbSiaf">

                                    <div id="asurion-ui-text-field-1-field-container" class="sc-bqiRlB llAVDZ">

                                        <label for="inp" class="inp ">

                                        <input type="text" id="zip_code_txt" name="zipcode" placeholder="" class="sc-crHmcD fKvJtF field" value="">

                                        <span class="label">ZIP code</span>

                                        <p class="errorMessage"></p>

                                        <span class="focus-bg"></span>

                                        </label>

                                    </div>

                                </div>

                                <?php //echo $this->load_booking_map();
 ?>

                                <button id="zip_code_click" class="zip_code_btn sc-jRQBWg iwGtgz btn">Continue</button>

                        </div>

                            <!-- service deatils step 3-->

                        <div class="ZIP-code-hide form-wizard-content" id="Howdo" data-tab-content="placement">

                                <a href="javascript:;"  class="step-back form-wizard-previous-btn backlock-1"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M16 12H3" stroke="black" stroke-width="2"></path><path d="M11 20L3 12.0212L10.9577 4" stroke="black" stroke-width="2"></path></svg></a>

                                <header class="sc-jObWnj dDZIDd">

                                <h1 font-size="5" font-weight="feather" class="sc-fKVqWL ggbnRe title">How do you want to get your <span class="device_name_ap"></span> fixed?</h1>

                                <p font-size="3" font-weight="base" class="sc-fKVqWL dxPFUv desc">Here’s what’s available in your area:</p>

                                </header>

                           

                                <div class="sc-jgrJph ijkcGb service-option" id='service-options'>

                                <a href="javascript:;" class="sc-hUpaCq jecUZV form-wizard-next-btn how_fix_it">

                                <div class="card-media" data-testid="selection-card-image"><img src="https://lmstechs.in/clevertech/wp-content/uploads/2022/08/Screenshot_2020-03-23-CleverTech-iclevertech-%E2%80%A2-Instagram-photos-and-videos7.jpg" alt="" class="image"></div>

                                <div class="card-container">

                                <div class="card-content">

                                <h2 font-size="3" font-weight="base" class="sc-fKVqWL dxPFUv card-title">Carry-In</h2>

                                <p font-size="2" font-weight="base" class="sc-fKVqWL gaIwTq description">Most repairs done in 45 minutes or less. Free diagnostics.</p>

                                </div>

                                <svg class="icon" width="10" height="18" viewBox="0 0 10 18" fill="none" xmlns="http://www.w3.org/2000/svg">

                                <path fill-rule="evenodd" clip-rule="evenodd" d="M10 9L1.33333 -3.78832e-07L-2.27393e-07 1.38461L7.33333 9L2.92155e-06 16.6154L1.33334 18L10 9Z" fill="black"></path>

                                </svg>

                                </div>

                                </a>

                                <a href="javascript:;" class="sc-hUpaCq jecUZV form-wizard-next-btn how_fix_it">

                                <div class="card-media" data-testid="selection-card-image"><img src="https://lmstechs.in/clevertech/wp-content/uploads/2022/08/Screenshot_2020-03-23-CleverTech-iclevertech-%E2%80%A2-Instagram-photos-and-videos.jpg" alt="" class="image"></div>

                                <div class="card-container">

                                <div class="card-content">

                                <h2 font-size="3" font-weight="base" class="sc-fKVqWL dxPFUv card-title">We Come to You</h2>

                                <p font-size="2" font-weight="base" class="sc-fKVqWL gaIwTq description">Most phone repairs done in 45 minutes or less. Reservation required.</p>

                                </div>

                                <svg class="icon" width="10" height="18" viewBox="0 0 10 18" fill="none" xmlns="http://www.w3.org/2000/svg">

                                <path fill-rule="evenodd" clip-rule="evenodd" d="M10 9L1.33333 -3.78832e-07L-2.27393e-07 1.38461L7.33333 9L2.92155e-06 16.6154L1.33334 18L10 9Z" fill="black"></path>

                                </svg>

                                </div>

                                </a>

                                <a href="javascript:;" class="sc-hUpaCq jecUZV form-wizard-next-btn how_fix_it">

                                <div class="card-media" data-testid="selection-card-image"><img src="https://new.ubreakifix.com/static/media/option-mail-in.d966b4e6.jpeg" alt="" class="image"></div>

                                <div class="card-container">

                                <div class="card-content">

                                <h2 font-size="3" font-weight="base" class="sc-fKVqWL dxPFUv card-title">Mail-in Repair</h2>

                                <p font-size="2" font-weight="base" class="sc-fKVqWL gaIwTq description">Free shipping both ways and repairs are completed in less than a week.</p>

                                </div>

                                <svg class="icon" width="10" height="18" viewBox="0 0 10 18" fill="none" xmlns="http://www.w3.org/2000/svg">

                                <path fill-rule="evenodd" clip-rule="evenodd" d="M10 9L1.33333 -3.78832e-07L-2.27393e-07 1.38461L7.33333 9L2.92155e-06 16.6154L1.33334 18L10 9Z" fill="black"></path>

                                </svg>

                                </div>

                                </a>

                                <input type="hidden" name="fixed_device" id="how_fixed">

                                </div>

                        </div>

                                <!-- Service step 3a -->



                        <div class="ZIP-code-hides form-wizard-content" id="selectphone">

                            <a href="javascript:;"  class="step-back form-wizard-previous-btn">

                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">

                                    <path d="M16 12H3" stroke="black" stroke-width="2"></path>

                                    <path d="M11 20L3 12.0212L10.9577 4" stroke="black" stroke-width="2"></path>

                                </svg>

                                </a>

                            <header class="sc-jObWnj dDZIDd">

                                <header class="sc-jObWnj dDZIDd">

                                    <h1 font-size="5" font-weight="feather" class="sc-fKVqWL ggbnRe title">Tell us about your <span class="device_name_ap"></h1>

                                </header>

                            </header>



                                <section class="page device-details-page">

                                    <div class="sc-hiwPVj fBaVL">

                                        <div>

                                            <span font-size="3" font-weight="base" class="sc-fKVqWL dxPFUv field-helper">What <span class="device_name_ap"></span> is it?</span>

                                            <div class="sc-cxpSdN kvfdSk" id="asurion-ui-dropdown-1-container">

                                               

                                                <div class="sub_dev"></div>     

                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="sc-llYSUQ dSHCHM">

                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M7.41 7.99991L12 13L16.59 7.99991L18 9.40991L12 15.4099L6 9.40991L7.41 7.99991Z" fill="#000000"></path>

                                                </svg>

                                            </div>

                                        </div>

                                        <div id="granddev" class="colors" style="display:none;">

                                            <span font-size="3" font-weight="base" class="sc-fKVqWL dxPFUv field-helper">What <span class="subdevice_name_ap"> is it?</span>

                                            <div class="sc-cxpSdN kvfdSk" id="asurion-ui-dropdown-2-container">

                                                <div class="grand_sub_dev"></div>

                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="sc-llYSUQ dSHCHM">

                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M7.41 7.99991L12 13L16.59 7.99991L18 9.40991L12 15.4099L6 9.40991L7.41 7.99991Z" fill="#000000"></path>

                                                </svg>

                                            </div>

                                        </div>

                                      

                                    </div>

                                </section>

                        </div>

                            <!-- start serices -->

                        <div class="ZIP-code-hide form-wizard-content" id="What’swrong" style="display:none;">

                            <a href="javascript:;"  class="step-back form-wizard-previous-btn backlockslid">

                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">

                                    <path d="M16 12H3" stroke="black" stroke-width="2"></path>

                                    <path d="M11 20L3 12.0212L10.9577 4" stroke="black" stroke-width="2"></path>

                                </svg>

                            </a>

                            <header class="sc-jObWnj dDZIDd">

                                <h1 font-size="5" font-weight="feather" class="sc-fKVqWL ggbnRe title">What’s wrong with your <span class="device_name_ap"></span>?</h1>

                            </header>

                 

                            <div class="sc-ehCJOs cYosoy"></div>

                             <input type="hidden" name="wrong_with_device" id="wrong_val">

                        </div>



                            <!-- end serices -->

                        <div class="ZIP-code-hide form-wizard-content booking_wrap" id="bookingmap_step" data-tab-content="schedule">

                            <div id="locationhide" > 

                                 <a href="javascript:;"  class="step-back form-wizard-previous-btn backlock"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M16 12H3" stroke="black" stroke-width="2"></path><path d="M11 20L3 12.0212L10.9577 4" stroke="black" stroke-width="2"></path></svg></a>

                                <header class="sc-jObWnj dDZIDd">



                                    <h1 font-size="5" font-weight="feather" class="sc-fKVqWL ggbnRe title">Choose a location for your repair.</h1>



                                </header>

                            <?php 
                              $tax_query = array();
                             $options = array(
                                'post_type' => 'csl_locations',
                                'post_status' => array('publish'),
                                'order' => 'date',
                                'orderby' => 'title',
                                'posts' => -1,
                                'tax_query' => $tax_query,
                            );

                            $listing_query = new WP_Query( $options );
                            // echo"<pre>";print_r($listing_query);
                                if ( $listing_query->have_posts() ) { ?>
                                <script>

                                  var allLocations = [

                                    <?php while ( $listing_query->have_posts() ) : $listing_query->the_post(); 

                                        $locid = get_the_ID();

                                        $business_address = str_replace(array('[\', \']'), '', get_post_meta( $locid, 'business_address', true ));

                                        $business_latitude = get_post_meta( $locid, 'business_latitude', true );

                                        $business_longitude = get_post_meta( $locid, 'business_longitude', true );

                                    ?>

                                        {

                                            name: "<?php the_title(); ?>",

                                            <?php if($business_latitude) { ?>

                                            lat: <?php echo $business_latitude; ?>,

                                            <?php } else { ?>

                                            lat: '',            

                                            <?php } ?>

                                            <?php if($business_longitude) { ?>

                                            lng: <?php echo $business_longitude; ?>,

                                            <?php } else { ?>

                                                lng: '',            

                                            <?php } ?>

                                            myid: <?php echo get_the_ID(); ?>,

                                            <?php if($business_address) { echo 'address: "' . str_replace(array("\r\n", "\n"), "<br>", $business_address) . '",'; }  ?>

                                        },

                                    <?php endwhile;

                                    wp_reset_postdata(); ?>

                                ];

                                var cslAPI = '<?php echo get_option('csl_map_api_key'); ?>';

                                var cslMaptype = '<?php if(!empty(get_option('csl_map_type'))) { echo get_option('csl_map_type'); } else { echo 'roadmap'; } ?>';

                                var clsIcon = '<?php if(!empty(get_option('csl_custom_map_marker'))) { echo get_option('csl_custom_map_marker'); } ?>';

                                </script>

                                <?php $csl_map_default_radius = get_option('csl_map_default_radius');

                                $csl_include_cat = get_option('csl_include_cat');

                                ?>

                              <div class="rbook"></div>

                              <h2 id="location-search-alert">All Locations</h2>
                              <div class="csl-wrapper" id="csl-wrapper">    
                                  <div class="csl-left">
                                      <div id="locations-near-you">
                                          <a href="#" class="location-near-you-box nextlocation"> </a>
                                      </div>
                                  </div>

                                  <div class="csl-right">
                                      <div id="locations-near-you-map"></div>
                                  </div>
                              </div>

                            <?php

                            }
                            ?>

                            <input type="hidden" name="booking_address" id="booking_address">

                            </div>

                            </div>

                            <!-- booking slot -->



                            <div id="Choosedayshow" class="ZIP-code-hide form-wizard-content" style="display: none;">

                                         <a href="javascript:;" class="step-back form-wizard-previous-btn backlockslidnew"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M16 12H3" stroke="black" stroke-width="2"></path><path d="M11 20L3 12.0212L10.9577 4" stroke="black" stroke-width="2"></path></svg></a>

                                     <header class="sc-jObWnj dDZIDd">

                                    

                                        <h1 font-size="5" font-weight="feather" class="sc-fKVqWL ggbnRe title">When do you want to come in?</h1>

                                    </header>

                                    <div class="date_cus">

                                        <div class="sc-bXTejn czOssj"><p>Choose a day</p>
                                            <button class="click_btn_book"><img src="https://new.ubreakifix.com/static/media/calendar-img.c25c7e1e.svg" alt="Open Calendar" class="sc-GEbAx bmVtcp"></button>
                                        </div>

                                      <section class="popup">
                                          <div class="popup__content">
                                            <div class="close">
                                              <span></span>
                                              <span></span>
                                            </div>
                                            <h4>Select a day</h4>
                                              <div id="calendar" class="calendar_book hidecal">
                                                 <div class="placeholder"></div>
                                              </div>
                                          </div>
                                        </section>
                                      <!-- <input type="date" name="book_date" class="sc-crHmcD fKvJtF field b_date"> -->
                                      <div class="day_date_sec">
                                      <?php
                                       $date = date('Y-m-d'); //today date
                                        $todate = date('n/j',strtotime($date));
                                        $tomdate = date('n/j', strtotime("+1 day", strtotime($date)));
                                        $today = date('D', strtotime($date));
                                          $weekOfdays = array();
                                          for($i =0; $i <= 6; $i++){
                                            $weekOfdays[$i]['date'] = date('n/j', strtotime("+$i day", strtotime($date)));
                                            $weekOfdays[$i]['day'] = date('D', strtotime("+$i day", strtotime($date)));
                                             $weekOfdays[$i]['show_date'] = date('D, F d, Y', strtotime("+$i day", strtotime($date)));
                                          }
                                         // echo"<pre>";print_r($weekOfdays);

                                          foreach($weekOfdays as $days){
                                           if($days['day'] == "Sun")
                                            {
                                              $dis = 'disabled';
                                              $addcls = 'radio_diabled';
                                            }
                                            else{
                                              $dis = '';
                                              $addcls = 's';
                                            }
                                          if($days['date'] == $todate)
                                            {
                                              $srt_today = "<div class='sc-Galmp bmNBPa'>Today</div>";
                                            }
                                            else if($days['date'] == $tomdate){
                                               $srt_today = "<div class='sc-Galmp eRiQJu'>Tomorrow</div>";
                                            }
                                            else
                                            {
                                              $srt_today = '';
                                            }
                                          
                                            echo '<span>'.$srt_today.'<input type="radio" id="' . $days['date'] . '" name="book_date" value="' . $days['date'] . ' ' . $days['day'] . '" class="b_time" '.$dis.' show-data="'.$days['show_date'].'"><label for="' . $days['date'] . '" class="radio_repair '.$addcls.'"><span>' . $days['day'] . '</span><br>' . $days['date'] . '</label></span>';

                                        }
                                        ?> 
                                        </div>
                                        <input type="hidden" class="show_date_ti" value="">
                                  </br>

                                   </div>


                                   <span font-size="2" font-weight="base" class="sc-fKVqWL gaIwTq field-helper">Choose a time on <span font-size="2" font-weight="heavy" class="sc-fKVqWL gaIwVl"> <?php echo $todate. ' '.$today;?></span>.</span>
                                    <div class="sc-cxpSdN kvfdSk" id="asurion-ui-dropdown-1-container">

                                    <select name="book_time" id="asurion-ui-dropdown-12" class="sc-bBHxTw hWnfMq b_date">

                                         <option value="" disabled="" selected="">---</option>
                                         <option value="10:00 AM" class="ena_time" id="10">10:00 AM</option>
                                         <option value="11:00 AM" class="ena_time" id="11">11:00 AM</option>
                                         <option value="12:00 PM" class="ena_time" id="12">12:00 PM</option>
                                         <option value="1:00 PM" class="ena_time" id="13">1:00 PM</option>
                                         <option value="2:00 PM" class="ena_time" id="14">2:00 PM</option>
                                         <option value="3:00 PM" class="ena_time" id="15">3:00 PM</option>
                                         <option value="4:00 PM" class="ena_time" id="16">4:00 PM</option>
                                         <option value="5:00 PM" class="ena_time" id="17">5:00 PM</option>
                                         <option value="6:00 PM" class="ena_time" id="18">6:00 PM</option>

                                    </select>

                                    <label for="asurion-ui-dropdown-1" id="asurion-ui-dropdown-1-label" class="sc-iwjdpV eDBquT">Select Time</label>

                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="sc-llYSUQ dSHCHM">

                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M7.41 7.99991L12 13L16.59 7.99991L18 9.40991L12 15.4099L6 9.40991L7.41 7.99991Z" fill="#000000"></path>

                                    </svg>

                                </div>

                                <a href="javascript:;" class="sc-jRQBWg iwGtgz btn lsat_step">Continue</a>

                            </div>



                            <div id="lsat_steps_new" class="ZIP-code-hides form-wizard-content" style="display: none;">

                                     <a href="javascript:;" class="step-back form-wizard-previous-btn backlockslidlsat end_step"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M16 12H3" stroke="black" stroke-width="2"></path><path d="M11 20L3 12.0212L10.9577 4" stroke="black" stroke-width="2"></path></svg></a>

                            <header class="sc-jObWnj dDZIDd">

                                <h1 font-size="5" font-weight="feather" class="sc-fKVqWL ggbnRe title">How can we reach you?</h1>

                                <p font-size="3" font-weight="base" class="sc-fKVqWL dxPFUv desc">We'll only use this information to contact you about your repair.</p>

                            </header>
                            
                            <section class="container form-section-Confirm">
                
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 form-floating">
                                    <input type="text" name="your-name" value="" size="40" placeholder="First Name" class="wpcf7-form-control wpcf7-text wpcf7-validates-as-required form-control" id="firstname_txt" aria-required="true" aria-invalid="false">
                                    <label for="text">First name</label>
                                    <p class="errorMessage1"></p>
                                </div>
                                <div class=" col-lg-6 col-md-6 col-sm-6 col-xs-12 form-floating">
                                    <input type="text" name="your-name" value="" size="40" placeholder="Last Name" class="wpcf7-form-control wpcf7-text wpcf7-validates-as-required form-control" id="lsatname_txt" aria-required="true" aria-invalid="false">
                                    <label for="text">Last name</label>
                                    <p class="errorMessage2"></p>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 form-floating">
                                    <input type="email" name="your-email" value="" size="40" placeholder="Email" class="wpcf7-form-control wpcf7-text wpcf7-email wpcf7-validates-as-required wpcf7-validates-as-email form-control" id="email_txt" aria-required="true" aria-invalid="false">
                                    <label for="email">Email</label>
                                    <p class="errorMessage3"></p>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 form-floating">
                                    <input type="tel" name="PhoneNumber" value="" placeholder="Phone Number" size="40" class="wpcf7-form-control wpcf7-text wpcf7-tel wpcf7-validates-as-required wpcf7-validates-as-tel form-control" id="phone_code_txt" aria-required="true" aria-invalid="false">
                                    <label for="email">Phone Number</label>
                                    <p class="errorMessage4"></p>
                                </div>
                            </div>
                
                
                            <div>
                                <p>You can contact me by</p>
                                <div class="form-check">
                                    <input type="checkbox" name="contact_by[]" value="Phone Call" id="agree_checkbox">
                                    <label class="form-check-label" for="">
                                        Phone Call
                                    </label>
                                    <input type="checkbox" name="contact_by[]" id="agree_checkbox" value="Email">
                                    <label class="form-check-label" for="">
                                        Email
                                    </label>
                                    <input type="checkbox" name="contact_by[]" value="SMS/Text" id="agree_checkbox">
                                    <label class="form-check-label" for="">
                                        Sms/Text
                                    </label>
                                </div>
                                <p id="errorMessage5"></p>
                            </div>
                            
                                <div class="appointment-details" style="display:none;">
                                    <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <h5>Appointment Time</h5>
                                    <div class="appoi_time" style="display: flex;">
                                        <div>
                                           <svg width="41" height="41" viewBox="0 0 41 41" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M29.1667 39.6683C34.6896 39.6683 39.1668 35.1912 39.1668 29.6683C39.1668 24.1455 34.6896 19.6683 29.1667 19.6683C23.6439 19.6683 19.1667 24.1455 19.1667 29.6683C19.1667 35.1912 23.6439 39.6683 29.1667 39.6683Z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="stroke: currentcolor;"></path><path d="M32.5001 29.6683H29.1667V24.6683" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="stroke: currentcolor;"></path><path d="M15.8334 31.3333H2.50004C1.57957 31.3333 0.833374 30.5871 0.833374 29.6666V6.33329C0.833374 5.41282 1.57957 4.66663 2.50004 4.66663H32.5C33.4205 4.66663 34.1667 5.41282 34.1667 6.33329V17.1666" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="stroke: currentcolor;"></path><path d="M9.16675 1.33496V9.66829" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="stroke: currentcolor;"></path><path d="M25.8334 1.33496V9.66829" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="stroke: currentcolor;"></path><path d="M0.833374 13.0016H34.1667" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="stroke: currentcolor;"></path></svg>
                                        </div>
                                        <span class="booking_time_ap"></span>&nbsp;
                                        <div class="appointment-time sc-fKVqWL booking_date_ap">
                                        </div><br/>
                                    </div>
                                    <a class="sc-iJKOTD bMjDWz action-link backlockslidlsatx" href="javascript:;">Change Time</a>
                                </div>
                            
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <h5>Repair Location</h5>
                                    <div class="appoi_time1"  style="display: flex;">
                                        <div>
                                            <svg width="41" height="41" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="pin-location-icon"><path fill-rule="evenodd" clip-rule="evenodd" d="M12 10.5C13.933 10.5 15.5 8.933 15.5 7C15.5 5.067 13.933 3.5 12 3.5C10.067 3.5 8.5 5.067 8.5 7C8.5 8.933 10.067 10.5 12 10.5Z" stroke="black" stroke-linecap="round" stroke-linejoin="round"></path><path fill-rule="evenodd" clip-rule="evenodd" d="M12 0.5C15.7861 0.500552 18.855 3.56993 18.855 7.356C18.855 10.571 13.913 18.541 12.421 20.873C12.3291 21.0165 12.1704 21.1033 12 21.1033C11.8296 21.1033 11.6709 21.0165 11.579 20.873C10.087 18.541 5.145 10.573 5.145 7.356C5.14474 5.53777 5.86684 3.79391 7.15243 2.50814C8.43802 1.22236 10.1818 0.5 12 0.5V0.5Z" stroke="black" stroke-linecap="round" stroke-linejoin="round"></path><path d="M17 19.7C20.848 20.024 23.5 20.709 23.5 21.5C23.5 22.605 18.352 23.5 12 23.5C5.648 23.5 0.5 22.605 0.5 21.5C0.5 20.71 3.135 20.027 6.958 19.7" stroke="black" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                                        </div>
                                        <div class="appointment-time loc_address" style="margin-left: 15px;">
                                        </div>
                                        
                                    </div>
                                    <a class="sc-iJKOTD bMjDWz action-link change_store" href="#">Change Store</a>
                                </div>
                                </div>
                                
                            </div>
                            <div>
                                <p class="t-and-c">By submitting your information on this form, you are agreeing to be contacted
                                    regarding your
                                    service request by telephone, email, or text including using pre-recorded or auto dialed phone
                                    calls or text messages to the phone number you have provided, including your wireless number, if
                                    provided. Consent to content doesn't require you to purchase service. By sharing your email
                                    address, you also agree to receive marketing communications. Please see our <a href="#">Privacy
                                        Policy and
                                        Terms & Conditions</a> for more detail.</p>
                            </div>
                            <div class=" text-center my-4">
                                 <input type="submit" value="Submit" class="wpcf7-form-control has-spinner btn confirem-btn popmake-5886" id="form_validation">
                                </div>
                                </section>

                            <!-- booking details -->

                             <!--<p><span class="wpcf7-form-control-wrap" data-name="your-name"><input type="text" name="your-name" value="" size="40" placeholder="First Name" class="wpcf7-form-control wpcf7-text wpcf7-validates-as-required" id="firstname_txt" aria-required="true" aria-invalid="false">-->

                             <!--<p class="errorMessage1"></p>-->

                             <!--</span></p>-->



                             <!--<p><span class="wpcf7-form-control-wrap" data-name="your-name"><input type="text" name="your-name" value="" size="40" placeholder="Last Name" class="wpcf7-form-control wpcf7-text wpcf7-validates-as-required" id="lsatname_txt" aria-required="true" aria-invalid="false" required>-->

                             <!--<p class="errorMessage2"></p>-->

                             <!--</span></p>-->



                              <!--<p><span class="wpcf7-form-control-wrap" data-name="your-email"><input type="email" name="your-email" value="" size="40" placeholder="Email" class="wpcf7-form-control wpcf7-text wpcf7-email wpcf7-validates-as-required wpcf7-validates-as-email" id="email_txt" aria-required="true" aria-invalid="false" required>-->

                              <!--<p class="errorMessage3"></p>-->

                              <!--</span></p> -->



                              <!--<p><span class="wpcf7-form-control-wrap" data-name="PhoneNumber"><input type="tel" name="PhoneNumber" value="" placeholder="Phone Number" size="40" class="wpcf7-form-control wpcf7-text wpcf7-tel wpcf7-validates-as-required wpcf7-validates-as-tel" id="phone_code_txt" aria-required="true" aria-invalid="false">-->

                              <!--<p class="errorMessage4"></p>-->

                              <!--</span></p>-->



                              <!--<label> You can contact me by</label> <span class="wpcf7-form-control-wrap" data-name="contact_by"><span class="wpcf7-form-control wpcf7-checkbox"><span class="wpcf7-list-item first"><label><input type="checkbox" name="contact_by[]" value="Phone Call" id="agree_checkbox"><span class="wpcf7-list-item-label">Phone Call</span></label></span><span class="wpcf7-list-item"><label><input type="checkbox" name="contact_by[]" id="agree_checkbox" value="Email"><span class="wpcf7-list-item-label">Email</span></label></span><span class="wpcf7-list-item last"><label><input type="checkbox" name="contact_by[]" value="SMS/Text" id="agree_checkbox"><span class="wpcf7-list-item-label">SMS/Text</span></label></span></span></span>-->
                              <!--<p id ="errorMessage5">-->
                              <!--   </p>-->

                                 

                              

                              <!-- <input type="hidden" name="action" value="send_form" style="display: none; visibility: hidden; opacity: 0;">

                               <button type="submit">Submit</button> -->



                                <!--<p><input type="submit" value="Submit" class="wpcf7-form-control has-spinner" id="form_validation"><span class="wpcf7-spinner"></span></p>-->



                                <input type="hidden" class="wpcf7-pum" value='{"closepopup":false,"closedelay":0,"openpopup":false,"openpopup_id":0}'>

                                <!--<div class="wpcf7-response-output" aria-hidden="true"></div>-->

                                <input type="hidden" name="vx_width" value="1366">

                                <input type="hidden" name="vx_height" value="768">

                                <input type="hidden" name="vx_url" value="<?php site_url(); ?>">



                            <!--    <div class="appointment-details" style="display:none;">-->

                            <!--           <div class="section">-->

                            <!--            <div class="section-header">Appointment Time</div>-->

                            <!--              <div class="section-body">-->

                                            

                            <!--                    <div class="sc-fKVqWL booking_date_ap">-->

                            <!--                    </div>-->

                            <!--                    <br>-->

                            <!--                    <span class="booking_time_ap"></span>-->



                            <!--                    <a class="sc-iJKOTD bMjDWz action-link backlockslidlsat" href="#">Change Time</a>-->

                            <!--                </div>-->

                            <!--            </div>-->



                            <!--            <div class="section">-->

                            <!--                <div class="section-header">Repair Location</div>-->

                            <!--                    <div class="section-body">-->

                            <!--                        <span class="loc_address"></span>-->

                            <!--                    </div>-->

                            <!--                    <a class="sc-iJKOTD bMjDWz action-link change_store" href="#">Change Store</a>-->

                            <!--            </div>-->

                                        

                            <!--</div>-->



                    

                        </div>

                        

                        

                        <div id="lsat_steps_new_end" class="ZIP-code-hides form-wizard-content" style="display: none;">

                                 

                            <header class="sc-jObWnj dDZIDd">

                                <h1 font-size="5" font-weight="feather" class="sc-fKVqWL ggbnRe title">See you soon!</h1>

                    

                            </header>

                            

                            <div class="appoi_time_main" style="display:flex">

        <div class="block-section left-side loca_app_book" style="display:none;">
            <div class="padding-block " style="background-color: #ddd;  border-radius: 10px;">

                <h4><span class="device_name_ap"></span>&nbsp;repair appointment</h4>

                <div class="appoi_time2"  style="display: flex ; justify-content : space-between">

                    <p class="booking_time_ap"></p>

                    <p class="appointment-time sc-fKVqWL booking_date_ap"></p>

                </div>

                <span class="bookappt">Appt. Confirmed</span>

            </div>

            <h4 style="margin-top: 35px;">Repair Location</h4>

            <div class="appoi_time3"  style="display: flex;">

                <div style="margin-top: 20px;">

                  <svg width="41" height="41" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="pin-location-icon"><path fill-rule="evenodd" clip-rule="evenodd" d="M12 10.5C13.933 10.5 15.5 8.933 15.5 7C15.5 5.067 13.933 3.5 12 3.5C10.067 3.5 8.5 5.067 8.5 7C8.5 8.933 10.067 10.5 12 10.5Z" stroke="black" stroke-linecap="round" stroke-linejoin="round"></path><path fill-rule="evenodd" clip-rule="evenodd" d="M12 0.5C15.7861 0.500552 18.855 3.56993 18.855 7.356C18.855 10.571 13.913 18.541 12.421 20.873C12.3291 21.0165 12.1704 21.1033 12 21.1033C11.8296 21.1033 11.6709 21.0165 11.579 20.873C10.087 18.541 5.145 10.573 5.145 7.356C5.14474 5.53777 5.86684 3.79391 7.15243 2.50814C8.43802 1.22236 10.1818 0.5 12 0.5V0.5Z" stroke="black" stroke-linecap="round" stroke-linejoin="round"></path><path d="M17 19.7C20.848 20.024 23.5 20.709 23.5 21.5C23.5 22.605 18.352 23.5 12 23.5C5.648 23.5 0.5 22.605 0.5 21.5C0.5 20.71 3.135 20.027 6.958 19.7" stroke="black" stroke-linecap="round" stroke-linejoin="round"></path></svg>

                </div>

                <div class="padding-block pb">


                    <p class="appointment-time loc_address"></p>

                        <p>Monday - Saturday 10am - 6pm</p>

                </div>

            </div>



        </div>

        <div class="block-section left-side loca_default_add">
            
               <h4 style="margin-top: 35px;">Repair Location</h4>

            <div class="appoi_time3"  style="display: flex;">

                <div style="margin-top: 20px;">

                  <svg width="41" height="41" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="pin-location-icon"><path fill-rule="evenodd" clip-rule="evenodd" d="M12 10.5C13.933 10.5 15.5 8.933 15.5 7C15.5 5.067 13.933 3.5 12 3.5C10.067 3.5 8.5 5.067 8.5 7C8.5 8.933 10.067 10.5 12 10.5Z" stroke="black" stroke-linecap="round" stroke-linejoin="round"></path><path fill-rule="evenodd" clip-rule="evenodd" d="M12 0.5C15.7861 0.500552 18.855 3.56993 18.855 7.356C18.855 10.571 13.913 18.541 12.421 20.873C12.3291 21.0165 12.1704 21.1033 12 21.1033C11.8296 21.1033 11.6709 21.0165 11.579 20.873C10.087 18.541 5.145 10.573 5.145 7.356C5.14474 5.53777 5.86684 3.79391 7.15243 2.50814C8.43802 1.22236 10.1818 0.5 12 0.5V0.5Z" stroke="black" stroke-linecap="round" stroke-linejoin="round"></path><path d="M17 19.7C20.848 20.024 23.5 20.709 23.5 21.5C23.5 22.605 18.352 23.5 12 23.5C5.648 23.5 0.5 22.605 0.5 21.5C0.5 20.71 3.135 20.027 6.958 19.7" stroke="black" stroke-linecap="round" stroke-linejoin="round"></path></svg>

                </div>

                <div class="padding-block pb">


                    <p class="appointment-time">1150 Murphy Ave Ste 205, San Jose CA 95131</p>

                        <p>Monday - Saturday 10am - 6pm</p>

                </div>

            </div>
        </div>

        <div class="block-section right-side">

            <h3>What happens next</h3>

            <h4>We’ll remind you.</h4>

            <p>Look for a reminder email the morning of your appointment. If you need to reschedule, find the reschedule link in your confirmation email or call the store</p>

        <h4>Head on over</h4>

        <p>Bring us your device for a free diagnostic and we'll give you an estimate on timing and cost. Most phone repairs take less than 4 hours.</p>

    <h4>When you arrive</h4>  

    <p>For carry-in services, simply walk in and the technician will take care of everything. You can go about your day and come back when it’s most convenient for you.</p>  

<h4>Get back to it, worry-free.</h4>   

<p>All our repairs come with a 1 year warranty.</p>

</div>

    </div>

                        </div>

                    </form>

          </div>


        <?php
        endif;
        $output = ob_get_contents();
        ob_end_clean();
        return $output;

    }



        public function load_add_device_details() {

        

         $device_name = $_POST['device_name'];

         $devices = get_field('device', 'option');



         ob_start();



                  if( $devices ):

                     foreach( $devices as $device ):

                        if($device['add_device'] == $device_name)

                        {

                            

                            ?>

                            <select name="sub_device" id="asurion-ui-dropdown-1" data-id="<?php echo $device_name;?>" class="sc-bBHxTw hWnfMq sub_devices_change">

                               <option value="" disabled="" selected>---</option>

                            <?php

                            foreach ($device['sub_devices'] as $key => $value) {

                            echo '<option value="'.esc_html__($value['add_sub_device']).'">'.$value['add_sub_device'].'</option>';

                            }

                            ?>

                             </option>

                            </select>

                            <label for="asurion-ui-dropdown-1" id="asurion-ui-dropdown-1-label" class="sc-iwjdpV eDBquT">Select <?php echo $device['add_device'];?></label>

                            <?php

                        }

                     endforeach;

                  endif;

                

                ?>

               

        <?php



        $output = ob_get_contents();

        ob_end_clean();

        echo $output;



        wp_die();

    }





        public function load_add_grand_device_details() {

         $device_name = $_POST['device_name'];

         $sub_device_name = $_POST['sub_device_name'];

         $devices = get_field('device', 'option');

         ob_start();

 

                 if( $devices ):

                     foreach( $devices as $device ):

                       //  Print_r($device);

                        if($device['add_device'] == $device_name)

                        {

                            ?>

                            <select name="grand_sub_device" id="asurion-ui-dropdown-1" data-id="<?php echo $device_name;?>" class="sc-bBHxTw hWnfMq grand_sub_device">

                               <option value="" disabled="" selected>---</option>

                            <?php

                            foreach ($device['sub_devices'] as $key => $value) {

                              if($value['add_sub_device'] == $sub_device_name)

                                {

                                    foreach ($value['grand_sub_device'] as $key => $val) {

                                        echo '<option value="'.esc_html__($val['grand_sub_devices']).'">'.$val['grand_sub_devices'].'</option>';

                                    }

                                }

                        

                            }

                            ?>

                             </option>

                            </select>

                            <label for="asurion-ui-dropdown-1" id="asurion-ui-dropdown-1-label" class="sc-iwjdpV eDBquT">Select <?php echo $sub_device_name;?></label>

                            <?php

                        }

                     endforeach;

                  endif;

                

                ?>

               

        <?php

        $output = ob_get_contents();

        ob_end_clean();

        echo $output;



        wp_die();

    }



    public function load_service_details() {

         $device_name = $_POST['device_name'];

         $devices = get_field('device', 'option');

         ob_start();



                  if( $devices ):

                     foreach( $devices as $device ):

                        if($device['add_device'] == $device_name)

                        {

                            ?>

                           

                            <?php

                            foreach ($device['service_details'] as $key => $value) {

                            echo '<a href="javascript:;" class="sc-iCfMLu jSMcTB item booking_map">

                                    <span class="image-container"><img class="image" src="https://d3reo6pj2fv7oy.cloudfront.net/device-repair-types/symptoms/screen-damage.svg" alt=""></span><span font-size="3" font-weight="base" class="sc-fKVqWL dxPFUv text">'.$value['service_title'].'</span>

                                    <svg width="10" height="18" viewBox="0 0 10 18" fill="none" xmlns="http://www.w3.org/2000/svg" class="icon">

                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M10 9L1.33333 -3.78832e-07L-2.27393e-07 1.38461L7.33333 9L2.92155e-06 16.6154L1.33334 18L10 9Z" fill="black"></path>

                                    </svg>

                                </a>';

                            }

                            ?>

                           

                            <?php

                        }

                     endforeach;

                  endif;

                

                ?>

               

        <?php

        

        $output = ob_get_contents();

        ob_end_clean();

        echo $output;



        wp_die();

    }



// function send_form(){

 

//     if ( empty( $_POST["first_name"] ) ) {

//         echo "Insert your name please";

//         wp_die();

//     }

 

//     if ( ! filter_var( $_POST["email"], FILTER_VALIDATE_EMAIL ) ) {

//         echo 'Insert your email please';

//         wp_die();

//     }

 

//     if ( empty( $_POST["PhoneNumber"] ) ) {

//         echo "Insert your comment please";

//         wp_die();

//     }

 

//     echo 'Done!';

//     wp_die();

// }

}



?>

    



