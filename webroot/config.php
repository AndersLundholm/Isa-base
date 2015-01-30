<?php
/**
 * Config-file for Isa. Change settings here to affect installation.
 *
 */
 
/**
 * Set the error reporting.
 *
 */
error_reporting(-1);              // Report all type of errors
ini_set('display_errors', 1);     // Display all errors 
ini_set('output_buffering', 0);   // Do not buffer outputs, write directly
 
 
/**
 * Define Isa paths.
 *
 */
define('ISA_INSTALL_PATH', __DIR__ . '/..');
define('ISA_THEME_PATH', ISA_INSTALL_PATH . '/theme/render.php');
 
 
/**
 * Include bootstrapping functions.
 *
 */
include(ISA_INSTALL_PATH . '/src/bootstrap.php');
 
 
/**
 * Start the session.
 *
 */
session_name(preg_replace('/[^a-z\d]/i', '', __DIR__));
session_start();
 
 
/**
 * Create the Isa variable.
 *
 */
$isa = array();
 
 
/**
 * Site wide settings.
 *
 */
$isa['lang']         = 'sv';
$isa['title_append'] = ' | Anders Lundholm';

$isa['header'] = <<<EOD

<div class="header">
    <div class="logo">
    <img class="siteLogo" src="img/isa.png" alt="Isa Logo">
	</div>
    <div class="title">
        <span class="siteTitle">Me - Anders Lundholm</span>
    	<span class="siteSlogan">Min Me-sida i kursen Databaser och Objektorienterad PHP-programmering.</span>
    </div>
</div>\n
EOD;

$isa['footer'] = <<<EOD
<div class="innerFooter">
    <div class="footerField">
        <h2>Webbplatsen</h2>
        <ul>
            <li><a href="http://validator.w3.org/unicorn/check?ucn_uri=referer&ucn_task=conformance" title="Unicorn" target="_blank">Unicorn</a></li>
            <li>&nbsp;</li>
            <li>Copyright &copy; <a href="mailto:flaskhalz@gmail.com">Anders Lundholm</a></li>
        </ul>
    </div>
    <div class="footerField">
        <h2>Kontakt</h2>
        <ul>
            <li><a href="#" title="Github" target="_blank">GitHub</a></li>
            <li><a href="#" title="Twitter" target="_blank">Twitter</a></li>
            <li><a href="#" title="LinkedIn" target="_blank">Linked In</a></li>

        </ul>
    </div>
</div>\n
EOD;


/**
 * Navigation bar.
 *
 */
$menu = array(
    'home'  => array('text'=>'Hem', 'title'=>'Hem', 'url'=>'index.php'),
    'report'  => array('text'=>'Redovisning', 'title'=>'Redovisning', 'url'=>'report.php', 'submenu'=> array(
    'kmom01' => array('text'=>'Kmom01', 'title'=>'Kmom01', 'url'=>'report.php#kmom01'),
    'kmom02' => array('text'=>'Kmom02', 'title'=>'Kmom02', 'url'=>'report.php#kmom02'),
    'kmom03' => array('text'=>'Kmom03', 'title'=>'Kmom03', 'url'=>'report.php#kmom03'),
    )),
    'source' => array('text'=>'Källkod', 'title'=>'Källkod', 'url'=>'source.php'),
);


/**
 * Theme related settings.
 *
 */
$isa['stylesheets'] = array('css/style.css');
$isa['stylesheets'][] = 'http://fonts.googleapis.com/css?family=Open+Sans:300italic,700italic,300,700';
$isa['favicon']    = 'img/favicon.png';