<?php
    class CountryManager{
        private static $instance;
        private function __construct(){ }
        public static function getInstance() {
			if (self::$instance == NULL) { self::$instance = new CountryManager(); } 
			return self::$instance;
		}
        // Get current language selected
            public function current(){
                $utils = Utils::getInstance();
                
                return @$utils->getCookie("country");
            }
        // available countries
            public function getCountriesAvailable(){
                $configuration = new Configuration();
                $dataBase = DataBase::getInstance();
                $data = $dataBase->getList("{$configuration->table_prefix}countries", "enabled = 1", "", "name ASC", true);
                return $data;
            }
            public function getAvailableCountries(){ return $this->getCountriesAvailable(); }
        // is valid language
            public function validCountry($value){
                if(!$value) return false;
                $value = strtolower($value);
                $value = explode("-", @$value); 
                if(@$value[1]) $value = @$value[1];
                else $value = @$value[0];
                $countries = $this->getCountriesAvailable();
                $aviable = false;
                for($i = 0; $i < @count($countries); $i++){
                    if($value == $countries[$i]->code && $countries[$i]->enabled == 1){ $aviable = true; break; }
                }
                return $aviable;
            }
            public function valid($value){ return $this->validCountry($value); }
        // set country
            public function set($country = "", $forced = false, $config = null, $auto_language = false){
                $utils = Utils::getInstance();
                $value = "";
                if($forced && $this->valid($country)){ 
                    $value = $country;
                }else if( $this->valid($this->getCountryPath()) ){
                    $value = $this->getCountryPath();
                }else if($this->valid($this->current()) && !$this->valid($country) ){
                    $value = $utils->getCookie("country");
                }else if($this->countryByIP()){
                    $value = $this->countryByIP();
                }else if($this->valid($country)){
                    $value = @$country;
                }else if($config){
                    $value = $config->country_default;
                }
                $utils->setCookie("country", $value);
                //++ set language
                    if($auto_language) $this->setLanguage("", $config);
                //--
                return $value;
            }
        // get country by ip
            public function countryByIP($lowercase = false){
                $utils = Utils::getInstance();
                $ip = $utils->ip();
                include_once __DIR__."/geoIP/geoip.inc";
                $gi = geoip_open(__DIR__."/geoIP/GeoIP.dat", GEOIP_STANDARD);
                $country = geoip_country_code_by_addr ($gi, $ip);
                if($lowercase) $country = strtolower($country);
                return @$country;
            }
        // set country lanuguage
            public function setLanguage($country = "", $config = null){
                if(!$country) $country = $this->current();
                $country = $this->getInfo($country);
                $language = @$country->default_language;     
                $languageManager = LanguageManager::getInstance();
                $languageManager->set($language, false, $config);
            }
        // get country data
            public function getInfo($country = ""){
                if(!$country) $country = $this->current();
                $configuration = new Configuration();
                $dataBase = DataBase::getInstance();
                $data = $dataBase->getRow("{$configuration->table_prefix}countries", "code = '".$country."'",  true);
                return $data;
            }
            public function info($country = ""){ return $this->getInfo($country); }
        // get Country by path
            public function getCountryPath($path = ""){
                $utils = Utils::getInstance();
                if(!@$path) $path = @$utils->getUrlVars(@$_REQUEST["path"]);
                $country = explode("-", @$path[0]); 
                if(@$country[1]){ $country = @$country[1];
                }else{ 
                    $country = ""; 
                    //$country = @$country[0];
                }
                return $country;
            }
    }
?>