<?php
/**
 * Plugin Name: G4S Multisite Product Sync (Source)
 * Plugin URI: 
 * Description: This plugin enables porting of data from the Source Website to the target site. The data can be of Universities and Products.
 * Version: 1.0
 * Author: Ankita
 * Author URI: 
 */

function made_ufc_register_settings() {
    register_setting( 'made_ufc_options_group', '_second_site_url' );
    register_setting( 'made_ufc_options_group', '_second_site_consumer_key' );
    register_setting( 'made_ufc_options_group', '_second_site_consumer_secret' );
}
add_action( 'admin_init', 'made_ufc_register_settings' );

function made_ufc_register_options_page() {
    add_options_page('Page Title', 'G4S Multisite Product Sync Setting', 'manage_options', 'G4SMultisiteProductSync', 'made_ufc_options_page');
}
add_action('admin_menu', 'made_ufc_register_options_page');
function made_ufc_options_page()
{
  echo '<div>';
  screen_icon();
    echo '<h2>UFC Setting</h2>';
    echo '<form method="post" action="options.php">';
    settings_fields( 'made_ufc_options_group' );
    echo '<table style="width:100%">';
    echo '<tr valign="top">';
    echo '<th scope="row" style="width:20%"><label for="_second_site_url">Site URL</label></th>';
    echo '<td style="width:80%"><input style="width:80%" type="text" id="_second_site_url" name="_second_site_url" value="'.get_option('_second_site_url').'" /></td>';
    echo '</tr>';
    echo '<tr valign="top">';
    echo '<th scope="row" style="width:20%"><label for="_second_site_consumer_key">Cunsumer Key</label></th>';
    echo '<td style="width:80%"><input style="width:80%" type="text" id="_second_site_consumer_key" name="_second_site_consumer_key" value="'.get_option('_second_site_consumer_key').'" /></td>';
    echo '</tr>';
    echo '<tr valign="top">';
    echo '<th scope="row" style="width:20%"><label for="_second_site_consumer_secret">Cunsumer Secret</label></th>';
    echo '<td style="width:80%"><input style="width:80%" type="text" id="_second_site_consumer_secret" name="_second_site_consumer_secret" value="'.get_option('_second_site_consumer_secret').'" /></td>';
    echo '</tr>';
    echo '</table>';
    submit_button();
    echo '</form>';
    echo '</div>';

	global $wpdb;
	
    /*$data = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."posts WHERE post_type = 'product' ORDER BY ID LIMIT 300,20");
    for($k=0;$k<count($data);$k++){
		$post_id = $data[$k]->ID;
        $post_data = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."posts WHERE ID = ".$post_id);
        $post_meta_data = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."postmeta WHERE meta_key != 'en_vdw_gallery_id' AND meta_key != 'es_vdw_gallery_id' AND meta_key != 'pt_vdw_gallery_id' AND meta_key != '_wpml_word_count' AND post_id = ".$post_id);
        
        $wpml_word_count = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."postmeta WHERE meta_key = '_wpml_word_count' AND post_id = ".$post_id);
        $second_site_id = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."postmeta WHERE meta_key = '_second_site_id' AND post_id = ".$post_id);
        $term_relationships = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."term_relationships WHERE object_id = ".$post_id);
        $en_vdw_gallery_id = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."postmeta WHERE meta_key = 'en_vdw_gallery_id' AND post_id = ".$post_id);
        $es_vdw_gallery_id = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."postmeta WHERE meta_key = 'es_vdw_gallery_id' AND post_id = ".$post_id);
        $pt_vdw_gallery_id = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."postmeta WHERE meta_key = 'pt_vdw_gallery_id' AND post_id = ".$post_id);

        $second_site_id_count = count($second_site_id);
        $post_data = json_encode($post_data);
        $post_meta_data = json_encode($post_meta_data);
        $wpml_word_count = json_encode($wpml_word_count);
        $second_site_id = json_encode($second_site_id);
        $term_relationships = json_encode($term_relationships);
        $en_vdw_gallery_id = $en_vdw_gallery_id[0]->meta_value;
        $es_vdw_gallery_id = $es_vdw_gallery_id[0]->meta_value;
        $pt_vdw_gallery_id = $pt_vdw_gallery_id[0]->meta_value;

        $post = [
            'post_data' => $post_data,
            'post_meta_data' => $post_meta_data,
            'wpml_word_count'   => $wpml_word_count,
            'second_site_id' => $second_site_id,
            'term_relationships' => $term_relationships,
            'en_vdw_gallery_id' => $en_vdw_gallery_id,
            'es_vdw_gallery_id' => $es_vdw_gallery_id,
            'pt_vdw_gallery_id' => $pt_vdw_gallery_id
        ];

        $log = $log  = "User: ".$_SERVER['REMOTE_ADDR'].' - '.date("F j, Y, g:i a").PHP_EOL;
        $log.='Post ID : '.$post_id.PHP_EOL;
        $log.='Post Type : '.$get_post_type.PHP_EOL;
        $log.='Post Status : '.$get_post_status.PHP_EOL;
        $log.='Post Data : '.json_encode($post).PHP_EOL;
        file_put_contents(WP_PLUGIN_DIR .'/g4s_multisite_product_sync/logs/log_'.date("j-n-Y").'.log', $log, FILE_APPEND);

        $url = get_option('_second_site_url')."/wp-json/wc/v3/add_update_prduct_api?";
        $consumer_key = get_option('_second_site_consumer_key');
        $consumer_secret = get_option('_second_site_consumer_secret');
        $url = $url.'consumer_key='.$consumer_key."&consumer_secret=".$consumer_secret;
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $post,
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache",
                "postman-token: bd84855a-0bbe-92b1-0d90-0d9370477c33"
            ),
        ));
        
        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            $myJSON = json_encode($err);
        } else {
            $myJSON = json_encode($response);
            if($second_site_id_count == 0){
                $wpdb->query("INSERT INTO ".$wpdb->prefix."postmeta (post_id,meta_key,meta_value) VALUES ($post_id,'_second_site_id','$response')");
            }
        }
    }
	*/
	
	$data = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."posts WHERE post_type = 'universities' ORDER BY ID");
    for($k=0;$k<count($data);$k++){
		$post_id = $data[$k]->ID;
        $post_data = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."posts WHERE ID = ".$post_id);
        $post_meta_data = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."postmeta WHERE meta_key != 'forprod' AND meta_key != 'university_links' AND post_id = ".$post_id);
        $forprod = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."postmeta WHERE meta_key = 'forprod' AND post_id = ".$post_id);
        $second_site_id = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."postmeta WHERE meta_key = '_second_site_id' AND post_id = ".$post_id);
        $university_links = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."postmeta WHERE meta_key = 'university_links' AND post_id = ".$post_id);
        $term_relationships = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."term_relationships WHERE object_id = ".$post_id);
	
        $second_site_id_count = count($second_site_id);
        $post_data = json_encode($post_data);
        $post_meta_data = json_encode($post_meta_data);
        $forprod = $forprod[0]->meta_value;
        $second_site_id = json_encode($second_site_id);
        $university_links = $university_links[0]->meta_value;
        $term_relationships = json_encode($term_relationships);

        $post = [
            'post_data' => $post_data,
            'post_meta_data' => $post_meta_data,
            'forprod'   => $forprod,
            'second_site_id' => $second_site_id,
            'university_links'   => $university_links,
            'term_relationships' => $term_relationships
        ];
		if($second_site_id_count == 0){
			
			$wpdb->query("INSERT INTO demo (name) VALUES ('1')");
			$wpdb->query("INSERT INTO demo (name) VALUES ('$second_site_id_count')");
			$wpdb->query("INSERT INTO demo (name) VALUES ('$second_site_id')");
			$wpdb->query("INSERT INTO demo (name) VALUES ('$post_id')");
			
		
			/*$log = $log  = "User: ".$_SERVER['REMOTE_ADDR'].' - '.date("F j, Y, g:i a").PHP_EOL;
			$log.='Post ID : '.$post_id.PHP_EOL;
			$log.='Post Type : '.$get_post_type.PHP_EOL;
			$log.='Post Status : '.$get_post_status.PHP_EOL;
			$log.='Post Data : '.json_encode($post).PHP_EOL;
			file_put_contents(WP_PLUGIN_DIR .'/g4s_multisite_product_sync/logs/log_'.date("j-n-Y").'.log', $log, FILE_APPEND);

			$url = get_option('_second_site_url')."/wp-json/wc/v3/add_update_univers_api?";
			$consumer_key = get_option('_second_site_consumer_key');
			$consumer_secret = get_option('_second_site_consumer_secret');
			$url = $url.'consumer_key='.$consumer_key."&consumer_secret=".$consumer_secret;
			$curl = curl_init();
			curl_setopt_array($curl, array(
				CURLOPT_URL => $url,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => "",
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 30,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => "POST",
				CURLOPT_POSTFIELDS => $post,
				CURLOPT_HTTPHEADER => array(
					"cache-control: no-cache",
					"postman-token: bd84855a-0bbe-92b1-0d90-0d9370477c33"
				),
			));
			
			$response = curl_exec($curl);
			$err = curl_error($curl);
			curl_close($curl);
			if ($err) {
				$myJSON = json_encode($err);
			} else {
				$myJSON = json_encode($response);
				if($second_site_id_count == 0){
					$wpdb->query("INSERT INTO ".$wpdb->prefix."termmeta (term_id,meta_key,meta_value) VALUES ($term_id,'_second_site_id','$response')");
				}
			}*/
		}
    }
}

