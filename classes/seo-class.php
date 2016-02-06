<?php
/**
 * Created by PhpStorm.
 * User: Hoang
 * Date: 1/3/16
 * Time: 11:03 AM
 */
/**
 * Class HW_SEO_Core
 */
abstract class HW_SEO_Core {
    /**
     * get child class instance
     * @return mixed
     */
    static function get_instance() {
        $class = get_called_class();
        if(!$class::$instance) $class::$instance = new $class();
        return $class::$instance;
    }
}
/**
 * Class HW_SEO_JsonLD
 */
class HW_SEO_JsonLD extends HW_SEO_Core{
    public static $instance;
    /**
     * @var array
     */
    private $hooks = array();
    /**
     * @var array
     */
    private $payload = array();

    function __construct() {
        $this->payload = array(
            // stuff for any page
            "@context" => "http://schema.org/"
        );
    }

    /**
     * @param array $data
     */
    private function render_json($data= array()) {
        return json_encode($data, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT|JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES) ;
    }
    /**
     * add function which hold json+ld data
     * @param $callback
     */
    public function add($callback) {
        if(is_string($callback)) $this->hooks[$callback] = $callback;
        else $this->hooks[] = $callback;
    }
    /**
     * @param array $data
     */
    function output() {
        foreach($this->hooks as $closure) {
            if(!is_callable($closure) && !function_exists($closure)) {
                continue;
            }
            $data = call_user_func($closure) ;
            if(!empty($data)) {
                $data = array_merge($this->payload, $data);
                $data = apply_filters('filter_jsonld_data', $data);
                echo '<script type="application/ld+json">';
                if(is_array($data)) echo $this->render_json($data ) ;
                echo '</script>';
            }
        }
        $extra = apply_filters('extra_jsonld_data', array());
        if(!empty($extra) && is_array($extra)) {
            echo '<script type="application/ld+json">';
            echo $this->render_json($extra ) ;
            echo '</script>';
        }
    }
}
