<?php
namespace App\Library;
/**
 * This is a very simple dic container for our classes
 *
 * @author vin
 */
class Registry {
  
  /**
   * Internal Array of classes
   * @var type 
   */
  protected $classes = array();
  
  /**
   * Registers a class by name and callback functions
   * @param type $name
   * @param \App\Library\callable $callback
   */
  public function register( $name, callable $callback ){
    $this->classes[$name] = $callback;
  }  
  
  /**s
   * Gets an instance using call back 
   * @param type $name
   */
  public function __get($name) {
    if( isset( $this->classes[$name] )){
      return $this->classes[$name]();
    } 
    throw new \RuntimeException("The requested service '$name' does not exist");
  }
  
  
}
