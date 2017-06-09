<?php
    function register($data){
        $cuppa = Cuppa::getInstance();
        $data = $cuppa->jsonDecode($data);
            if(!@$data->username) $data->username = $data->email;
            if(@$data->password) $data->password = $cuppa->utils->sha1Salt(@$data->password, $cuppa->configuration->global_encode_salt);
            if(@$data->image){
                $imageData = new stdClass(); $imageData->image = $data->image; 
                $data->image = uploadPhoto($cuppa->jsonEncode($imageData));
            }
            $data->user_group_id = "3";
            $data->enabled = "1";
            if($data->topics) $data->topics = json_encode($data->topics);
            $data = $cuppa->db->ajust($data, true, true, "topics");
            $data->date_registered = "NOW()";
        $result = $cuppa->db->insert("cu_users", $data, true, true);
        if($result){
            return getUser($result->id);
        }else{
            return "";
        }
    }
    // $user = username,email
    function login($user, $password){
        $cuppa = Cuppa::getInstance();
        $result =  $cuppa->user->login("site_login", $user, $password, true);
        if($result){
            return getUser($result->id);
        }else{
            return "";
        }
    }
    function loginFacebook($facebook, $email){
        if(!$facebook){ return; }
        $cuppa = Cuppa::getInstance();
        // check facebook id
            $result =  $cuppa->db->getRow("cu_users", "enabled = 1 AND facebook = '".$facebook."'", true);
            if($result){ return getUser($result->id); }
        // check email
            $result = $cuppa->db->getRow("cu_users", "enabled = 1 AND email = '".$email."'", true);
            if($result){
                $data = new stdClass();
                $data->facebook = "'".$facebook."'";
                $cuppa->db->update("cu_users", $data, "id = ".$result->id);
                $result = getUser($result->id);
                return $result;
            }
            return "";
    }
    // getUser
        function getUser($id){
            $cuppa = Cuppa::getInstance();
            $result = $cuppa->db->getRow("cu_users",  "id = ".$id, true);
            unset($result->restore_password);
            unset($result->user_group_id);
            unset($result->site_login);
            unset($result->admin_login);
            unset($result->user_group_name);
            unset($result->enabled);
            unset($result->password);
            unset($result->device_token);
            // topics
                $topics = json_decode($result->topics);
                $topics_names = array();
                forEach($topics as $item){
                    array_push($topics_names, $cuppa->db->getColumn("tu_topics", "name", "id = ".$item));
                }
                $result->topics_names = $topics_names;
            return $result;
        }
    // getUsers
        function getUsers($value, $user, $page = 0){
            $cuppa = Cuppa::getInstance();
            $limit = 30; $limit = $page*$limit.",".$limit;
            $condition = "enabled = 1 AND id <> $user";
            $condition.= " AND ( name LIKE '%$value%' OR email LIKE '%$value%' OR username LIKE '%$value%' )";
            $result = $cuppa->db->getList("cu_users",  $condition, $limit, "", true);
            if($result){
                for($i = 0; $i < count($result); $i++){ 
                    $result[$i] = getUser($result[$i]->id);
                }
            }
            return $result;
        }
    // getStats
        function getStats($id, $user = ""){
            $cuppa = Cuppa::getInstance();
            $data = new stdClass();
                $data->posts = $cuppa->db->getTotalRows("tu_questions", "user = ".$id);
                $data->followers = $cuppa->db->getTotalRows("tu_user_follow", "follow = ".$id);
                $data->following = $cuppa->db->getTotalRows("tu_user_follow", "user = ".$id);
                if($user){ $data->follow = $cuppa->db->getTotalRows("tu_user_follow", "user = ".$user." AND follow = ".$id); }
            return $data;
        }
    // validEmail
        function emailAvailable($email){
            if(!filter_var($email, FILTER_VALIDATE_EMAIL)){ return false; }
            $cuppa = Cuppa::getInstance();
            $result = $cuppa->db->getRow($cuppa->configuration->table_prefix."users", "email = '".$email."'"); 
            if($result){ return false;
            }else{ return true; }
        }
    // validEmail
        function usernameAvailable($username){
            $cuppa = Cuppa::getInstance();
            $result = $cuppa->db->getRow($cuppa->configuration->table_prefix."users", "username = '".$username."'"); 
            if($result){
                $data = new stdClass();
                $data->error = "Username is not available";
                $data->userAvailable = usernameUnique($username);
                return $data;
            }else{ return true; }
        }
    // get random username
        function usernameUnique($username){
            $cuppa = Cuppa::getInstance();
            $name = $username;
            return $name;
        }
    // update
        function update($data){
            $cuppa = Cuppa::getInstance();
            $data = $cuppa->jsonDecode($data);
            $info = $cuppa->db->ajust($data, true, true, "topics");
            unset($info->user);
            $result = $cuppa->db->update("cu_users", $info, "id = ".$data->user);
            return getUser($data->user);
        }
    // update image
    // $info = {$user:id, $image:Bitmap}
        function uploadPhoto($info){
            $cuppa = Cuppa::getInstance();
            $info = $cuppa->jsonDecode($info);
            if(!$info->image) return;
            //++ save 
                $path = realpath(__DIR__ . '/..')."/";
                $folder = "media/user_images/";
                $file = uniqid().rand().rand().".jpg";
                $cuppa->file->createFile(base64_decode($info->image), $path.$folder.$file);
            //--
            if(!@$info->user) return $folder.$file;
            $data = new stdClass();
            $data->image = $folder.$file;
            $data = $cuppa->db->ajust($data, true);
            $cuppa->db->update("cu_users", $data, "id = ".$info->user);
            return $folder.$file;
        }
    // follow
        function follow($user, $follow){
            $cuppa = Cuppa::getInstance();
            $data = new stdClass();
            $data->user = $user;
            $data->follow = $follow;
            $data->date = "NOW()";
            $cuppa->db->insert("tu_user_follow", $data);
            return getStats($follow, $user);
        }
    // unfollow
        function unfollow($user, $follow){
            $cuppa = Cuppa::getInstance();
            $data = new stdClass();
            $cuppa->db->delete("tu_user_follow", "user = ".$user." AND follow = ".$follow);
            return getStats($follow, $user);
        }
?>