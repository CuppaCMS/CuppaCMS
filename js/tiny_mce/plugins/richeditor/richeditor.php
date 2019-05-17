<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Rich editor</title>
        <script type="text/javascript" src="../../tiny_mce_popup.js"></script>
        <script src="src-noconflict/ace.js"></script>
        <script src="src-noconflict/ext-language_tools.js"></script>
        <script src="jquery.js"></script>
        <script src="cuppa.js"></script>
        <script src="code_beautify.js"></script>
	</head>
	<body>
		<div class="mceActionPanel" style="overflow: hidden;">
			<div style="overflow: hidden;float: right;">
                <div style="float: left; padding-right: 5px;"><span>Live:</span> <input checked="checked" type="checkbox" id="auto_update" style="position: relative; top: 4px;" /></div>
                <input type="submit" role="button" value="Update" id="insert" onclick="saveContent()" style="float: left;" />
                <input type="submit" role="button" value="Apply" id="insert" onclick="saveContent(true)" style="float: left;" />
            </div>
        </div>
        <style>
            #editor{ position: absolute; top: 40px; bottom: 0px; left: 0px; right: 0px; border: 1px solid #DDD; }
        </style>
        <div id="editor"></div>
        <script>
            langTools = ace.require("ace/ext/language_tools");
            var cssClasses = tinyMCEPopup.editor.dom.getClasses();
            var customCompleter = {
                getCompletions: function(editor, session, pos, prefix, callback) {
                    console.log("prefix", prefix, pos)
                    for(var n = 0; n < cssClasses.length; n++){
                        var className = cssClasses[n].class;
                        if(className.indexOf(prefix) != -1){
                            callback(null, [{name: className, value: className, score: 1, meta: "local"}]);
                        }
                    }
                }
            }
            langTools.addCompleter(customCompleter);

            var editor = ace.edit("editor");
                editor.setTheme("ace/theme/twilight");
                editor.getSession().setMode("ace/mode/html");
                //editor.completers = ["tufik", "chediak"];
                console.log(editor.completers)
                editor.setOptions({enableBasicAutocompletion: true, enableSnippets: true, enableLiveAutocompletion: true});
            //++ set value
                var code = tinyMCEPopup.editor.getContent({source_view : true});
                code = cuppa.replace(code, "<!--", "", {range:["<style", "</style>"]});
                code = cuppa.replace(code, "-->", "", {range:["<style", "</style>"]});
                code = vkbeautify.xml(code);
                editor.getSession().setValue(code);
            //--
            //++ save content
                function saveContent(close) {
                    var code = editor.getSession().getValue();
                	tinyMCEPopup.editor.setContent(code, {source_view : true});
                    tinyMCEPopup.editor.save();
                	if(close) tinyMCEPopup.close();
                }
            //--
            //++ c+s save
                ctr_s = function(event){
                    if((event.ctrlKey || event.metaKey) && event.which == 83) {
                        event.preventDefault();
                        tinyMCEPopup.editor.settings.save();
                        return false;
                    };
                }; $(document).off("keydown").on("keydown", ctr_s);
            //--
            //++ auto update content (realtime)
                /*
                editor.addEventListener('change', function(e){
                    var checked = document.getElementById("auto_update").checked;
                    if(checked){ saveContent(false); }
                });
                */
            //--
            //++ auto update content (by seconds)
                var timeout = null;
                function updateLoop(){ saveContent(false); timeout = setTimeout(updateLoop, 2000); }
                function onAutoUpdateLoop(){
                    var checked = document.getElementById("auto_update").checked;
                    if(checked) updateLoop(); 
                    else clearTimeout(timeout);
                }; $("#auto_update").bind("change", onAutoUpdateLoop); onAutoUpdateLoop();
                /**/
            //--
            //++ disabl ESC key,
                $("body").on("keyup", function(e){ if (e.which === 27) return false; });
            //--
            //++ change mode
                function changeMode(){
                    //++ before
                        var Range = ace.require("ace/range").Range;
                        var cursor = editor.selection.getCursor();
                        var range = new Range(0,0,cursor.row,cursor.column);
                        var text1 = editor.session.getTextRange(range);
                            text1 = text1.replace("<!--", "");
                            text1 = text1.replace("-->", "");
                            text1 = text1.replace("<![CDATA[", "");
                            text1 = text1.replace("!--", "");
                            text1 = text1.replace("// ]]>", "");
                    //--
                    //++ inside "" tags
                        var html_tag = "";
                            html_tag = text1.split("<"); 
                            html_tag = html_tag[html_tag.length-1];
                            html_tag = html_tag.split(">");
                            html_tag = (html_tag.length == 1) ? html_tag[0] : null;
                        try{
                            html_tag = html_tag.split('"'); 
                            html_tag.pop();
                            html_tag = html_tag.pop();
                            html_tag = jQuery.trim(html_tag.replace("=",""));
                            html_tag = html_tag.split(" ");
                            html_tag = html_tag.pop();
                        }catch(err){  }
                        html_tag = (html_tag == "script") ? html_tag : null;
                    //--
                    //++ normal tags
                        if(!html_tag){
                            try{
                                html_tag = text1.split("<");
                                html_tag = html_tag.filter(function(item){ if(cuppa.trim(item.charAt(0)) != "" && cuppa.trim(item.charAt(0)) != "=" ) return cuppa.trim(item); });
                                html_tag = html_tag[html_tag.length-1];
                                html_tag = html_tag.split("/");
                                html_tag = html_tag[0];
                                html_tag = html_tag.split(">");
                                html_tag = html_tag[0];
                                html_tag = html_tag.split(" ");
                                html_tag = html_tag[0];
                                html_tag = html_tag.replace(/(\r\n|\n|\r)/gm,"");
                            }catch(err){ }
                        }
                    //--
                    //++ set code
                        mode = "ace/mode/html";
                        editor.getSession().setMode(mode);
                    //--
                }; $("#editor").bind("keydown click focus keyup", changeMode);
            //--
        </script>
	</body>
</html>
