<?php
namespace App\Models;

/**
 * We'd want a library for persisting and generating models, but let's keep it simple
 *
 * @author vin
 */
class User {
  
 public $id;  
 public $username;
 protected $password;
 protected $salt;
 

 /**
  * Creates a new user and saves it to the db
  * @param type $username
  * @param type $password
  */
 public function createNewUser($username, $password, \PDO $db){
   $this->username = $username;
   $this->salt = uniqid('thisilldo', true);
   $this->password = crypt( $password, $this->salt);   
   $stmt = $db->prepare("INSERT INTO `users` (`username`, `password`, `salt`) VALUES ( ?, ?, ?);");
   $stmt->bindParam( 1, $this->username);
   $stmt->bindParam( 2, $this->password);
   $stmt->bindParam( 3, $this->salt);
   if( $stmt->execute() ){
    $this->id = $db->lastInsertId();
    return $this->id;
   }    
 } 
 
 /**
  * Trys to login, return nes user if succesful, else fails
  * @param type $username
  * @param type $password
  * @param \PDO $db
  */
 public static function login($username, $password, \PDO $db){  
  $stmt = $db->prepare("SELECT * FROM users WHERE username = ?");
  $stmt->bindParam( 1, $username);
  $stmt->execute();
  $stmt->setFetchMode(\PDO::FETCH_CLASS,"App\Models\User"); //I've never had a chance to use fetch class before, kind of cool
  $user = $stmt->fetch();   
  if( !$user ){
    return FALSE;
  }
  if($user->checkPassword($password)){
    return $user;
  }
  return FALSE;
 }
 
 /**
  * Checks the given password against this password
  * Assumes model is instantiated
  */
 public function checkPassword($given){
   return crypt($given, $this->salt) === $this->password;
 }
 
 
}
