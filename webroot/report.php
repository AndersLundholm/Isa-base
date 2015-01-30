<?php 
/**
 * This is a Isa pagecontroller.
 *
 */
// Include the essential config-file which also creates the $isa variable with its defaults.
include(__DIR__.'/config.php'); 
 
 
// Do it and store it all in variables in the Isa container.
$isa['title'] = "Redovisning";
 
$isa['main'] = <<<EOD
<div class="section blue">
    <article class="content">
    	<h1>Redovisning</h1>
		<p>Här redovisas uppgifterna.</p>
    </article>
</div>
<div class="section white">
	<span id="kmom01" class="anchor blue"></span>
    <article class="content">
		<h2>Kmom01</h2>
		<p>Detta är en exempelsida som visar hur Isa ser ut och fungerar.</p>
    </article>
</div>
<div class="section blue">
    <span id="kmom02" class="anchor white"></span>
    <article class="content">
		<h2>Kmom02</h2>
    </article>
</div>
<div class="section white">
	<span id="kmom03" class="anchor blue"></span>
    <article class="content">
		<h2>Kmom03</h2>
    </article>
</div>
<div class="section blue">
    <span id="kmom04" class="anchor white"></span>
    <article class="content">
		<h2>Kmom04</h2>
    </article>
</div>
<div class="section white">
	<span id="kmom05" class="anchor blue"></span>
    <article class="content">
		<h2>Kmom05</h2>
    </article>
</div>
<div class="section blue">
    <span id="kmom06" class="anchor white"></span>
    <article class="content">
		<h2>Kmom06</h2>
    </article>
</div>
<div class="section white">
	<span id="kmom07" class="anchor blue"></span>
    <article class="content">
		<h2>Kmom07</h2>
    </article>
</div>
<div class="section blue">
    <span id="kmom08" class="anchor white"></span>
    <article class="content">
		<h2>Kmom08</h2>
    </article>
</div>
<div class="section white">
	<span id="kmom09" class="anchor blue"></span>
    <article class="content">
		<h2>Kmom09</h2>
    </article>
</div>
<div class="section blue">
    <span id="kmom10" class="anchor white"></span>
    <article class="content">
		<h2>Kmom10</h2>
    </article>
</div>
EOD;
 
// Finally, leave it all to the rendering phase of Isa.
include(ISA_THEME_PATH);