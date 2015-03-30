<?php
/**
* File upload class.
*
*/
class CFileUpload {
    
    /**
    * Upload an image file.
    * @param $dir the target directory to upload the file to.
    * @return array with upload status, user information and the image path.
    */
    public function fileUpload($dir="upload/") {
        $output = null;
        $target_file = $dir . basename($_FILES["file"]["name"]);
        $uploadOk = true;
        $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
        
        // Check if image file is a actual image or fake image
        if(isset($_POST["saveMovie"])) {
            $check = getimagesize($_FILES["file"]["tmp_name"]);
            if($check !== false) {
                $uploadOk = true;
            } else {
                $output = "File is not an image. ";
                $uploadOk = false;
            }
        }

        // Check if file already exists
        if (file_exists($target_file)) {
            $output =  "File already exists. ";
            $uploadOk = false;
        } 

        // Check file size
        if ($_FILES["file"]["size"] > 1000000) {
            $output = "File is too large. ";
            $uploadOk = false;
        } 

        // Allow certain file formats
        if($imageFileType != "jpg" && $imageFileType != "png" 
            && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
            $output = "Only JPG, JPEG, PNG & GIF files are allowed. ";
            $uploadOk = false;
        } 

        // Check if $uploadOk is set to false by an error
        if ($uploadOk == false) {
            $output = "File was not uploaded. ";
        // if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
                $output = "The file ". basename( $_FILES["file"]["name"]). " has been uploaded. ";
            } else {
                $output = "Error uploading file. ";
            }
        }

        return array('uploadOk' => $uploadOk, 'output' => $output, 'image' => $target_file);
    }

}