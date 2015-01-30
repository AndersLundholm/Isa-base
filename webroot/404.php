<?php 
/**
 * This is a Isa pagecontroller.
 *
 */
// Include the essential config-file which also creates the $isa variable with its defaults.
include(__DIR__.'/config.php'); 
 
 
// Do it and store it all in variables in the Isa container.
$isa['title'] = "404";
$isa['header'] = "";
$isa['main'] = "This is a Isa 404. Document is not here.";
$isa['footer'] = "";
 
// Send the 404 header 
header("HTTP/1.0 404 Not Found");
 
 
// Finally, leave it all to the rendering phase of Isa.
include(ISA_THEME_PATH);