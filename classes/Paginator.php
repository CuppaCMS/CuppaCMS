<?php
	class Paginator{
        public $limit = 20;
        public $submit_form = "form";
        public $function_name = "cuppa.changePage";
        public $max_numbers = 2;
        public $pages_info = true;
        public $add_separator = false;
        
		public function __construct($limit = 20, $submit_form = "form", $condition_encode = false, $function_name = "cuppa.changePage"){
		  $this->limit = $limit;
          $this->submit_form = $submit_form;
          $this->function_name = $function_name;
		}
		// Create a personal paginator
			public function getPaginator($current_page = 0, $total_pages = 2, $type = 1, $function_name = "cuppa.changePage", $submit_form = "form"){
                $this->function_name = $function_name;
                $this->submit_form = $submit_form;
                $total_pages = ceil($total_pages);
                if($type == 1){
                    return $this->getPaginatorType1($current_page, $total_pages);
                }else if($type == 2){
                    return $this->getPaginatorType2($current_page, $total_pages);
                }
			}
		// Create a paginator passing: table_name of data_base, current_page and, list_limit
			public function getAutoPaginator($table_name, $current_page = 0, $list_limit = 25, $type = 1, $conditions = "", $condition_encode = false, $function_name = "cuppa.changePage", $submit_form = "form"){
                $this->limit = $list_limit;
                $this->function_name = $function_name;
                $this->submit_form = $submit_form;
                $db = DataBase::getInstance();
				$total_rows = $db->getTotalRows($table_name, $conditions);
				$total_pages = ceil($total_rows/$list_limit);
				if($total_pages <= 1) return NULL;
                if($type == 1){
				    return $this->getPaginatorType1($current_page, $total_pages, $conditions, $condition_encode);
                }else if($type == 2){
                    return $this->getPaginatorType2($current_page, $total_pages, $conditions, $condition_encode);
                }
			}
            //++ Get Paginator Type 1
    			private function getPaginatorType1($current_page = 0, $total_pages = 2, $conditions = "", $condition_encode = false){
    				$cuppa = Cuppa::getInstance();
                    $language = $cuppa->language->load();
                    if($total_pages <= 1) return "";
    				$field = "<div class='paginator'>";
    					$field .= "<ul>";
    						if($current_page > 0) $field .= "<li><a onclick='".$this->function_name."(0, \"".$this->submit_form."\",\"".$this->limit."\")'><div title='First page' class='paging_far_left'></div></a></li>";
    						if($current_page > 0) $field .= "<li><a onclick='".$this->function_name."(".($current_page-1).", \"".$this->submit_form."\",\"".$this->limit."\")'><div title='Prev page' class='paging_left'></div></a></li>";
    						if($this->pages_info) $field .= "<li><div title=''  class='current_page'>".$cuppa->language->getValue("Page",$language)." <b>".(@$current_page+1)."</b> / $total_pages</div></li>";
    						if($current_page < $total_pages-1) $field .= "<li><a onclick='".$this->function_name."(".($current_page+1).",\"".$this->submit_form."\",\"".$this->limit."\")'><div title='Next page' class='paging_right'></div></a></li>";
    						if($current_page < $total_pages-1) $field .= "<li><a onclick='".$this->function_name."(".($total_pages-1).",\"".$this->submit_form."\",\"".$this->limit."\")'><div title='Last page' class='paging_far_right'></div></a></li>";
    						$field .= "<li><div class='select_page_div'>".$this->getPagesList($current_page, $total_pages)."</div></li>";
    					$field .= "</ul>";                    
    					$field .= "<input type='hidden' value='".$current_page."' id='page' name='page' />";
                        $field .= "<input type='hidden' value='' id='page_item_start' name='page_item_start' />";
                        if($condition_encode) $field .= '<input type="hidden" id="conditions" name="conditions" value="'.base64_encode($conditions).'"/>';
                        else $field .= '<input type="hidden" id="conditions" name="conditions" value="'.$conditions.'"/>';
    				$field .= "</div>";
    				return $field;
    			}
        			private function getPagesList($current_page, $totalPages){
        				$cuppa = Cuppa::getInstance();
                        $language = $cuppa->language->load();
                        $field = "<select onchange='".$this->function_name."(this.value, \"".$this->submit_form."\",\"".$this->limit."\")' class='select_page' id='select_page' name='select_page'>";
        					$field .= "<option value='$current_page' >".$cuppa->language->getValue("Select page",$language)."</option>";
        					for($i = 0; $i < $totalPages; $i++ ){
        						if($i != $current_page) $field .= "<option value='$i'>".($i+1)."</option>";
        					}
        				$field .= "</select>";
        				return $field;
        			}
            //--
            //++ Get Paginator Type 2
                private function getPaginatorType2($current_page = 0, $total_pages = 1, $conditions = "",  $condition_encode = false){
                    $cuppa = Cuppa::getInstance();
                    $language = $cuppa->language->load();
                    if($total_pages == 1) return "";
                    $field = "<div class='paginator'>";
                        //++ Page info
                            if($this->pages_info)
                                $field .= "<div class='page_numbers'>".$cuppa->language->getValue("Page",$language)." ".($current_page+1)." ".$cuppa->language->getValue("of",$language)." ".$total_pages."</div>";
                        //--
                        //++ Pages
                            $field .= "<div class='pages'>";
                                //++ Firs page
                                    if($current_page != 0)
                                    $field .= "<a class='first_page' onclick='".$this->function_name."(0,\"".$this->submit_form."\",\"".$this->limit."\")' ><span>".$cuppa->language->getValue("First",$language)."</span></a>";
                                //--
                                //++ add apges
                                    $j = 0;
                                    for($i = $current_page - $this->max_numbers; $i < $total_pages; $i++){
                                        if($i < 0) $i = 0;
                                        if($i > $current_page + $this->max_numbers) break;
                                        if($j > 0) $field .= "<div class='separator'></div>";                                                                                                          
                                        if($i == $current_page)
                                            $field .= "<a class='page_item selected'><span>".($i+1)."</span></a>";
                                        else 
                                            $field .= "<a class='page_item' onclick='".$this->function_name."(".$i.",\"".$this->submit_form."\",\"".$this->limit."\")' ><span>".($i+1)."</span></a>";
                                        $j++;
                                    }
                                //--
                                //++ Last page
                                    if($current_page+1 != $total_pages)
                                        $field .= "<a class='last_page' onclick='".$this->function_name."(".($total_pages-1).",\"".$this->submit_form."\",\"".$this->limit."\")' ><span>".$cuppa->language->getValue("Last",$language)."</span></a>";
                                //--
                            $field .= "</div>";
                        //-- Pages
                    $field .= "</div>";
                    $field .= "<input type='hidden' value='".$current_page."' id='page' name='page' />";
                    $field .= "<input type='hidden' value='' id='page_item_start' name='page_item_start' />";
                    if($condition_encode) $field .= '<input type="hidden" id="conditions" name="conditions" value="'.base64_encode($conditions).'"/>';
                    else $field .= '<input type="hidden" id="conditions" name="conditions" value="'.$conditions.'"/>';
                    return $field;
                }
            //--
	}
?>
