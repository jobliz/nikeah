<?php

class Request {
    
    public function Request() {
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->base_path = $_SERVER['PHP_SELF'];
        $this->index = basename($_SERVER['PHP_SELF']);
        $this->abstracted_path = substr($_SERVER['PHP_SELF'], 0, -strlen($this->index));
        $this->relative_path = substr($_SERVER['REQUEST_URI'], strlen($this->abstracted_path) - 1);
    }
    
    public function translate($string) {
        return $this->abstracted_path.$string;
    }   
}  

# based on http://blog.sosedoff.com/2009/09/20/rails-like-php-url-router/
class DottedRoute {
    
    public $regex;
    public $names;
    public $params;
    public $dict;
    
    public function DottedRoute($path) {
        $temp = array();
        preg_match_all('@:([\w]+)@', $path, $temp, PREG_PATTERN_ORDER);
        $this->names = $temp[1];
        $this->regex = preg_replace('@:[\w]+@', '([a-zA-Z0-9_\+\-%]+)', $path);
        $this->regex = '@^'.$this->regex.'/?$@';
    }   
    
    public function check($url) {
        $temp = array();
        if(preg_match($this->regex, $url, $temp)) {
            array_shift($temp);
            $this->params = $temp;
            $this->dict = array();
            for($i = 0; $i < sizeof($temp); ++$i) {
                $this->dict[$this->names[$i]] = $temp[$i];
            }
            return True;
        }
        return False;
    }
}

class DottedWeb {
    
    public $get_routes;
    public $post_routes;
    
    public function WebSite() {
        $this->get_pair = array();
        $this->post_pair = array();
    }
    
    public function setGET($pair) {
        foreach($pair as $get_path => $function_string) {
            $this->get_pair[$function_string] = new DottedRoute($get_path);
        }
    }
    
    public function setPOST($pair) {
        foreach($pair as $post_path => $function_string) {
            $this->post_pair[$function_string] = $post_path;
        }
    }
    
    function run($method, $url) {
        
        if($method == 'GET') {
            $found = False;
            foreach($this->get_pair as $function_string => $dotted_route) {
                if($dotted_route->check($url) === True) {
                    call_user_func_array($function_string, $dotted_route->params);
                    $found = True;
                    break;
                }
            }
            if($found == False) {
                die('404');
            }
        }
        
        if($method == 'POST') {
            foreach($this->post_pair as $function_string => $post_path) {
                if($url === $post_path) {
                    call_user_func_array($function_string, array($_POST));
                }
            }
        }
    }
}

?>
