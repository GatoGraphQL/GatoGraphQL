<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_CODE_WEBSITEFRAMEWORK', PoP_ServerUtils::get_template_definition('code-websiteframework'));
define ('GD_TEMPLATE_CODE_DESIGNPRINCIPLES_API', PoP_ServerUtils::get_template_definition('code-designprinciples-api'));
define ('GD_TEMPLATE_CODE_DESIGNPRINCIPLES_DESCENTRALIZATION', PoP_ServerUtils::get_template_definition('code-designprinciples-decentralization'));
// define ('GD_TEMPLATE_CODE_WEBSITEFRAMEWORK_GETPOST', PoP_ServerUtils::get_template_definition('code-websiteframework-getpost'));
// define ('GD_TEMPLATE_CODE_WEBSITEFRAMEWORK_MICROSERVICES', PoP_ServerUtils::get_template_definition('code-websiteframework-microservices'));
define ('GD_TEMPLATE_CODE_DEMODOWNLOADS', PoP_ServerUtils::get_template_definition('code-demodownloads'));
define ('GD_TEMPLATE_CODE_WEBSITEFEATURES_PAGECONTAINERS', PoP_ServerUtils::get_template_definition('code-websitefeatures-pagecontainers'));
define ('GD_TEMPLATE_CODE_WEBSITEFEATURES_THEMEMODES', PoP_ServerUtils::get_template_definition('code-websitefeatures-thememodes'));
define ('GD_TEMPLATE_CODE_WEBSITEFEATURES_SOCIALNETWORK', PoP_ServerUtils::get_template_definition('code-websitefeatures-socialnetwork'));
define ('GD_TEMPLATE_CODE_WEBSITEFEATURES_UNDERTHEHOOD', PoP_ServerUtils::get_template_definition('code-websitefeatures-underthehood'));
define ('GD_TEMPLATE_CODE_WEBSITEFEATURES_ADVANTAGES', PoP_ServerUtils::get_template_definition('code-websitefeatures-advantages'));
define ('GD_TEMPLATE_CODE_WEBSITEFEATURES_IDEALFORIMPLEMENTING', PoP_ServerUtils::get_template_definition('code-websitefeatures-idealforimplementing'));
define ('GD_TEMPLATE_CODE_WEBSITEFEATURES_TODOS', PoP_ServerUtils::get_template_definition('code-websitefeatures-todos'));

