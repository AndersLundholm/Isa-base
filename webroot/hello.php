<?php 
/**
 * This is a Isa pagecontroller.
 *
 */
// Include the essential config-file which also creates the $isa variable with its defaults.
include(__DIR__.'/config.php'); 
 
 
// Do it and store it all in variables in the Isa container.
$isa['title'] = "Hello World";
 
$isa['header'] = <<<EOD
<img class='sitelogo' src='img/isa.png' alt='Isa Logo'/>
<span class='sitetitle'>Isa webbtemplate</span>
<span class='siteslogan'>Återanvändbara moduler för webbutveckling med PHP</span>
EOD;
 
$isa['main'] = <<<EOD
<h1>Hej Världen</h1>
<p>Detta är en exempelsida som visar hur Isa ser ut och fungerar.</p>
EOD;
 
$isa['footer'] = <<<EOD
<footer><span class='sitefooter'>Copyright (c) Anders Lundholm (anders@andlu.se) | <a href='https://github.com/anderslundholm'>Isa på GitHub</a> | <a href='http://validator.w3.org/unicorn/check?ucn_uri=referer&amp;ucn_task=conformance'>Unicorn</a></span></footer>
EOD;
 
 
// Finally, leave it all to the rendering phase of Isa.
include(ISA_THEME_PATH);