<?php
/**
 * Bootstrapping functions, essential and needed for Isa to work together with some common helpers. 
 *
 */
 
/**
 * Default exception handler.
 *
 */
function myExceptionHandler($exception) {
    echo "Isa: Uncaught exception: <p>" . $exception->getMessage() . "</p><pre>" . $exception->getTraceAsString(), "</pre>";
}
set_exception_handler('myExceptionHandler');
 
 
/**
 * Autoloader for classes.
 *
 */
function myAutoloader($class) {
    $path = ISA_INSTALL_PATH . "/src/{$class}/{$class}.php";
    if(is_file($path)) {
        include($path);
    } else {
        throw new Exception("Classfile '{$class}' does not exists.");
    }
}
spl_autoload_register('myAutoloader');


/**
* Dumps the data in the passed array and convert all applicable characters 
* to HTML entities.
*/
function dump($array) {
    echo "<pre>" . htmlentities(print_r($array, 1)) . "</pre>";
}


/**
* @return the current URL
*/
function getCurrentUrl() {
    $url = "http";
    $url .= (@$_SERVER["HTTPS"] == "on") ? 's' : '';
    $url .= "://";
    $serverPort = ($_SERVER["SERVER_PORT"] == "80") ? '' :
        (($_SERVER["SERVER_PORT"] == 443 && @$_SERVER["HTTPS"] == "on") ? '' :
            ":{$_SERVER['SERVER_PORT']}");
    $url .= $_SERVER["SERVER_NAME"] . $serverPort . htmlspecialchars(
        $_SERVER["REQUEST_URI"]);
    return $url;
}


/**
 * Get the login status of the user in the form of a descriptive string if the
 * user is logged in or not, and login/logout page.
 * @return array with login status.
 */
function get_login_status() {
    $login_status = array();
    if(isset($_SESSION['user'])) {
        $login_status['authenticated'] = true;
        $login_status['str'] = "Log out";
        $login_status['page'] = "logout.php";
    } else {
        $login_status['authenticated'] = false;
        $login_status['str'] = "Log in";
        $login_status['page'] = "login.php";
    }
    return $login_status;
}