<?php
    require_once "../../config.php";
?>
<template id="com.cuppa.fileManager.UploadModal" >
    <style>body{ overflow: hidden !important; }</style>
    <div class="blockade cover-fixed p-x-20 p-y-20 o-auto ani-fade" style="z-index: 2">
        <div class="c-a-1">
            <div class="c-a-2">
                <div class="modal m-w-800" >
                    <!-- Header -->
                    <div class="header flex flex-justify-between flex-align-center z-index-1" >
                        <h1 class="t-22 p-l-20">Upload</h1>
                        <div id="btnClose" class="buttonIcon min-height-50 min-width-50" style="border-radius: 0;">
                            <i class="fas fa-times"></i>
                        </div>
                    </div>
                    <!-- -->
                    <!-- content -->
                    <div class="flex-grow bg2 p-x-20 p-y-20" >
                        <div id="btnUpload" class="drag-area bg1 min-height-350 border-radius-3">
                            <div class="frame cover m-x-20 m-y-20 border-radius-3 flex flex-column flex-justify-center color6" >
                                <i class="fas fa-upload t-40 color7 o-75 icon"></i>
                                <p class="t-18 m-t-10">Drag & Drop File Here</p>
                                <div id="statusBar" class="statusBar cover border-radius-3 absolute m-x-10 m-b-10 o-hidden" style="top:auto; height: 10px; display: none;" >
                                    <div id="progressBar" class="bar cover bg7" style="right:auto; width: 0"></div>
                                </div>
                            </div>
                        </div>
                        <input id="txtURL" class="input1 m-t-10" placeholder="URL Path" value="" >
                        <div class="t-right m-t-10">
                            <button id="btnCancel" class="button1 type2 m-r-5">Close</button>
                            <button id="btnCopy" class="button1">Copy</button>
                        </div>
                    </div>
                    <!-- -->
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    (function() {
        class UploadModal extends HTMLElement {

            constructor() {
                super(); cuppa.componentAdjust(this,'com.cuppa.fileManager.UploadModal');
                cuppa.on([this.btnClose, this.btnCancel], "click", this.close);
                cuppa.on(this.btnCopy, "click", this.copy);
                cuppa.loadJS(["js/fileupload/jquery.js"], ()=>{ cuppa.loadJS(["js/fileupload/jquery.ui.widget.js", "js/fileupload/jquery.iframe-transport.js", "js/fileupload/jquery.fileupload.js"], this.initUpload); });
            }

            initUpload(){
                cuppa.fileUpload(this.btnUpload, {folder:cuppa.getData(PATH_CU_FM).path, php_path:'<?php echo $phpPath ?>', start:this.uStart, progress:this.uProgress, callback:this.uComplete});
            }

            uStart(data){
                cuppa.css(this.statusBar, {display:"block"});
                cuppa.css(this.progressBar, {width:0});
            }

            uProgress(data){
                cuppa.css(this.progressBar, {width:data.progress+"%"});
            }

            uComplete(data){
                if(data.data.error){ this.txtURL.value = data.data.error; return; }
                
                fileManager.reload();
                let url = "media/" + cuppa.getData(PATH_CU_FM).path + "/" +data.data.name;
                    url = cuppa.replace(url,"///","/");
                    url = cuppa.replace(url,"//","/");
                this.txtURL.value = url;
            }
            
            copy(){
                cuppa.copy(this.txtURL, {success:()=>{ this.btnCopy.innerHTML = "Copied"  } });
            }

            close(){
                cuppa.remove(this);
            }
        }
        customElements.define('upload-modal', UploadModal);
    })();
</script>