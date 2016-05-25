<?php
    include_once $_SERVER['DOCUMENT_ROOT'].$_COOKIE["administrator_document_path"]."classes/Cuppa.php";
    $cuppa = Cuppa::getInstance();
    $unique = $cuppa->utils->getUniqueString("cont_");
?>
<style>
    .<?php echo $unique ?> .box{ padding: 0px 20px 20px; width:50%; }
    .<?php echo $unique ?> .box .image{ width:118px; height:118px; background: #EEE; text-align: center; margin-right: 25px; }
    .<?php echo $unique ?> .box .text{ color: #888; }
</style>
<div class="<?php echo $unique ?> max_width">
    <div class="flex flex_align_between">
        <!-- item -->
        <div class="box flex">
            <div class="image flex_box_middle">
                <div>
                    <span style="color: #e74c3c;">image</span>
                </div>
            </div>
            <div class="text" style="flex: 1;">
                <div class="t_18" style="color: #e74c3c;">24/7 REAL PERSON CUSTOMER SUPPORT</div>
                <div style="margin-top: 15px;">Dolor nunc vule putateulr ips dol consec. Donec semp ertet laciniate ultricie upien disseco dolo lectus fgilla itollicil tua ludin dolor nec met quam accumsan dolorecondime netus lulam utlacus adipiscing ipsu molestie euismod estibulum vel ipsum sit amet sollicitudin ante.</div>
            </div>
        </div>
        <!-- -->
        <!-- item -->
        <div class="box flex">
            <div class="image flex_box_middle">
                <div>
                    <span style="color: #e74c3c;">image</span>
                </div>
            </div>
            <div class="text" style="flex: 1;">
                <div class="t_18" style="color: #e74c3c;">SHARE YOUR DATA WITH FRIENDS</div>
                <div style="margin-top: 15px;">Dolor nunc vule putateulr ips dol consec. Donec semp ertet laciniate ultricie upien disseco dolo lectus fgilla itollicil tua ludin dolor nec met quam accumsan dolorecondime netus lulam utlacus adipiscing ipsu molestie euismod estibulum vel ipsum.</div>
            </div>
        </div>
        <!-- -->
        <!-- item -->
        <div class="box flex">
            <div class="image flex_box_middle">
                <div>
                    <span style="color: #e74c3c;">image</span>
                </div>
            </div>
            <div class="text" style="flex: 1;">
                <div class="t_18" style="color: #e74c3c;">THE BEST SECURE CLOUD STORAGE</div>
                <div style="margin-top: 15px;">Dolor nunc vule putateulr ips dol consec. Donec semp ertet laciniate ultricie upien disseco dolo lectus fgilla itollicil tua ludin dolor nec met quam accumsan dolorecondime.</div>
            </div>
        </div>
        <!-- -->
        <!-- item -->
        <div class="box flex">
            <div class="image flex_box_middle">
                <div>
                    <span style="color: #e74c3c;">image</span>
                </div>
            </div>
            <div class="text" style="flex: 1;">
                <div class="t_18" style="color: #e74c3c;">15 GB OF FREE STORAGE</div>
                <div style="margin-top: 15px;">Dolor nunc vule putateulr ips dol consec. Donec semp ertet laciniate ultricie upien disseco dolo lectus fgilla itollicil tua ludin dolor nec met quam accumsan dolorecondime netus lulam utlacus adipiscing ipsu molestie.</div>
            </div>
        </div>
        <!-- -->
    </div>
</div>