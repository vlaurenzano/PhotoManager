<?php
namespace App\Models;

/**
 * Our photo model
 */
class Photo {
  public $id;
  public $user_id;
  public $image;  
  
  
  public function createNewPhotoFromUpload($file, User $user, \PDO $db){
    $imageString = $this->base64_encode_image($file['tmp_name'], $file['type']);
    $stmt = $db->prepare("INSERT INTO `photos` (`user_id`, `image`) VALUES ( ?, ?);");    
    $stmt->bindParam( 1, $user->id);
    $stmt->bindParam( 2, $imageString);
    return $stmt->execute();
  }  
  
  public static function getPhotosForUser( User $user, \PDO $db){
    $stmt = $db->prepare("SELECT * FROM photos WHERE user_id = ?");
    $stmt->bindParam( 1, $user->id);
    $stmt->execute();
    return $stmt->fetchAll(\PDO::FETCH_CLASS,"App\Models\Photo");      
  }
  
  /**
   * Got from php.net  http://php.net/manual/en/function.base64-encode.php
   * @param type $filename
   * @param type $filetype
   * @return type
   */
  protected function base64_encode_image ($filename,$filetype) {
    $imgbinary = fread(fopen($filename, "r"), filesize($filename));
    return 'data:image/' . $filetype . ';base64,' . base64_encode($imgbinary);    
  }
  
  /**
   * We're requiring user as well to prevent users from crossbrowser attacing
   * other users photos, haha
   * @param type $photo
   * @param \App\Models\User $user
   * @return type
   */
  public function delete($photoId, User $user, \PDO $db){
    $stmt = $db->prepare("DELETE FROM `photos` WHERE `user_id` = ?  AND id = ?");    
    $stmt->bindParam(1, $user->id);
    $stmt->bindParam(2, $photoId);
    return $stmt->execute();    
  }
  
}
