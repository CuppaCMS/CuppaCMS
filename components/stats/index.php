<?php
    include_once(realpath(__DIR__ . '/../..')."/classes/Cuppa.php");
    $cuppa = Cuppa::getInstance(); $cuppa->user->valid("admin_login");
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
    .stats .last_activiy{ padding: 15px 20px; font-size: 12px; }
    .stats .last_activiy .message *{ vertical-align: middle; line-height: 22px; }
    .table_info th{ border: 1px solid #e9e9e9; }
</style>
<script>
    stats = new function(){ };
</script>
<div class="stats" id="stats">
    <div class="last_activiy">
        <!-- table log info -->
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
        <!-- -->
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