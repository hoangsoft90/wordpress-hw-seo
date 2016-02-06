<?php
/*
Plugin Name: HW SEO
Plugin URI: http://hoangweb.com
Description: optimize SEO on page
Author: Hoangweb.COM
*/
define('HW_SEO_URL', plugins_url('', __FILE__));
define ('HW_SEO_DIR', plugin_dir_path(__FILE__ )) ;
/**
 * Admin page framework
 */
if(!class_exists('AdminPageFramework_Registry')) {
    include_once ('libs/admin-page-framework.min.php');
}

include_once ('includes/functions.php');
/**
 * classes
 */
include_once ('classes/seo-class.php');
include_once ('includes/json-ld.php');

include_once ('admin/settings.php');

/**
 * Class HW_SEO
 */
class HW_SEO extends HW_SEO_Core{
    /**
     * @var
     */
    public static $instance;
    /**
     * @var
     */
    var $json_ld;

    public function __construct() {
        $this->json_ld =  HW_SEO_JsonLD::get_instance();

        $this->setup_hooks();
        $this->init();
    }


    /**
     * localbusiness render seo on page (draft)
     */
    function seo_localbusiness() {#__print(HW_SEO_LocalBusiness::get_options('streetAddress'));
        //hwseo_get_tab('localbusiness');

    }

    function setup_hooks() {
        #add_action('init', array($this, '_init'));
        add_action('wp_head', array($this, '_print_head'));
        add_action('wp_footer', array($this, '_print_footer'));
    }

    /**
     * init
     */
    function init() {
        hwseo_load_tab('localbusiness');

        $this->json_ld->add('hwseo_ldjson_article');
        $this->json_ld->add('hwseo_ldjson_author');
        $this->json_ld->add('hwseo_ldjson_localBusiness' );  //array(hwseo_get_tab('localbusiness'), 'seo_localbusiness')
        $this->json_ld->add('hwseo_jsonld_person');
    }
    function _print_footer(){}
    /**
     * @hook wp_head
     */
    function _print_head() {
        $this->json_ld->output();

    }
}
add_action('init', 'HW_SEO::get_instance');