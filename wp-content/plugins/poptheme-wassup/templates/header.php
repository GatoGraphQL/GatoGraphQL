<!DOCTYPE HTML>
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<head profile="http://gmpg.org/xfn/11" prefix="og: http://ogp.me/ns#">
	<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
	<?php /* For Bootstrap v3.0: http://getbootstrap.com/getting-started/ */ ?>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<?php if (is_ssl()) : ?>
		<?php /* Avoid insecure HTTP requests over HTTPS. Taken from https://developers.google.com/web/fundamentals/security/prevent-mixed-content/fixing-mixed-content */ ?>
		<meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
	<?php endif; ?>
	<?php $title = gd_get_document_title(); ?>
	<?php $encoded_title = esc_html($title); ?>
	<?php $site_name = get_bloginfo('name'); ?>
	<title><?php echo gd_get_document_title() ?></title>
	<?php if ( is_search() || is_author() ) : ?>
	<meta name="robots" content="noindex, nofollow" />
	<?php endif ?>	
	<link rel="alternate" type="application/rss+xml" href="<?php bloginfo('rss2_url') ?>" title="<?php printf( __( '%s latest posts', 'poptheme-wassup' ), esc_html( get_bloginfo('name'), 1 ) ) ?>" />
	<link rel="alternate" type="application/rss+xml" href="<?php bloginfo('comments_rss2_url') ?>" title="<?php printf( __( '%s latest comments', 'poptheme-wassup' ), esc_html( get_bloginfo('name'), 1 ) ) ?>" />
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />	
	<link rel="shortcut icon" href="<?php echo gd_get_favicon() ?>" />
	<?php /* meta for Facebook / Twitter / Google Search */ ?>
	<meta name="twitter:card" content="summary">
	<meta property="og:site_name" content="<?php echo $site_name ?>">
	<meta property="og:title" content="<?php echo $encoded_title ?>">
	<meta name="twitter:title" content="<?php echo $encoded_title ?>">
	<?php $url = GD_TemplateManager_Utils::get_current_url(); ?>
	<link rel="canonical" href="<?php echo $url ?>">
	<meta property="og:url" content="<?php echo $url ?>">
	<meta name="twitter:url" content="<?php echo $url ?>">
	<?php $twitter_user = gd_twitter_user() ?>
	<meta name="twitter:site" content="<?php echo $twitter_user ?>">
	<meta name="twitter:creator" content="<?php echo $twitter_user ?>">
	<?php 
	if (is_single()) {
		$description = gd_get_post_description(); 
	}
	elseif (is_page()) {
		$description = gd_header_page_description();
	}
	elseif (is_author()) {
		global $author;
	    $curauth = get_userdata($author);
		$description = sprintf(__('View %1$s profile and get in touch through %2$s.', 'poptheme-wassup'), $curauth->display_name, $site_name);
	}
	elseif (is_tag()) {
		$description = sprintf(__('Entries tagged “%1$s” in %2$s.', 'poptheme-wassup'), single_tag_title("", false), $site_name);
	}
	// If none of the above, always use the Website description
	// elseif (is_home() || is_front_page()) {
	else {
		$description = gd_header_site_description();
	}
	?>
	<meta name="description" content="<?php echo $description ?>">
	<meta property="og:description" content="<?php echo $description ?>">
	<meta name="twitter:description" content="<?php echo $description ?>">
	<?php
	$thumb = gd_get_document_thumb();
	?>
	<meta property="og:image" content="<?php echo $thumb['src'] ?>">
	<meta property="og:image:type" content="<?php echo $thumb['mime-type'] ?>">
	<meta property="og:image:width" content="<?php echo $thumb['width'] ?>">
	<meta property="og:image:height" content="<?php echo $thumb['height'] ?>">
	<meta name="twitter:image" content="<?php echo $thumb['src'] ?>">
	<meta name="twitter:image:width" content="<?php echo $thumb['width'] ?>">
	<meta name="twitter:image:height" content="<?php echo $thumb['height'] ?>">
	<?php wp_head(); ?>
	<?php /* For Bootstrap v3.0: http://getbootstrap.com/getting-started/ */ ?>
	<?php /* HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries */ ?>
	<!--[if lt IE 9]>
		<?php foreach (get_compatibility_js_files() as $file) : ?>
			<script src="<?php echo $file ?>"></script>
		<?php endforeach; ?>
	<![endif]-->	
</head>
<body id="body" class="pop-loadingframe pop-loadingjs <?php echo gd_classes_body() ?>">
	<?php // Comment Leo 10/08/2016: commented changing the title to "Loading", because Google shows this temporary title in its search results ?>
	<?php /* ?><script type="text/javascript">document.title="<?php echo gd_get_initial_document_title() ?>";</script> <?php */ ?>
	<div id="wrapper" class="pop-fullscreen">
		<div class="loading-screen">
			<?php include POPTHEME_WASSUP_TEMPLATES.'/loading-screen.php' ?>
		</div>
		<!--div class="background-screen"></div-->
		<?php 
		// Include the Theme Header
		$vars = GD_TemplateManager_Utils::get_vars();
		include $vars['theme-path'].'/header.php';
		?>