// Add & Update Product & universities
add_action('save_post', 'custom_save_function', 100, 2);
function custom_save_function( $post_id ) {
    
    $get_post_type = get_post_type($post_id);
    $get_post_status = get_post_status($post_id);
    global $wpdb;
   
    if($get_post_type == 'product'){
        $post_data = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."posts WHERE ID = ".$post_id);
        $post_meta_data = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."postmeta WHERE meta_key != 'en_vdw_gallery_id' AND meta_key != 'es_vdw_gallery_id' AND meta_key != 'pt_vdw_gallery_id' AND meta_key != '_wpml_word_count' AND post_id = ".$post_id);
        
        $wpml_word_count = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."postmeta WHERE meta_key = '_wpml_word_count' AND post_id = ".$post_id);
        $second_site_id = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."postmeta WHERE meta_key = '_second_site_id' AND post_id = ".$post_id);
        $term_relationships = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."term_relationships WHERE object_id = ".$post_id);
        $en_vdw_gallery_id = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."postmeta WHERE meta_key = 'en_vdw_gallery_id' AND post_id = ".$post_id);
        $es_vdw_gallery_id = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."postmeta WHERE meta_key = 'es_vdw_gallery_id' AND post_id = ".$post_id);
        $pt_vdw_gallery_id = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."postmeta WHERE meta_key = 'pt_vdw_gallery_id' AND post_id = ".$post_id);

        $second_site_id_count = count($second_site_id);
        $post_data = json_encode($post_data);
        $post_meta_data = json_encode($post_meta_data);
        $wpml_word_count = json_encode($wpml_word_count);
        $second_site_id = json_encode($second_site_id);
        $term_relationships = json_encode($term_relationships);
        $en_vdw_gallery_id = $en_vdw_gallery_id[0]->meta_value;
        $es_vdw_gallery_id = $es_vdw_gallery_id[0]->meta_value;
        $pt_vdw_gallery_id = $pt_vdw_gallery_id[0]->meta_value;

        $post = [
            'post_data' => $post_data,
            'post_meta_data' => $post_meta_data,
            'wpml_word_count'   => $wpml_word_count,
            'second_site_id' => $second_site_id,
            'term_relationships' => $term_relationships,
            'en_vdw_gallery_id' => $en_vdw_gallery_id,
            'es_vdw_gallery_id' => $es_vdw_gallery_id,
            'pt_vdw_gallery_id' => $pt_vdw_gallery_id
        ];

        $log = $log  = "User: ".$_SERVER['REMOTE_ADDR'].' - '.date("F j, Y, g:i a").PHP_EOL;
        $log.='Post ID : '.$post_id.PHP_EOL;
        $log.='Post Type : '.$get_post_type.PHP_EOL;
        $log.='Post Status : '.$get_post_status.PHP_EOL;
        $log.='Post Data : '.json_encode($post).PHP_EOL;
        file_put_contents(WP_PLUGIN_DIR .'/g4s_multisite_product_sync/logs/log_'.date("j-n-Y").'.log', $log, FILE_APPEND);

        $url = get_option('_second_site_url')."/wp-json/wc/v3/add_update_prduct_api?";
        $consumer_key = get_option('_second_site_consumer_key');
        $consumer_secret = get_option('_second_site_consumer_secret');
        $url = $url.'consumer_key='.$consumer_key."&consumer_secret=".$consumer_secret;
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $post,
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache",
                "postman-token: bd84855a-0bbe-92b1-0d90-0d9370477c33"
            ),
        ));
        
        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            $myJSON = json_encode($err);
        } else {
            $myJSON = json_encode($response);
            if($second_site_id_count == 0){
                $wpdb->query("INSERT INTO ".$wpdb->prefix."postmeta (post_id,meta_key,meta_value) VALUES ($post_id,'_second_site_id','$response')");
            }
        }
    }
    if($get_post_type == 'universities'){
        $post_data = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."posts WHERE ID = ".$post_id);
        $post_meta_data = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."postmeta WHERE meta_key != 'forprod' AND meta_key != 'university_links' AND post_id = ".$post_id);
        $forprod = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."postmeta WHERE meta_key = 'forprod' AND post_id = ".$post_id);
        $second_site_id = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."postmeta WHERE meta_key = '_second_site_id' AND post_id = ".$post_id);
        $university_links = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."postmeta WHERE meta_key = 'university_links' AND post_id = ".$post_id);
        $term_relationships = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."term_relationships WHERE object_id = ".$post_id);

        $second_site_id_count = count($second_site_id);
        $post_data = json_encode($post_data);
        $post_meta_data = json_encode($post_meta_data);
        $forprod = $forprod[0]->meta_value;
        $second_site_id = json_encode($second_site_id);
        $university_links = $university_links[0]->meta_value;
        $term_relationships = json_encode($term_relationships);

        $post = [
            'post_data' => $post_data,
            'post_meta_data' => $post_meta_data,
            'forprod'   => $forprod,
            'second_site_id' => $second_site_id,
            'university_links'   => $university_links,
            'term_relationships' => $term_relationships
        ];

        $log = $log  = "User: ".$_SERVER['REMOTE_ADDR'].' - '.date("F j, Y, g:i a").PHP_EOL;
        $log.='Post ID : '.$post_id.PHP_EOL;
        $log.='Post Type : '.$get_post_type.PHP_EOL;
        $log.='Post Status : '.$get_post_status.PHP_EOL;
        $log.='Post Data : '.json_encode($post).PHP_EOL;
        file_put_contents(WP_PLUGIN_DIR .'/g4s_multisite_product_sync/logs/log_'.date("j-n-Y").'.log', $log, FILE_APPEND);

        $url = get_option('_second_site_url')."/wp-json/wc/v3/add_update_univers_api?";
        $consumer_key = get_option('_second_site_consumer_key');
        $consumer_secret = get_option('_second_site_consumer_secret');
        $url = $url.'consumer_key='.$consumer_key."&consumer_secret=".$consumer_secret;
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $post,
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache",
                "postman-token: bd84855a-0bbe-92b1-0d90-0d9370477c33"
            ),
        ));
        
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
            $myJSON = json_encode($err);
        } else {
            $myJSON = json_encode($response);
            if($second_site_id_count == 0){
                $wpdb->query("INSERT INTO ".$wpdb->prefix."postmeta (post_id,meta_key,meta_value) VALUES ($post_id,'_second_site_id','$response')");
            }
        }
    }
}

