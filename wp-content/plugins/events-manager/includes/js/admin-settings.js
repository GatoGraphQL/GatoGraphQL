jQuery(document).ready(function($){
	//Meta Box Options
	var open_close = $('<a href="#" style="display:block; float:right; clear:right; margin:10px;">'+EM.open_text+'</a>');
	$('#em-options-title').before(open_close);
	open_close.click( function(e){
		e.preventDefault();
		if($(this).text() == EM.close_text){
			$(".postbox").addClass('closed');
			$(this).text(EM.open_text);
		}else{
			$(".postbox").removeClass('closed');
			$(this).text(EM.close_text);
		} 
	});
	$(".postbox > h3").click(function(){ $(this).parent().toggleClass('closed'); });
	$(".postbox").addClass('closed');
	//Navigation Tabs
	$('.tabs-active .nav-tab-wrapper .nav-tab').click(function(){
		el = $(this);
		elid = el.attr('id');
		$('.em-menu-group').hide(); 
		$('.'+elid).show();
		$(".postbox").addClass('closed');
		open_close.text(EM.open_text);
	});
	$('.nav-tab-wrapper .nav-tab').click(function(){
		$('.nav-tab-wrapper .nav-tab').removeClass('nav-tab-active');
		$(this).addClass('nav-tab-active');
	});
	var navUrl = document.location.toString();
	if (navUrl.match('#')) { //anchor-based navigation
		var nav_tab = navUrl.split('#');
		var current_tab = 'a#em-menu-' + nav_tab[1];
		$(current_tab).trigger('click');
		if( nav_tab.length > 2 ){
			section = $("#em-opt-"+nav_tab[2]);
			if( section.length > 0 ){
				section.children('h3').trigger('click');
		    	$('html, body').animate({ scrollTop: section.offset().top - 30 }); //sends user back to current section
			}
		}
	}else{
		//set to general tab by default, so we can also add clicked subsections
		document.location = navUrl+"#general";
	}
	$('.nav-tab-link').click(function(){ $($(this).attr('rel')).trigger('click'); }); //links to mimick tabs
	$('input[type="submit"]').click(function(){
		var el = $(this).parents('.postbox').first();
		var docloc = document.location.toString().split('#');
		var newloc = docloc[0];
		if( docloc.length > 1 ){
			var nav_tab = docloc[1].split('#');
			newloc = newloc + "#" + nav_tab[0];
			if( el.attr('id') ){
				newloc = newloc + "#" + el.attr('id').replace('em-opt-','');
			}
		}
		document.location = newloc;
	});
	//Page Options
	$('input[name="dbem_cp_events_has_archive"]').change(function(){ //event archives
		if( $('input:radio[name="dbem_cp_events_has_archive"]:checked').val() == 1 ){
			$('tbody.em-event-archive-sub-options').show();
		}else{
			$('tbody.em-event-archive-sub-options').hide();
		}
	}).trigger('change');
	$('select[name="dbem_events_page"]').change(function(){
		if( $('select[name="dbem_events_page"]').val() == 0 ){
			$('tbody.em-event-page-options').hide();
		}else{
			$('tbody.em-event-page-options').show();
		}
	}).trigger('change');
	$('input[name="dbem_cp_locations_has_archive"]').change(function(){ //location archives
		if( $('input:radio[name="dbem_cp_locations_has_archive"]:checked').val() == 1 ){
			$('tbody.em-location-archive-sub-options').show();
		}else{
			$('tbody.em-location-archive-sub-options').hide();
		}
	}).trigger('change');
	//For rewrite titles
	$('input:radio[name=dbem_disable_title_rewrites]').live('change',function(){
		checked_check = $('input:radio[name=dbem_disable_title_rewrites]:checked');
		if( checked_check.val() == 1 ){
			$('#dbem_title_html_row').show();
		}else{
			$('#dbem_title_html_row').hide();	
		}
	});
	$('input:radio[name=dbem_disable_title_rewrites]').trigger('change');
	//for event grouping
	$('select[name="dbem_event_list_groupby"]').change(function(){
		if( $('select[name="dbem_event_list_groupby"]').val() == 0 ){
			$('tr#dbem_event_list_groupby_header_format_row, tr#dbem_event_list_groupby_format_row').hide();
		}else{
			$('tr#dbem_event_list_groupby_header_format_row, tr#dbem_event_list_groupby_format_row').show();
		}
	}).trigger('change');
	//ML Stuff
	$('.em-translatable').click(function(){
		$(this).nextAll('.em-ml-options').toggle();
	});
	//radio triggers
	$('input.em-trigger').change(function(e){
		var el = $(this);
		el.val() == '1' ? $(el.attr('data-trigger')).show() : $(el.attr('data-trigger')).hide();
	});
	$('input.em-trigger:checked').trigger('change');
});