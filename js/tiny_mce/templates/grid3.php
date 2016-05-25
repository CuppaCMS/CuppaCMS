<?php
    include_once $_SERVER['DOCUMENT_ROOT'].$_COOKIE["administrator_document_path"]."classes/Cuppa.php";
    $cuppa = Cuppa::getInstance();
    $unique = $cuppa->utils->getUniqueString("cont_");
?>
<style>
    .<?php echo $unique ?> .box{ width: 33.33%; text-align: center; margin-bottom: 30px; }
    .<?php echo $unique ?> .box .image{ margin-bottom: 10px; padding: 20px; border: 1px solid #aaa; }
    .<?php echo $unique ?> .box .card{ max-width: 320px; margin: 0px 20px;  padding: 20px; }
</style>
<div class="<?php echo $unique ?> flex max_width flex_align_start">
    <!-- item -->
    <div class="box flex">
        <div class="card">
            <div class="image">image</div>
            <div class="t_18">Supports Photoshop</div>
            <div>Everything created in PS or Sketch is preserved &mdash; including all layers, smart objects, pages, or artboards.</div>
        </div>
    </div>
    <!-- -->
    <!-- item -->
    <div class="box flex">
        <div class="card">
            <div class="image">image</div>
            <div class="t_18">Photoshop &amp; Sketch</div>
            <div>Everything created in PS or Sketch is preserved &mdash; including all layers.</div>
        </div>
    </div>
    <!-- -->
    <!-- item -->
    <div class="box flex">
        <div class="card">
            <div class="image">image</div>
            <div class="t_18">Sketch</div>
            <div>Everything created in PS or Sketch is preserved &mdash; including all layers, smart objects, pages, or artboards.</div>
        </div>
    </div>
    <!-- -->
</div>