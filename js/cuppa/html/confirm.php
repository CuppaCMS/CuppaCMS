<div class="confirm">
    <?php
        include_once $_SERVER['DOCUMENT_ROOT'].$_COOKIE["administrator_document_path"]."classes/Cuppa.php";
        $cuppa = Cuppa::getInstance();
        if(!@$path) $path = $cuppa->utils->getUrlVars(@$_POST["path"]);
        $language = $cuppa->language->load();
    ?>
    <style>
        .confirm{
            position: fixed;
            display: block;
            width: 100%;
            max-width: 420px;
            padding: 10px;
            color: #333;
        }
        .confirm .space{
            position: relative;
            display: block;
            border-radius: 3px;
            background: #FFF;
            overflow: hidden;
            padding: 20px 30px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
        }
        .confirm .btn_close{
            position: absolute;
            top: 20px;
            right: 20px;
            cursor: pointer;
            width: 13px;
            height: 13px;
            background-position: center;
            background-repeat: no-repeat;
            background-size: contain;
        }
        .confirm .text1{
            font-size: 18px;
            line-height: normal;
            font-weight: bold;
        }
        .confirm .text2{
            margin: 10px 0px 20px;
            line-height: normal;
        }
        .confirm .confirm_buttons{
            position: relative;
            text-align: center;
        }
        .confirm .btn_accept{
            border: 1px solid #3079ED;
            color: #FFF;
            border-radius: 3px;
            text-shadow: 0px 1px 1px rgba(0,0,0,0.2);
            cursor: pointer;
            background: #4d90fe;
            background: linear-gradient(to bottom,  #4d90fe 0%,#4787ee 100%);
            user-select: none;
            -moz-user-select: none;
            -webkit-user-select: none;
            height: 29px;
            padding: 0px 19px;
            text-transform: capitalize;
            width: auto;
            font-style: normal;
        }
            .confirm .btn_accept:hover{
                border: 1px solid #196AEB;
                box-shadow: 0px 1px 2px rgba(0,0,0,0.20);
                background: #4a8dfc;
                background: linear-gradient(to bottom,  #4a8dfc 0%,#337bed 100%); 
            }
            
        .confirm .btn_cancel{
            border: 1px solid #C6322A !important;
            border-radius: 3px !important;
            color: #FFF !important;
            text-shadow: 0px 1px 1px rgba(0,0,0,0.2);
            cursor: pointer;
            background: #dd4b39;
            background: linear-gradient(to bottom,  #dd4b39 0%,#dd4b39 100%); 
            user-select: none;
            -moz-user-select: none;
            -webkit-user-select: none;
            height: 29px !important;
            text-transform: capitalize;
            width: auto;
        }
            .confirm .btn_cancel:hover{
                border: 1px solid #B12D26 !important;
                box-shadow: 0px 1px 2px rgba(0,0,0,0.20) !important;
                background: #db4a38;
                background: linear-gradient(to bottom,  #db4a38 0%,#c13e2c 100%);
            }
        
        
        .confirm input[type=button]:active{
            box-shadow: inset 0px 1px 3px rgba(0,0,0,0.20) !important;
            background: #2e76e8;
        }
    </style>
    <script>
        confirm = {}
        //++ resize
            confirm.resize = function(){
               var dimentions = cuppa.dimentions(".confirm");
               jQuery(".confirm").css("left", ( jQuery(window).width() - dimentions.width )*0.5 );
               jQuery(".confirm").css("top", ( jQuery(window).height() - dimentions.height )*0.5 );
            }; 
        //--
        //++ accept
            confirm.accept = function(){
                jQuery("*").blur();                
                cuppa.setContent({load:false, duration:0.3, name:".confirm", last:true});
                cuppa.blockade({load:false, name:".blockade", duration:0.2, delay:0.2, last:true});
                cuppa.shareObject(true);
                $(confirm).trigger("response", [true]);
            }
        //--
        //++ close
            confirm.close = function(){
                jQuery("*").blur();                
                cuppa.setContent({load:false, duration:0.3, name:".confirm", last:true});
                cuppa.blockade({load:false, name:".blockade", duration:0.2, delay:0.2, last:true});
                cuppa.shareObject(false);
                $(confirm).trigger("response", [false]);
            }
        //--
        //++ end
            confirm.end = function(){
                cuppa.removeEventGroup("confirm");
            }; cuppa.addRemoveListener(".confirm", confirm.end);
        //--
        //++ init
            confirm.init = function(){
                cuppa.addEventListener("resize", confirm.resize, window, "confirm"); confirm.resize();
                $(".confirm").css("z-index", cuppa.maxZIndex()+1);
                jQuery("*").blur();
                TweenMax.fromTo(".confirm", 0.4, {alpha:0}, {alpha:1, ease:Cubic.easeOut, delay:0.2});
            }; cuppa.addEventListener("ready",  confirm.init, document, "confirm");
        //--
    </script>
    <div class="space">
        <?php if(@$_POST["title"]){ ?>
            <div class="text1"><?php echo $cuppa->echoString($_POST["title"]) ?></div>
        <?php } ?>
        <?php if(@$_POST["message"]){ ?>
            <div class="text2"><?php echo $cuppa->echoString($_POST["message"]) ?></div>
        <?php } ?>
        <?php if(@$_POST["hide_buttons"] != "true"){ ?>
            <div onclick="confirm.close()" class="btn_close" style="background-image: url(<?php echo $cuppa->getPath() ?>/js/cuppa/cuppa_images/close_gray.png)" ></div>
            <div class="confirm_buttons">
                <button class="btn_cancel" type="button" onclick="confirm.close()" ><?php echo $cuppa->language->getValue("cancel", $language);  ?></button>
                <button class="btn_accept" type="button" onclick="confirm.accept()" ><?php echo $cuppa->language->getValue("accept", $language);  ?></button>
            </div>
        <?php } ?>
    </div>
</div>