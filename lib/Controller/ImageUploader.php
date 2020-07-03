<?php

namespace MyApp\Controller;

class ImageUploader extends \MyApp\Controller {
  private $_imageFileName;
  private $_imageType;

  public function upload(){
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
      if($_POST['key'] == 'upload'){

        try{
          // 画像のエラーチェック
          $this->_validateUpload();
          // 画像タイプのチェック
          $ext = $this->_validateImageTyple();
          // 画像をフォルダに保存
          $savePath = $this->_save($ext);
          // サムネイルの作成
          $this->_createThumbnail($savePath);
          // imagesフォルダから元画像を削除
          $this->_deleteImages();
          // 画像のパスをデータベースに格納
          $this->_saveIconPath();
        }catch(\Exception $e){
          echo $e->getMessage();
          exit;
        }
        header('Location:' . SITE_URL . '/myPage.php');
        
      }
    }
  }
  private function _saveIconPath(){
    $user = new \MyApp\Model\User();
    $user->_saveIconPath($_SESSION['me']->id, $this->_imageFileName);
  }

  private function _deleteImages(){
    unlink(IMAGES_DIR. '/'. $this->_imageFileName);
  }
  
  private function _createThumbnail($savePath){
    $imageSize = getimagesize($savePath);
    $width = $imageSize[0];
    $height = $imageSize[1];

    $this->_createThumbnailMain($savePath, $width, $height);
  }

  private function _createThumbnailMain($savePath, $width, $height){
    switch($this->_imageType){
      case IMAGETYPE_GIF:
        $srcImage = imagecreatefromgif($savePath);
        break;
      case IMAGETYPE_JPEG:
        $srcImage = imagecreatefromjpeg($savePath);
        break;
      case IMAGETYPE_PNG:
        $srcImage = imagecreatefrompng($savePath);
        break;
    }
    $thumbImage = imagecreatetruecolor(THUMBNAIL_WIDTH_AND_HEIGHT, THUMBNAIL_WIDTH_AND_HEIGHT);
    imagecopyresampled($thumbImage, $srcImage, 0, 0, 0, 0, THUMBNAIL_WIDTH_AND_HEIGHT, THUMBNAIL_WIDTH_AND_HEIGHT, $width, $height);

    switch($this->_imageType) {
      case IMAGETYPE_GIF:
        imagegif($thumbImage, THUMBNAIL_DIR . '/' . $this->_imageFileName);
        break;
      case IMAGETYPE_JPEG:
        imagejpeg($thumbImage, THUMBNAIL_DIR . '/' . $this->_imageFileName);
        break;
      case IMAGETYPE_PNG:
        imagepng($thumbImage, THUMBNAIL_DIR . '/' . $this->_imageFileName);
        break;
    }
  }


  private function _save($ext){
    $this->_imageFileName = sprintf(
      '%s_%s.%s',
      time(),
      sha1(uniqid(mt_rand(), true)),
      $ext
    );

    $savePath = IMAGES_DIR. '/'. $this->_imageFileName;
    $res = move_uploaded_file($_FILES['image']['tmp_name'], $savePath);
    if($res === false){
      throw new \Exception('Could not upload');
    }

    return $savePath;
  }

  private function _validateImageTyple(){
    // 画像の種類（gitとかpngとか）を調べて変数に格納
    $this->_imageType = exif_imagetype($_FILES['image']['tmp_name']);

    switch($this->_imageType){
      case IMAGETYPE_GIF:
        return 'gif';
      case IMAGETYPE_JPEG:
        return 'jpg';
      case IMAGETYPE_PNG:
        return 'png';
      default:
        throw new \Exception('PNGかJPEGかGIFのみれす');
    }
  }


  private function _validateUpload(){
    if(!isset($_FILES['image']) || !isset($_FILES['image']['error'])){
      throw new \Exception('Upload Error!');
    }

    switch($_FILES['image']['error']){
      case UPLOAD_ERR_OK:
        return true;
      case UPLOAD_ERR_INI_SIZE:
      case UPLOAD_ERR_FORM_SIZE:
        throw new \Exception('File too large!');
      default:
        throw new \Exception('なんかエラー:'. $_FILES['image']['error']);
    }
  }
}