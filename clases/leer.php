<?php

class Leer{
    
    //metodo de clase:
    public static function get($param) {
        
        if(isset($_GET[$param])){
            
            if(is_array($_GET[$param])){
                return Leer::leerArray($_GET[$param]);
            }else{
                
                return Leer::limpiar($_GET[$param]);
            }
            
            
            
        }else{
            
            return null;
        }
        
    }
    
    public static function post($param) {
        
        if(isset($_POST[$param])){
            
            if(is_array($_POST[$param])){
                return Leer::leerArray($_POST[$param]);
            }else{
                
                return Leer::limpiar($_POST[$param]);
            }
            
        }else{
            
            return null;
        }
        
    }
    
    
    public static function request($param) {
        
        $v = Leer::get($param);
        
        if($v == null){            
            $v = Leer::post($param);
        }
        
        return $v;
        
        
    }
    
    private static function limpiar($param){
        
        return $param;
        
    }
    
    private static function leerArray($param){
         if(is_array($param)){
                $a = array();
                foreach($param as $key =>$value){
                    
                    $a[]=Leer::limpiar($param);
                }
                return $a;
            }
    }
    
    public static function isArray($param){
        
        if(isset($_GET[$param])){
            
            return is_array($_GET[$param]);
        }else if(isset($_POST[$param])){
            return is_array($_POST[$param]);
            
        }
        return null;
    }
    
}



