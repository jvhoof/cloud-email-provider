<?php

class Upload extends Controller {

    function __construct($f3) {
        parent::__construct($f3);
        $this->layout = 'layouts/layout.html';
        $this->mimetype = 'text/html';
        $this->render = true;
    }

    //! HTTP route pre-processor
    function beforeroute($f3) {
        parent::beforeroute($f3);
    }

    function show($f3) {
        $this->logger->write( '[Upload->show] Start' );
        $f3->set('html_title','Barracuda Networks');
        $f3->set('content','upload.html');
    }

    function post($f3) {
        $this->render = false;
        $this->logger->write( '[Upload->upload] Start' );
        $web = \Web::instance();
        $f3->set('UPLOADS',$f3->get('TEMP')); // don't forget to set an Upload directory, and make it writable!

        $overwrite = false; // set to true, to overwrite an existing file; Default: false
        $slug = true; // rename file to filesystem-friendly version

        $files = $web->receive(function($file,$formFieldName){
            $this->logger->write( '[Upload->put] file ' . var_dump($file) );
                var_dump($file);
                /* looks like:
                  array(5) {
                      ["name"] =>     string(19) "csshat_quittung.png"
                      ["type"] =>     string(9) "image/png"
                      ["tmp_name"] => string(14) "/tmp/php2YS85Q"
                      ["error"] =>    int(0)
                      ["size"] =>     int(172245)
                    }
                */
                // $file['name'] already contains the slugged name now

                // maybe you want to check the file size
                if($file['size'] > (1024 * 1024)) // if bigger than 1 MB
                    return false; // this file is not valid, return false will skip moving it

                // everything went fine, hurray!
                return true; // allows the file to be moved from php tmp dir to your defined upload dir
            },
            $overwrite,
            $slug
        );

        
        foreach($files as $file => $check) {
            var_dump($files);
            $this->logger->write( '[Upload->upload] key: ' . $file . ' value: ' . $check );
            if( $check ) {
                $txt_file    = file_get_contents($file);
                $rows        = explode("\n", $txt_file);
                array_shift($rows);

                foreach($rows as $row => $data)
                {
                    $this->logger->write( '[Upload->upload] content file key: ' . $row . ' value: ' . $data );
                }
            }
        }

        //$files will contain all the <f></f>iles uploaded, in your case 1 hence $files[0];
        $answer = array( 'answer' => 'Files transfer completed' );
        $json = json_encode( $answer );
        echo $json;
    }
}
?>
