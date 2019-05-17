(function() {
	tinymce.create('tinymce.plugins.richeditor', {
		init : function(ed, url) {
		  ed.addCommand('mceRicheditor', function() {
				ed.windowManager.open({
					file : url + '/richeditor.php',
					width : 900,
					height : 570,
					inline : 1
				}, 
				{
					plugin_url : url
				});
			});
			ed.addButton('richeditor', {
				title : 'Rich editor',
				cmd : 'mceRicheditor',
				image : url + '/icon.gif'
			});
		},
		createControl : function(n, cm) {
			return null;
		},
		getInfo : function() {
			return {
				longname : 'Rich editor',
				author : 'Tufik Chediak',
				authorurl : 'http://cloudbitinteractive.com/',
				infourl : 'http://cloudbitinteractive.com/',
				version : "1.0"
			};
		}
	});
	tinymce.PluginManager.add('richeditor', tinymce.plugins.richeditor);
})();