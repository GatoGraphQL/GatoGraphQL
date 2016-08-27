<?php
/*
 * RSS Page
 * This page handles the even RSS feed.
 * You can override this file by and copying it to yourthemefolder/plugins/events-manager/templates/ and modifying as necessary.
 * 
 */ 
header ( "Content-type: application/rss+xml; charset=UTF-8" );
echo '<?xml version="1.0" encoding="UTF-8" ?>'."\n";
?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
	<channel>
		<title><?php echo esc_html(get_option ( 'dbem_rss_main_title' )); ?></title>
		<link><?php	echo EM_URI; ?></link>
		<description><?php echo esc_html(get_option('dbem_rss_main_description')); ?></description>
		<docs>http://blogs.law.harvard.edu/tech/rss</docs>
		<pubDate><?php echo date('D, d M Y H:i:s +0000', get_option('em_last_modified', current_time('timestamp',true))); ?></pubDate>
		<atom:link href="<?php echo esc_attr(EM_RSS_URI); ?>" rel="self" type="application/rss+xml" />
		<?php
		$description_format = str_replace ( ">", "&gt;", str_replace ( "<", "&lt;", get_option ( 'dbem_rss_description_format' ) ) );
        $rss_limit = get_option('dbem_rss_limit');
        $page_limit = $rss_limit > 50 || !$rss_limit ? 50 : $rss_limit; //set a limit of 50 to output at a time, unless overall limit is lower		
		$args = !empty($args) ? $args:array(); /* @var $args array */
		$args = array_merge(array('scope'=>get_option('dbem_rss_scope'), 'owner'=>false, 'limit'=>$page_limit, 'page'=>1, 'order'=>get_option('dbem_rss_order'), 'orderby'=>get_option('dbem_rss_orderby')), $args);
		$args = apply_filters('em_rss_template_args',$args);
		$EM_Events = EM_Events::get( $args );
		$count = 0;
		while( count($EM_Events) > 0 ){
			foreach ( $EM_Events as $EM_Event ) {
				/* @var $EM_Event EM_Event */
				$description = $EM_Event->output( get_option ( 'dbem_rss_description_format' ), "rss");
				$description = ent2ncr(convert_chars($description)); //Some RSS filtering
				$event_url = $EM_Event->output('#_EVENTURL');
				?>
				<item>
					<title><?php echo $EM_Event->output( get_option('dbem_rss_title_format'), "rss" ); ?></title>
					<link><?php echo $event_url; ?></link>
					<guid><?php echo $event_url; ?></guid>
					<pubDate><?php echo get_gmt_from_date(date('Y-m-d H:i:s', $EM_Event->start), 'D, d M Y H:i:s +0000'); ?></pubDate>
					<description><![CDATA[<?php echo $description; ?>]]></description>
				</item>
				<?php
				$count++;
			}
        	if( $rss_limit != 0 && $count >= $rss_limit ){ 
        	    //we've reached our limit, or showing one event only
        	    break;
        	}else{
        	    //get next page of results
        	    $args['page']++;
        		$EM_Events = EM_Events::get( $args );
        	}
		}
		?>
		
	</channel>
</rss>