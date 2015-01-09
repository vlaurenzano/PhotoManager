
<h2> Welcome <?=$user->username;?> </h2>

<?php if( $photos ): ?>
  <h3>My Photos</h3>
    <?php foreach( $photos as $photo ): ?>
  <img src="<?=$photo->image;?>" width="400"style="margin-top:5px;"/>
  <form method="POST" action="index.php?route=delete" enctype="multipart/form-data">
    <input type="hidden" name="photo" value="<?=$photo->id;?>"/>
    <input type="submit" value="delete"/>
  </form>
  <?php endforeach; ?>
<?php endif;?>

<h3>Upload Photo</h3> 
<form method="POST" action="index.php?route=upload" enctype="multipart/form-data">
  <input type="file" accept="image/*" name="photo"/>
  <input type="submit"/>
</form>

<div style="margin-top:10px">
  <a href="index.php?route=logout">logout</a>
</div>