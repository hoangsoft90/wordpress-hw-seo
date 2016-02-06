<?php
/**
 * Created by PhpStorm.
 * User: Hoang
 * Date: 1/3/16
 * Time: 11:57 AM
 */
//user setting fields
include (HW_SEO_DIR. '/admin/admin-user.php');
include_once (HW_SEO_DIR. '/classes/admin-tabs.php');
/**
 * Class HWSEO_Settings_page
 */
if(class_exists('AdminPageFramework')):
class HWSEO_Settings_page extends AdminPageFramework{
    /**
     * page slug
     */
    const PAGE_SLUG = 'hwseo_settings';

    /**
     * return valid field name from string
     * @param $str:
     * @return mixed
     */
    static public function valid_tab_slug($str){
        if(is_string($str)) $str = preg_replace('#[\s@\#\$\!%\^\&\*\(\)\-\+\[\]\=\~]#','_',$str);
        //$str = preg_replace('#-{2,}#','_',$str);
        return $str;
    }
    function prepare_actions() {

    }

    /**
     * initializing data
     */
    function init() {
        hwseo_load_tab('localbusiness');
    }
    /**
     * tabs data
     * @return array
     */
    function get_tabs($tab='') {
        return $tab? (isset(HWSEO_Setting_Tab::$tabs[$tab])? HWSEO_Setting_Tab::$tabs[$tab]: null) : HWSEO_Setting_Tab::$tabs;
    }
    /**
     * setup form fields
     */
    public function setUp() {
        $this->init();

        //prepare hooks
        $this->prepare_actions();
        // Set the root menu
        $this->setRootMenuPage( 'Settings');
        // Add the sub menus and the pages
        $this->addSubMenuItems(
            array(
                'title'    =>    'HW SEO',        // the page and menu title
                'page_slug'    =>    self::PAGE_SLUG         // the page slug
            )
        );
        //get tabs
        foreach($this->get_tabs() as $slug => $tab) {
            $this->addInPageTabs(self::PAGE_SLUG,  array(
                'tab_slug' => $slug,
                'title' => $tab['title'],
                'description' => $tab['description']
            ));
            if(isset($tab['init']) && is_callable($tab['init']) ) {
                call_user_func($tab['init'],  $slug, $tab, $this); //init tab
            }
            //add callback for tab content
            // load + page slug + tab slug
            elseif(isset ($tab['callback']) && is_callable($tab['callback']) ) {
                add_action( 'load_' . self::PAGE_SLUG . '_' . self::valid_tab_slug($slug), $tab['callback'] );
            }
            //internal callback
            elseif( method_exists($this, 'replyToAddFormElements_tab_'.$slug )) {
                add_action( 'load_' . self::PAGE_SLUG . '_' . self::valid_tab_slug($slug), array( $this, 'replyToAddFormElements_tab_'.$slug ) );
            }
            #add_action( 'load_' . self::PAGE_SLUG , array( $this, 'replyToAddFormElements') ,10,2);

        }
        $this->setInPageTabTag( 'h2' );
    }
    /**
     * Methods for Hooks, echo page content rule: do_{page slug} and it will be automatically gets called.
     */
    public function do_hwseo_settings() {
        // Show the saved option value.
        //$taxonomies_template = hw_get_setting(array(APF_Page_Templates::SETTINGS_GROUP,'taxonomies_template'));
        // The extended class name is used as the option key. This can be changed by passing a custom string to the constructor.
        /*echo '<h3>Saved Values</h3>';
        echo '<h3>Show as an Array</h4>';
        echo $this->oDebug->getArray( get_option( 'APF_CreateForm' ) );
        echo '<h3>Retrieve individual field values</h4>';
        echo '<pre>APF_CreateForm[my_first_section][my_text_field][0]: ' . AdminPageFramework::getOption( 'APF_CreateForm', array( 'my_first_section', 'my_text_field', 0 ), 'default' ) . '</pre>';
        echo '<pre>APF_CreateForm[my_second_section][my_dropdown_list]: ' . AdminPageFramework::getOption( 'APF_CreateForm', array( 'my_second_section', 'my_dropdown_list' ), 'default' ) . '</pre>';
        */
    }
    /**
     * The pre-defined validation callback method.
     *
     * Notice that the method name is validation_{instantiated class name}_{field id}. You can't print out inside callback but stored in session variale instead
     *
     * @param    string|array    $sInput        The submitted field value.
     * @param    string|array    $sOldInput    The old input value of the field.
     */
    public function validation_HWSEO_Settings_page( $sInput, $sOldInput ) {
        return $sInput;
    }
    /**
     * Validates the submitted form data.
     *
     * Alternatively you may use validation_{instantiated class name} method.
     */
    public function validate( $aSubmit, $aStored, $oAdminWidget ) {

        // Uncomment the following line to check the submitted value.
        // AdminPageFramework_Debug::log( $aSubmit );

        return $aSubmit;

    }

}
endif;
if(is_admin()) new HWSEO_Settings_page();