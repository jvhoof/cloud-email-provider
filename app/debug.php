<?php

class Debug extends Controller {

    function __construct($f3) {
        parent::__construct($f3);
        $this->layout = 'layouts/layout.html';
        $this->mimetype = 'text/html';
        $this->render = false;
    }

    //! HTTP route pre-processor
    function beforeroute($f3) {
        parent::beforeroute($f3);
    }
    
    function show($f3) {
        echo json_encode($_SERVER);
    }
}
?>
