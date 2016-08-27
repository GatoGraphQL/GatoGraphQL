jQuery(function ($) {

	/* jQuery library for GDE */
	
	// settings tabs
	$('#gentab').click(function(e) { switchTab(e, 'gentab'); });
	$('#protab').click(function(e) { switchTab(e, 'protab'); });
	$('#advtab').click(function(e) { switchTab(e, 'advtab'); });
	$('#suptab').click(function(e) { switchTab(e, 'suptab'); });
	
	// reset gentab (tab click on profile edit)
	$('#gentab-reload').click(function(e) { window.location.replace(window.location.href); });
	
	function switchTab(e, tab) {
		e.preventDefault();
		
		// detect physical tab change to hide status messages
		if (typeof window.gdeTabChg != 'undefined') {
			$('#message').hide();
			delete window.gdeTabChg;
		}
		
		var newcontid = tab.substr(0,3) + "content";
		
		$('#' + newcontid).show();
		$('#' + tab).addClass('ui-tabs-selected ui-state-active');
		$('#' + tab).blur();
		
		if (tab !== 'gentab') {
			$('#gencontent').hide();
			$('#gentab').removeClass('ui-tabs-selected ui-state-active');
		}
		if (tab !== 'protab') {
			$('#procontent').hide();
			$('#protab').removeClass('ui-tabs-selected ui-state-active');
		}
		if (tab !== 'advtab') {
			$('#advcontent').hide();
			$('#advtab').removeClass('ui-tabs-selected ui-state-active');
		}
		if (tab !== 'suptab') {
			$('#supcontent').hide();
			$('#suptab').removeClass('ui-tabs-selected ui-state-active');
		}
		
		// record tab change
		window.gdeTabChg = true;
	}
	
	// load correct form values at page load
	
	// gen tab
	var mode = $('#viewer').val();
	updateOptions(mode);
	var title = $('#viewer').children(":selected").attr("title");
	updateHelp('viewer-h', title);
	tbSelected();
	allowPrint();
	allowSecure();
	var title = $('#link_show').children(":selected").attr("title");
	updateHelp('linkshow-h', title);
	var title = $('#tb_mobile').children(":selected").attr("title");
	updateHelp('mobile-h', title);
	
	// adv tab
	var title = $('#beta_check').children(":selected").attr("title");
	updateHelp('beta-h', title);
	var title = $('#ga_enable').children(":selected").attr("title");
	updateHelp('ga-h', title);
	toggleEd();
	toggleGa();
	
	// handle value changes
	$('#viewer').change(function() {
		var title = $(this).children(":selected").attr("title");
		updateHelp('viewer-h', title);
		var mode = $('#viewer').val();
		updateOptions(mode);
	});
	$('#gdet_h').change(function() {
		tbSelected();
	});
	$('#tb_mobile').change(function() {
		var title = $(this).children(":selected").attr("title");
		updateHelp('mobile-h', title);
		tbSelected();
	});
	$('#gdet_n').click(function() {
		tbSelected();
		allowSecure();
	});
	$('#tb_fullscr').click(function() {
		allowPrint();
		allowSecure();
	});
	$('#link_show').change(function() {
		var title = $(this).children(":selected").attr("title");
		updateHelp('linkshow-h', title);
		var lset = $('#link_show').val();
		toggleLink(lset);
		allowSecure();
	});
	$('#block').click(function() {
		if (($('#block').is(':checked')) && ($('#gdet_h').is(':checked'))) {
			$('#gdet_n').attr('checked', false); 
		}
	});
	
	$('#vw_bgcolor').attr('data-default-color', '#EBEBEB');
	$('#vw_pbcolor').attr('data-default-color', '#DADADA');
	var colorPickerOptions = { palettes: false };
	$('.gde-color-field').wpColorPicker(colorPickerOptions).removeAttr('disabled');
	
	// adv tab
	$('#ed_disable').change(function() {
		toggleEd();
	});
	$('#beta_check').change(function() {
		var title = $('#beta_check').children(":selected").attr("title");
		updateHelp('beta-h', title);
	});
	$('#ga_enable').change(function() {
		var title = $('#ga_enable').children(":selected").attr("title");
		updateHelp('ga-h', title);
		toggleGa();
	});
	
	function tbSelected() {
		allowSecure();
		if ($('#gdet_h').is(':checked')) {
			toggleTb('hide');
			toggleFs('hide');
		} else if ($('#tb_mobile').val() == "always") {
			toggleFs('hide');
			$('#allowNewWin').hide();
		} else {
			toggleTb('show');
			$('#allowNewWin').show();
			if ($('#gdet_n').is(':checked')) {
				toggleFs('show');
			} else {
				toggleFs('hide');
			}
		}
	}
	
	function allowPrint() {
		if ($('#tb_fullscr').val() == "default") {
			$('#allowPrint').hide();
			$('#blockAnon').hide();
		} else if ($('#gdet_n').is(':checked')) {
			$('#allowPrint').show();
			$('#blockAnon').show();
		}
	}
	
	function allowSecure() {
		var isSecurable = false;
		if ($('#link_show').val() == "none") {
			if ($('#gdet_h').is(':checked')) {
				isSecurable = true;
			} else if (! $('#gdet_h').is(':checked') && $('#tb_fullscr').val() !== "default") {
				isSecurable = true;
			} else if (! $('#gdet_n').is(':checked')) {
				isSecurable = true;
			}
		}
		if (isSecurable) {
			$('#linkblock').show();
		} else {
			$('#linkblock').hide();
			$('#block').attr('checked', false); 
		}
	}
	
	function updateHelp(id, text) {
		$('#' + id).html(text);
	}
	
	function updateOptions(mode) {
		if (mode == "standard") {
			$('#gde-enh-fs').fadeOut();
		} else if (mode == "enhanced") {
			$('#gde-enh-fs').fadeIn();
		}
		var lset = $('#link_show').val();
		toggleLink(lset);
	}
	
	function toggleTb(tbset) {
		if (tbset == "hide") {
			$('#mobiletb').hide();
			$('#toolbuttons').hide();
		} else {
			$('#mobiletb').show();
			$('#toolbuttons').show();
		}
	}
	
	function toggleFs(tbset) {
		if (tbset == "hide") {
			$('#fullscreen').hide();
		} else {
			$('#fullscreen').show();
		}
	}
	
	function toggleLink(lset) {
		var mode = $('#viewer').val();
		if (lset == "none") {
			$('#linktext').hide();
			$('#linkpos').hide();
			$('#linkbehavior').hide();
		} else {
			$('#linktext').show();
			$('#linkpos').show();
			$('#linkbehavior').show();
		}
	}
	
	function toggleEd() {
		if ($('#ed_disable').is(':checked')) {
			$('#ed-embed').hide();
			$('#ed-upload').hide();;
		} else {
			$('#ed-embed').show();
			$('#ed-upload').show();
		}
	}
	
	function toggleGa() {
		var ga = $('#ga_enable').val();
		if (ga == "no" || ga == "compat") {
			$('#ga-cat').hide();
			$('#ga-label').hide();
		} else {
			$('#ga-cat').show();
			$('#ga-label').show();
		}
	}
	
	/**
	 * Used for dx logging
	 */
	$('.gde-viewlog').click(function(e) {
		var id = this.id;
		id = id.replace('log-', '');
		
		var url = jqs_vars.gde_url + "libs/lib-service.php";
		
		
		// write data in new window //  onsubmit="this.target=\'_blank\'"
		var form = $('<form action="' + url + '" method="post" target="_blank"><input type="hidden" name="blogid" value="' + id + '" /><input type="hidden" name="viewlog" value="all"></form>');
		$('body').append(form);
		$(form).submit();
	});
	
	/**
	 * Used for import/export
	 */
	$('#export-submit').click(function(e) {
		e.preventDefault();
		var type = $('input[name=type]:checked', '#gde-backup').val();
		if (type == "all") {
			var url = jqs_vars.gde_url + "libs/lib-service.php?json=all&save=1";
			window.location.href = url;
		} else if (type == "profiles") {
			var url = jqs_vars.gde_url + "libs/lib-service.php?json=profiles&save=1";
			window.location.href = url;
		} else if (type == "settings") {
			var url = jqs_vars.gde_url + "libs/lib-service.php?json=settings&save=1";
			window.location.href = url;
		}
	});
	$("#import-submit").click(function(e) {
		var ext = $('#upload').val().split('.').pop().toLowerCase();
		if ($.inArray(ext, ['json']) == -1) {
			e.preventDefault();
			alert( jqs_vars.badimport );
		} else {
			var conf = confirm( jqs_vars.warnimport );
			if (!conf) {
				e.preventDefault();
			}
		}
	});
	
	/**
	 * Used to manage profiles
	 */
	$('.edit').click(function(e) {
		var id = this.id;
		id = id.replace('edit-', '');
		
		if (id !== "1") {
			e.preventDefault();
			var form = $('<form action="" method="post"><input type="hidden" name="profile" value="' + id + '" /><input type="hidden" name="action" value="edit"></form>');
			$('body').append(form);
			$(form).submit();
		}
	});
	$('.delete').click(function(e) {
		e.preventDefault();
		var id = this.id;
		id = id.replace('delete-', '');
		
		var conf = confirm( jqs_vars.delete );
		if (conf) {
			var form = $('<form action="" method="post"><input type="hidden" name="profile" value="' + id + '" /><input type="hidden" name="action" value="delete"></form>');
			$('body').append(form);
			$(form).submit();
		}
	});
	$('.default').click(function(e) {
		e.preventDefault();
		var id = this.id;
		id = id.replace('default-', '');
		
		var conf = confirm( jqs_vars.default );
		if (conf) {
			var form = $('<form action="" method="post"><input type="hidden" name="profile" value="' + id + '" /><input type="hidden" name="action" value="default"></form>');
			$('body').append(form);
			$(form).submit();
		}
	});
	
	/**
	 * Used to handle debug information
	 */
	// show/hide debug info
	$("#ta_toggleon").click(function() {
		$("#debugblock").show();
		$("#dbinfo-show").hide();
		$("#dbinfo-hide").show();
	});
	$("#ta_toggleoff").click(function() {
		$("#debugblock").hide();
		$("#dbinfo-show").show();
		$("#dbinfo-hide").hide();
	});
	
	// warn on debug deselection
	$('#senddb').click(function() {
		if (this.checked) {
			$("#debugwarn").hide();
		} else {
			$("#debugwarn").show();
		}
	});
	
	// validate input
	$("#debugsend").click(function(e) {
		$(".err").hide();
		
		var em = $("#sender").val();
		if (em == "" || (!validateEmail('sender')) ) {
			$("#err_email").show();
			$("#sender").focus();
			return false;
		}
		
		e.preventDefault();
		
		if ($('#sc').val() !== "" && $('#msg').val() == "") {
			alert( jqs_vars.baddebug );
		} else if ($('#msg').val() == "") {
			alert( jqs_vars.baddebug );
		} else {
			$("#debugsend").attr("disabled","true");
			$("#debugsend").attr('class', 'button-secondary');
			$("#debugsend").css('outline','0')
			$("#formstatus").show();
			
			submitDebugForm();
		}
	});
	
	// submit handler
	function submitDebugForm() {
		
		// get form data
		var url = $('#debugForm').attr("action");
		var name = $('#sender_name').val();
		var debug = $('#debugtxt').val();
		var email = $('#sender').val();
		var sc = $('#sc').val();
		var eurl = $('#url').val();
		var msg = $('#msg').val();
			
		// check for debug info
		if ($('#senddb').is(':checked')) {
			var senddb = debug;
		} else {
			var senddb = '';
		}
		
		// check for cc
		if ($('#cc').is(':checked')) {
			var sendcc = "yes";
		} else {
			var sendcc = "no";
		}
		
		// post the data
		$.post(url, { 
			name: name,
			email: email,
			sc: sc,
			url: eurl,
			msg: msg,
			senddb: senddb,
			cc: sendcc
			}, function(data) {
				if (data == "success") {
					var notice = $("#formstatus").html();
					notice = notice.replace('in-proc.gif', 'done.gif');
					$("#formstatus").empty().append(notice);
					$("#doneconfirm").show();
				} else {
					var notice = $("#formstatus").html();
					notice = notice.replace('in-proc.gif', 'fail.gif');
					$("#formstatus").empty().append(notice);
					$("#failconfirm").show();
				}
		});
	}
});

function validateEmail(txtEmail){
	var a = document.getElementById(txtEmail).value;
	var filter = /^((\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*?)\s*;?\s*)+/;
	if (filter.test(a)) {
		return true;
	} else {
		return false;
	}
}