<?php
/**
 * Created by PhpStorm.
 * User: Hoang
 * Date: 1/3/16
 * Time: 12:10 PM
 */
/**
 * Class HW_SEO_LocalBusiness
 */
class HW_SEO_LocalBusiness extends HWSEO_Setting_Tab {
    /**
     * setting group
     */
    const SETTINGS_GROUP = 'localbusiness';
    /**
     * class instance
     * @var
     */
    public  static $instance;

    function __construct() {

    }

    /**
     * fields definition
     * @return array|mixed
     */
    public function get_fields_definition() {
        return array(
            array(
                'field_id' => 'streetAddress',
                'type' => 'text',
                'title' => 'streetAddress',
                'description' => ''
            ),
            array(
                'field_id' => 'addressLocality',
                'type' => 'text',
                'title' => 'addressLocality',
                'description' => ''
            ),
            array(
                'field_id' => 'addressRegion',
                'type' => 'text',
                'title' => 'addressRegion',
                'description' => ''
            ),
            array(
                'field_id' => 'postalCode',
                'type' => 'text',
                'title' => 'postalCode',
                'description' => ''
            ),
            array(
                'field_id' => 'addressCountry',
                'type' => 'text',
                'title' => 'addressCountry',
                'description' => ''
            ),

        );
    }
    /**
     * @param $oAdminPage
     * @return mixed|void
     */
    public static function replyToAddFormElements($oAdminPage) {
        $setting = self::$setting;
        $tab = $setting->get_tabs(__CLASS__);
        $oAdminPage->addSettingField($setting::PAGE_SLUG); //group
        $oAdminPage->addSettingSections($setting::PAGE_SLUG ,
            array(
                'section_id' => self::SETTINGS_GROUP ,
                'title' => $tab['title'],
                'description' => $tab['description'],
                'section_tab_slug' => 'setting_tabs',
                'repeatable'  => false,
            ));
        //set group for just one field
        $oAdminPage->addSettingFields(
            self::SETTINGS_GROUP);
        self::register_fields($oAdminPage, true);

    }
    public static function tab_info() {
        return array('title'=> 'Localbusiness', 'description' => '');
    }

}
HW_SEO_LocalBusiness::add_setting_tab();