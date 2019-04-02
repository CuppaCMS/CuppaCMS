<?php
    include_once $_SERVER['DOCUMENT_ROOT'].$_COOKIE["administrator_document_path"]."classes/Cuppa.php";
    $cuppa = Cuppa::getInstance(); $cuppa->user->valid("admin_login");
    $language = $cuppa->language->load();
    $table = $cuppa->dataBase->getRow($cuppa->configuration->table_prefix."tables", "table_name='".$_POST["params"]["table_name"]."'", true);
    $field_types = @json_decode(base64_decode($table->params));
    if(@!$field_types) $field_types = @json_decode($table->params);
	$columns = $cuppa->dataBase->getColums($table->table_name);
?>
<style>
    .filters{
        position: relative;
    }
    .filters .title_bar{
        margin-bottom: 10px;
        font-weight: bold;
        padding: 10px 10px;
        background: #EEE;
    }
    .filters .data{
        position: relative;
        padding: 0px 10px;
    }
</style>
<div class="filters">
    <?php $filters_ban = 0; ?>
    <?php for($i = 0; $i < count(@$columns); $i++){ ?>
        <?php if(@$field_types->{$columns[$i]}->type == "Select" || @$field_types->{$columns[$i]}->type == "Check" || @$field_types->{$columns[$i]}->type == "Radio" || @$field_types->{$columns[$i]}->type == "Language_Selector" || @$field_types->{$columns[$i]}->type == "Select_List" || @$field_types->{$columns[$i]}->type == "Date" ){?>
            <?php $filters_ban++; ?>
            <h3 class="title_bar">
                <span><?php echo $cuppa->language->getValue($columns[$i], $language);  ?></span>
            </h3>
            <div class="data">
                <?php echo $cuppa->permissions->getList(4, $table->table_name.",".$columns[$i]) ?>
            </div>
        <?php } ?>
    <?php } ?>
    <?php if(!$filters_ban){ ?>
        <div class="no_file" style="text-align: center; padding: 40px 0px;">
            <img src="templates/default/images/template/face.png" style="vertical-align: middle;"  />
            <div style="display: inline-block; text-align: left; margin-left: 10px; vertical-align: middle;">
                <h2 style="color: #777;"><?php echo $language->no_filters_available ?></h2>
                <div style="max-width: 250px; color: #AAA;"><?php echo $language->no_filters_available_messages ?></div>
            </div>
        </div>
    <?php } ?>
</div>