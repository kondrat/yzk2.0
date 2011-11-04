<?php

class UploadComponent extends Object {

    /**
     * 	Private Vars
     */
    private $_file;
    private $_destination;
    private $_name;
    private $_allowed;
    /**
     * 	Public Vars
     */
    public $errors;

    function startup(&$controller) {
        // This method takes a reference to the controller which is loading it.
        // Perform controller initialization here.
        $this->controller = &$controller;
    }

    /**
     * upload
     * - handle uploads of any type
     * 		@ file - a file (file to upload) $_FILES[FILE_NAME]
     * 		@ path - string (where to upload to)
     * 		@ name [optional] - override saved filename
     * 		@ allowed [optional] - allowed filetypes
     * 			- defaults to 'jpg','jpeg','gif','png'
     * 	ex:
     * 	$upload = new upload($_FILES['MyFile'], 'uploads');
     *
     */
    public function upload($file, $destination) {

        $this->result = array();
        $this->errors = array();

        // -- save parameters
        $this->_file = $file;

        $this->_destination = $destination;
        $this->_destinationTmp = $destination."-mtp";
        
        //alowed file type
        $this->_allowed = array('zip', 'ZIP');

        // -- check that FILE array is even set
        if (isset($file) && is_array($file) && !$this->upload_error($file['error'])) {

            //clean given tmp directiory
            
            if( !$this->cleanFilesInDir($this->_destinationTmp) ){
                $this->error(__d('users','Mistake with uploading zip arhive',true));
                return 1;
            }
            
            
            // -- cool, now set some variables

            $fileName = $this->uniquename($this->_destinationTmp. DS . $file['name']);

            $fileTmp = $file['tmp_name'];
            $fileSize = $file['size'];
            $fileType = $file['type'];
            $fileError = $file['error'];


            // -- error if not correct extension
            if (!in_array($this->ext($fileName), $this->_allowed)) {
                $this->error(__d('users', 'This file extention is not allowed',true));
            } else {

                // -- it's been uploaded with php
                if (is_uploaded_file($fileTmp)) {
                    // -- how are we handling this file
                    // -- where to put the file?
                    //$output = $fileName;
                    // -- just upload it
                    if (move_uploaded_file($fileTmp, $fileName)) {
                        chmod($fileName, 0644);
                        $this->result['filename'] = basename($fileName);



                        $zip = new ZipArchive;
                        $res = $zip->open($this->_destinationTmp .DS. $this->result['filename']);
                        if ($res === TRUE) {
                            $zip->extractTo($this->_destinationTmp);
                            $zip->close();
                            @unlink($this->_destinationTmp . DS .$this->result['filename']);

                            $uploadedFiles = array();
                            $handle = opendir($this->_destinationTmp);
                            $filesMustBe = array('cacert.pem', 'cert.crt', 'private.key', 'req.csr', '.', '..');
                            if ($handle) {
                                /* This is the correct way to loop over the directory. */
                                while (false !== ($fileInDir = readdir($handle))) {
                                    $uploadedFiles[] = $fileInDir;
                                }

                                foreach ($uploadedFiles as $v) {

                                    if (!in_array($v, $filesMustBe)) {

                                        // error: wrong archive
                                        $this->error(__d('users', 'Wrong file inside zip archive',true));
                                        
                                        break;
                                    } else {
                                        
                                        if ($v == 'cert.crt') {
                                            $md5check = md5_file($this->_destinationTmp. DS . 'cert.crt');
                                            $this->result['md5cert'] = $md5check;
                                            $fl = file($this->_destinationTmp. DS . 'cert.crt');
                                            //debug($fl);
                                            
                                            if (preg_match("/Not Before:/i", $fl[7],$matches)) {
                                                
                                                $posStr = strpos($fl[7], ":") + 1;
                                                $time = substr($fl[7], $posStr);
                                                $m = strtotime($time);
                                                //debug($m);
                                                $this->result['notBefore'] = date('Y-m-d H:i:s', $m);
                                            }
                                            if (preg_match("/Not After :/i", $fl[8],$matches)) {
                                                
                                                $posStr = strpos($fl[8], ":") + 1;
                                                $time = substr($fl[8], $posStr);
                                                $m = strtotime($time);
                                                //debug($m);
                                                $this->result['notAfter'] = date('Y-m-d H:i:s', $m);
                                            }
                                            
                                           
                                            
//                                            echo "Jul  7 11:24:45 2011 GMT" . "\n";
//                                            $m = strtotime("Jul  7 11:24:45 2011 GMT");
//                                            echo $m."\n";
//                                            echo date('l jS \of F Y h:i:s A', $m). "\n";
                                        }
                                    }
                                }

                                closedir($handle);
                            }
                        } else {
                            //not possible to open zip arhive
                            $this->error(__d('users', 'Not possible to open archive',true));
                        }
                    } else {
                        $this->error(__d('users', 'Not possible to open archive',true));
                        //$this->error("Could not move '$fileName' to '$destination'");
                    }
                } else {
                    $this->error(__d('users', 'Not possible to open archive',true));
                    //$this->error("Possible file upload attack on '$fileName'");
                }
            }
        } else {
            $this->error(__d('users', 'Not possible to open archive',true));
            //$this->error($this->upload_error($file['error']));
        }


        if ($this->errors != array()) {
            $this->cleanFilesInDir($this->_destinationTmp);
            return 1;
        } else {

            return $this->result;
        }
        
    }

