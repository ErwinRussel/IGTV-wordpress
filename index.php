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

// ADMIN PANEL 
// REGISTER PANEL
function igtv_plugin_setup_menu(){
    add_menu_page( 'IGTV Plugin Page', 'IGTV Plugin', 'manage_options', 'igtv-plugin', 'settings_panel' );
}

add_action('admin_menu', 'igtv_plugin_setup_menu');
// REGISTER SETTINGS
function plugin_register_settings() {
    add_option( 'igtv-handle', 'Handle for IGTV page');
    register_setting( 'igtv_plugin_options_group', 'igtv-handle', 'igtv_plugin_callback' );
 }
add_action( 'admin_init', 'plugin_register_settings' );

// ADMIN PANEL VIEW
function settings_panel(){
    ?>
    <div class="wrap">
    <h1>IGTV Plugin</h1>
    <h4>Add the shortcode "[IGTV-wordpress]" to a page.</h4>
    <form action="options.php" method="post">
    <?php
    settings_fields( 'igtv_plugin_options_group' );
    // do_settings_sections( 'igtv_plugin_options_group' );
    ?>
    Instagram handle: <input type="text" id="igtv-handle" name="igtv-handle" value="<?php echo get_option('igtv-handle'); ?>"><br>
    <?php submit_button(); ?>
    </form>
    </div>
    <?php
}

add_action( 'parse_query', 'wtnerd_global_vars' );

// PLUGIN 

// LOAD SCRIPTS AND STYLES

add_action('wp_enqueue_scripts', 'callback_for_setting_up_scripts');

function callback_for_setting_up_scripts() {
    wp_enqueue_style( 'slick-css', plugin_dir_url( __FILE__ ) . '/slick/slick/slick.css');
    wp_enqueue_style( 'slick-css-theme', plugin_dir_url( __FILE__ ) . '/slick/slick/slick-theme.css', 'slick-css', true );
    wp_enqueue_style( 'IGTVstyle', plugin_dir_url( __FILE__ ) . '/IGTVstyle.css', array('slick-css','slick-css-theme'), true );
    wp_enqueue_script( 'bootstrap-js', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js', array('jquery'), '3.4.1', true );
    wp_enqueue_script( 'slickjs', plugin_dir_url( __FILE__ ) . '/slick/slick/slick.js', array ( 'jquery' ), false ,true);
    wp_enqueue_script('IGTVscript', plugin_dir_url( __FILE__ ) .'/IGTVscript.js', array ( 'jquery' ), false ,true);
}

// IGTV REQUEST

function request_check($atts){
    $output = "";

    $output .= "<div class=\"igtv-plugin\">";
    $output .= "<section class=\"regular slider\">";

    $url = "https://www.instagram.com/" . get_option('igtv-handle') . "/?__a=1";
    // $url = 'https://www.instagram.com/thefirm.official/?__a=1';
    $contents = file_get_contents($url);
    $amountvid = 0;

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

                $output .= "<div class=\"item slick-tile";
                if($first){
                    $output .= " active";
                    $first = false;
                }
                $output .= "\">";
                $output .= "<div class=\"video-wrapper\">";
                $output .= "<video class=\"img-responsive\" controls=\"\" 
                controlslist=\"nodownload\" 
                playsinline=\"\" 
                style=\"width:100%; height:auto\"
                poster=\"". $poster ."\" preload=\"metadata\" type=\"video/mp4\" src=\"". $src ."\" 
                loop=\"\"></video>";
                $output .= "</div>";
                $output .= "</div>";
            }

        }
    }

    if(sizeof($IGTVobjects)<6){
        $loop = sizeof($IGTVobjects);
        for($x = 0; $x < 7 - $loop; $x++){
            $output .= "<div class=\"slick-tile\" style=\"border: 2px solid #282828;\"><img></div>";
        }
    }
    
    $output .= "</section>";
    $output .= "</div>";

    return $output;
}

// SHORTCODE
add_shortcode('IGTV-wordpress', 'request_check');

?>