<div class="test">
    <?php
        include_once $_SERVER['DOCUMENT_ROOT'].$_COOKIE["web_document_path"]."administrator/classes/Cuppa.php";
        $cuppa = Cuppa::getInstance();
        $language = $cuppa->language->load("web");
        $current_language = $cuppa->language->current();
        $current_country = $cuppa->country->current();
        if(!@$path) $path = $cuppa->utils->getUrlVars(@$_POST["path"]);
    ?>
    <style>
        .test{ }
    </style>
    <script>
        test = new function(){
            // submit
                this.submit = function(){
                    var data = cuppa.serialize(".test form");
                        data.table = "test";
                        data.method = "insert";
                        data.data = JSON.stringify({content:"dddd", date:"NOW()"});
                    var headers = {"key": "x52gBNaaulfod0uwtqtnmI9OqkKr6jUY"};
                        $.ajax({url:"administrator/api/", method:"POST", data:data, headers:headers}).done(function(result){
                            result = JSON.parse(result);
                            trace(result);
                        });
                }.bind(this);
            // constructor
                this.constructor = function(){
                    $("#test").submit(this.submit);
                }.bind(this); $(this.constructor);
        };
    </script>
    <form id="test" onsubmit="return false">
        <input name="name" value="tufik" />
        <input name="age" value="18" />
        <button type="submit">Submit</button>
    </form>
</div>