// Delete Product & universities
add_action('before_delete_post', 'custom_delete_function');
function custom_delete_function( $post_id ){
    global $wpdb;
    $second_site_id = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."postmeta WHERE meta_key = '_second_site_id' AND post_id = ".$post_id);
    
    $log = $log  = "User: ".$_SERVER['REMOTE_ADDR'].' - '.date("F j, Y, g:i a").PHP_EOL;
    $log.='Post ID : '.$post_id.PHP_EOL;
    $log.='Post Type : Deleted This Post'.PHP_EOL;
    file_put_contents(WP_PLUGIN_DIR .'/g4s_multisite_product_sync/logs/log_'.date("j-n-Y").'.log', $log, FILE_APPEND);
    
    $post = [
        'id' => $second_site_id[0]->meta_value
    ];
	
    $url = get_option('_second_site_url')."/wp-json/wc/v3/api_delete_post?";
    $consumer_key = get_option('_second_site_consumer_key');
    $consumer_secret = get_option('_second_site_consumer_secret');
    $url = $url.'consumer_key='.$consumer_key."&consumer_secret=".$consumer_secret;
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => $post,
        CURLOPT_HTTPHEADER => array(
            "cache-control: no-cache",
            "postman-token: bd84855a-0bbe-92b1-0d90-0d9370477c33"
        ),
    ));
    
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    if ($err) {
        $myJSON = json_encode($err);
    } else {
        $myJSON = json_encode($response);
    }
}

