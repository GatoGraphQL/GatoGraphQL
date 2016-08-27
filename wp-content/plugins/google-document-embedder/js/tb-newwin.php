<?php

// access wp functions externally (allow translation)
require_once('../libs/lib-bootstrap.php');

if ( isset( $_GET['a'] ) && $_GET['a'] == "fs" ) {
	$tip = __('View in full screen', 'google-document-embedder');
	$func = "gdeFullScreen";
	$ico = "openFullScreenButtonIcon";
} else {
	$tip = __('Open in new window', 'google-document-embedder');
	$func = "gdeNewWin";
	$ico = "openInViewerButtonIcon";
}

if ( isset( $_GET['url'] ) ) {
	$url = $_GET['url'];
	if ( isset( $_GET['hl'] ) ) {
		$lang = $_GET['hl'];
	} else {
		$lang = "";
	}
} elseif ( isset( $_GET['p'] ) ) {
	$allowPrint = true;
}

header("Content-type: text/javascript");

?>
/* override open in new window behavior */

if (top === self) {
	// don't show button at all if this is already full screen
	var dv = document.getElementById('controlbarOpenInViewerButton');
	dv.style.display = "none";
} else {
	// replace new window function

	// get current button and title
	var dv2 = document.getElementById('controlbarOpenInViewerButton');
	var btntext = dv2.getAttribute("title");
	
	// recreate button (change icon, tip, and function)
	var dv1 = document.createElement("div");
	dv1.setAttribute("id", "gdeControlbarOpenInViewerButton");
	dv1.setAttribute("class", "goog-inline-block goog-toolbar-button-outer-box");
	dv1.setAttribute("onclick", "<?php echo $func; ?>();");
	
<?php
	// use WP language (not Google) for tooltip if full screen - this string doesn't exist in Google version
	if ( isset( $_GET['a'] ) && $_GET['a'] == "fs" ) {
?>
	dv1.setAttribute("title", "<?php echo $tip; ?>");
<?php
	} else {
	// use Google language version for new window, instead of WP language
?>
	dv1.setAttribute("title", btntext);
<?php
	}
?>
	
	dv1.innerHTML = '<div class="goog-inline-block goog-toolbar-button-inner-box"><div id="<?php echo $ico; ?>" class="toolbar-button-icon"></div></div>';
	
	// replace the button
	var parentDiv = dv2.parentNode;
	parentDiv.replaceChild(dv1, dv2);
}

function gdeFullScreen() {
	// open
	var thisIFrame = gdeGetUrl();
	
	parent.window.location = thisIFrame;
}

function gdeNewWin() {
	// open
	var thisIFrame = gdeGetUrl();
	
	window.open(thisIFrame, '_blank');
}

function gdeGetUrl() {
	var thisIFrame = document.URL;
	
<?php
	if ( isset( $allowPrint ) ) {
		// load the stripped chrome viewer (with print button)
?>
	thisIFrame = thisIFrame.replace("embedded=true", "chrome=true");
<?php
	} elseif ( isset ( $url ) ) {
		// use Google page, but allow opening in same window
?>
	thisIFrame = "https://docs.google.com/viewer?url=" + "<?php echo $url; ?>&chrome=true&hl=<?php echo $lang; ?>";
<?php
	}
?>
	return thisIFrame;
}
