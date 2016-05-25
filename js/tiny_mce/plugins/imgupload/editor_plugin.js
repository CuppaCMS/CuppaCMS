(function() {
	tinymce.create('tinymce.plugins.imguploadPlugin', {
		init : function(ed, url) {
			ed.addCommand('mceimguploadPopup', function() {
				var e = ed.selection.getNode();
				if (ed.dom.getAttrib(e, 'class').indexOf('mceItem') != -1)
					return;

				ed.windowManager.open({
					file : url + '/popup.php',
					width : 450,
					height : 290,
					inline : 1
				}, {
					plugin_url : url
				});
			});
			ed.addButton('imgupload', {
				title : 'Upload file',
				cmd : 'mceimguploadPopup',
				image : url + '/images/upload.gif'
			});
		},
		getInfo : function() {
			return {
				longname  : 'Upload file',
				author    : 'TGolden Group',
				authorurl : 'http://tgoldengroup.com',
				infourl   : 'http://tgoldengroup.com',
				version   : "1.0"
			};
		}
	});
	// Register plugin
	   tinymce.PluginManager.add('imgupload', tinymce.plugins.imguploadPlugin);
})();