// Add Category & Tags
add_action('create_product_cat','custom_created_term',100,2);
add_action('create_product_tag','custom_created_term',100,2);
function custom_created_term( $term_id, $term_taxonomy_id ) {
   
    global $wpdb;
    $term_data = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."terms WHERE term_id = ".$term_id);
    $term_meta_data = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."termmeta WHERE term_id = ".$term_id);
    $term_taxonomy_data = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."term_taxonomy WHERE term_id = ".$term_id);
    
    $term_data = json_encode($term_data);
    $term_meta_data = json_encode($term_meta_data);
    $term_taxonomy_data = json_encode($term_taxonomy_data);

    $post = [
        'term_data' => $term_data,
        'term_meta_data' => $term_meta_data,
        'term_taxonomy_data' => $term_taxonomy_data,
        'term_taxonomy_id' => $term_taxonomy_id
    ];

    $log = $log  = "User: ".$_SERVER['REMOTE_ADDR'].' - '.date("F j, Y, g:i a").PHP_EOL;
    $log.='Term ID : '.$term_id.PHP_EOL;
    $log.='term_taxonomy_id : '.$term_taxonomy_id.PHP_EOL;
    $log.='Term Data : '.json_encode($post).PHP_EOL;
    file_put_contents(WP_PLUGIN_DIR .'/g4s_multisite_product_sync/logs/log_'.date("j-n-Y").'.log', $log, FILE_APPEND);

    $url = get_option('_second_site_url')."/wp-json/wc/v3/api_category_add?";
    $consumer_key = get_option('_second_site_consumer_key');
    $consumer_secret = get_option('_second_site_consumer_secret');
    $url = $url.'consumer_key='.$consumer_key."&consumer_secret=".$consumer_secret;
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => $post,
        CURLOPT_HTTPHEADER => array(
            "cache-control: no-cache",
            "postman-token: bd84855a-0bbe-92b1-0d90-0d9370477c33"
        ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    if ($err) {
        $myJSON = json_encode($err);
    } else {
        $myJSON = json_encode($response);
        $wpdb->query("INSERT INTO ".$wpdb->prefix."termmeta (term_id,meta_key,meta_value) VALUES ($term_id,'_second_site_id','$response')");
    }
}

