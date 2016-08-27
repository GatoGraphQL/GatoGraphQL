<?php

/**
 * List supported file types of the viewer
 *
 * @since   2.5.0.1
 * @note	Not wrapped in function, for ease of access from javascript (js/dialog.js)
 * @return  Array of supported exts, or JSON object
 */

$types = array(
	// ext		=>	mime_type
	"ai"		=>	"application/postscript",
	"doc"		=>	"application/msword",
	"docx"		=>	"application/vnd.openxmlformats-officedocument.wordprocessingml",
	"dxf"		=>	"application/dxf",
	"eps"		=>	"application/postscript",
	"otf"		=>	"font/opentype",
	"pages"		=>	"application/x-iwork-pages-sffpages",
	"pdf"		=>	"application/pdf",
	"pps"		=>	"application/vnd.ms-powerpoint",
	"ppt"		=>	"application/vnd.ms-powerpoint",
	"pptx"		=>	"application/vnd.openxmlformats-officedocument.presentationml",
	"ps"		=>	"application/postscript",
	"psd"		=>	"image/photoshop",
	"rar"		=>	"application/rar",
	"svg"		=>	"image/svg+xml",
	"tif"		=>	"image/tiff",
	"tiff"		=>	"image/tiff",
	"ttf"		=>	"application/x-font-ttf",
	"xls"		=>	"application/vnd.ms-excel",
	"xlsx"		=>	"application/vnd.openxmlformats-officedocument.spreadsheetml",
	"xps"		=>	"application/vnd.ms-xpsdocument",
	"zip"		=>	"application/zip"
);

if ( ! isset( $no_output ) ) {
	$json = json_encode( $types );
	header("Content-type: application/json");
	echo $json;
}

?>
