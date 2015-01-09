<?php
namespace App\Library;
/**
 * An abstraction of the server variables we'll be using here
 * Normall we'd have more methods here as well as include other server variabe
 * Definitely would abstract session as well in it's own class
 * @author vin
 */
class Server {
  
  /**
   * Return get vars
   */
  public function getAllGet(){
    return $_GET;
  }
  
  /**
   * Returns pos vars
   * @return type
   */
  public function getAllPost(){
    return $_POST;
  }
  
   /**
   * Check get for variable, else returns fault
   * @param type $name
   * @param type $default
   * @return boolean
   */
  public function getFromGet($name, $default = FALSE){
    return isset($_GET[$name]) ? $_GET[$name] : $default;
  }
  
   /**
   * Check post for variable, else returns fault
   * @param type $name
   * @param type $default
   * @return boolean
   */
  public function getFromPost($name, $default = FALSE){
    return isset($_POST[$name]) ? $_POST[$name] : $default;
  }
  
  /**
   * Try get then post or return the default
   * @param type $name
   * @param type $default
   * @return type
   */
  public function getFromGetThenPost($name, $default = FALSE){
    if($this->getFromGet($name)){
      return $this->getFromGet($name);
    }
    return $this->getFromPost($name, $default);
  }
  
  /**
   * Add to sessin
   */
  public function writeToSession($name, $value){
    $this->checkSession();
    $_SESSION[$name] = $value;
  }
  
  /**
   * Return from session
   * @param type $name
   * @param type $default
   */
  public function getFromSession( $name, $default = FALSE){
    $this->checkSession();
    return isset($_SESSION[$name]) ? $_SESSION[$name] : $default;
  }
  
  /**
   * Checks to see if the session is started yet
   */
  protected function checkSession(){
    if( !session_id()){
      session_start();
    }
  }
  
  /**
   * Destroys our session
   */
  public function destroySession(){
    session_destroy();
  }
  
  /**
   * Redirect to url
   * @param type $url
   */
  public function redirect( $url ){
    header("Location: $url");
  }
  
  
  
}