// Update Category & Tags
add_action('edited_product_cat','update_category_function',100,2);
add_action('edited_product_tag','update_category_function',100,2);
function update_category_function( $term_id, $term_taxonomy_id ) {
    global $wpdb;
    $term_data = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."terms WHERE term_id = ".$term_id);
    $term_meta_data = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."termmeta WHERE term_id = ".$term_id);
    $term_taxonomy_data = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."term_taxonomy WHERE term_id = ".$term_id);
    $second_site_id = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."termmeta WHERE meta_key = '_second_site_id' AND term_id = ".$term_id);
    
    $second_site_id_count = count($second_site_id);
    $term_data = json_encode($term_data);
    $term_meta_data = json_encode($term_meta_data);
    $term_taxonomy_data = json_encode($term_taxonomy_data);
    $second_site_id = json_encode($second_site_id);
    $post = [
        'term_data' => $term_data,
        'term_meta_data' => $term_meta_data,
        'term_taxonomy_data' => $term_taxonomy_data,
        'second_site_id' => $second_site_id,
        'term_taxonomy_id' => $term_taxonomy_id
    ];

    $log = $log  = "User: ".$_SERVER['REMOTE_ADDR'].' - '.date("F j, Y, g:i a").PHP_EOL;
    $log.='Term ID : '.$term_id.PHP_EOL;
    $log.='term_taxonomy_id : '.$term_taxonomy_id.PHP_EOL;
    $log.='Term Data : '.json_encode($post).PHP_EOL;
    file_put_contents(WP_PLUGIN_DIR .'/g4s_multisite_product_sync/logs/log_'.date("j-n-Y").'.log', $log, FILE_APPEND);

    $url = get_option('_second_site_url')."/wp-json/wc/v3/api_category_update?";
    $consumer_key = get_option('_second_site_consumer_key');
    $consumer_secret = get_option('_second_site_consumer_secret');
    $url = $url.'consumer_key='.$consumer_key."&consumer_secret=".$consumer_secret;
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => $post,
        CURLOPT_HTTPHEADER => array(
            "cache-control: no-cache",
            "postman-token: bd84855a-0bbe-92b1-0d90-0d9370477c33"
        ),
    ));
    
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    if ($err) {
        $myJSON = json_encode($err);
    } else {
        $myJSON = json_encode($response);
        if($second_site_id_count == 0){
            $wpdb->query("INSERT INTO ".$wpdb->prefix."termmeta (term_id,meta_key,meta_value) VALUES ($term_id,'_second_site_id','$response')");
        }
    }
}

