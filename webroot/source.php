<?php 
/**
 * This is a Isa pagecontroller.
 *
 */
// Include the essential config-file which also creates the $isa variable with its defaults.
include(__DIR__.'/config.php'); 
 

// Add style for csource
$isa['stylesheets'][] = 'css/source.css';
 
// Create the object to display sourcecode
$source = new CSource(array('secure_dir' => '..', 'base_dir' => '..'));

// Do it and store it all in variables in the Isa container.
$isa['title'] = "Visa källkod";

$isa['main'] = <<<EOD
<div class="section blue">
    <article class="content">
		<h1>Visa källkod</h1>
    </article>
</div>
EOD;

$isa['main'] .= $source->View();
 
// Finally, leave it all to the rendering phase of Isa.
include(ISA_THEME_PATH);