<?php
    include_once(realpath(__DIR__ . '/../..')."/classes/Cuppa.php");
    $cuppa = Cuppa::getInstance(); $cuppa->user->valid();
    if(!@$path) $path = $cuppa->utils->getUrlVars(@$_POST["path"]);
    $language = $cuppa->language->load();
    //++ Table log
        $sql = "SELECT * FROM( 
                    SELECT tl.*, uc.name AS user_creator, uu.name AS user_update, ud.name AS user_updating FROM ".$cuppa->configuration->table_prefix."tables_log AS tl 
                    LEFT JOIN ".$cuppa->configuration->table_prefix."users AS uc
                    ON uc.id = tl.user_id_creator
                    LEFT JOIN ".$cuppa->configuration->table_prefix."users AS uu
                    ON uu.id = tl.user_id_update    
                    LEFT JOIN ".$cuppa->configuration->table_prefix."users AS ud
                    ON ud.id = tl.user_id_updating
                    ORDER BY date_update DESC, date_registered DESC, id DESC        
                )as data LIMIT 15";
        $table_log = $cuppa->dataBase->sql($sql, true);
    //--
?>
<style>
    .stats{ position: relative; }
    .stats .general_info{ font-size: 0px; }
    .stats .general_info .td{ overflow: visible; }
    .stats .item{ position: relative; display: inline-block; padding: 15px 20px; border: 1px solid #E9E9E9; border-top: 0px; border-left: 0px; }
    .stats .item .title{ font-size: 24px; color: #acacac; }
    .stats .item .number{ position: relative; font-size: 32px; color: #4B8DF8; }
    .new_visitors_chart{ position: relative; border: 1px solid #E9E9E9; border-bottom: 0px; border-top: 0px; float: right; overflow: hidden; }
    .stats .last_activiy{ padding: 15px 20px; font-size: 12px; }
    .stats .last_activiy .message *{ vertical-align: middle; line-height: 22px; }
    .table_info th{ border: 1px solid #e9e9e9; }
    .ga_login_container{ display: none; border: 1px solid #DDD; position: relative; font-size: 14px; background: #F6F6F6; color: #acacac; text-align: center; padding: 30px 10px; }        
    .gapi-analytics-auth-styles-signinbutton{ cursor: pointer; transition: 0.3s background-color;  }
    .gapi-analytics-auth-styles-signinbutton:hover{ background: #e7890c; }
    .ga_config_alert{ background: #FF9D27; color: #FFF; padding: 10px; }
    .ga_config_alert a{ text-decoration: underline; }
    .ga_config_alert li{ padding: 5px 0px; list-style-type: decimal; }
/* Responsive */
    .r1100 .stats .item{ width: 100%; border-left:1px solid #e9e9e9 !important; }
    .r950 .stats .general_info .td{ position: relative !important; display: block !important; width: 100% !important; }
    .r950 .new_visitors_chart{ float: none; margin-bottom: 0px; margin-top: 10px; border: 1px solid #e9e9e9; width: 100%; }
</style>
<script>
    stats = {}
    stats.general_data = null;
    //++ dawChars
        stats.drawGeneralChar = function(){
            var ga_general = cuppa.jsonDecode(cuppa.getCookie("ga_general"));
            if(!ga_general) return;
            var data = new Array();
                data.push(['Date', 'Sessions']);
                for(var i = 0; i < ga_general.rows.length; i++){
                    var row = new Array(); row.push( ga_general.rows[i][0] ); row.push( parseFloat(ga_general.rows[i][1]) ); data.push(row);
                }
                data = google.visualization.arrayToDataTable(data);
            var options = {
                  title: "",
                  hAxis: {textPosition:"out", showTextEvery:5, textStyle:{fontSize:12}},
                  vAxis: {minValue: 0, textPosition:"in", textStyle:{fontSize:12}},
                  colors:['#4B8DF8'],
                  height:250,
                  legend:{position: 'top'},
                  theme:{chartArea: {width: '100%'}},
                  pointSize:7,
                  lineWidth:4,
                  backgroundColor:"none"
            }
            var chart = new google.visualization.AreaChart(document.getElementById('general_chart'));
                chart.draw(data, options);
            $(".ga_sessions .number").html(ga_general.totalsForAllResults["ga:sessions"]);
            $(".ga_users .number").html(ga_general.totalsForAllResults["ga:users"]);
            $(".ga_pageviews .number").html(ga_general.totalsForAllResults["ga:pageviews"]);
            $(".ga_bounce_rate .number").html(parseFloat(ga_general.totalsForAllResults["ga:bounceRate"]).toFixed(2)+"%");
        }
        stats.drawNewVisitorsChar = function(){
            var ga_general = cuppa.jsonDecode(cuppa.getCookie("ga_general"));
            if(!ga_general) return;
            var data = new google.visualization.DataTable();
                data.addColumn('string', 'Topping');
                data.addColumn('number', 'Slices');
                data.addRows([
                  ['New visitors', parseFloat(ga_general.totalsForAllResults["ga:percentNewSessions"])],
                  ['Returning visitors', 100-parseFloat(ga_general.totalsForAllResults["ga:percentNewSessions"])]
                ]);
                var options = {
                    title:null,
                    width:350,
                    height:300,
                    legend:{position: 'top'},
                    colors:["#4B8DF8",'#50B432']
                };
                var chart = new google.visualization.PieChart(document.getElementById('new_visitors_chart'));
                    chart.draw(data, options);
        }
    //--
    //++ show Pages
        stats.showPagesviews = function(){
            var ga_pageviews = cuppa.jsonDecode(cuppa.getCookie("ga_pageviews"));
            if(!ga_pageviews) return;
            $(".stats .table_no_info").remove();
            $(".table_pages .tr_template").not( $(".table_pages .tr_template").get(0) ).remove();
            for(var i = 0; i < ga_pageviews.rows.length; i++){
                var item = $($(".table_pages .tr_template").get(0)).clone().css("display","");
                    $(item.find("td").get(0)).html( ga_pageviews.rows[i][0] );
                    $(item.find("td").get(1)).html( ga_pageviews.rows[i][1] );
                $(".table_pages").append(item);
            }
        }
    //--
    //++ getChartsInfo
        stats.getChartsInfo = function(){
            //++ get sessions data
                if(cuppa.getCookie("ga_general")){
                    stats.resize();
                }else{
                    try{
                        var query = { ids:"ga:<?php echo $cuppa->configuration->ga_view ?>", metrics: 'ga:sessions,ga:bounceRate,ga:percentNewSessions,ga:pageviews,ga:users', dimensions: 'ga:date', 'start-date': '31daysAgo', 'end-date': 'yesterday'};
                        var report1 = new gapi.analytics.report.Data({query: query});
                            report1.execute();
                            report1.on('success', function(response){
                                cuppa.setCookie("ga_general", cuppa.jsonEncode(response), 1);
                                stats.resize();
                            });
                    }catch(err){}
                }
            //--
            //++ get page views
                if(cuppa.getCookie("ga_pageviews")){
                    stats.showPagesviews();
                }else{
                    try{
                        var query = { ids:"ga:<?php echo $cuppa->configuration->ga_view ?>", metrics: 'ga:pageviews', dimensions: 'ga:pagePath', sort:"-ga:pageviews", 'max-results':8, 'start-date': '31daysAgo', 'end-date': 'yesterday'};
                        var report2 = new gapi.analytics.report.Data({query: query});
                            report2.execute();
                            report2.on('success', function(response){
                                cuppa.setCookie("ga_pageviews", cuppa.jsonEncode(response), 1);
                                stats.showPagesviews();
                            });
                    }catch(err){}
                }
            //--
        };
    //--
    //++ resize
        stats.resize = function(){
            stats.drawGeneralChar();
            stats.drawNewVisitorsChar();
        }; 
    //--
    //++ end
        stats.end = function(){ cuppa.removeEventGroup("stats"); }; 
    //--
    //++ init
        stats.init = function(){
            cuppa.addRemoveListener(".stats", stats.end);
            cuppa.addEventListener("resize", stats.resize, window, "stats"); stats.resize();
            if(window.gapi){ stats.getChartsInfo(); }
        }; cuppa.addEventListener("ready",  stats.init, document, "stats");
    //--
    //++ load GA api
        (function(w,d,s,g,js,fs){
              g=w.gapi||(w.gapi={});g.analytics={q:[],ready:function(f){this.q.push(f);}};
              js=d.createElement(s);fs=d.getElementsByTagName(s)[0];
              js.src='https://apis.google.com/js/platform.js';
              fs.parentNode.insertBefore(js,fs);js.onload=function(){g.load('analytics');};
        }(window,document,'script'));
    //--
    //++ Show/Hide Google Data
        <?php if($cuppa->configuration->ga_client_id && $cuppa->configuration->ga_view){ ?>
            gapi.analytics.ready(function() {
                gapi.analytics.auth.authorize({ container: 'ga_login_container', clientid: '<?php echo $cuppa->configuration->ga_client_id ?>' });
                gapi.analytics.auth.on('success', function(response) { $(".ga_login_container").css("display","none"); stats.getChartsInfo(); });
                gapi.analytics.auth.on('error', function(response) { $(".ga_login_container").css("display","block"); });
            });
        <?php }else{ ?>
            $(function(){
                $(".ga_data").remove();
                $(".stats .last_activiy").css("padding","0 0px");
            });
        <?php } ?>
    //--
</script>
<div class="stats" id="stats">
    <h1 style="margin-bottom: 5px;">Google Analytics</h1>
    <?php if(!$cuppa->configuration->ga_client_id || !$cuppa->configuration->ga_view){ ?>
        <div class="ga_config_alert">
            <span>To setup Google Analytics, you need to first <a href="https://console.developers.google.com/start/api?id=analytics&credential=client_key" target="_blank"><strong>create or select a project in the Google Developers Console and enable the API.</strong></a>Using this link guides you through the process and activates the Google Analytics API automatically.</span>
            <ul style="margin-top: 10px;">
                <li>
                    Go to the <a href="https://console.developers.google.com/start/api?id=analytics&credential=client_key" target="_blank"><strong>Google Developers Console.</strong></a> and create your project.
                </li>
                <li>
                    From the Credentials page, click Create new Client ID under the OAuth heading to create your OAuth 2.0 credentials.
                </li>
                <li>
                    In Cuppa CMS, go to <a href="#/component/configuration/stats"><strong>Settings > Stats</strong></a> and complete the field  <strong>Client ID</strong>, <strong>Analytics View.</strong>
                </li>
                <li>
                    To get more info visite the <a href="https://developers.google.com/analytics/devguides/reporting/core/v3/quickstart/web-js" target="_blank" ><strong>Google Analytics references.</strong></a>
                </li>
            </ul>
            <p><strong>Note:</strong> This app only work in the authorized URL's, not localhost</p>
        </div>
    <?php }else{ ?>
        <div id="ga_login_container" class="ga_login_container" >
            <div style="text-align: center; margin-bottom: 15px;"><?php echo $language->ga_config_sign_in ?>.</div>
        </div>
    <?php } ?>    
    <div class="ga_data" class="frame" style="padding: 20px 20px 0px;">
        <div class="general_chart" id="general_chart" style="position: relative; margin-top: -20px;"></div>
    </div>
    <div class="table general_info">
        <div class="td td1 ga_data" style="text-align: center; width: 300px;">
            <div class="new_visitors_chart" id="new_visitors_chart" style="display: inline-block;"></div>
            <!-- Page info -->
                <table class="table_info table_pages" style="margin-top: 10px; font-size: 12px;">
                    <tr>
                        <th><?php echo @$language->page ?></th>
                        <th><?php echo @$language->pageviews ?></th>
                    </tr> 
                    <tr class="tr_template" style="display: none;">
                        <td style="color: #4b8df8; text-align: left;"></td>
                        <td></td>
                    </tr>
                </table>
            <!-- -->
            <!-- table log no info -->
                <div class="table_no_info" style="border: 1px solid #e9e9e9; border-top: none;">
                    <div class="no_file" style="text-align: center; padding: 40px 0px;">
                        <img src="templates/default/images/template/face.png" style="vertical-align: middle;"  />
                        <div style="display: inline-block; text-align: left; margin-left: 10px; vertical-align: middle;">
                            <h2 style="color: #777;"><?php echo $language->no_info_available ?></h2>
                        </div>
                    </div>
                </div>
            <!-- -->
        </div>
        <div class="td td2" style="width: auto;">
            <div class="item ga_data ga_sessions">
                <div class="title"><?php echo @$language->sessions; ?></div>
                <div class="number">0</div>
            </div>
            <div class="item ga_data ga_users">
                <div class="title"><?php echo @$language->users ?></div>
                <div class="number">0</div>
            </div>
            <div class="item ga_data ga_pageviews">
                <div class="title"><?php echo @$language->pageviews ?></div>
                <div class="number">0</div>
            </div>
            <div class="item ga_data ga_bounce_rate">
                <div class="title"><?php echo @$language->bounce_rate ?></div>
                <div class="number">0%</div>
            </div>
            <div class="last_activiy">
                <table class="table_info" style="margin-top: 10px;">
                    <tr>
                        <th><?php echo @$language->user ?></th>
                        <th><?php echo @$language->last_activity ?></th>
                    </tr> 
                    <?php for($i = 0; $i < count($table_log); $i++){ ?>
                        <?php
                            $user = (@$table_log[$i]->user_update) ? @$table_log[$i]->user_update : @$table_log[$i]->user_creator;
                            $message = "";
                            if(@$table_log[$i]->deleted == "1"){
                                $user = @$table_log[$i]->user_update;
                                $message = $language->stats_has_deleted;
                                $message = str_replace("#id", "<span class='highlight_red'>id ".@$table_log[$i]->reference_id."</span>", $message);
                                $message = str_replace("#table", "<span class='highlight_red'>".@$table_log[$i]->table_name."</span>", $message);
                            }else if(@$table_log[$i]->date_updating != "0000-00-00 00:00:00"){
                                $user = @$table_log[$i]->user_updating;
                                $time1 = strtotime($table_log[$i]->date_updating);
                                $time2 = strtotime(Date("Y-m-d H:i:s"));
                                $secons = $time2 - $time1;
                                if($secons < 30){
                                    $message = $language->stats_is_updating;
                                    $message = str_replace("#id", "<span class='highlight_yellow'>id ".@$table_log[$i]->reference_id."</span>", $message);
                                    $message = str_replace("#table", "<span class='highlight_yellow'>".@$table_log[$i]->table_name."</span>", $message);
                                }else{
                                    $message = $language->stats_has_updated;
                                    $message = str_replace("#id", "<span class='highlight_blue'>id ".@$table_log[$i]->reference_id."</span>", $message);
                                    $message = str_replace("#table", "<span class='highlight_blue'>".@$table_log[$i]->table_name."</span>", $message);
                                }
                            }else if(@$table_log[$i]->date_updating == "0000-00-00 00:00:00" && @$table_log[$i]->user_id_updating){
                                $message = $language->stats_has_updated;
                                $message = str_replace("#id", "<span class='highlight_blue'>id ".@$table_log[$i]->reference_id."</span>", $message);
                                $message = str_replace("#table", "<span class='highlight_blue'>".@$table_log[$i]->table_name."</span>", $message);
                            }else{
                                $message = $language->stats_has_created;
                                $message = str_replace("#id", "<span class='highlight_green'>id ".@$table_log[$i]->reference_id."</span>", $message);
                                $message = str_replace("#table", "<span class='highlight_green'>".@$table_log[$i]->table_name."</span>", $message);
                            }
                        ?>
                        <tr class="<?php if($i%2 == 0) echo "gray" ?>">
                            <td><?php echo @$user ?></td>
                            <td class="message"><?php echo @$message; ?></td>
                        </tr>   
                    <?php } ?>
                </table>
                <!-- table log no info -->
                    <?php if(!$table_log){ ?>
                        <div class="table_no_info">
                            <div class="no_file" style="text-align: center; padding: 40px 0px;">
                                <img src="templates/default/images/template/face.png" style="vertical-align: middle;"  />
                                <div style="display: inline-block; text-align: left; margin-left: 10px; vertical-align: middle;">
                                    <h2 style="color: #777;"><?php echo $language->table_log_empty ?></h2>
                                    <div style="max-width: 250px; color: #AAA;"><?php echo $language->table_log_empty_message ?></div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                <!-- -->
            </div>
        </div>
    </div>
</div>