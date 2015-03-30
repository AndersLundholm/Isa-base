<?php 
/**
 * This is a Isa pagecontroller.
 *
 */
// Include the essential config-file which also creates the $isa variable with its defaults.
include(__DIR__.'/config.php'); 



// Content
$isa['title'] = "Hem";
$isa['main'] = <<<EOD
<div class="section blue">
    <article class="content">
        <h1>Hello World!</h1>
        <p>This is the Isa framework.</p>
    </article>
</div>
EOD;
 
 
// Rendering phase of Isa.
include(ISA_THEME_PATH);