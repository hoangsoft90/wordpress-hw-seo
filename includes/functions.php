<?php
/**
 * Created by PhpStorm.
 * User: Hoang
 * Date: 1/3/16
 * Time: 10:12 AM
 */
if(!function_exists('get_avatar_url')):
function get_avatar_url($get_avatar){
    preg_match("/src='(.*?)'/i", $get_avatar, $matches);
    return $matches[1];
}
endif;

/**
 * @param string $content
 */
if(! function_exists('hwseo_catch_that_image')):
function hwseo_catch_that_image($content='', $default= '') {
    global $post;
    if(!$content) $content = $post->post_content;
    if ( preg_match_all( '/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $content, $matches ) ) {
        $first_img = $matches[1][0];
    } else {
        $first_img = false;
    }
    return $first_img? $first_img : $default;
}
endif;
/**
 * @param $tab
 */
function hwseo_load_tab($tab) {
    if(file_exists(HW_SEO_DIR. '/admin/tabs/'.$tab. '.php'))
    include_once (HW_SEO_DIR. '/admin/tabs/'.$tab. '.php');
}

/**
 * @param $tab
 * @return mixed
 */
function hwseo_get_tab($tab) {
    return HWSEO_Setting_Tab::get_tab($tab) ;
}

/**
 * @param $name
 * @param string $default
 * @return mixed|null|void
 */
function hwseo_option($name='', $default= '') {
    if($name) return AdminPageFramework::getOption( 'HWSEO_Settings_page', $name, $default );
    else return AdminPageFramework::getOption( 'HWSEO_Settings_page');
}
function hwseo_get_current_user() {
    global $wp_query;
    if(is_author()) return $wp_query->get_queried_object();
}