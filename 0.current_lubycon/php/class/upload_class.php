<?php
class upload
{
    private $_temp_path = '../../../../Lubycon_Contents/contents/temp/';
    private $_filesize_array = array();
    private $_filepath_array = array();
    private $_filename_array = array();
    private $_zip;
    private $_profile_name = 'profile.';

    private $_white_list_media = ['jpg', 'jpeg', 'png', 'psd', 'pdf', 'gif', 'bmp', 'pdd', 'tif', 'raw', 'ai', 'esp', 'svg', 'svgz', 'iff', 'fpx', 'frm', 'pcx', 'pct', 'pic', 'pxr', 'sct', 'tga', 'vda', 'icb', 'vst', 'alz', 'zip', 'rar', 'jar', '7z', 'hwp', 'txt', 'doc', 'xls', 'xlsx', 'docx', 'pptx', 'pdf', 'ppt', 'me'];
    private $_white_list_img = ['jpg', 'jpeg', 'png', 'psd', 'gif', 'bmp', 'pdd', 'tif', 'raw', 'ai', 'esp', 'svg', 'svgz', 'iff', 'fpx', 'frm', 'pcx', 'pct', 'pic', 'pxr', 'sct', 'tga', 'vda', 'icb', 'vst'];
    private $_white_list_zip = ['alz', 'zip', 'rar', 'jar', '7z'];
    private $_white_list_txt = ['hwp', 'txt', 'doc', 'xls', 'xlsx', 'docx', 'pptx', 'pdf', 'ppt', 'me'];
    private $_white_list_all = ['all'];


    public function __construct()
    {
        $this->_zip = new ZipArchive;
    }

    public function validate_size($files,$limit_size)
    {
        //print_r($files);
        if(is_array($files))
        {
            for($i=0; $i<count($files['name']); $i++) 
            {
                $_filesize_array[$i] = $files['size'][$i]; // 각 파일사이즈 크기 배열에 푸시
            }
            if( intval(array_sum($_filesize_array)) <= intval($limit_size)) // 파일크기 검사
            {
                echo array_sum($_filesize_array) . ' file size validate done <br/>';
                echo 'file size check done<hr/>';
            }else
            {
                die('over limit size');
            }
        }else
        {
            die('nothing submit');
        }
    }

    public function validate_ext($files,$white_list)
    {
        if(is_array($files))
        {
            switch ($white_list) 
            {
                case 'media': $white_list = $this->_white_list_media; break;
                case 'img': $white_list = $this->_white_list_img; break;
                case 'zip': $white_list = $this->_white_list_zip; break;
                case 'txt': $white_list = $this->_white_list_txt; break;
                case 'all': $white_list = $this->_white_list_all; break;
                default: $white_list = $this->_white_list_all; break;
            }
            for($i=0; $i<count($files['name']); $i++) 
            {
                $filename = $files['name'][$i]; // 오리지날 파일이름
                $ext = substr(strrchr($filename, '.'), 1); // 확장자 추출
                if ( !in_array($ext, $white_list) && $white_list != ['all'] )  // 확장자 검사
                {
                    die($filename.' not allow<br/>');
                }
                echo $filename . ' is in white list<br/>';
            }
            echo 'file ext check done<hr/>';
        }else
        {
            die('nothing submit');
        }
    }

    public function file_move($files,$upload_path,$zip_compress)
    {
        if( is_dir($upload_path) ? chmod($upload_path,0777) : mkdir($upload_path,0777) ) // make final save dir
        {
            if( @is_uploaded_file($files['tmp_name'][0])) // check is uploaded file or ajax file
            {
               foreach ($files["error"] as $key => $error)  // for each as files (key is error code)
               {    
                    echo '1';
                    $tmp_name = $files["tmp_name"][$key]; // uploaded temp name validate 
                    $name = iconv("UTF-8","EUC-KR",$files['name'][$key]); // uploaded file name validate

                    if ( $error == UPLOAD_ERR_OK && $zip_compress ) // check well upload and compress or not
                    {
                        move_uploaded_file($tmp_name, $this->_temp_path.$name); // move to temp folder
                        $this->_filepath_array[$key] = $this->_temp_path.$name; // push array temp file path
                        $this->_filename_array[$key] = $name; // push array only filename
                    
                        echo "move to uploaded file temp folder, ready to zip<br/>";

                    }else if( $error == UPLOAD_ERR_OK && !$zip_compress ) // not compress and move files
                    {
                        move_uploaded_file($tmp_name, $upload_path.$name); // move file to save place
                        $this->_filepath_array[$key] = $upload_path.$name; // final uploaded file name
                    
                        echo "succece upload<br/>";
                    }else
                    {
                        die('something was wrong... check the logic');
                    }
                }
                print_r($this->_filepath_array); // move uploaded files path
                return;
            }else // maybe ajax
            {
                if(is_array($files))  //lots of files
                {
                    for($i=0 ; $i< count($files); $i++)
                    {
                        $modName = basename($files[$i]); //파일명 추출
                        $oldfile = $this->_temp_path.$modName; // temp file
                        $newfile = $upload_path.$modName; // 

                        if(file_exists($oldfile)) {
                             if(!copy($oldfile, $newfile)) {
                                   echo "fail";
                             } else if(file_exists($newfile)) {
                                   unlink($oldfile);
                                   echo $newfile . "<br/>"; //uploaded file path
                             }
                        } 
                    };
                }else // only profile img
                {
                    $modName = basename($files); //파일명 추출
                    $ext = substr(strrchr($modName, '.'), 1); // 확장자 추출
                    $oldfile = $this->_temp_path.$modName; // temp file
                    $newfile = $upload_path.$this->_profile_name.$ext; // copyed file

                    if(file_exists($oldfile)) 
                    {
                        if(!copy($oldfile, $newfile)) 
                        {
                            echo "fail";
                        } else if(file_exists($newfile)) 
                        {
                            unlink($oldfile);
                            echo $newfile . "<br/>"; //uploaded file path
                        }
                    }
                }
            }
        }


    }

    public function zipfile($files,$zip_compress, $upload_path = null , $upload_zip)
    {
        if($zip_compress) // zip
        {
            if( count($this->_filepath_array) && $upload_zip )
            {
                foreach( $this->_filepath_array as $index => $file )
                {
                    if( !file_exists($file) )
                    {
                        unset( $this->_filepath_array[$index] );
                    }
                }

                if( $this->_zip-> open( $upload_zip , file_exists($upload_zip) ? ZipArchive::OVERWRITE : ZipArchive::CREATE ))
                {
                    foreach( $this->_filepath_array as $index => $file )
                    {
                        $this->_zip->addFile($file,$this->_filename_array[$index]);
                    }
                    $this->_zip->close();

                    foreach ($files["name"] as $key => $name)  // 파일 갯수만큼 foreach 하며 에러 상태메세지 
                    {
                        unlink( $this->_filepath_array[$key] ); //임시파일 제거
                    }
                    echo "succece zip<br/>";
                }
            }
        }else // not zip
        {
            echo '<br/>do not zip just save';
        }
    }
}
?>