    public function moveCertFiles($dist){
            $one = rename($dist."-mtp".DS."cert.crt", $dist.DS."cert.crt");
            $two = rename($dist."-mtp".DS."cacert.pem", $dist.DS."cacert.pem");
            $three = rename($dist."-mtp".DS."private.key", $dist.DS."private.key");
            $four = rename($dist."-mtp".DS."req.csr", $dist.DS."req.csr"); 
            if($one && $two && $three && $four){
                return TRUE;
            }else{
                $this->cleanFilesInDir($dist);
                $this->cleanFilesInDir($dist."-mtp");
                return FALSE;
            }
    }
    
/**
 *
 * @param type $notBefore
 * @param type $notAfter
 * @param type $md5cert
 * @return boolean
 */
    public function checkCertValidity($notBefore = null, $notAfter = null, $md5cert = null){
        $givenTimeNotBefore = strtotime($notBefore);
        $givenTimeNotAfter = strtotime($notAfter);
        //return $givenTime - microtime();
        $a = explode(' ', microtime());
        $now = (double)$a[1];
        
        if ( $now < $givenTimeNotAfter && $now > $givenTimeNotBefore && $md5cert != NULL ){
            return TRUE;
            //return array('true',$now,$givenTimeNotAfter,$givenTimeNotBefore);
        } else {
            return FALSE;
            //return array('false',$now,$givenTimeNotAfter,$givenTimeNotBefore);
        }
        
    }

    // -- return the extension of a file	
    function ext($file) {
        $ext = trim(substr($file, strrpos($file, ".") + 1, strlen($file)));
        return $ext;
    }

/**
 *
 * @param type $dirname
 * @return boolean
 */
    private function cleanFilesInDir($dirname) {
//        if (is_dir($dirname)) {
//            $handle = opendir($dirname);
//            if ($handle) {
//                while (false !== ($fileInDir = readdir($handle))) {
//                    if($fileInDir != "." || $fileInDir != ".."){
//                        @unlink($dirname. DS . $fileInDir);
//                    } else if(is_dir($fileInDir) && ($fileInDir != "." || $fileInDir != "..") ) {
//                        cleanFilesInDir($fileInDir);
//                    }
//                    
//                }
//                closedir($handle);
//                return TRUE;
//            } else {
//                return FALSE;
//            }
//        } else {
//            //return FALSE;
//        }
        $this->recusiveFileClean($dirname);
        
        $files = scandir($dirname);
        array_shift($files);    // remove '.' from array
        array_shift($files);
        
        if($files != array()){
            return FALSE;
        } else {
            return TRUE;
        }
        
    }
  
    public function recusiveFileClean($dir) {
        $files = scandir($dir);
        array_shift($files);    // remove '.' from array
        array_shift($files);    // remove '..' from array

        foreach ($files as $file) {
            $file = $dir . '/' . $file;
            if (is_dir($file)) {
                $this->recusiveFileClean($file);
                @rmdir($file);
            } else {
                
                @unlink($file);
            }
        }
        //rmdir($dir);
    }
    
    
    
    
    // -- add a message to stack (for outside checking)
    function error($message) {
        array_push($this->errors, $message);
    }

    function newname($file) {
        return time() . "." . $this->ext($file);
    }

    function uniquename($file) {
        $parts = pathinfo($file);
        $dir = $parts['dirname'];
        $file = ereg_replace('[^[:alnum:]_.-]', '', $parts['basename']);
        $ext = $parts['extension'];
        if ($ext) {
            $ext = '.' . $ext;
            $file = substr($file, 0, -strlen($ext));
        }
        $i = 0;
        while (file_exists($dir . DS . $file . $i . $ext)) {
            $i++;
        }
        return $dir . DS . $file . $i . $ext;
    }

    function upload_error($errorobj) {
        $error = false;
        switch ($errorobj) {
            case UPLOAD_ERR_OK:
                break;
            case UPLOAD_ERR_INI_SIZE: $error = "The uploaded file exceeds the upload_max_filesize directive (" . ini_get("upload_max_filesize") . ") in php.ini.";
                break;
            case UPLOAD_ERR_FORM_SIZE: $error = "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.";
                break;
            case UPLOAD_ERR_PARTIAL: $error = "The uploaded file was only partially uploaded.";
                break;
            case UPLOAD_ERR_NO_FILE: $error = "No file was uploaded.";
                break;
            case UPLOAD_ERR_NO_TMP_DIR: $error = "Missing a temporary folder.";
                break;
            case UPLOAD_ERR_CANT_WRITE: $error = "Failed to write file to disk";
                break;
            default: $error = "Unknown File Error";
        }
        return ($error);
    }

}

?>