add_action('add_attachment','add_attachment_function');
function add_attachment_function( $post_id) {
    $url = wp_get_attachment_url($post_id);
    $post = [
        'url' => $url,
        'post_id' => $post_id
    ];
    
    $log = $log  = "User: ".$_SERVER['REMOTE_ADDR'].' - '.date("F j, Y, g:i a").PHP_EOL;
    $log.='Attachment ID : '.$post_id.PHP_EOL;
    $log.='Term Data : '.json_encode($post).PHP_EOL;
    file_put_contents(WP_PLUGIN_DIR .'/g4s_multisite_product_sync/logs/log_'.date("j-n-Y").'.log', $log, FILE_APPEND);

    $url = get_option('_second_site_url')."/wp-json/wc/v3/api_file_upload?";
    $consumer_key = get_option('_second_site_consumer_key');
    $consumer_secret = get_option('_second_site_consumer_secret');
    $url = $url.'consumer_key='.$consumer_key."&consumer_secret=".$consumer_secret;
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => $post,
        CURLOPT_HTTPHEADER => array(
            "cache-control: no-cache",
            "postman-token: bd84855a-0bbe-92b1-0d90-0d9370477c33"
        ),
    ));
    
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    if ($err) {
        $myJSON = json_encode($err);
    } else {
        $myJSON = json_encode($response);
    }  
}
?>
