<?php
/**
* Config-file for Isa. Change settings here to affect installation.
*/
 
/**
* Set the error reporting.
*/
error_reporting(-1);              // Report all type of errors
ini_set('display_errors', 1);     // Display all errors 
ini_set('output_buffering', 0);   // Do not buffer outputs, write directly
 

/**
* Define Isa paths.
*/
define('ISA_INSTALL_PATH', __DIR__ . '/..');
define('ISA_THEME_PATH', ISA_INSTALL_PATH . '/theme/render.php');
 
 
/**
* Include bootstrapping functions.
*/
include(ISA_INSTALL_PATH . '/src/bootstrap.php');

/**
* Define database username and password
*/
define('DB_USER', 'database_username_here');
define('DB_PASSWORD', 'database_password_here');


/**
 * Start the session if $startSession is not set to false.
 */
$startSession = isset($startSession) ? $startSession : true;
if($startSession) {
    session_name(preg_replace('/[^a-z\d]/i', '', __DIR__));
    session_start();
} 

/**
 * Get login status.
 */
$login_status = get_login_status();

/**
* Create the Isa variable.
*/
$isa = array();
 
/**
* Site wide settings.
*/
$isa['lang']         = 'en';
$isa['title_append'] = ' | Isa framework';

$isa['header'] = <<<EOD

<div class="header">
    <div class="logo">
    <a href="index.php" title="Home"><img class="siteLogo" src="img/isa.png" alt="Isa Logo"></a>
	</div>
    <div class="title">
        <span class="siteTitle">Isa framework</span>
    	<span class="siteSlogan">A boilerplate for smaller websites or webbapplications using PHP.</span>
    </div>
</div>\n
EOD;

$isa['footer'] = <<<EOD
<div class="innerFooter">
    <div class="footerField">
        <h2>Website</h2>
        <ul>
            <li><a href="http://validator.w3.org/unicorn/check?ucn_uri=referer&amp;ucn_task=conformance" title="Unicorn" target="_blank">Unicorn</a></li>
            <li>&nbsp;</li>
            <li>Copyright &copy; <a href="mailto:anders.andlu.se">Anders Lundholm</a></li>
        </ul>
    </div>
    <div class="footerField">
        <h2>Contact</h2>
        <ul>
            <li><a href="https://github.com/anderslundholm" title="Github" target="_blank">GitHub</a></li>
        </ul>
    </div>
</div>\n
EOD;

/**
* Navigation bar.
*/
$menu = array(
    'home'  => array('text'=>'Home', 'title'=>'Home', 'url'=>'index.php'),
    'example'  => array('text'=>'Example', 'title'=>'Example', 'url'=>'#')
);

/**
* Theme related settings.
*/
$isa['stylesheets'] = array('css/style.css');
$isa['stylesheets'][] = 'http://fonts.googleapis.com/css?family=Open+Sans:300italic,700italic,300,700';
$isa['favicon']    = 'img/favicon.png';

/**
* Settings for the database.
*/
$isa['database']['dsn']            = 'mysql:host=localhost;dbname=database_name_here;';
$isa['database']['username']       = DB_USER;
$isa['database']['password']       = DB_PASSWORD;
$isa['database']['driver_options'] = array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'");