<div class="api_keys_edit">
    <?php
        include_once "../classes/Cuppa.php";
        $cuppa = Cuppa::getInstance();
        $current_language = $cuppa->language->current();
        $current_country = $cuppa->country->current();
    ?>
    <style>
        .api_keys_edit{ }
        .window_right .btn_generate{ display: inline-block !important; float:none; width:29px; height:29px;  background: none; border: 0px; border-radius: 50px; margin-left: 5px;}
        .window_right .tr_Key .td_field *{ vertical-align:  middle; }
    </style>
    <script>
        api_keys_edit = new function(){
            // generate
                this.generate = function(){
                    var key = cuppa.randomString(32);
                    $(".window_right #key_field").val(key);
                }.bind(this);
            // constructor
                this.constructor = function(){
                    $(".window_right #key_field").after( $(".window_right .btn_generate") );
                    if( !$(".window_right #key_field").val() ) this.generate();
                }.bind(this); $(this.constructor);
        };
    </script>
    <a class="tool_button tooltip btn_generate" title="<?php echo $language->update_key ?>" onclick="api_keys_edit.generate()" ><span style="color: #DDD;">f</span></a>
</div>