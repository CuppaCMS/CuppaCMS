<div class="alert">
    <?php
        include_once $_SERVER['DOCUMENT_ROOT'].$_COOKIE["administrator_document_path"]."classes/Cuppa.php";
        $cuppa = Cuppa::getInstance();
        if(!@$path) $path = $cuppa->utils->getUrlVars(@$_POST["path"]);
        $language = $cuppa->language->load();
    ?>
    <style>
        .alert{
            position: fixed;
            display: block;
            width: 100%;
            max-width: 420px;
            padding: 10px;
            color: #333;
        }
        .alert .space{
            position: relative;
            display: block;
            border-radius: 3px;
            background: #FFF;
            overflow: hidden;
            padding: 20px 30px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
        }
        .alert .btn_close{
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
        .alert .text1{
            font-size: 18px;
            line-height: normal;
            font-weight: bold;
        }
        .alert .text2{
            margin: 10px 0px 20px;
            line-height: normal;
        }
        .alert .alert_buttons{
            position: relative;
            text-align: center;
        }
        .alert .btn_accept{
            border: 1px solid #3079ED;
            color: #FFF;
            border-radius: 3px;
            text-shadow: 0px 1px 1px rgba(0,0,0,0.2);
            cursor: pointer;
            background: #4d90fe; /* Old browsers */
            background: -moz-linear-gradient(top,  #4d90fe 0%, #4787ee 100%); /* FF3.6+ */
            background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#4d90fe), color-stop(100%,#4787ee)); /* Chrome,Safari4+ */
            background: -webkit-linear-gradient(top,  #4d90fe 0%,#4787ee 100%); /* Chrome10+,Safari5.1+ */
            background: -o-linear-gradient(top,  #4d90fe 0%,#4787ee 100%); /* Opera 11.10+ */
            background: -ms-linear-gradient(top,  #4d90fe 0%,#4787ee 100%); /* IE10+ */
            background: linear-gradient(to bottom,  #4d90fe 0%,#4787ee 100%); /* W3C */
            filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#4d90fe', endColorstr='#4787ee',GradientType=0 ); /* IE6-9 */
            user-select: none;
            -moz-user-select: none;
            -webkit-user-select: none;
            height: 29px;
            padding: 0px 19px;
            text-transform: capitalize;
            width: auto;
            font-style: normal;
        }
        .alert .btn_accept:hover{
            border: 1px solid #196AEB;
            box-shadow: 0px 1px 2px rgba(0,0,0,0.20);
            background: #4a8dfc; /* Old browsers */
            background: -moz-linear-gradient(top,  #4a8dfc 0%, #337bed 100%); /* FF3.6+ */
            background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#4a8dfc), color-stop(100%,#337bed)); /* Chrome,Safari4+ */
            background: -webkit-linear-gradient(top,  #4a8dfc 0%,#337bed 100%); /* Chrome10+,Safari5.1+ */
            background: -o-linear-gradient(top,  #4a8dfc 0%,#337bed 100%); /* Opera 11.10+ */
            background: -ms-linear-gradient(top,  #4a8dfc 0%,#337bed 100%); /* IE10+ */
            background: linear-gradient(to bottom,  #4a8dfc 0%,#337bed 100%); /* W3C */
            filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#4a8dfc', endColorstr='#337bed',GradientType=0 ); /* IE6-9 */
        }
        .alert input[type=button]:active{
            box-shadow: inset 0px 1px 3px rgba(0,0,0,0.20) !important;
            background: #2e76e8;
        }
    </style>
    <script>
        alert = {}
        //++ resize
            alert.resize = function(){
               var dimentions = cuppa.dimentions(".alert");
               jQuery(".alert").css("left", ( jQuery(window).width() - dimentions.width )*0.5 );
               jQuery(".alert").css("top", ( jQuery(window).height() - dimentions.height )*0.5 );
            }; 
        //--
        //++ close
            alert.close = function(){
                jQuery("*").blur();
                cuppa.setContent({load:false, duration:0.3, name:".alert", last:true});
                cuppa.blockade({load:false, name:".blockade", duration:0.2, delay:0.2, last:true});
            }
        //--
        //++ end
            alert.end = function(){
                cuppa.removeEventGroup("alert");
            }; cuppa.addRemoveListener(".alert", alert.end);
        //--
        //++ init
            alert.init = function(){
                cuppa.addEventListener("resize", alert.resize, window, "alert"); alert.resize();
                $(".alert").css("z-index", cuppa.maxZIndex()+1);
                jQuery("*").blur();
                TweenMax.fromTo(".alert", 0.4, {alpha:0}, {alpha:1, ease:Cubic.easeOut, delay:0.2});
            }; cuppa.addEventListener("ready",  alert.init, document, "alert");
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
            <div onclick="alert.close()" class="btn_close" style="background-image: url(<?php echo $cuppa->getPath() ?>/js/cuppa/cuppa_images/close_gray.png)" ></div>
            <div class="alert_buttons">
                <button class="btn_accept" type="button" onclick="alert.close()" ><?php echo $cuppa->language->getValue("accept", $language);  ?></button>
            </div>
        <?php } ?>
    </div>
</div>