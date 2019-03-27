<?php
    include_once $_SERVER['DOCUMENT_ROOT'].$_COOKIE["administrator_document_path"]."classes/Cuppa.php";
    $cuppa = Cuppa::getInstance();
    $language = $cuppa->language->load();
    $current_language = $cuppa->language->current();
    if(!@$path) $path = $cuppa->utils->getUrlVars(@$_POST["path"]);
    $sql = "SELECT * FROM (
            SELECT 1 as id, 'home' as title, 0 as parent_id, s.contents, 0 as `order` FROM {$cuppa->configuration->table_prefix}content_by_sections as s
            WHERE section = 0
            UNION
            SELECT m.id, m.title, m.parent_id, s.contents, m.`order` FROM {$cuppa->configuration->table_prefix}menu_items as m
            JOIN {$cuppa->configuration->table_prefix}content_by_sections as s ON s.section = m.id
            WHERE m.menus_id NOT IN (1,2)    
            UNION
            SELECT m.id, m.title, m.parent_id, '' as contents, m.`order` FROM {$cuppa->configuration->table_prefix}menu_items as m
            WHERE m.menus_id NOT IN (1,2)    
        ) as data GROUP BY id  ORDER BY parent_id, `order` ASC";
    $sections = $cuppa->dataBase->sql($sql, true);
    $sections = $cuppa->utils->tree($sections, "id", "parent_id", "title");
?>
<div class="content_list">
    <style>
        .content_list{ }
    </style>
    <script>
        content_list = {}
        //++ submit
            content_list.submit = function(){
                if($(".filter_section select").val() != ""){
                    var cond = "<?php echo @$view ?>.id IN ("+$(".filter_section select option:selected").attr("data")+")";
                    $(".filter_section input[name=custom_condition]").val(cond);
                }else{
                    $(".filter_section input[name=custom_condition]").val("");
                }
                jQuery("#page").val(0); jQuery("#form").submit();
            }
        //--
        //++ resize
            content_list.resize = function(){ }; 
        //--
        //++ end
            content_list.removed = function(e){ cuppa.removeEventGroup("content_list"); }
        //--
        //++ init
            content_list.init = function(){
                cuppa.addEventListener("resize", content_list.resize, window, "content_list"); content_list.resize(); $(".content_list img").load(content_list.resize); TweenMax.delayedCall(0.1, content_list.resize);
                cuppa.addEventListener("removed", content_list.removed, ".content_list", "content_list");
                $(".filter_section input").val( "<?php echo $cuppa->POST("custom_condition"); ?>" );
                $(".filter_section select").val("<?php echo $cuppa->POST("section"); ?>");
                cuppa.selectStyle(".filter_section select", true);
                $(".form_list .right").prepend(  $(".filter_section") );
            }; cuppa.addEventListener("ready",  content_list.init, document, "content_list");
        //--
    </script>
    <div class="filter_section filter_content" style="margin-bottom: 5px; float: left;">
        <select name="section" onchange='content_list.submit()' width="200px" >
            <option value=""><?php echo $language->section ?></option>
            <?php forEach($sections as $index => $item){ ?>
                <?php
                    $item = (object) $item;
                    $ids = join(",", json_decode($item->contents));
                ?>
                <option value="<?php echo $index ?>" data="<?php echo $ids ?>"><?php echo $item->deep_string.$item->title ?></option>
            <?php } ?>
        </select>
        <input name="custom_condition" value="" type="hidden" />
    </div>
</div>