//tinyMCEPopup.requireLangPack();
	
var GDEInsertDialog = {
	init : function() {
		var f = document.forms[0];
        var shortcode;

				jQuery('#url').blur(function(){
					update_sc();
					check_uri();
					update_insert();
				});
				
				jQuery('#profile').change(function(){
					update_sc();
				});
				
				//jQuery('.use_defaults').change(function(){
				//	use_defaults();
				//});
				
				jQuery('#height').blur(function(){
					update_sc();
				});
				
				jQuery('#width').blur(function(){
					update_sc();
				});
				
				//jQuery('#page').blur(function(){
				//	update_sc();
				//});
				
				jQuery('.save').click(function(){
					 update_sc();
				});
				
				jQuery('.disable_cache').click(function(){
					 update_sc();
				});
		
		function check_uri() {
			if ( jQuery('#url').val() !='' ) {
				jQuery.getJSON('../libs/lib-exts.php', function(data) {
					var types = [];
					jQuery.each(data, function(key, val) {
						types.push(key);
					});
					
					var alltypes = types.join("|");
					var typeCheck = new RegExp("\.(" + alltypes + ")$", "i");
					var matches = typeCheck.exec(jQuery('#url').val());
					if (matches == null) {
						// no matches, unsupported file type
						jQuery('#uri-note-file').show();
						jQuery('#uri-note-base').hide();
					} else {
						var path_regex = /^http/i;
						
						if( !(path_regex.test( jQuery('#url').val() )) ) {
							// file base url appended
							jQuery('#uri-note-file').hide();
							jQuery('#uri-note-base').show();
						} else {
							jQuery('#uri-note-file').hide();
							jQuery('#uri-note-base').hide();
						}
					}
				});
			} else {
				jQuery('#uri-note-file').hide();
				jQuery('#uri-note-base').hide();
			}
		}
		
		/*function use_defaults() {
			if ( jQuery('.use_defaults').is(':checked') ) {
				jQuery('.ovrride').hide();
			} else {
				jQuery('.ovrride').show();
			}
			update_sc();
		}*/
		
		function update_insert() {
			if ( jQuery('#shortcode').val() !='' ) {
				jQuery('#insert').removeAttr('disabled');
				jQuery('#insert').removeAttr('class');
			} else {
				jQuery('#insert').attr('disabled','disabled');
				jQuery('#insert').attr('class','disabled');
			}
		}
		
		function update_sc() {
			 shortcode = 'gview';
			 
				if ( jQuery('#url').val() !='' ) {
					check_uri();
					shortcode = shortcode + '  file="'+jQuery('#url').val()+'"';
				} else {
					jQuery('#uri-note').html('');
					jQuery('#shortcode').val('');
					update_insert();
					return;
				}
				
				if (( jQuery('#profile').val() != "1" )) {
					shortcode = shortcode + '  profile="'+jQuery('#profile').val()+'"';
				}
				
				if ( ! jQuery('.use_defaults').is(':checked') ) {
				
					var height = jQuery('#height').val();
					var width = jQuery('#width').val();
					
					if ( height != 0  && height != null ) {
						height = sanitize(height);
						jQuery('#height').val(height);
						shortcode = shortcode + '  height="'+height+'"';
					}
					
					if ( width != 0  && width != null ) {
						width = sanitize(width);
						jQuery('#width').val(width);
						shortcode = shortcode + '  width="'+width+'"';
					}
					
					//if (( jQuery('#page').val() != "1" ) & ( jQuery('#page').val() ) !='') {
					//	shortcode = shortcode + '  page="'+jQuery('#page').val()+'"';
					//}
					
					if ( jQuery("input[name='save']:checked").val() == '1') {
						shortcode = shortcode + '  save="1"';
					} else if ( jQuery("input[name='save']:checked").val() == '0') {
						shortcode = shortcode + '  save="0"';
					}
					
					if ( jQuery('.disable_cache').is(':checked') ) {
						shortcode = shortcode + ' cache="0"';
					}
				
				}
				
				var newsc = shortcode.replace(/  /g,' ');
				
				jQuery('#shortcode').val('['+newsc+']');
				update_insert();
		}
		
		function sanitize( dim ) {
			if (dim.indexOf("%") == -1) {
				dim = dim.replace(/[^0-9]/g, '');
				dim += "px";
			} else {
				dim = dim.replace(/[^0-9]/g, '');
				dim += "%";
			}
			
			return dim;
		}
	},
	insert : function() {
		// insert the contents from the input into the document
		tinyMCEPopup.editor.execCommand('mceInsertContent', false, jQuery('#shortcode').val());
		tinyMCEPopup.close();
	}
};

tinyMCEPopup.onInit.add(GDEInsertDialog.init, GDEInsertDialog);