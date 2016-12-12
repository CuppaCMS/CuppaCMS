<div class="lightbox">
    <!--
        params: url: String                                   title of lightbox
                content: String                               text / label to rich_language_content
        Example:
            cuppa.blockade({duration:0.3});
            cuppa.instance({url:'js/cuppa_html/lightbox.php', data:{content:'label'}, append:'body' });

    -->
    <?php
        include_once $_SERVER['DOCUMENT_ROOT'].$_COOKIE["web_document_path"]."administrator/classes/Cuppa.php";
        $cuppa = Cuppa::getInstance();
        $language = $cuppa->language->load("web");
        $current_language = $cuppa->language->current();
        $current_country = $cuppa->country->current();
        $content = $cuppa->langValueRich($cuppa->POST("content"), true);
        if(!$content) $content = $cuppa->POST("content");
    ?>
    <style>
        .lightbox{ position: fixed; display: block; width: 100%; max-width: 800px; height: 80%; transform: translate(-50%, -50%) !important; left: 50%; top:50%; background: #FFF; }
        .lightbox .info{ position: absolute; top: 40px; left: 20px; right: 20px; bottom: 20px; overflow: auto; }
        .lightbox .btn_close{ position: absolute; top: 15px; right: 15px; cursor: pointer; width: 13px; height: 13px; background-position: center; background-repeat: no-repeat; background-size: contain; }
        @media (max-width:800px){
            .lightbox{ top:0px; left: 0px; height: 100%; transform:none !important; }
        }
    </style>
    <script>
        lightbox = new function(){
            //++ constructior
                this.constructor = function(data){
                    jQuery("*").blur();
                    TweenMax.fromTo(".lightbox", 0.4, {alpha:0}, {alpha:1, ease:Cubic.easeOut, delay:0.2});
                    $(this.html).css("z-index", cuppa.maxZIndex()+1);                    
                }
            //--
            //++ close
                this.close = function(){
                    jQuery("*").blur();                
                    cuppa.setContent({load:false, duration:0.3, name:".lightbox", last:true});
                    cuppa.blockade({load:false, name:".blockade", duration:0.2, delay:0.2, last:true});
                }
            //--
        };
    </script>
    <div class="info">
        <?php
            if($content->content){ 
                $cuppa->echoString($content->content);
                $cuppa->echoString($content->code);
            }else{
                $cuppa->echoString($content);
            }
        ?> 
    </div>
    <div onclick="lightbox.close()" class="btn_close btn_close_gray" ></div>
</div>