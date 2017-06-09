<?php
    /*  info =  base64_encode/json_encode
                {$user:id, $question:String, $images:[BytesArrays], $topics:[ids], $type:'yes_no, multi_images'}
    */
        function upload($info){
            $cuppa = Cuppa::getInstance();
            $info = $cuppa->jsonDecode($info);
            //++ process images
                if(@$info->images){
                    $images = [];
                    forEach($info->images as $image){
                        $path = realpath(__DIR__ . '/..')."/";
                        $folder = "media/questions/user_".$info->user."/";
                        if(!file_exists($path.$folder)){ $cuppa->file->createFolder($path.$folder); }
                        $file = uniqid().rand().rand().".jpg";
                        $cuppa->file->createFile(base64_decode($image), $path.$folder.$file);
                        array_push($images, $folder.$file);
                    }
                }
                $info->images = json_encode(@$images);
            //--
            $info->topics = json_encode(@$info->topics);
            $info = $cuppa->db->ajust($info, true, true, "images,topics");
            $info->date = "NOW()";
            return $cuppa->db->insert("tu_questions", $info);
        }
    /* delete */
        function delete($id){
            $cuppa = Cuppa::getInstance();
            $cuppa->db->delete("tu_questions", "id = ".$id);
            $cuppa->db->delete("tu_questions_votes", "question = ".$id);
            return 1;
        };
    /* get Questions
        $data = {user:id (required), $condition:"enabled = 1", $page = 0}
    */
        function getQuestions($data){
            include_once "user.php";
            $cuppa = Cuppa::getInstance();
            $data = $cuppa->jsonDecode($data);
            if(!@$data->page) $data->page = 0;
            $cond = "enabled = 1";
            if(@$data->condition) $cond.= " AND ".$data->condition;
            $limit = 30; $limit = $data->page*$limit.",".$limit;
            $result = $cuppa->db->getList("tu_questions",  $cond, $limit, "id DESC", true);
            forEach($result as $item){ 
                $item->user_info = getUser($item->user);
                $item->votes = getVotes($item->id, true);
                $item->user_vote = $cuppa->db->getRow("tu_questions_votes", "question = ".$item->id." AND user = ".$data->user, true);
                $item->follow = $cuppa->db->getTotalRows("tu_user_follow", "user = ".$data->user." AND follow = ".$item->user);
            }
            return $result;
        }
    // get Question
        function getQuestion($id){
            include_once "user.php";
            $cuppa = Cuppa::getInstance();
            $result = $cuppa->db->getRow("tu_questions", "id = ".$id, true);
            $result->user_data = getUser($result->user);
            return $result;
        }
    /*  info =  base64_encode/json_encode
                {$user:id, $question:$id, $vote:Any}
    */
        function vote($info){
            include_once "user.php";
            include_once "notifications.php";
            $cuppa = Cuppa::getInstance();
            $info = $cuppa->jsonDecode($info);
            $data = $info;
            if($data->vote) $data->vote = json_encode($data->vote);
            $data = $cuppa->db->ajust($data, true, true, "vote");
            $data->date = "NOW()";
            $cuppa->db->insert("tu_questions_votes", $data);
            $votes = getVotes($info->question, true);
            // set notification
                $question = getQuestion($info->question);
                $user = getUser($info->user);
                $message = $user->name." has voted your question.";
                $metaData = json_encode($question);
                notification($question->user, $message, $metaData);
            return $votes;
        }
    function getVotes($question, $return = false){
        $cuppa = Cuppa::getInstance();
        $question = $cuppa->db->getRow("tu_questions", "id = ".$question, true);
        $data = new stdClass();
        if($question->type == "yes_no"){
            $data->yes = $cuppa->db->getTotalRows("tu_questions_votes", "question = ".$question->id." AND vote = '\"yes\"'");
            $data->no = $cuppa->db->getTotalRows("tu_questions_votes", "question = ".$question->id." AND vote = '\"no\"'");
            $data->total = $data->yes+$data->no;
        }else if($question->type == "multi_images"){
            $images = json_decode($question->images);
            $data->votes = array();
            $data->total = 0;
            forEach($images as $index=>$item){
                $votes = $cuppa->db->getTotalRows("tu_questions_votes", "question = ".$question->id." AND vote = '".$index."'");
                $data->total += $votes;
                array_push($data->votes, $votes);
            }
        }
        return $data;
    }
    
?>