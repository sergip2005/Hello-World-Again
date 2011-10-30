<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
function fb() {
    $obj = &get_instance();
    $args = func_get_args();
    return call_user_func_array(array($obj->firephp, 'fb'), $args);
    return true;
}
?>