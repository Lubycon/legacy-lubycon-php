<?php
class Zipper
{
    private $_files = array(),
            $_zip;

    public function __construct()
    {
        $this->_zip = new ZipArchive;
    }

    public function add($input)
    {
        if(is_array($input))
        {
            //echo "it is array<br/>";
            $this->_files = array_merge($this->_files , $input);
        }else
        {
            //echo "it is not array<br/>";
            $this->_files[] = $input;
        }
    }

    public function store( $location = null )
    {
        if( count($this->_files) && $location )
        {
            foreach( $this->_files as $index => $file )
            {
                if( !file_exists($file) )
                {
                    unset( $this->_files[$index] );
                }
            }

            if( $this->_zip-> open( $location , file_exists($location) ? ZipArchive::OVERWRITE : ZipArchive::CREATE ))
            {
                foreach( $this->_files as $file )
                {
                    $this->_zip->addFile($file,$file);
                }
                $this->_zip->close();
            }
        }
    }
}
?>