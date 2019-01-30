stage = {}
stage.content;
stage.language;
stage.currentLanguage;
//++ Init
    stage.init = function(){
        //++ Responsive classes
            cuppa.addResponsiveClass("r400", 0, 400, 1);
            cuppa.addResponsiveClass("r650", 0, 650, 2);
            cuppa.addResponsiveClass("r780", 0, 780, 3);
            cuppa.addResponsiveClass("r950", 0, 950, 4);
            cuppa.addResponsiveClass("r1100", 0, 1100, 5);
            cuppa.addResponsiveClass("r1200", 0, 1200, 6);
        //--
        //++ Change URL
            jQuery(".wrapper").html("");
            cuppa.managerURL(stage.changeURL, true);
            cuppa.managerURL.addListener(cuppa.googleTrakingHandler, "stage", false);
        //--
        if(cuppa.managerURL.path.search("secure") != -1) window.location.href = "";
    }; jQuery(document).ready(stage.init);
//--
//++ Change URL
    stage.changeURL = function(e, params, data, data_get){
        //++ Define url
            var url = "templates/default/html/home/home.php";
            if(params.path_array && params.path_array[0] == "component"){
                url = "components/"+params.path_array[1]+"/";
            }
        //--
        //++ Load content
            data.path = params.path;
            if(data_get) data.data_get = cuppa.jsonEncode(data_get);
            stage.loadContent(url, data);
        //--
    }
//--
//++ Load principal content
    stage.loadContent = function(url, data, data_get){
        if(stage.content) stage.content.ajax.abort();
        cuppa.moveContent("body", false, true,0,0,0.3, Cubic.easeInOut);
        try{ menu.showCharger(true) }catch(err){};
        cuppa.blockadeScreen();
        stage.content = cuppa.preloadContent({url:url, name:"wrapper_content", preload:false, data:data });
        function onComplete(){
            try{ menu.showCharger(false) }catch(err){};
            cuppa.blockadeScreen(false);
            cuppa.removeEventListener("complete", onComplete, stage.content, "stage");
            TweenMax.killTweensOf(".wrapper");
            var timeline = new TimelineMax();
                timeline.to(".wrapper", 0.1, {alpha:0});
                timeline.add(function(){ jQuery(".wrapper").html(stage.content); } );
                timeline.to(".wrapper", 0.3, {alpha:1});
        }; cuppa.addEventListener("complete", onComplete, stage.content, "stage");
        
        function onError(){
            try{ menu.showCharger(false) }catch(err){};
            cuppa.blockadeScreen(false);
        };  cuppa.addEventListener("error", onError, stage.content, "stage");
    }
//--
//++ Load right window content
    stage.loadRightContent = function(url, data, name){
        if(!name) name = "window_right";
        if(!data) data = {}; data.url = url; data.path = cuppa.managerURL.path;
        if(stage.content) stage.content.ajax.abort();
        try{ menu.showCharger(true) }catch(err){};
        cuppa.moveContent("body", false, true,0,0,0.3, Cubic.easeInOut);
        var blockade = cuppa.blockade({duration:0.2, name:"blockade_w_right", target:".wrapper", opacity:0.2});
        var content = cuppa.setContent({url:"templates/default/html/windows/right.php", insideOf:".wrapper", zIndex:"auto", name:name, preload:false, data:data});
        jQuery(content).bind("complete", function(){
            try{ menu.showCharger(false) }catch(err){};
        });
    }
//--
//++ Load Config Alert
    stage.loadConfigAlert = function(field, urlConfig, params){
        urlConfig =  urlConfig;
        var data = {}
            data.urlConfig = urlConfig;
            data.field = field;
            data.params = params;
        cuppa.blockade({opacity:0.2, duration:0.2});
        cuppa.setContent({'url':'alerts/alertConfigField.php', 'data':data, 'preload':false, duration:0.3});
    }
//--
//++ Load Permissions Lightbox
    stage.loadPermissionsLightbox = function(group, reference, title){
        if(!title) title = "Permissions";
        var data = {}
            data.url = "components/permissions/list_permissions_lightbox.php";
            data.title = title;
            data.params = {group:group, reference:reference};
        cuppa.blockade({opacity:0.2, duration:0.2});
        cuppa.setContent({'url':'alerts/alertLightbox.php', 'data':data, 'preload':false});
    }
    stage.loadPermissionsFilterLightbox = function(table_name, title){
        if(!title) title = "Permissions";
        var data = {}
            data.url = "components/permissions/list_permissions_filters_lightbox.php";
            data.title = title;
            data.params = {table_name:table_name};
        cuppa.blockade({'autoDeleteContent':'#new_content'});
        cuppa.setContent({'url':'alerts/alertLightbox.php', 'data':data, 'preload':false});
	};
    stage.loadPermissionsApiKeyLightbox = function(table_name, title){
        if(!title) title = "Permissions";
        var data = {}
            data.url = "components/permissions/list_permissions_api_key_lightbox.php";
            data.title = title;
            data.params = {table_name:table_name};
        cuppa.blockade({'autoDeleteContent':'#new_content'});
        cuppa.setContent({'url':'alerts/alertLightbox.php', 'data':data, 'preload':false});
	};
//--
//++ Load Lightbox
    stage.loadLightbox = function(title, url, params){
        var data = {}
            data.url = url;
            data.title = title;
            data.params = params;
        cuppa.blockade({'autoDeleteContent':'#new_content', opacity:0.2, duration:0.2});
        cuppa.setContent({'url':'alerts/alertLightbox.php', 'data':data, 'preload':false});
    };
    stage.loadIFrame = function(title, url, params){
        var data = {}
            data.url = url;
            data.title = title;
            data.params = params;
        cuppa.blockade({'autoDeleteContent':'#new_content', opacity:0.2, duration:0.2});
        cuppa.setContent({'url':'alerts/alertIFrame.php', 'data':data, 'preload':false});
    };
    stage.loadFileManager = function(){
        stage.loadIFrame("File manager", "js/filemanager/index.php", "&width=90%&height=90%");
    };    
//--
//++ Toast, type = success, info, error, warning
    stage.toast = function(message, type){
        if(type == undefined) type = "success";
        toastr.options.timeOut = 2000;
        toastr.options.closeButton = true;
        toastr.options.positionClass = "toast-bottom-right";
        toastr.options.newestOnTop = false;
        if(type == "success") toastr.success(message);
        else if (type == "error") toastr.error(message);
        else if(type == "info") toastr.info(message);
        else if(type == "warning") toastr.warning(message);
    };
//--
//++ Change Page
    stage.changePage = function(page, submit_form, limit){
		jQuery(submit_form+" #page").val(page);
        jQuery(submit_form+" #page_item_start").val(parseInt(page)*parseInt(limit));
        var data = cuppa.urlToObject($(submit_form).serialize());
        cuppa.managerURL.setParams({path:cuppa.managerURL.path}, true, data);
    };
//--