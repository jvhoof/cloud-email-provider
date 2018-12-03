<?php

class Main extends Controller {

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
        $f3->set('html_title','Barracuda Networks');
        $f3->set('content','main.html');
    }
}
?>
