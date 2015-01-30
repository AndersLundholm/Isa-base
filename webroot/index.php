<?php 
/**
 * This is a Isa pagecontroller.
 *
 */
// Include the essential config-file which also creates the $isa variable with its defaults.
include(__DIR__.'/config.php'); 
 
 
// Do it and store it all in variables in the Isa container.
$isa['title'] = "ME";

$isa['main'] = <<<EOD
<div class="section blue">
    <article class="content">
		<h1>Hej Världen</h1>
		<p>Detta är en exempelsida som visar hur Isa ser ut och fungerar.</p>
    </article>
</div>
EOD;
 
 
// Finally, leave it all to the rendering phase of Isa.
include(ISA_THEME_PATH);