class GetPoP_Template_Processor_Codes extends GD_Template_Processor_CodesBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_CODE_WEBSITEFRAMEWORK,
			GD_TEMPLATE_CODE_DESIGNPRINCIPLES_API,
			GD_TEMPLATE_CODE_DESIGNPRINCIPLES_DESCENTRALIZATION,
			// GD_TEMPLATE_CODE_WEBSITEFRAMEWORK_GETPOST,
			// GD_TEMPLATE_CODE_WEBSITEFRAMEWORK_MICROSERVICES,
			GD_TEMPLATE_CODE_DEMODOWNLOADS,
			GD_TEMPLATE_CODE_WEBSITEFEATURES_PAGECONTAINERS,
			GD_TEMPLATE_CODE_WEBSITEFEATURES_THEMEMODES,
			GD_TEMPLATE_CODE_WEBSITEFEATURES_SOCIALNETWORK,
			GD_TEMPLATE_CODE_WEBSITEFEATURES_UNDERTHEHOOD,
			GD_TEMPLATE_CODE_WEBSITEFEATURES_ADVANTAGES,
			GD_TEMPLATE_CODE_WEBSITEFEATURES_IDEALFORIMPLEMENTING,
			GD_TEMPLATE_CODE_WEBSITEFEATURES_TODOS,
		);
	}

	function get_code($template_id, $atts) {

		// $frameworkitem = '<h3><span class="fa-stack fa-2x"><i class="fa fa-circle fa-stack-2x"></i><i class="fa fa-fw %s fa-stack-1x fa-inverse"></i></span>%s</h3><pre class="breakable">%s</pre>';
		$frameworkitem = '<h2><i class="fa fa-fw %s"></i>%s</h2><pre class="breakable">%s</pre>';
		$frameworksubitem = '<h3>%s</h3><pre class="breakable">%s</pre>';
		switch ($template_id) {

			case GD_TEMPLATE_CODE_WEBSITEFRAMEWORK:

				// $item = '<pre class="breakable"><div class="media"><div class="pull-left"><span class="fa-stack fa-2x"><i class="fa fa-circle fa-stack-2x"></i><i class="fa fa-fw %s fa-stack-1x fa-inverse"></i></span></div><div class="media-body"><strong>%s</strong><br/>%s</div></div></pre>';
				$media = '<div class="media"><div class="pull-left"><h4 class="media-heading">%s</h4></div><div class="media-body">%s</div></div>';
				return sprintf(
					'<div class="alert alert-info">%s</div><p>%s</p>',
					sprintf(
						__('<strong>PoP</strong> combines <a href="%s"><strong>Wordpress</strong></a> and <a href="%s"><strong>Handlebars</strong></a> into an <strong>MVC architecture</strong> framework:<br/><ul><li><strong>Wordpress</strong> is the model/back-end</li><li><strong>Handlebars</strong> templates are the view/front-end</li><li><strong>the PoP engine</strong> is the controller</li></ul>', 'getpop-processors'),
						'https://wordpress.org/',
						'http://handlebarsjs.com/'
					),
					sprintf(
						'<h3>%s</h3><div class="well">%s</div>',
						__('How does it work?', 'getpop-processors'),
						// sprintf(
						// 	'<ol><li>%s</li><li>%s</li><li>%s</li><li>%s</li><li>%s</li></ol>',
						// 	__('PoP intercepts the user request in the front-end and delivers it to the webserver using AJAX.', 'getpop-processors'),
						// 	__('Wordpress processes the request, and provides the results to the PoP controller.', 'getpop-processors'),
						// 	__('PoP generates a response in JSON, and feeds this JSON code to back to the front-end', 'getpop-processors'),
						// 	__('Handlebars templates transform the JSON code into HTML.', 'getpop-processors'),
						// 	__('PoP appends the HTML into the DOM and executes user-defined javascript functions on the new elements.', 'getpop-processors')
						// )	
						sprintf(
							$media,
							'1',
							__('PoP intercepts the user request in the front-end and delivers it to the webserver using AJAX.', 'getpop-processors')
						).
						sprintf(
							$media,
							'2',
							__('Wordpress processes the request, and provides the results to the PoP controller.', 'getpop-processors')
						).
						sprintf(
							$media,
							'3',
							__('PoP generates a response in JSON, and feeds this JSON code back to the front-end', 'getpop-processors')
						).
						sprintf(
							$media,
							'4',						
							__('Handlebars templates transform the JSON code into HTML.', 'getpop-processors')
						).
						sprintf(
							$media,
							'5',
							__('PoP appends the HTML into the DOM and executes user-defined javascript functions on the new elements.', 'getpop-processors')
						)	
					)				
				);

			case GD_TEMPLATE_CODE_DEMODOWNLOADS:

				$row = '<div class="row"><div class="col-sm-6">%s</div><div class="col-sm-6">%s</div></div>';

				// View Demo

				// The value of this constant must be set on the theme's "environment" plugin
				if (GETPOP_URL_DEMOWEBSITE) {

					$left = sprintf(
						'<p><a href="%s" target="_blank" class="btn btn-block btn-lg btn-info"><i class="fa fa-fw fa-external-link"></i>%s</a></p>',
						// Allow qTranslate to add language information, so that visiting GetPoP in ES will lead to the ES version of the demo site
						apply_filters('GetPoP_Template_Processor_Codes:demowebsite:url', GETPOP_URL_DEMOWEBSITE),
						__('View demo', 'getpop-processors')
					);

					if ($examples = apply_filters('GetPoP_Template_Processor_Codes:demodownloads:examples', array())) {

						$right = sprintf(
							'<strong><em>%s</em></strong> %s',
							__('PoP websites in the wild:', 'getpop-processors'),
							implode(__(' | ', 'getpop-processors'), $examples)
						);

						$code = sprintf(
							$row,
							$left,
							$right
						);
					}
					else {

						$code = $left;	
					}
				}

				// Downloads
				if (GETPOP_URL_DOWNLOADLINK || !GETPOP_URL_GITHUBREPOSITORY) {
					
					$left = sprintf(
						'<p><a href="%s" target="_blank" class="btn btn-block btn-primary btn-lg %s" %s><i class="fa fa-fw fa-download"></i>%s</a></p>',
						GETPOP_URL_DOWNLOADLINK ? GETPOP_URL_DOWNLOADLINK : '#',
						GETPOP_URL_DOWNLOADLINK ? '' : 'disabled',
						GETPOP_URL_DOWNLOADLINK ? '' : 'disabled="disabled"',
						__('Download', 'getpop-processors').(GETPOP_URL_DOWNLOADLINK ? '' : ' '.sprintf('<small>%s</small>', __('(coming soon)', 'getpop-processors')))
					);

					if (GETPOP_URL_DOWNLOADLINK) {

						$right = sprintf(
							'<p><strong><em>%s</em></strong> %s</p>',
							__('Latest version:', 'getpop-processors'),
							apply_filters(
								'GetPoP_Template_Processor_Codes:demodownloads:download:version',
								pop_version()
							)
						);
					}
					else {

						$right = sprintf(
							'<p>%s</p>',
							apply_filters(
								'GetPoP_Template_Processor_Codes:demodownloads:disableddownload:description',
								__('Download is disabled', 'getpop-processors')
							)
						);
					}

					$code .= sprintf(
						$row,
						$left,
						$right
					);
				}

				// GitHub
				if (GETPOP_URL_GITHUBREPOSITORY) {

					$left = sprintf(
						'<p><a href="%s" target="_blank" class="btn btn-block btn-warning btn-lg"><i class="fa fa-fw fa-github"></i>%s</a></p>',
						GETPOP_URL_GITHUBREPOSITORY,
						__('Open in GitHub', 'getpop-processors')
					);
					$right = '';

					$code .= sprintf(
						$row,
						$left,
						$right
					);
				}

				return $code;

			case GD_TEMPLATE_CODE_WEBSITEFEATURES_PAGECONTAINERS:

				$col = '<div class="col-sm-4"><a target="%s" href="%s" class="btn %s btn-block">%s</a></div>';
				return 
					sprintf(
						'<pre class="breakable">%s</pre>', 
						// __('Different ways to show a page', 'getpop-processors'),
						__('The same page can be open in many different containers, such as sidebars, floating windows, modal windows, or your own creations.', 'getpop-processors')
					).sprintf(
						'<p><strong><em>%s</em></strong></p><div class="row">%s%s%s</div>',
						sprintf(
							__('Open “%s”...', 'getpop-processors'),
							get_the_title(POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_BLOG)
						),
						sprintf(
							$col,
							GD_URLPARAM_TARGET_NAVIGATOR,
							get_permalink(POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_BLOG),
							'btn-info hidden-xs', // Do not show for mobile phone version, since the sidebar doesn't show unless pressing on the menu button
							sprintf(
								__('In a sidebar', 'getpop-processors'),
								get_the_title(POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_BLOG)
							)
						),
						sprintf(
							$col,
							GD_URLPARAM_TARGET_ADDONS,
							get_permalink(POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_BLOG),
							'btn-warning',
							sprintf(
								__('In a floating window', 'getpop-processors'),
								get_the_title(POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_BLOG)
							)
						),
						sprintf(
							$col,
							GD_URLPARAM_TARGET_QUICKVIEW,
							get_permalink(POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_BLOG),
							'btn-primary',
							sprintf(
								__('In a modal window', 'getpop-processors'),
								get_the_title(POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_BLOG)
							)
						)
					);

			case GD_TEMPLATE_CODE_DESIGNPRINCIPLES_API:

				// iframe wrapper: setting up width and height in code to fix the iOS Safari problem: https://stackoverflow.com/questions/16937070/iframe-size-with-css-on-ios
				$col = '<div><em>'.__('Source: ', 'getpop-processors').'</em><a href="%1$s" target="_blank">%1$s</a><br/><div class="iframe-wrapper" style="width: %2$s; height: 100px;"><iframe class="iframe-code" src="%1$s" width="%2$s" height="100" frameborder="0"></iframe></div></div>';
				return sprintf(
					'%s<br/>%s<br/>%s<br/>',
					sprintf(
						$frameworkitem,
						'fa-code',
						__('API', 'getpop-processors'),
						sprintf(
							'%s<br/><br/>%s',
							__('The PoP engine automatically provides the Wordpress website of its own API. The response’s JSON code can be fed to any third-party websites, mobile phone apps, etc, removing the need to implement additional server-side applications.', 'getpop-processors'),
							sprintf(
								__('There is no need for the developer to implement API endpoints: just by adding <em><strong>%s=%s</strong></em> to the URL, each and every page in the website is already its own endpoint.', 'getpop-processors'),
								GD_URLPARAM_OUTPUT,
								GD_URLPARAM_OUTPUT_JSON
							)
						)
					),
					sprintf(
						$col,
						add_query_arg(GD_URLPARAM_OUTPUT, GD_URLPARAM_OUTPUT_JSON, get_permalink(POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_BLOG)),
						'100%'
					),
					sprintf(
						$col,
						add_query_arg(GD_URLPARAM_DATASTRUCTURE, GD_DATALOAD_DATASTRUCTURE_RESULTS, add_query_arg(GD_URLPARAM_MODULE, GD_URLPARAM_MODULE_DATA, add_query_arg(GD_URLPARAM_OUTPUT, GD_URLPARAM_OUTPUT_JSON, get_permalink(POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_BLOG))))/*)*/,
						'100%'
					)
				);

				// return 
				// 	sprintf(
				// 		'<div class="alert alert-info"><div class="media"><div class="pull-left">%s</div><div class="media-body">%s</div></div></div>', 
				// 		'<i class="fa fa-2x fa-fw fa-exclamation-circle"></i>',
				// 		sprintf(
				// 			__('The website is itself a full-fledged API, allowing all its content (posts, users, comments, tags) to be accessed by external parties, to be easily available to components (such as Twitter Typeahead.js), to interact with a mobile phone app, or to interact with other PoP websites. There is no need to implement API endpoints: by adding <em><strong>%s=%s</strong></em> to the URL, each and every page in the website is already its own endpoint.', 'getpop-processors'),
				// 			GD_URLPARAM_OUTPUT,
				// 			GD_URLPARAM_OUTPUT_JSON
				// 		)

				// 	).sprintf(
				// 		'<pre class="breakable">%s</pre><div class="row">%s%s</div>',
				// 		sprintf(
				// 			__('The configuration and database object data behind each page can be retrieved in JSON format, by simply adding parameter <em><strong>%s=%s</strong></em> in the URL. To retrieve only the object data, add parameter <em><strong>%s=%s</strong></em> in the URL. To get the data in a specific format, such as in an array, add parameter <em><strong>%s=%s</strong></em>.', 'getpop-processors'),
				// 			GD_URLPARAM_OUTPUT,
				// 			GD_URLPARAM_OUTPUT_JSON,
				// 			GD_URLPARAM_MODULE,
				// 			GD_URLPARAM_MODULE_DATA,
				// 			GD_URLPARAM_DATASTRUCTURE,
				// 			GD_DATALOAD_DATASTRUCTURE_RESULTS
				// 		),
				// 		sprintf(
				// 			$col,
				// 			add_query_arg(GD_URLPARAM_OUTPUT, GD_URLPARAM_OUTPUT_JSON, get_permalink(POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_BLOG)),
				// 			'100%'
				// 		),
				// 		sprintf(
				// 			$col,
				// 			add_query_arg(GD_URLPARAM_DATASTRUCTURE, GD_DATALOAD_DATASTRUCTURE_RESULTS, add_query_arg(GD_URLPARAM_MODULE, GD_URLPARAM_MODULE_DATA, add_query_arg(GD_URLPARAM_OUTPUT, GD_URLPARAM_OUTPUT_JSON, get_permalink(POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_BLOG))))/*)*/,
				// 			'100%'
				// 		)
				// 	);

			case GD_TEMPLATE_CODE_DESIGNPRINCIPLES_DESCENTRALIZATION:

				return sprintf(
					$frameworkitem,
					'fa-server',
					__('Decentralized', 'getpop-processors'),
					sprintf(
						'%s<br/><br/>%s<br/>%s',
						__('The structure of the JSON code produced by the PoP engine is the same for all PoP websites, allowing them to communicate among themselves, fetching and processing data from each other in real time.', 'getpop-processors'),
						sprintf(
							'%s<ul><li>%s</li><li>%s</li><li>%s</li></ul>',
							__('PoP allows to: ', 'getpop-processors'),
							__('Decentralize a website’s data sources into several websites', 'getpop-processors'),
							__('Aggregate data from other, independent PoP websites', 'getpop-processors'),
							__('Easily implement microservices: a PoP website can be composed of atomic components, each of them fed with data coming from its own server', 'getpop-processors')
						),
						__('<strong>PoP provides support for POST requests:</strong> user-generated content, such as creating a post or adding a comment, will be stored on the source/aggregated website.', 'getpop-processors')
					)
				);

			// case GD_TEMPLATE_CODE_WEBSITEFRAMEWORK_GETPOST:

			// 	return sprintf(
			// 		$frameworkitem,
			// 		'fa-star',
			// 		__('Save any user-generated content on the source website', 'getpop-processors'),
			// 		__('<strong>Support for POST requests:</strong> user-generated content, such as creating a post or adding a comment, will be stored on the source/aggregated website.<br/><br/>(For this, the user will also need an account on the source website, or use OAuth to log in with a third-party provider, such as Facebook Login).', 'getpop-processors')
			// 	);

			// case GD_TEMPLATE_CODE_WEBSITEFRAMEWORK_MICROSERVICES:

			// 	return sprintf(
			// 		$frameworkitem,
			// 		'fa-star',
			// 		__('Easy to implement microservices', 'getpop-processors'),
			// 		__('Decentralization makes it easy to implement microservices: different modules of the website can be implemented using atomic components, to be fed with data coming from different servers.', 'getpop-processors')
			// 	);

			case GD_TEMPLATE_CODE_WEBSITEFEATURES_THEMEMODES:

				// $col = '<div class="col-sm-6"><strong>%3$s</strong><br/><em>'.__('Source: ', 'getpop-processors').'</em><a href="%1$s" target="_blank">%1$s</a><br/><div class="iframe-wrapper" style="width: %2$s; height: 400px;"><iframe class="iframe-code" src="%1$s" width="%2$s" height="400" frameborder="0" allowfullscreen="true"></iframe></div></div>';
				$col = '<div><strong>%3$s</strong><br/><em>'.__('Source: ', 'getpop-processors').'</em><a href="%1$s" target="_blank">%1$s</a><br/><div class="iframe-wrapper" style="width: %2$s; height: 300px;"><iframe class="iframe-code" src="%1$s" width="%2$s" height="300" frameborder="0" allowfullscreen="true"></iframe></div></div>';
				return 
					sprintf(
						// '<div class="alert alert-info"><div class="media"><div class="pull-left">%s</div><div class="media-body">%s</div></div></div>', 
						// '<i class="fa fa-2x fa-fw fa-exclamation-circle"></i>',
						'<pre class="breakable">%s<br/><br/>%s</pre><br/>', 
						__('Similar to Wordpress, the PoP framework has themes, however these are implemented using Handlebars templates. Each theme can, itself, have different “modes” or presentation styles. It can be used for generating printing or embeddable versions of the theme, display alternative layout designs, etc.', 'getpop-processors'),
						sprintf(
							__('The theme mode is specified by adding parameter <em><strong>%s</strong></em> in the URL.', 'getpop-processors'),
							GD_URLPARAM_THEMEMODE
						)
					).sprintf(
						// '<pre class="breakable">%s</pre><div class="row">%s%s</div>',
						// sprintf(
						// 	__('The theme mode is specified by adding parameter <em><strong>%s</strong></em> in the URL.', 'getpop-processors'),
						// 	GD_URLPARAM_THEMEMODE
						// ),
						// '<div class="row">%s%s</div>',
						'<div>%s<br/>%s</div>',
						sprintf(
							$col,
							add_query_arg(GD_URLPARAM_FORMAT, GD_TEMPLATEFORMAT_LIST, add_query_arg(GD_URLPARAM_THEMEMODE, GD_THEMEMODE_WASSUP_EMBED, get_permalink(POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_BLOG))),
							'100%',
							__('“Embeddable” theme mode', 'getpop-processors')
						),
						sprintf(
							$col,
							add_query_arg(GD_URLPARAM_FORMAT, GD_TEMPLATEFORMAT_LIST, add_query_arg(GD_URLPARAM_THEMEMODE, GD_THEMEMODE_WASSUP_PRINT, get_permalink(POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_BLOG))),
							'100%',
							__('“Printable” theme mode', 'getpop-processors')
						)
					);

			case GD_TEMPLATE_CODE_WEBSITEFEATURES_SOCIALNETWORK:

				$in = $this->get_att($template_id, $atts, 'firstitem-open') ? 'in' : '';
				$panel_class = $this->get_att($template_id, $atts, 'panel-class');
				$col = '<div class="'.$panel_class.'"><div class="panel-heading" role="tab" id="%1$s"><h4 class="panel-title"><a role="button" data-toggle="collapse" data-parent="#additionalfeatures" href="#%2$s" aria-expanded="true" aria-controls="%2$s">%3$s</a></h4></div><div id="%2$s" class="panel-collapse collapse %5$s" role="tabpanel" aria-labelledby="%1$s"><div class="panel-body">%4$s</div></div></div>';
				// $col_extra = '<h4>%1$s</h4><p>%2$s</p>%3$s<br/>';
				return sprintf(
					'<div class="panel-group" id="additionalfeatures" role="tablist" aria-multiselectable="true">%s</div>',
						sprintf(
							$col,
							'mf-heading-0',
							'mf-collapse-0',
							__('@Mentions and #Hashtags', 'getpop-processors'),
							__('When creating a post or adding a comment, the user can mention any other user, and this person will receive a notification, and add Twitter-like hashtags.', 'getpop-processors'),
							$in
						).sprintf(
							$col,
							'mf-heading-2',
							'mf-collapse-2',
							__('Support for Communities', 'getpop-processors'),
							__('The website allows for two types of user account: “Individuals” and “Organizations”. The Organizations account type allows to have members, and all content from their members also shows on the Organization’s profile.', 'getpop-processors'),
							''
						).sprintf(
							$col,
							'mf-heading-4',
							'mf-collapse-4',
							__('Highlights, and Up/Down voting posts', 'getpop-processors'),
							__('Any user can highlight meaningful bits of information from each post, making it easier for other users to understand the essence of the post. These ones can, in turn, be up/down voted by all users, showing support or lack of it.', 'getpop-processors'),
							''
						).sprintf(
							$col,
							'mf-heading-10',
							'mf-collapse-10',
							__('Submission of links', 'getpop-processors'),
							__('In addition to creating posts, users can also submit a URL link. The corresponding webpage will be embedded in a post, if possible (only available for HTTPS websites).', 'getpop-processors'),
							''
						).sprintf(
							$col,
							'mf-heading-9',
							'mf-collapse-9',
							__('User notifications', 'getpop-processors'),
							__('Users receive real-time notifications concernig any activity related to them (eg: a comment added to the user’s article, the user having a new follower, etc), and any activity by users belonging to their network, which is composed by their followed users, and those users who are members of the same organizations.', 'getpop-processors'),
							''
						).sprintf(
							$col,
							'mf-heading-7',
							'mf-collapse-7',
							__('Following users and recommending posts', 'getpop-processors'),
							__('Users can follow other users, thus receiving notifications of their activities, and recommend posts, giving a notification to the user’s network.', 'getpop-processors'),
							''
						).sprintf(
							$col,//$col_extra,
							'mf-heading-8',
							'mf-collapse-8',
							__('Real time updates', 'getpop-processors'),
							__('Whenever there is a new post added to the website, all users concurrently browsing the website (either logged in or not) will receive an automatic update', 'getpop-processors'),
							''
						).sprintf(
							$col,
							'mf-heading-5',
							'mf-collapse-5',
							__('Co-authoring and inter-linking posts', 'getpop-processors'),
							__('When creating content, users are allowed to select any number of co-authors, which will not only be displayed on the corresponding post, but also will have rights to edit the post. Also, they can select related existing posts, and these will appear inter-linked together.', 'getpop-processors'),
							''
						).sprintf(
							$col,
							'mf-heading-6',
							'mf-collapse-6',
							__('Different levels of content moderation', 'getpop-processors'),
							__('Content posted by the users can be published immediately, or await for admin’s approval for publishing. In case the post is published immediately, a “Flag inappropriate content” mechanism is in place for the users to moderate the content.', 'getpop-processors'),
							''
						).sprintf(
							$col,
							'mf-heading-11',
							'mf-collapse-11',
							__('Maximizing visibility of content', 'getpop-processors'),
							sprintf(
								__('Most content in the website is always visible. Only user-specific pages (such as <a href="%s">%s</a> or <a href="%s">%s</a>) require the user to log-in to be shown. When the user logs out, only the tabs corresponding to these user-specific pages will close, all other tabs will remain open.', 'getpop-processors'),
								get_permalink(POP_WPAPI_PAGE_MYCONTENT),
								get_the_title(POP_WPAPI_PAGE_MYCONTENT),
								get_permalink(POP_WPAPI_PAGE_EDITAVATAR),
								get_the_title(POP_WPAPI_PAGE_EDITAVATAR)
							),
							''
						).
						sprintf(
							$col,
							'mf-heading-1',
							'mf-collapse-1',
							__('Tabbed Browsing', 'getpop-processors'),
							__('Similar to a browser, the website keeps all pages open, and uses tabs to switch among all of them.', 'getpop-processors'),
							''
						)
					);

			case GD_TEMPLATE_CODE_WEBSITEFEATURES_UNDERTHEHOOD:

				$in = $this->get_att($template_id, $atts, 'firstitem-open') ? 'in' : '';
				$panel_class = $this->get_att($template_id, $atts, 'panel-class');
				$col = '<div class="'.$panel_class.'"><div class="panel-heading" role="tab" id="%1$s"><h4 class="panel-title"><a role="button" data-toggle="collapse" data-parent="#underthehood" href="#%2$s" aria-expanded="true" aria-controls="%2$s">%3$s</a></h4></div><div id="%2$s" class="panel-collapse collapse %5$s" role="tabpanel" aria-labelledby="%1$s"><div class="panel-body">%4$s</div></div></div>';
				$link = '<a href="%s">%s</a>';
				$linktarget = '<a href="%s" target="%s">%s</a>';
				$preloaded = array();
				if (PoPTheme_Wassup_Utils::get_addcontent_target() == GD_URLPARAM_TARGET_ADDONS) {
					$preloaded[] = sprintf(
						$linktarget,
						get_permalink(POPTHEME_WASSUP_PAGE_ADDWEBPOST),
						GD_URLPARAM_TARGET_ADDONS,
						get_the_title(POPTHEME_WASSUP_PAGE_ADDWEBPOST)
					);
				}
				$preloaded[] = sprintf(
					$link,
					get_permalink(POPTHEME_WASSUP_GF_PAGE_CONTACTUS),
					get_the_title(POPTHEME_WASSUP_GF_PAGE_CONTACTUS)
				);
				$preloaded[] = sprintf(
					$link,
					get_permalink(POPTHEME_WASSUP_GF_PAGE_NEWSLETTER),
					get_the_title(POPTHEME_WASSUP_GF_PAGE_NEWSLETTER)
				);
				return sprintf(
					'<div class="panel-group" id="underthehood" role="tablist" aria-multiselectable="true">%s</div>',
						sprintf(
							$col,
							'uth-heading-1',
							'uth-collapse-1',
							__('Fully cacheable', 'getpop-processors'),
							sprintf(
								__('Most pages in the website are cached, independently if the user is logged in or not. This can be done because these pages have been stripped of all user information related to the page (eg: has the user recommended it?), so that the response is always the same for all users. All user information related to the page is loaded on a subsequent, immediate request.<br/><br/>Only those pages which show user-specific information (such as <a href="%s">%s</a>, <a href="%s">%s</a>, etc) are not cached.', 'getpop-processors'),
								get_permalink(POP_WPAPI_PAGE_MYCONTENT),
								get_the_title(POP_WPAPI_PAGE_MYCONTENT),
								get_permalink(POP_WPAPI_PAGE_EDITAVATAR),
								get_the_title(POP_WPAPI_PAGE_EDITAVATAR)
							),
							$in
						).sprintf(
							$col,
							'uth-heading-2',
							'uth-collapse-2',
							__('Different levels of cache', 'getpop-processors'),
							__('In addition to caching the HTML response, the website caches the settings for all similar posts. This way, upon requesting two posts belonging to the same post type (such as events), the second post will access the cached settings and need only retrieve the object data from the database.', 'getpop-processors'),
							''
						).sprintf(
							$col,
							'uth-heading-3',
							'uth-collapse-3',
							__('Lazy loading', 'getpop-processors'),
							__('Selected content, such as comments added to a post, can be loaded only after the page has loaded, allowing for a faster initial page load.', 'getpop-processors'),
							''
						).sprintf(
							$col,
							'uth-heading-4',
							'uth-collapse-4',
							__('Extra information added to the URL, hidden to the user', 'getpop-processors'),
							__('When the user clicks on a link, PoP intercepts the request to submit it using AJAX. PoP can add extra parameters to the URL, which are not in the original link (and so hidden to the user), such as: language, theme, timestamp, etc.', 'getpop-processors'),
							''
						).sprintf(
							$col,
							'uth-heading-5',
							'uth-collapse-5',
							__('Deep linking for functionalities', 'getpop-processors'),
							sprintf(
								__('Whenever possible, functionalities in the website are executed using a friendly URL, instead of doing “<em>%s</em>” and executing some javascript code. As such, this functionality can be called even if the user opens the link in a new tab. Eg: add comment to post links, location map links, contact user links.', 'getpop-processors'),
								htmlentities('<a href="#">...</a>')
							),
							''
						).sprintf(
							$col,
							'uth-heading-6',
							'uth-collapse-6',
							__('Fully crawlable by Google and other search engines', 'getpop-processors'),
							sprintf(
								__('Even though the data is sent using JSON, and the website is rendered using AJAX, all information is crawlable by all search engines. This is accomplished by also sending the page data in standard HTML only for the initially loaded page, which does not have parameter “%s=%s”, and so it responds using HTML. Furthermore, no links have this parameter either (it is added by PoP in runtime, after intercepting the request), so the search engine will never retrieve a page in JSON format, only in HTML.', 'getpop-processors'),
								GD_URLPARAM_OUTPUT,
								GD_URLPARAM_OUTPUT_JSON
							),
							''
						).sprintf(
							$col,
							'uth-heading-7',
							'uth-collapse-7',
							__('Background pre-loading of data', 'getpop-processors'),
							sprintf(
								__('The software pre-loads specified pages, running in the background, so they are already loaded by the time the user clicks on the corresponding link. Eg: when clicking on the following links they will open immediately, since they were preloaded: %s. Once these pages have loaded, if available, their code is stored in the browser using Local Storage.', 'getpop-processors'),
								implode(__(', ', 'getpop-processors'), $preloaded)
							),
							''
						).sprintf(
							$col,
							'uth-heading-8',
							'uth-collapse-8',
							__('Quick server’s response', 'getpop-processors'),
							__('Themes based on PoP implement their layouts using Handlebars templates, which are cached by the browser. These templates generate the HTML in the front-end, allowing the server’s response to send only settings and object data, thus speeding up the communication between the client and server.', 'getpop-processors'),
							''
						).sprintf(
							$col,
							'uth-heading-9',
							'uth-collapse-9',
							__('Compatible with Wordpress’ legacy code', 'getpop-processors'),
							__('PoP requires plug-ins to provide their data query results in a given format, so they must implement some functionality to adapt it for PoP. However, this integration is minimal, and can be provided in an additional plug-in.', 'getpop-processors'),
							''
						)
					);
			
			case GD_TEMPLATE_CODE_WEBSITEFEATURES_ADVANTAGES:

				$in = $this->get_att($template_id, $atts, 'firstitem-open') ? 'in' : '';
				$panel_class = $this->get_att($template_id, $atts, 'panel-class');
				$col = '<div class="'.$panel_class.'"><div class="panel-heading" role="tab" id="%1$s"><h4 class="panel-title"><a role="button" data-toggle="collapse" data-parent="#advantages" href="#%2$s" aria-expanded="true" aria-controls="%2$s">%3$s</a></h4></div><div id="%2$s" class="panel-collapse collapse %5$s" role="tabpanel" aria-labelledby="%1$s"><div class="panel-body">%4$s</div></div></div>';
				$link = '<a href="%s">%s</a>';
				$linktarget = '<a href="%s" target="%s">%s</a>';
				return sprintf(
					'<div class="panel-group" id="advantages" role="tablist" aria-multiselectable="true">%s</div>',
						sprintf(
							$col,
							'adv-heading-1',
							'adv-collapse-1',
							__('SEO friendly', 'getpop-processors'),
							sprintf(
								__('PoP is SEO friendly because a unique URL serves both the website and the API: crawlers can both find the information, and know what is its URL, without having to <a href="%s">implement artificial artifacts</a> to match these two, such as the (now deprecated) practice of adding "!#" in the URL string as previously <a href="%s">suggested by Google</a>.', 'getpop-processors'),
								'https://www.smashingmagazine.com/2011/09/searchable-dynamic-content-with-ajax-crawling/',
								'https://developers.google.com/webmasters/ajax-crawling/docs/getting-started'
							),
							$in
						).sprintf(
							$col,
							'adv-heading-2',
							'adv-collapse-2',
							__('Documentation-less API', 'getpop-processors'),
							__('The API automatically provided by PoP does not need to be documented, because simply removing parameter “output=json” from the corresponding URL will render the website, printing all information as provided through the API for GET requests, and displaying the corresponding form with the required fields for POST operations.', 'getpop-processors'),
							''
						).sprintf(
							$col,
							'adv-heading-3',
							'adv-collapse-3',
							__('Separation of concerns, code reusability and team collaboration', 'getpop-processors'),
							__('The modularity of PoP allows for creating components which comprise multiple distinct modules, each of these implementing a specific functionality (or concern). Well-separated modules can be reused, both within their component and by other components, as well as developed and updated independently. Components can thus be improved or modified without having to know the details of, or having to make changes to, other components. Work can be properly and efficiently split among team members, both at the component level, by assigning different modules to different people, as well as within the module level, by splitting work for a single module into back-end and front-end coding to be done by different people.', 'getpop-processors'),
							''
						).sprintf(
							$col,
							'adv-heading-4',
							'adv-collapse-4',
							__('One unique server-side development for several client-side implementations', 'getpop-processors'),
							__('The API exposed by PoP can be used by any allowed system or application, such as third-party websites, mobile phone apps, components from the own website, etc.', 'getpop-processors'),
							''
						)
					);

			case GD_TEMPLATE_CODE_WEBSITEFEATURES_IDEALFORIMPLEMENTING:

				$in = $this->get_att($template_id, $atts, 'firstitem-open') ? 'in' : '';
				$panel_class = $this->get_att($template_id, $atts, 'panel-class');
				$col = '<div class="'.$panel_class.'"><div class="panel-heading" role="tab" id="%1$s"><h4 class="panel-title"><a role="button" data-toggle="collapse" data-parent="#idealforimplementing" href="#%2$s" aria-expanded="true" aria-controls="%2$s">%3$s</a></h4></div><div id="%2$s" class="panel-collapse collapse %5$s" role="tabpanel" aria-labelledby="%1$s"><div class="panel-body">%4$s</div></div></div>';
				return sprintf(
					'<div class="panel-group" id="idealforimplementing" role="tablist" aria-multiselectable="true">%s</div>',
						sprintf(
							$col,
							'ifi-heading-1',
							'ifi-collapse-1',
							__('Niche social networks/crowd-sourced websites', 'getpop-processors'),
							__('PoP offers plenty of crowd-sourcing and social networking features, such as: user content creation, user profiles, recommending and sharing posts, following users, real time user notifications, community user account, moderation mechanism, etc.', 'getpop-processors'),
							$in
						).sprintf(
							$col,
							'ifi-heading-2',
							'ifi-collapse-2',
							__('APIs for Wordpress websites', 'getpop-processors'),
							__('Any Wordpress website which needs an API to allow third parties to interact with it, can implement the PoP engine and use its API.', 'getpop-processors'),
							''
						).sprintf(
							$col,
							'ifi-heading-3',
							'ifi-collapse-3',
							__('Server back-end for mobile apps', 'getpop-processors'),
							__('Similarly, PoP allows any Wordpress website to export its data for its own mobile app, without the need to implement yet another application for the back-end.', 'getpop-processors'),
							''
						).sprintf(
							$col,
							'ifi-heading-4',
							'ifi-collapse-4',
							__('Decentralized websites', 'getpop-processors'),
							__('PoP allows a website to decentralize its data sources, getting data from all of them as needed, in real time.', 'getpop-processors'),
							''
						).sprintf(
							$col,
							'ifi-heading-5',
							'ifi-collapse-5',
							__('Content aggregators', 'getpop-processors'),
							__('Inversely, a PoP website can fetch data from other, independent websites, so becoming an aggregator.', 'getpop-processors'),
							''
						).sprintf(
							$col,
							'ifi-heading-6',
							'ifi-collapse-6',
							__('Microservices', 'getpop-processors'),
							__('Being able to decentralize its data sources enables applications to be divided into atomic modules, each one of them executing just a specific functionality. PoP then assembles all data from all modules together.', 'getpop-processors'),
							''
						)
					);

			case GD_TEMPLATE_CODE_WEBSITEFEATURES_TODOS:

				$in = $this->get_att($template_id, $atts, 'firstitem-open') ? 'in' : '';
				$panel_class = $this->get_att($template_id, $atts, 'panel-class');
				$col = '<div class="'.$panel_class.'"><div class="panel-heading" role="tab" id="%1$s"><h4 class="panel-title"><a role="button" data-toggle="collapse" data-parent="#todos" href="#%2$s" aria-expanded="true" aria-controls="%2$s">%3$s</a></h4></div><div id="%2$s" class="panel-collapse collapse %5$s" role="tabpanel" aria-labelledby="%1$s"><div class="panel-body">%4$s</div></div></div>';
				return sprintf(
					'<div class="panel-group" id="todos" role="tablist" aria-multiselectable="true">%s</div>',
						sprintf(
							$col,
							'td-heading-1',
							'td-collapse-1',
							__('Faster initial load', 'getpop-processors'),
							__('PoP takes its time to load initially, because it loads the whole application’s javascript files. A better way would be to load only whatever files are needed, and load the rest immediately after, in the background.', 'getpop-processors'),
							$in
						).sprintf(
							$col,
							'td-heading-2',
							'td-collapse-2',
							__('Standalone components/plug-ins', 'getpop-processors'),
							__('In addition to creating themes, PoP can also be used to create plug-ins. This way, Wordpress websites can benefit from components implemented using PoP under their own themes. Eg of components to develop: inline comments, front-end user content creation, user notifications, search/quick access input, etc.', 'getpop-processors'),
							''
						).sprintf(
							$col,
							'td-heading-3',
							'td-collapse-3',
							__('Multiple data sources for components', 'getpop-processors'),
							__('Components will be able to load data from several websites concurrently. This way, the events calendar will be able to fetch events, in real time, from many different websites.', 'getpop-processors'),
							''
						).sprintf(
							$col,
							'td-heading-4',
							'td-collapse-4',
							__('“Attend event” functionality', 'getpop-processors'),
							__('The plug-in used currently for events is <strong>Events Manager</strong>, which allows users to attend events, however the implementation for PoP has not been done yet.', 'getpop-processors'),
							''
						).sprintf(
							$col,
							'td-heading-5',
							'td-collapse-5',
							__('Volunteering platform', 'getpop-processors'),
							__('Currently, when applying to volunteer for a project, a form is shown for the user to input his or her information, which is then sent to the project organizer by email. In the future, PoP will have a volunteering platform, where projects keep the information of which volunteers applied for it, and allows volunteers and project owners to leave references for each other. After volunteering, the experience will appear in the user’s profile page, and notifications will be sent to the user’s network.', 'getpop-processors'),
							''
						).sprintf(
							$col,
							'td-heading-6',
							'td-collapse-6',
							__('Messaging', 'getpop-processors'),
							__('PoP will enable users to send and read messages within the website.', 'getpop-processors'),
							''
						).sprintf(
							$col,
							'td-heading-7',
							'td-collapse-7',
							__('Integration with WooCommerce for a marketplace', 'getpop-processors'),
							__('After integrating with <strong>WooCommerce</strong>, users will be able to upload and sell products in a PoP website. Even more, PoP aggregators will be able to sell products from its aggregated websites, allowing for a decentralized marketplace.', 'getpop-processors'),
							''
						).sprintf(
							$col,
							'td-heading-8',
							'td-collapse-8',
							__('Integration with bbPress for forums', 'getpop-processors'),
							__('As said, to add user forums in PoP.', 'getpop-processors'),
							''
						).sprintf(
							$col,
							'td-heading-9',
							'td-collapse-9',
							__('Create an app for PoP', 'getpop-processors'),
							__('Because all PoP websites understand the same JSON code, a single mobile app could serve pretty much all of them. Even more, once PoP becomes a standalone plug-in (see TODO above), any existing Wordpress website, just by installing this plug-in, will be able to export its data to feed the mobile app, so a single mobile app could serve all Wordpress websites! The app could have pre-defined channels or domains from which to read the data, and allow the user to input a new one.', 'getpop-processors'),
							''
						)
					);
		}
	
		return parent::get_code($template_id, $atts);
	}

	function init_atts($template_id, &$atts) {

		switch ($template_id) {
		
			case GD_TEMPLATE_CODE_WEBSITEFRAMEWORK:
		
				$this->append_att($template_id, $atts, 'class', 'readable');
				break;
		
			case GD_TEMPLATE_CODE_WEBSITEFEATURES_SOCIALNETWORK:
			case GD_TEMPLATE_CODE_WEBSITEFEATURES_IDEALFORIMPLEMENTING:
			case GD_TEMPLATE_CODE_WEBSITEFEATURES_TODOS:
			case GD_TEMPLATE_CODE_WEBSITEFEATURES_UNDERTHEHOOD:
			case GD_TEMPLATE_CODE_WEBSITEFEATURES_ADVANTAGES:

				$this->add_att($template_id, $atts, 'firstitem-open', true);
				$this->add_att($template_id, $atts, 'panel-class', 'panel panel-info');
				break;
		}
			
		return parent::init_atts($template_id, $atts);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GetPoP_Template_Processor_Codes();