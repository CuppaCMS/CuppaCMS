<script src="scripts.js" type="text/javascript"></script>
<div class="test">
    <style>
        .test{ }
    </style>
    <script>
        test = new function(){
            // submit
                this.submit = function(){
                    var data = cuppa.serialize(".test form");
                        data.table = "cu_api_keys";
                        data.method = "consult";
                        //data.sql = "SELECT u.*, ug.name as 'group_name' FROM cu_users AS u JOIN cu_user_groups AS ug ON u.user_group_id = ug.id";
                        //data.data = JSON.stringify({content:"dddd", date:"NOW()"});
                    var headers = {"key": "jk1jo9gQstam1xws5eI7DXkLYs5snETm"};
                        $.ajax({url:"http://localhost/projects/tgolden_group/cuppa/www/administrator/api/", method:"POST", data:data, headers:headers}).done(function(result){
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