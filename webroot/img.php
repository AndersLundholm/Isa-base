<?php 
/**
 * This is a Isa pagecontroller.
 *
 */

//Disable starting a new session to be able to cache images.
$startSession = false;
// Include the essential config-file which also creates the $isa variable with its defaults.
include(__DIR__.'/config.php'); 

// Ensure error reporting is on
error_reporting(-1);              // Report all type of errors
ini_set('display_errors', 1);     // Display all errors 
ini_set('output_buffering', 0);   // Do not buffer outputs, write directly

//Set the directory where img and cache folders are located.
$dir = __DIR__;

// Create the image object.
$image = new CImage($dir);