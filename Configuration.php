<?php 
	class Configuration{
		public $db_host = "localhost";
		public $db_name = "cuppa";
		public $db_user = "root";
		public $db_password = "";
		public $administrator_template = "default";
		public $list_limit = "25";
		public $font_list = "Raleway";
		public $secure_login = "0";
		public $secure_login_value = "";
		public $secure_login_redirect = "";
		public $language_default = "en";
		public $country_default = "us";
		public $global_encode = "sha1Salt";
		public $global_encode_salt = "a4dxjm3RnMj6IM4talVI5VZ1jwP8azyg";
		public $ssl = "0";
		public $lateral_menu = "expanded";
		public $base_url = "";
		public $auto_logout_time = "";
		public $redirect_to = "false";
		public $table_prefix = "cu_";
		public $allowed_extensions = "*.gif; *.jpg; *.jpeg; *.pdf; *.ico; *.png; *.svg;";
		public $upload_default_path = "upload_files";
		public $maximum_file_size = "5242880";
		public $csv_column_separator = ",";
		public $tinify_key = "mK74DCKkTCi2aNInIsdTifFby2LAiDqX";
		public $email_outgoing = "";
		public $forward = "";
		public $smtp = "0";
		public $email_host = "";
		public $email_port = "";
		public $email_password = "";
		public $smtp_security = "";
		public $code = "";
	} 
?>