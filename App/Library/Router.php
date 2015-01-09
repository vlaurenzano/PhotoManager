<?php
namespace App\Library;

/**
 * This is a simple router which will dispatch routes to their given controllers 
 * Would normally read some type of configuration or be configured from outside but in this case we're just going to do everything in here
 * Would also be much  more robust as far as options go
 *
 * @author vin
 */
class Router {
  
  /**
   * Our Server Extraction
   * @var Server 
   */
  protected $server;
  
  /**
   * Our registry
   * @var type 
   */
  protected $registry;
  
  /**
   * Our routes to match, this is ver simplified
   * Normally we would want to indicate which controller, and which methods are accepted
   * @var type 
   */
  protected $routes = array(      
     'register' => array(         
        'method' => 'register',
         'public' => true
      ),
     'new-user' => array(         
        'method' => 'newUser',
         'public' => true
      ),
     'login' => array(         
        'method' => 'login',
         'public' => true
      ),
      'dashboard' => array(               
        'method' => 'dashboard',      
         'public' => false         
      ),      
      'logout' => array(               
        'method' => 'logout',      
         'public' => false         
      )      
  );
  
  /**
   * Takes in the registry and gets dependencies from it
   * @param \App\Library\Registry $registry
   */
  public function __construct(Registry $registry) {    
    $this->setServer($registry->server);
    $this->setRegistry($registry);
  }
  
  /**
   * Sets the server
   * @param \App\Library\Server $server
   */
  public function setServer(Server $server){
    $this->server = $server;
  }
  
  /**
   * 
   * @param \App\Library\Registry $registry
   */
  public function setRegistry(Registry $registry){
    $this->registry = $registry;
  }
  
  /**
   * Let's route the request 
   */
  public function route(){   
    $controller = $this->registry->defaultController;
    $route = $this->server->getFromGetThenPost('route');
    if( !$route ){
      return $controller->login();
    }
    if( isset( $this->routes[$route]) ){
      //if this is a protected page and we're not logged in
      if( !$this->routes[$route]['public'] && !$this->server->getFromSession('user') ){
        return $controller->restricted();
      }
      $route = $this->routes[$route]['method'];      
      return $controller->$route();
    }
    return $controller->notFound();
  }
  
}
