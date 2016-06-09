<?php
    include_once $_SERVER['DOCUMENT_ROOT'].$_COOKIE["web_document_path"]."administrator/classes/Cuppa.php";
    $cuppa = Cuppa::getInstance();
    $language = $cuppa->language->load("web");
    $current_language = $cuppa->language->current();
    if(!@$path) $path = $cuppa->utils->getUrlVars(@$_POST["path"]);
    if(!@$zoom) $zoom = 2;
    if(!@$latitude) $latitude = 0;
    if(!@$longitude) $longitude = 0;
    if(!isset($show_categories)) $show_categories = true;
    
    
    $sql = "SELECT pla.*, cat.id as 'cat_id', cat.name as 'cat_name', cat.pin FROM ex_map_places as pla JOIN ex_map_categories as cat ON pla.category = cat.id
            WHERE pla.enabled = 1 AND pla.id IN( $place_ids ) AND (pla.language = '' OR pla.language = '".$current_language."')";
    $places = $cuppa->dataBase->sql($sql, true);
    
    
    $latitude = 0;
    $longitude = 0;
    foreach($places as  $item){
        $latitude += (float) $item->latitude;
        $longitude += (float) $item->longitude;
    }
    $latitude = $latitude/count($places);
    $longitude = $longitude/count($places);
    $zoom = $places[0]->zoom;
?>
<style>
    .map{ position: absolute; left: 0px; top: 0px; right: 0px; bottom: 0px; background: #E9E5DC; }
    .map .map-canvas{ position: absolute; left: 0px; top: 0px; right: 0px; bottom: 0px; }
    .map_info_title{ overflow: hidden; color: #0B4A66 !important; font-weight: bold !important; }
    .map_info_description{ margin-top: 5px !important; } 
    .map_info_description p{margin: 0px; padding: 0px; border: 0px;}
</style>
<script>
    map = {}
    map.places = cuppa.jsonDecode("<?php echo $cuppa->jsonEncode($places); ?>");
    //++ config
        map.config = function(){
            // Map styles
                var styles= [ 
                    { featureType: "all", stylers: [ { saturation: -80 }]},
                    { featureType: "road.arterial", elementType: "all", stylers: [ { saturation: -100 }, { lightness: -30 }, { visibility: "on" } ]},
                    { featureType: "road.highway", elementType: "all", stylers: [ { saturation: -100 }, { lightness: -5 }, { visibility: "on" } ] },
                    { featureType: "poi.business", elementType: "labels", stylers: [ { visibility: "off" } ] }
                ];
            // Options
                var options = {}
                    options.center = new google.maps.LatLng(<?php echo $latitude ?>, <?php echo $longitude ?>);
                    options.zoom = <?php echo $zoom ?>;
                    options.mapTypeId = google.maps.MapTypeId.ROADMAP;
                    options.disableDefaultUI = false;
                    options.scrollwheel = true;
                    //options.styles = styles;
                map.map = new google.maps.Map(jQuery("#map-canvas")[0], options);
            // Set Pins
                $(map.places).each(function(){
                    map.setPlace(this, true);
                });
        }
    //--
    //++ set place
        map.setPlace = function(item, show){
            if(show){
                var opt = {}
                    opt.position = new google.maps.LatLng(item.latitude, item.longitude);
                    opt.map = map.map;
                    opt.icon = "<?php echo @$cuppa->getPath() ?>" + item.pin;
                    opt.name = "marker_" + item.id;
                    opt.animation = google.maps.Animation.DROP;
                var marker =  new google.maps.Marker(opt);
                item.marker = marker;
                //++ html
                    var html = "<div class='map_info_title'>"+item.name+"</div>";
                        if(item.description) html+= "<div class='map_info_description'>"+item.description+"</div>";
                //--
                    marker.infoWindow = new google.maps.InfoWindow({ maxWidth: 300, content: html });
                google.maps.event.addListener(marker, 'mouseover', function() { this.infoWindow.open(map.map, this); });
                google.maps.event.addListener(marker, 'mouseout', function() { this.infoWindow.close(map.map, this); }); 
            }else{
                try{ item.marker.setMap(null); item.marker = null; }catch(err){}
                
            }
        }
    //--
    //++ setPlaces
         map.setPlaces = function(e){
            var category_id = $(this).attr("id");
            if(!$(this).hasClass("selected") ){
                $(this).addClass("selected");
                $(map.places).each(function(){
                    if(this.category == category_id) map.setPlace(this, true);
                });
            }else{
                $(this).removeClass("selected");
                $(map.places).each(function(){
                    if(this.category == category_id) map.setPlace(this, false);
                });
            }
         }
    //--
    //++ Tooltip
        map.tooltip = {}
        map.tooltip.showTooltip = function(item, show, add_to_positionX, add_to_positionY, text){
            if(add_to_positionX == undefined) add_to_positionX = 0;
            if(add_to_positionY == undefined) add_to_positionY = 0;
            if(jQuery(".map .tooltip").css("display") == "block"){
                TweenMax.to(".map .tooltip", 0.2, { alpha:0, left: parseFloat(jQuery(".map .tooltip").css("left"))+10 , display:"none", onComplete:function(){ map.tooltip.showTooltip(item, show, add_to_positionX, add_to_positionY, text); }});
                return;
            }
            if(show){
                if(text != undefined) jQuery(".map .tooltip div").text( text );
                else jQuery(".map .tooltip div").text( jQuery(item).attr("title") );
                var dim = cuppa.dim(item);
                var posX = dim.x + dim.width + add_to_positionX;
                var posY = dim.y3 + add_to_positionY;                                
                jQuery(".map .tooltip").css("left", posX + 20).css("top", posY).css("opacity", 0);
                TweenMax.to(".map .tooltip", 0.3, {alpha:1, left:posX, display:"block", ease:Cubic.easeOut});
            }
        }
    //--
    //++ resize
        map.resize = function(){ }; 
    //--
    //++ end
        map.removed = function(e){ cuppa.removeEventGroup("map"); }
    //--
    //++ init
        map.init = function(){
            cuppa.addEventListener("resize", map.resize, window, "map"); map.resize(); $(".map img").load(map.resize); TweenMax.delayedCall(0.1, map.resize);
            cuppa.addEventListener("removed", map.removed, ".map", "map");
            map.config(); $(window).load(map.config);
            //++ categories events
                $(".map .buttons .button").bind("mouseenter", function(){
                    map.tooltip.showTooltip(this, true, 5, 8);
                }).bind("mouseleave", function(){
                    map.tooltip.showTooltip(this, false);
                }).bind("click", map.setPlaces)
            //--
        }; cuppa.addEventListener("ready",  map.init, document, "map");
    //--
</script>
<div class="map">
    <div id="map-canvas" class="map-canvas"></div>
</div>