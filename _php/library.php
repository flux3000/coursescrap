<?php

// This is a place for assorted PHP functions.

//phpinfo(INFO_VARIABLES);

/**
 * Send debug code to the Javascript console
 */ 
function debug_to_console($data) {
    if(is_array($data) || is_object($data))
	{
		echo("<script>console.log('PHP: ".json_encode($data)."');</script>");
	} else {
		echo("<script>console.log('PHP: ".$data."');</script>");
	}
}
?>