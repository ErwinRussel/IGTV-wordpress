<?php
/**
 * Plugin Name: Wordpress IGTV
 * Plugin URI: https://www.erwinrussel.com
 * Description: Display IGTV in a carrousel from a public profile
 * Version: 0.1
 * Text Domain: IGTV-wordpress
 * Author: Erwin Russel
 * Author URI: https://www.erwinrussel.com
 */

add_action('admin_menu', 'test_plugin_setup_menu');
 
add_action('admin_menu', 'my_cool_plugin_create_menu');

function register_my_cool_plugin_settings() {
    register_setting( 'handle', 'new_option_name' );
}

function test_plugin_setup_menu(){
        add_menu_page( 'Test Plugin Page', 'IGTV Plugin', 'manage_options', 'test-plugin', 'test_init' );
}

function test_init(){
    global $instagram_handle;
    $instagram_handle = 'erwinruss';
    ?>
    <div class="wrap">
    <h1>IGTV Plugin</h1>
    <form action="options.php" method="post">
    <?php
    settings_fields( 'myoption-group' );
    do_settings_sections( 'myoption-group' );
    ?>
    Instagram Handle: <input type="text" name="handle"><br>
    <?php submit_button(); ?>
    </form>
    </div>
    <?php
    if(isset($_POST['handle'])) {
        // Check that sets is numeric, and above 0
        if(is_numeric($_POST['handle']) && $_POST['handle'] > 0) {
            $instagram_handle = $_POST['handle'];
        }
    }
}
add_action( 'parse_query', 'wtnerd_global_vars' );

function tbare_wordpress_plugin_demo($atts) {	 
    return get_option('handle');
}

add_action('wp_enqueue_scripts', 'callback_for_setting_up_scripts');
// function callback_for_setting_up_scripts() {
//     wp_register_style( 'namespace', 'style.css' );
//     wp_enqueue_style( 'namespace' );
//     wp_enqueue_script( 'namespaceformyscript', 'script.js', array( 'jquery' ) );
// }

function request_check($atts){
    echo "<section class=\"regular slider\">";

    $url = 'https://www.instagram.com/thefirm.official/?__a=1';
    $contents = file_get_contents($url);

    // Get INSTATV Shortcode 
    if($contents !== false){
        $resJSON = json_decode($contents);

        $first = true;

        $IGTVobjects = $resJSON->graphql->user->edge_felix_video_timeline->edges;

        foreach ($IGTVobjects as &$vid){
            $poster = $vid->node->thumbnail_resources[4]->src;

            // $src = $vid->node->display_url;

            $IGTVshortcode = $vid->node->shortcode;

            $IGTVcontent = file_get_contents("https://www.instagram.com/tv/" . $IGTVshortcode . "/?__a=1");

            if($IGTVcontent !== false){
                $resJSON = json_decode($IGTVcontent);

                $src = $resJSON->graphql->shortcode_media->video_url;

                echo "<div class=\"item";
                if($first){
                    echo " active";
                    $first = false;
                }
                echo "<div class=\"video-wrapper\">";
                echo "<video class=\"img-responsive\" controls=\"\" 
                controlslist=\"nodownload\" 
                playsinline=\"\" 
                style=\"width:100%; height:auto\"
                poster=\"". $poster ."\" preload=\"metadata\" type=\"video/mp4\" src=\"". $src ."\" 
                loop=\"\"></video>";
                echo "</div>";
            }
        }
    }
    
    echo "</section>";
}

add_shortcode('IGTV-wordpress', 'request_check');
?>