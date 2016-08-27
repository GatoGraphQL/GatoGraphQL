jQuery(document).ready(function () {
	
	/*
	 * Click handler for the delete button
	 * @param event
	 */
	var coauthors_delete_onclick = function(e){
		if(confirm(coAuthorsPlusStrings.confirm_delete)) {
			return coauthors_delete(this);
		}
		return false;
	};

	function coauthors_delete( elem ) {
		
		var $coauthor_row = jQuery(elem).closest('.coauthor-row');
		$coauthor_row.remove();

		// Hide the delete button when there's only one Co-Author
		if ( jQuery( '#coauthors-list .coauthor-row .coauthor-tag' ).length <= 1 )
			jQuery( '#coauthors-list .coauthor-row .coauthors-author-options' ).addClass('hidden');
		
		return true;
	}
	
	var coauthors_edit_onclick = function(event) {
		var $tag = jQuery(this);
		
		var $co = $tag.prev();
								
		$tag.hide();
		$co.show()
			.focus()
			;
		
		$co.previousAuthor = $tag.text();
	}
	
	/*
	 * Save coauthor
	 * @param int Author ID
	 * @param string Author Name
	 * @param object The autosuggest input box
	 */
	function coauthors_save_coauthor(author, co) {
		
		// get sibling <span> and update
		co.siblings('.coauthor-tag')
			.html(author.name)
			.append(coauthors_create_author_gravatar(author))
			.show()
			;
		
		// Update the value of the hidden input
		co.siblings('input[name="coauthors[]"]').val(author.nicename);
	}
	
	
	/*
	 * Add coauthor
	 * @param string Author Name
	 * @param object The autosuggest input box
	 * @param boolean Initial set up or not?
	 */
//	function coauthors_add_coauthor(authorID, authorName, co, init, count){
	function coauthors_add_coauthor(author, co, init, count){

		// Check if editing
		if( co && co.siblings('.coauthor-tag').length ) {
			coauthors_save_coauthor(author, co);
		} else {
			// Not editing, so we create a new author entry
			if(count == 0) {
				var coName = (count == 0) ? 'coauthors-main' : '';
				// Add new author to <select>
				//coauthors_select_author( author );
			}
			var options = { addDelete: true, addEdit: false };

			// Create autosuggest box and text tag
			if(!co) var co = coauthors_create_autosuggest(author, coName)
			var tag = coauthors_create_author_tag(author);
			var input = coauthors_create_author_hidden_input(author);
			var $gravatar = coauthors_create_author_gravatar(author, 25);
			
			tag.append($gravatar);
			
			coauthors_add_to_table(co, tag, input, options);
			
			if(!init) {
				// Create new author-suggest and append it to a new row
				var newCO = coauthors_create_autosuggest('', false);
				coauthors_add_to_table(newCO);
				move_loading(newCO);
			}
		}

		co.bind('blur', coauthors_stop_editing);
		
		// Set the value for the auto-suggest box to the Author's name and hide it
		co.val(unescape(author.name))
			.hide()
			.unbind('focus')
			;
		
		return true;
	}
	
	
	/*
	 * Add the autosuggest box and text tag to the Co-Authors table
	 * @param object Autosuggest input box
	 * @param object Text tag
	 * @param 
	 */
	function coauthors_add_to_table( co, tag, input, options ) {
		if(co) {
			var $div = jQuery('<div/>')
						.addClass('suggest')
						.addClass('coauthor-row')
						.append(co)
						.append(tag)
						.append(input)
						;
			
			//Add buttons to row
			if(tag) coauthors_insert_author_edit_cells($div, options);
			
			jQuery('#coauthors-list').append($div);
		}
	}
	
	/* 
	 * Adds a delete and edit button next to an author
	 * @param object The row to which the new author should be added
	 */
	function coauthors_insert_author_edit_cells($div, options){

		var $options = jQuery('<div/>')
			.addClass('coauthors-author-options')
			;

		/*
		if(options.addEdit) {
			var editBtn = jQuery('<span></span>')
							.addClass('edit-coauthor')
							.text(coAuthorsPlusStrings.edit_label)
							.bind('click', coauthors_edit_onclick)
							;
			td.append(editBtn);
		}
		*/
		if(options.addDelete) {
			var deleteBtn = jQuery('<span/>')
								.addClass('delete-coauthor')
								.text(coAuthorsPlusStrings.delete_label)
								.bind('click', coauthors_delete_onclick)
								;
			$options.append(deleteBtn);
		}
		
		$div.append($options);
		return $div;
	}
	
	/*
	 * Creates autosuggest input box
	 * @param string [optional] Name of the author
	 * @param string [optional] Name to be applied to the input box
	 */
	function coauthors_create_autosuggest(authorName, inputName) {
	
		if(!inputName) inputName = 'coauthorsinput[]';
	
		var $co = jQuery('<input/>');
		
		$co.attr({
			'class': 'coauthor-suggest'
			, 'name': inputName
			})
			.appendTo($coauthors_div)
			/*
			.autocomplete(coauthors_all, {
				matchContains: true
				, scroll: false
				, formatItem: function(row) { return row[2] + ' ' + row[0] + ' | ' + row[1] }
				, formatResult: function(row) { return row[1]; }
			})
			*/
			.suggest(coAuthorsPlus_ajax_suggest_link, {
				onSelect: coauthors_autosuggest_select
			})
			.keydown(coauthors_autosuggest_keydown)
			;
		
		if(authorName)
			$co.attr( 'value', unescape( authorName ) );
		else
			$co.attr( 'value', coAuthorsPlusStrings.search_box_text )
				.focus( function(){ $co.val( '' ) } )
				.blur( function(){ $co.val( coAuthorsPlusStrings.search_box_text ) } )
				;
				
		return $co;
	
	}
	
	// Callback for when a user selects an author
	function coauthors_autosuggest_select() {
		$this = jQuery(this);
		var vals = this.value.split("|");
		
		var author = {}
		author.id = jQuery.trim(vals[0]);										
		author.login = jQuery.trim(vals[1]);
		author.name = jQuery.trim(vals[2]);
		author.email = jQuery.trim(vals[3]);
		author.nicename = jQuery.trim(vals[4]);
		
		if(author.id=="New") {
			//alert('Eventually, this will allow you to add a new author right from here. But it\'s not ready yet. *sigh*');
			coauthors_new_author_display(name);
		} else {
			//coauthors_add_coauthor(login, name, co);
			coauthors_add_coauthor(author, $this);
			// Show the delete button if we now have more than one co-author
			if ( jQuery( '#coauthors-list .coauthor-row .coauthor-tag' ).length > 1 )
				jQuery( '#coauthors-list .coauthor-row .coauthors-author-options' ).removeClass('hidden');
		}
	}
	
	// Prevent the enter key from triggering a submit
	function coauthors_autosuggest_keydown(e) {
		if(e.keyCode == 13) {return false;}
	}
	
	/*
	 * Blur handler for autosuggest input box
	 * @param event
	 */
	function coauthors_stop_editing(event) {
		
		var co = jQuery(this);
		var tag = jQuery(co.next());
		
		co.attr('value',tag.text());
		
		co.hide();
		tag.show();
		
	//	editing = false;
	}
	
	/*
	 * Creates the text tag for an author
	 * @param string Name of the author
	 */
	function coauthors_create_author_tag(author) {
		
		var $tag = jQuery('<span></span>')
							.html(unescape(author.name))
							.attr('title', coAuthorsPlusStrings.input_box_title)
							.addClass('coauthor-tag')
							// Add Click event to edit
							.click(coauthors_edit_onclick);
		return $tag;
	}
	
	function coauthors_create_author_gravatar(author, size) {
		
		var gravatar_link = get_gravatar_link(author.email, size);
		
		var $gravatar = jQuery('<img/>')
							.attr('alt', author.name)
							.attr('src', gravatar_link)
							.addClass('coauthor-gravatar')
							;
		return $gravatar;
	}
	
	// MD5 (Message-Digest Algorithm) by WebToolkit -- needed for gravatars
    // http://www.webtoolkit.info/javascript-md5.html
	function MD5(s){function L(k,d){return(k<<d)|(k>>>(32-d))}function K(G,k){var I,d,F,H,x;F=(G&2147483648);H=(k&2147483648);I=(G&1073741824);d=(k&1073741824);x=(G&1073741823)+(k&1073741823);if(I&d){return(x^2147483648^F^H)}if(I|d){if(x&1073741824){return(x^3221225472^F^H)}else{return(x^1073741824^F^H)}}else{return(x^F^H)}}function r(d,F,k){return(d&F)|((~d)&k)}function q(d,F,k){return(d&k)|(F&(~k))}function p(d,F,k){return(d^F^k)}function n(d,F,k){return(F^(d|(~k)))}function u(G,F,aa,Z,k,H,I){G=K(G,K(K(r(F,aa,Z),k),I));return K(L(G,H),F)}function f(G,F,aa,Z,k,H,I){G=K(G,K(K(q(F,aa,Z),k),I));return K(L(G,H),F)}function D(G,F,aa,Z,k,H,I){G=K(G,K(K(p(F,aa,Z),k),I));return K(L(G,H),F)}function t(G,F,aa,Z,k,H,I){G=K(G,K(K(n(F,aa,Z),k),I));return K(L(G,H),F)}function e(G){var Z;var F=G.length;var x=F+8;var k=(x-(x%64))/64;var I=(k+1)*16;var aa=Array(I-1);var d=0;var H=0;while(H<F){Z=(H-(H%4))/4;d=(H%4)*8;aa[Z]=(aa[Z]|(G.charCodeAt(H)<<d));H++}Z=(H-(H%4))/4;d=(H%4)*8;aa[Z]=aa[Z]|(128<<d);aa[I-2]=F<<3;aa[I-1]=F>>>29;return aa}function B(x){var k="",F="",G,d;for(d=0;d<=3;d++){G=(x>>>(d*8))&255;F="0"+G.toString(16);k=k+F.substr(F.length-2,2)}return k}function J(k){k=k.replace(/\r\n/g,"\n");var d="";for(var F=0;F<k.length;F++){var x=k.charCodeAt(F);if(x<128){d+=String.fromCharCode(x)}else{if((x>127)&&(x<2048)){d+=String.fromCharCode((x>>6)|192);d+=String.fromCharCode((x&63)|128)}else{d+=String.fromCharCode((x>>12)|224);d+=String.fromCharCode(((x>>6)&63)|128);d+=String.fromCharCode((x&63)|128)}}}return d}var C=Array();var P,h,E,v,g,Y,X,W,V;var S=7,Q=12,N=17,M=22;var A=5,z=9,y=14,w=20;var o=4,m=11,l=16,j=23;var U=6,T=10,R=15,O=21;s=J(s);C=e(s);Y=1732584193;X=4023233417;W=2562383102;V=271733878;for(P=0;P<C.length;P+=16){h=Y;E=X;v=W;g=V;Y=u(Y,X,W,V,C[P+0],S,3614090360);V=u(V,Y,X,W,C[P+1],Q,3905402710);W=u(W,V,Y,X,C[P+2],N,606105819);X=u(X,W,V,Y,C[P+3],M,3250441966);Y=u(Y,X,W,V,C[P+4],S,4118548399);V=u(V,Y,X,W,C[P+5],Q,1200080426);W=u(W,V,Y,X,C[P+6],N,2821735955);X=u(X,W,V,Y,C[P+7],M,4249261313);Y=u(Y,X,W,V,C[P+8],S,1770035416);V=u(V,Y,X,W,C[P+9],Q,2336552879);W=u(W,V,Y,X,C[P+10],N,4294925233);X=u(X,W,V,Y,C[P+11],M,2304563134);Y=u(Y,X,W,V,C[P+12],S,1804603682);V=u(V,Y,X,W,C[P+13],Q,4254626195);W=u(W,V,Y,X,C[P+14],N,2792965006);X=u(X,W,V,Y,C[P+15],M,1236535329);Y=f(Y,X,W,V,C[P+1],A,4129170786);V=f(V,Y,X,W,C[P+6],z,3225465664);W=f(W,V,Y,X,C[P+11],y,643717713);X=f(X,W,V,Y,C[P+0],w,3921069994);Y=f(Y,X,W,V,C[P+5],A,3593408605);V=f(V,Y,X,W,C[P+10],z,38016083);W=f(W,V,Y,X,C[P+15],y,3634488961);X=f(X,W,V,Y,C[P+4],w,3889429448);Y=f(Y,X,W,V,C[P+9],A,568446438);V=f(V,Y,X,W,C[P+14],z,3275163606);W=f(W,V,Y,X,C[P+3],y,4107603335);X=f(X,W,V,Y,C[P+8],w,1163531501);Y=f(Y,X,W,V,C[P+13],A,2850285829);V=f(V,Y,X,W,C[P+2],z,4243563512);W=f(W,V,Y,X,C[P+7],y,1735328473);X=f(X,W,V,Y,C[P+12],w,2368359562);Y=D(Y,X,W,V,C[P+5],o,4294588738);V=D(V,Y,X,W,C[P+8],m,2272392833);W=D(W,V,Y,X,C[P+11],l,1839030562);X=D(X,W,V,Y,C[P+14],j,4259657740);Y=D(Y,X,W,V,C[P+1],o,2763975236);V=D(V,Y,X,W,C[P+4],m,1272893353);W=D(W,V,Y,X,C[P+7],l,4139469664);X=D(X,W,V,Y,C[P+10],j,3200236656);Y=D(Y,X,W,V,C[P+13],o,681279174);V=D(V,Y,X,W,C[P+0],m,3936430074);W=D(W,V,Y,X,C[P+3],l,3572445317);X=D(X,W,V,Y,C[P+6],j,76029189);Y=D(Y,X,W,V,C[P+9],o,3654602809);V=D(V,Y,X,W,C[P+12],m,3873151461);W=D(W,V,Y,X,C[P+15],l,530742520);X=D(X,W,V,Y,C[P+2],j,3299628645);Y=t(Y,X,W,V,C[P+0],U,4096336452);V=t(V,Y,X,W,C[P+7],T,1126891415);W=t(W,V,Y,X,C[P+14],R,2878612391);X=t(X,W,V,Y,C[P+5],O,4237533241);Y=t(Y,X,W,V,C[P+12],U,1700485571);V=t(V,Y,X,W,C[P+3],T,2399980690);W=t(W,V,Y,X,C[P+10],R,4293915773);X=t(X,W,V,Y,C[P+1],O,2240044497);Y=t(Y,X,W,V,C[P+8],U,1873313359);V=t(V,Y,X,W,C[P+15],T,4264355552);W=t(W,V,Y,X,C[P+6],R,2734768916);X=t(X,W,V,Y,C[P+13],O,1309151649);Y=t(Y,X,W,V,C[P+4],U,4149444226);V=t(V,Y,X,W,C[P+11],T,3174756917);W=t(W,V,Y,X,C[P+2],R,718787259);X=t(X,W,V,Y,C[P+9],O,3951481745);Y=K(Y,h);X=K(X,E);W=K(W,v);V=K(V,g)}var i=B(Y)+B(X)+B(W)+B(V);return i.toLowerCase()};
	
	// Adapted from http://www.deluxeblogtips.com/2010/04/get-gravatar-using-only-javascript.html
	function get_gravatar_link(email, size) {
		var size = size || 80;	
		
		// need to check if page is secure or not 
		var gravatar_url = 'http://www.gravatar.com/avatar/';
		if ("https:" == document.location.protocol) {
    			// secure
			gravatar_url =  'https://secure.gravatar.com/avatar/';
		}
		return gravatar_url + MD5(email) + '.jpg?s=' + size;
	}
	
	/*
	 * Creates the text tag for an author
	 * @param string Name of the author
	 */
	function coauthors_create_author_hidden_input (author) {
		var input = jQuery('<input />')
						.attr({
							'type': 'hidden',
							'id': 'coauthors_hidden_input',
							'name': 'coauthors[]',
							'value': unescape(author.nicename)
							})
						;
		
		return input; 
	}
	
	/*
	 * Creates display for adding new author
	 * @param string Name of the author
	 */
	/*
	function coauthors_new_author_create_display ( ) {
	
		var author_window = jQuery('<div></div>')
								.appendTo(jQuery('body'))
								.attr('id','new-author-window')
								.addClass('wrap')
								.append(
									jQuery('<div></div>')
										.addClass('icon32')
										.attr('id','icon-users')
									)
								.append(
									jQuery('<h2></h2>')
										.text('Add new author')
										.attr('id', 'add-new-user')
	
									)
								.append(
									jQuery('<div/>')
										.attr('id', 'createauthor-ajax-response')
									)
								;
		
		var author_form	= jQuery('<form />')
							.appendTo(author_window)
							.attr({
								id: 'createauthor',
								name: 'createauthor',
								method: 'post',
								action: ''
							})
							;
		
		
		
		var create_text_field = function( name, id, label) {
			
			var field = jQuery('<input />')
							.attr({
								type:'text',
								name: name,
								id: id,
							})
			var label = jQuery('<label></label>')
							.attr('for',name)
							.text(label)
							
			//return {field, label};
				
		};
		
		create_field('user_login', 'user_login', 'User Name');
		create_field('first_name', 'first_name', 'First Name');
		
		//last_name
		//email
		//pass1
		//email password checkbox
		//role
	}
	*/

	var $coauthors_div = null;
		
	/**
	 * Initialize the Coauthors UI.
	 *
	 * @param array List of coauthors objects.
	 *  Each coauthor object should have the (string) properties:
	 *    login
	 *    email
	 *    name
	 *    nicename
	 */
	function coauthors_initialize(post_coauthors) {
		// Add the controls to add co-authors

		$coauthors_div = jQuery('#coauthors-edit');
	
		if( $coauthors_div.length ) {
			// Create the co-authors table
			var table = jQuery('<div/>')
				.attr('id', 'coauthors-list')
				;
			$coauthors_div.append(table);
		}

		// Select authors already added to the post
		var addedAlready = [];
		//jQuery('#the-list tr').each(function(){
		var count = 0;
		jQuery.each(post_coauthors, function() {
			coauthors_add_coauthor(this, undefined, true, count );
			count++;
		});

		// Hide the delete button if there's only one co-author
		if ( jQuery( '#coauthors-list .coauthor-row .coauthor-tag' ).length < 2 )
			jQuery( '#coauthors-list .coauthor-row .coauthors-author-options' ).addClass('hidden');

	
		// Create new author-suggest and append it to a new row
		var newCO = coauthors_create_autosuggest('', false);
		coauthors_add_to_table(newCO);
	
		$coauthors_loading = jQuery('#ajax-loading').clone().attr('id', 'coauthors-loading');
		move_loading(newCO);
	

		// Make co-authors sortable so an editor can control the order of the authors  
		jQuery('#coauthors-edit').ready(function($) {
			$( "#coauthors-list" ).sortable({
				axis: 'y',
				handle: '.coauthor-tag',
				placeholder: 'ui-state-highlight',
				items: 'div.coauthor-row:not(div.coauthor-row:last)',
				containment: 'parent',
			});
		});

	}
	

	function show_loading() {
		$coauthors_loading.css('visibility', 'visible');
	}
	function hide_loading() {
		$coauthors_loading.css('visibility', 'hidden');
	}
	function move_loading($input) {
		$coauthors_loading.insertAfter($input);
	}
	// Show laoding cursor for autocomplete ajax requests
	jQuery(document).ajaxSend(function(e, xhr, settings) {
		if( settings.url.indexOf(coAuthorsPlus_ajax_suggest_link) != -1 ) {
			// Including existing authors on the AJAX suggest link
			// allows us to filter them out of the search request
			var existing_authors = jQuery('input[name="coauthors[]"]').map(function(){return jQuery(this).val();}).get();
			settings.url = settings.url.split('&existing_authors')[0];
			settings.url += '&existing_authors=' + existing_authors.join(',');
			show_loading();
		}
	});
	// Hide laoding cursor when autocomplete ajax requests are finished
	jQuery(document).ajaxComplete(function(e, xhr, settings) {
		if( settings.url.indexOf(coAuthorsPlus_ajax_suggest_link) != -1 )
			hide_loading();
	});

	if ( 'post-php' == adminpage || 'post-new-php' == adminpage ) {
		var $post_coauthor_logins = jQuery('input[name="coauthors[]"]');
		var $post_coauthor_names = jQuery('input[name="coauthorsinput[]"]');
		var $post_coauthor_emails = jQuery('input[name="coauthorsemails[]"]');
		var $post_coauthor_nicenames = jQuery('input[name="coauthorsnicenames[]"]');
	
		var post_coauthors = [];
	
		for(var i = 0; i < $post_coauthor_logins.length; i++) {
			post_coauthors.push({
				login: $post_coauthor_logins[i].value,
				name: $post_coauthor_names[i].value,
				email: $post_coauthor_emails[i].value,
				nicename: $post_coauthor_nicenames[i].value
			});
		}

		// Remove the read-only coauthors so we don't get craziness
		jQuery('#coauthors-readonly').remove();
		coauthors_initialize(post_coauthors);
	}
	else if ( 'edit-php' == adminpage ) {

		var wpInlineEdit = inlineEditPost.edit;

		inlineEditPost.edit = function( id ) {

			wpInlineEdit.apply( this, arguments )

			// get the post ID
			var postId = 0
			if ( typeof( id ) == 'object' )
				postId = parseInt( this.getId( id ) )

			if ( postId > 0 ) {

				var $postRow = jQuery( '#post-' + postId )

				// Move the element to the appropriate position in the view
				// JS hack for core bug: https://core.trac.wordpress.org/ticket/26982
				jQuery('.quick-edit-row .inline-edit-col-left .inline-edit-col').find('.inline-edit-coauthors').remove() // remove any previously added elements
				var el = jQuery('.inline-edit-group.inline-edit-coauthors', '#edit-' + postId );
				el.detach().appendTo('.quick-edit-row .inline-edit-col-left .inline-edit-col').show();

				// initialize coauthors
				var post_coauthors = jQuery.map(jQuery('.column-coauthors a', $postRow), function(el) {
					return {
						login: jQuery(el).data('user_login'),
						name: jQuery(el).data('display_name'),
						email: jQuery(el).data('user_email'),
						nicename: jQuery(el).data('user_nicename')
					}
				})
				coauthors_initialize(post_coauthors);

			}
		}
	}

});

if( typeof(console) === 'undefined' ) {
	var console = {}
	console.log = console.error = function() {};
}