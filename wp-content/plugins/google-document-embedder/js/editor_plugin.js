(function() {
	tinymce.create('tinymce.plugins.gde', {
		init : function(ed,url) {
			// fix path
			url = url.replace('/js', '');
			
			ed.addCommand('gde_cmd', function() {
				ed.windowManager.open( {
					file : url + '/libs/lib-eddialog.php',
					width : 475 + parseInt(ed.getLang('gde.delta_width',0)),
					height : 475 + parseInt(ed.getLang('gde.delta_height',0)),	// 500 with page option
					inline : 1}, {
						plugin_url : url
					}
				)}
			);
			ed.addButton('gde', { /* LANGGDE */
				title : 'Google Doc Embedder',
				cmd : 'gde_cmd',
				image : url + '/img/gde-button.png'
			});
			ed.onNodeChange.add
				(function(ed,cm,n) {
					cm.setActive('gde',n.nodeName=='IMG')
				})
		},
		createControl : function(n,cm) {
			return null
		},
		getInfo : function() { 
			return { 
				longname : 'Google Doc Embedder',
				author : 'Kevin Davis',
				authorurl : 'https://wordpress.org/plugins/google-document-embedder/',
				infourl : 'https://wordpress.org/plugins/google-document-embedder/',
				version : "1.5"}
		}
	});
	tinymce.PluginManager.add('gde',tinymce.plugins.gde);
})();