<?php
    include_once $_SERVER['DOCUMENT_ROOT'].$_COOKIE["administrator_document_path"]."classes/Cuppa.php";
    $cuppa = Cuppa::getInstance();
    $unique = $cuppa->utils->getUniqueString("cont_");
?>
<style>
    .<?php echo $unique ?> .box{ padding-bottom: 20px; margin-bottom: 20px; border-bottom: 1px solid #999; }
    .<?php echo $unique ?> .box .image{ width:350px; text-align: center; margin: 0px 30px; }
    .<?php echo $unique ?> .box .text{ color: #888; margin: 0px 30px; }
</style>
<div class="<?php echo $unique ?> max_width" style="max-width:960px">
    <!-- item -->
        <div class="box flex">
            <div class="image ">
                <img src="http://cdn.avocode.com/static/images/features/features-1.png" style="width:316px" />
            </div>
            <div class="text flex_box_middle" style="flex: 1;">
                <div>
                    <div class="t_18" style="color: #e74c3c;">24/7 REAL PERSON CUSTOMER SUPPORT</div>
                    <div style="margin-top: 15px;">Designing for multiple screens also means image assets in multiple formats and resolutions. How annoying right? With Avocode you don’t have to worry. Just choose to scale up or scale down your vector shapes, pick your image format and your retina graphics are ready!</div>
                    <div style="margin-top:40px;">Supported formats: <span class="label label-primary">PNG</span> <span class="label label-success">JPG</span></div>
                </div>
            </div>
        </div>
    <!-- -->
    <!-- item -->
        <div class="box flex">
            <div class="text flex_box_middle" style="flex: 1;">
                <div>
                    <div class="t_18" style="color: #e74c3c;">24/7 REAL PERSON CUSTOMER SUPPORT</div>
                    <div style="margin-top: 15px;">Designing for multiple screens also means image assets in multiple formats and resolutions. How annoying right? With Avocode you don’t have to worry. Just choose to scale up or scale down your vector shapes, pick your image format and your retina graphics are ready!</div>
                </div>
            </div>
            <div class="image ">
                <img src="http://cdn.avocode.com/static/images/features/features-2.png" style="width:316px" />
            </div>
        </div>
    <!-- -->
</div>