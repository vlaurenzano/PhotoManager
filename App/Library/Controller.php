<?php
namespace App\Library;

use \App\Models\User;
use \App\Models\Photo;
/**
 * This would be abstract and extended by the diff controllers
 * since this is a simple app, everythig will happen here
 *
 * Normally we'd want some class to process the views as well, but they are super simple in this case
 * 
 * @author vin
 */
class Controller {
  
  /**
   * Registry
   * @var type 
   */
  protected $registry;
  
  /**
   * Server
   * @var type 
   */
  protected $server;  
  
  /**   
   * @param \App\Library\Registry $registry
   */
  public function __construct(Registry $registry) {
    $this->setRegistry($registry);
    $this->setServer($registry->server);
  }
  
  /**
   * 
   * @param \App\Library\Registry $registry
   */
  public function setRegistry(Registry $registry){
    $this->registry = $registry;
  }
  
   /**
   * Sets the server
   * @param \App\Library\Server $server
   */
  public function setServer(Server $server){
    $this->server = $server;
  }
    
  /**
   * Login Action
   */
  public function login(){   
   if( $this->server->getFromPost('username') && $this->server->getFromPost('password') ){
     $user = User::login(  $this->server->getFromPost('username'), $this->server->getFromPost('password'), $this->registry->db );
     if( $user ){
       $this->server->writeToSession('user', \serialize($user));
       $this->server->redirect('index.php?route=dashboard');
     }
   }
   if( $this->server->getAllPost()){
    echo "<div style='background-color:yellow'>The login values were incorrect, try again</div>";
   }
   return require __dir__ . '/../Views/login.html'; 
  }
  
  /**
   * Register Action
   */
  public function register(){
   require __dir__ . '/../Views/register.html'; 
  }
  
  /**
   * New user
   */
  public function newUser(){
    $username = $this->server->getFromPost('username');
    $password =  $this->server->getFromPost('password');
    $password2 =  $this->server->getFromPost('password2');
    if( !( $username && $password && $password2)){
      echo "<div style='background-color:yellow'>One or more form values missing, try again</div>";
      return require __dir__ . '/../Views/register.html'; 
    }
    if( $password !== $password2 ){
      echo "<div style='background-color:yellow'>Passwords don't match, try again</div>";
      return require __dir__ . '/../Views/register.html'; 
    }
    $user = new User;
    if( $user->createNewUser($username, $password, $this->registry->db) ){
      $this->server->writeToSession('user', \serialize($user));
      $this->server->redirect('index.php?route=dashboard');      
    }
    echo "<div style='background-color:yellow'>Try a different user name</div>";
    return require __dir__ . '/../Views/register.html'; 
     
  }
  
  /**
   * User dashboard
   */
  public function dashboard(){
    $user = unserialize($this->server->getFromSession('user'));
    $photos = Photo::getPhotosForUser($user, $this->registry->db);
    return require __dir__ . '/../Views/dashboard.php'; 
  }
  
  /**
   * We're not going to verify image or anything here
   * Obviously just demo
   * @return type
   */
  public function upload(){
    $photo = $this->server->getFile('photo');
    if(!$photo['tmp_name']){ 
      return $this->server->redirect('index.php?route=dashboard');   
    }
    $p = new Photo();
    $p->createNewPhotoFromUpload($photo,$user = unserialize($this->server->getFromSession('user')), $this->registry->db);    
    return $this->server->redirect('index.php?route=dashboard');   
  }
  
  public function delete(){
    $photo = $this->server->getFromPost('photo');
    if( !$photo ){
      return $this->server->redirect('index.php?route=dashboard');   
    }
    Photo::delete($photo, unserialize($this->server->getFromSession('user')), $this->registry->db);
    return $this->server->redirect('index.php?route=dashboard');   
  }
  
  /**
   * For restricted pages
   */
  public function restricted(){
    echo '<h2>This page is restricted, please login or register</h2>';
    return require __dir__ . '/../Views/login.html';     
  }
  
  /**
   * destroys the session so we won't be logged out any more
   */
  public function logout(){
    $this->server->destroySession();
    $this->server->redirect('index.php');
  }
  
  /**
   * Not Found
   * @return type
   */
  public function notFound(){
    echo '<h2>Page Not Found</h2><a href="index.php">Return Home</a>';
    return;
  }
  
  
  
}
