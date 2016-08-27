=== Google Doc Embedder ===
Contributors: danlester, k3davis
Tags: doc, docx, pdf, ppt, pptx, xls, office, powerpoint, google, document, embed
Author URI: https://wordpress.org/plugins/google-document-embedder/
Requires at least: 3.5
Tested up to: 4.4
Stable tag: trunk
License: GPLv2 or later

Lets you embed PDF, MS Office, and many other file types in a web page using the free Google Docs Viewer (no Flash or PDF browser plug-ins required).

== Description ==

Google Doc Embedder lets you embed several types of files into your WordPress pages using the free Google Docs Viewer - allowing inline viewing (and optional downloading) of a wide range of popular file types, with no Flash or PDF browser plug-ins required.

> <strong>NOTE: Recent changes by Google to their viewer mean that some other plugins may be more suitable in some cases:</strong><br>
> 
> * If you are able to store your files in Google Drive, try the [Google Drive Embedder plugin](http://wp-glogin.com/wpgoogledoctodrive). This will be faster to display, and more reliable.
> * To make it difficult for users to download the full PDF (the old 'enhanced mode'), please find out more about [our new plugin - PDF Embedder Secure](http://wp-pdf.com/secure/?utm_source=GDE%20Readme&utm_medium=wordpressorg&utm_campaign=Freemium).
> * Mobile-friendly functionality for viewing PDFs is available in [PDF Embedder Premium](http://wp-pdf.com/premium/?utm_source=GDE%20Readme%20Premium&utm_medium=wordpressorg&utm_campaign=Freemium).
> 
> If this plugin works as you require then we will continue to support it assuming Google still provides the underlying Doc Viewer in its current form!

= More about Google Doc Embedder =

Similar to services like Scribd, Google Doc Embedder will allow you to embed these files directly into your page or post, not requiring
the user to have Microsoft Word, Adobe Reader, PowerPoint, or other software installed to view the contents. Unlike Scribd, the files do
not need to be uploaded to any service first - including Google Docs - but can exist anywhere publicly accessible on your site or the
internet.

Supported file formats include:

* Adobe Acrobat (PDF)
* Microsoft Word (DOC/DOCX*)
* Microsoft PowerPoint (PPT/PPTX*)
* Microsoft Excel (XLS/XLSX*)
* TIFF Images (TIF, TIFF)
* Apple Pages (PAGES)
* Adobe Illustrator (AI)
* Adobe Photoshop (PSD)
* Autodesk AutoCad (DXF)
* Scalable Vector Graphics (SVG)
* PostScript (EPS/PS)
* OpenType/TrueType Fonts (OTF, TTF)
* XML Paper Specification (XPS)
* Archive Files (ZIP/RAR)

Office XML (2007+) file formats are sometimes problematic with Google Viewer. Please test your documents, and when possible I recommend
you use the Office 2003 equivalent formats instead.

Note: Use of this plug-in implies your agreement with Google's published [Terms of Service](http://docs.google.com/viewer/TOS?hl=en "Terms of Service").

Translations:

* English (en\_US), built-in
* Czech (cs\_CZ) by Jirka, thanks!
* Dutch (nl\_NL) by Niko Strijbol, thanks!
* French (fr\_FR) by [Erwan](http://profiles.wordpress.org/erwanlescop "Erwan"), thanks!
* Hungarian (hu\_HU) by [szemcse](http://profiles.wordpress.org/szemcse "szemcse"), thanks!
* Italian (it\_IT) by [Marco](https://plus.google.com/+MarcoMardegan "Marco"), thanks!
* Russian (ru\_RU) by J&#243;zek, thanks!
* Spanish (es\_ES) by [elarequi](http://elarequi.com/propuestastic/ "elarequi"), thanks!
* Turkish (tr\_TR) by [LettoBlog](http://profiles.wordpress.org/lettoblog "LettoBlog"), thanks!
* Ukrainian (uk) by J&#243;zek, thanks!

== Installation ==

1. Upload the entire `google-document-embedder` folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Done.

Upload the documents to your site using the media upload facility built into WordPress, via FTP, or link to documents on another (public)
site. Use the Media Library or Google Doc Embedder button in the Visual editor to build the appropriate shortcode, or use the documentation.

For basic manual instructions, please see the FAQ.

Go to "GDE Settings" (under "Settings" in the admin panel) to change defaults, or override individually using the shortcode syntax in the FAQ.

== Frequently Asked Questions ==

= Where can the files live? =
The file to embed must first be publicly available somewhere on the internet, in order for Google to retrieve the document for conversion.
You can upload it to your WordPress site using the standard techniques, or link to a file on another site. For testing purposes, your site can run locally or on private networks, but the documents you wish to embed must be publicly accessible.

= How do I embed a file in my page or post? =
There are several ways you can insert a supported document, depending on your preference:

* Manually enter the shortcode (explained below).
* Click the "Add Media" button to upload or select a file from your media library, and ensure the "Link To" setting in Attachment Display Settings
is set to "Media File." Then click "Insert into post."
* Use the Google Doc Embedder button in the Visual editor to insert the `[gview]` shortcode.
* Paste the URL into the HTML editor, select it, and click the "GDE" quicktag button (HTML/Text editor).

To manually insert the `[gview]` shortcode into your page or post to embed the file, use the syntax below (use of the HTML tab in the editor
recommended):

`[gview file="http://url.to/file.pdf"]`

Note: the `file=` attribute (generally pointing to the full URL of the file) is **required**. If the majority of your files are referenced
from the same directory, you can set a File Base URL in GDE Settings and only put the changing portion in the `file=` attribute (or a full
URL for a file outside of that base URL). File Base URL will be prepended to the value of `file=` unless `file=` starts with `http` or `//`
(dynamic protocol selection).

Common optional attributes:

* `profile=` : Enter the number or name of the desired profile for the viewer to use (default profile is used if not specified)
* `width=` : To override the profile's default width of the viewer, enter a new width value - e.g., "400px" or "80%"
* `height=` : To override the profile's default height of the viewer, enter a new height value - e.g., "400px" or "80%"

= What are "Profiles"? =
Profiles allow you to create a unique batch of settings and access them from the viewer using only a profile ID (or name), rather than 
writing a horrifically complicated shortcode. This allows each instance of GDE (even on the same page) to be completely customizable while
keeping the shortcode syntax simple.

= Will it embed files that are password-protected or stored in protected folders/sites? =
Most likely, no. If your file requires a login to view - such as being saved in a password-protected directory, or behind a firewall
(on your intranet, etc.), the viewer will probably not be able to access the file. This is what is meant above, that the document should
be "publicly available." Please save the file in a publicly accessible location for best results.

= What about private documents? =
The file must be publicly available, but there is no reason why you need to publish the location beyond supplying it to the GDE plugin. 
However, savvy users will be able to locate the URL and download the original file. 
To make it difficult for users to download PDF files (the old 'enhanced mode' of GDE), please find out more about [our new plugin - PDF Embedder Secure](http://wp-pdf.com/secure/?utm_source=GDE%20FAQ&utm_medium=wordpressorg&utm_campaign=Freemium).

= Does it work with files saved in Google Docs/Drive? =
This plug-in utilizes the viewer from Google Docs in a standalone fashion. There is no direct integration with Google Docs and even those
documents stored there and shared publicly do not embed reliably with their standalone viewer (ironically), so at this time that use is not
supported by the plug-in.

If you are able to store your files in Google Drive, you may want to try the [Google Drive Embedder plugin](http://wp-glogin.com/wpgoogledoctodrive). This will be faster to display, and more reliable in that case.

= Does it work in Multisite environments? =
The plugin works on network installs, though it must be activated on a per-site basis (not network activated). There are no multisite
specific features at this time, but it will function normally in this environment. If you use GDE in a multisite environment, I welcome your feedback on what functionality you would like to see.

== Screenshots ==

1. Default appearance of embedded viewer (cropped)
2. Enhanced viewer toolbar showing option to view full screen in same window
3. Enhanced viewer colors can be customized using basic settings or your own CSS file. This "dark" template is included as an example.
4. Preview of the settings page (portion of profile edit page)
5. TinyMCE Editor integration

== Changelog ==

= 2.6 =

New version number to avoid previous version number conflicts.
This version should work with WP4.4

= 2.5.22 =

Post editor button fixed - was removed in previous version.

= 2.5.21 =

Changed the name of text domain from 'gde' to 'google-document-embedder'. 
This was needed so WordPress' new translation system works - text domain must match plugin slug. 

= 2.5.19 =
* Fixed potential XSS attack flaw - please update to this version.

= 2.5.18 =
* Added: Notice on settings page about recent changes to Google Viewer.

= 2.5.17 =
* Fixed: Potential security vulnerability in disabled enhanced viewer code

= 2.5.16 =
* Removed: Masked URL ability, pending rewrite after Google breakage

= 2.5.15 =
* Removed: Enhanced Viewer and mobile option, pending rewrite after Google breakage
* Added: Italian translation (thanks Marco)
* Fixed: Potential block download and hidden toolbar conflict (thanks Tom)
* Fixed: Garbled Cyrillic text in editor dialog (thanks bones852)

= 2.5.14 =
* Fixed: Download link had no text in some circumstances
* Updated: Hungarian translation (thanks szemcse)

= 2.5.13 =
* Fixed: Javascript error within WP 3.9 visual editor (thanks Sergey)
* Fixed: (E) Unsupported browser message on some documents
* Changed: (E) Lengthened default network timeout
* Removed: Long-useless authonly= and lang= shortcode parameters (use profiles)

= 2.5.12 =
* Changed: Updated mobile detection library
* Changed: Updated settings navigation style for WP 3.8+
* Changed: Updated Dutch translation (thanks Niko)

= 2.5.11 =
* Fixed: Settings and profiles import
* Changed: Removed more unreachable legacy code

= 2.5.10 =
* Fixed: PHP warning on multisite during uninstall (thanks TigranTovmasyan)
* Changed: (E) Viewer settings use native color picker
* Changed: Now requires WordPress 3.5+ (removed legacy functions)

= 2.5.9 =
* Fixed: Mixed content restrictions while parent page is SSL
* Removed: page= shortcode attribute (broken in Google Viewer)
* Removed: Beta delivery api (server bandwidth issues/WP repo policies)

= 2.5.8 =
* Added: (E) Right-click menu disabled on secure documents
* Fixed: Support tab service errors
* Changed: Czech translation updated (thanks Jirka)

= 2.5.7 =
* Fixed: No submit button on support tab if using < WP 3.4 (thanks fatherb)
* Fixed: save=0 option not respected in some cases
* Fixed: (E) cache=0 option breaks secure document display (thanks Manish)
* Fixed: (E) Javascript sometimes broken with WP_DEBUG enabled
* Changed: (E) Increased timeout period on fetching document contents

= 2.5.6 =
* Fixed: (E) Hangs on document load due to Google code changes
* Fixed: (E) Profile language not honored on New Window button tip (thanks Ege)
* Fixed: New profiles may appear blank on initial load
* Fixed: HTTP status code check is too stringent for some servers
* Fixed: Spurious database error on deactivation if log table not present
* Fixed: PHP error when API key retrieval fails

= 2.5.5 =
* Added: (E) Option to restrict full screen view to logged-in users
* Fixed: "Unable to load profile settings" on some installs
* Fixed: Rate limit exceeded on is.gd shortened URLs (thanks Dan)
* Fixed: Some settings remain in database after uninstall
* Fixed: Dimensions not sanitized in editor dialog
* Changed: Insert from Media Library ability restored for WP 3.5+

= 2.5.4 =
* Fixed: Force download option gives PHP error (thanks Zverina)
* Fixed: Force download breaks on filenames with multiple extensions
* Fixed: Numeric-only profile names accepted (conflict with IDs)
* Fixed: Can't validate redirected URLs (thanks onesoftindiana)
* Fixed: Duplicating table indexes when logging enabled

= 2.5.3 =
* Fixed: Unsupported file type regression interferes with some uploads

= 2.5.2 =
* Fixed: Fatal error with certain plugins and themes
* Changed: Network activation halted for now (multisite single activation OK)
* Changed: Removed reliance on web services for file type recognition
* Changed: Multisite database improvements
* Changed: Text domain now called on the appropriate hook

= 2.5.1 =
* Added: Russian translation (thanks J&#243;zek)
* Added: Dutch translation (thanks Robert)
* Fixed: Default profile not created on clean install (thanks Clifford)
* Fixed: Viewer size from shortcode doesn't default to pixel
* Fixed: Erroneous error message when file validation is blocked

== Upgrade Notice ==

