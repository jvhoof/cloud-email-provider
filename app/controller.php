<?php

//! Base controller
class Controller {

	protected $db;
	protected $logger;

	//! HTTP route pre-processor
    function beforeroute($f3) {
        if (!$f3->exists('SESSION.messages')) {
            $f3->set('SESSION.messages', array('success' => array(), 'warning' => array(), 'error' => array()));
        }
    }

	//! HTTP route post-processor
    function afterroute($f3) {

        if( isset($this->render) && $this->render ) {
            echo Template::instance()->render($this->layout, $this->mimetype);
        }
        $f3->set('SESSION.messages', array('success' => array(), 'warning' => array(), 'error' => array()));
    }

	//! Instantiate class
	function __construct($f3) {
        $logger = new \Log("log/" . date("Ymd") . "events.log");
		$this->logger=$logger;

        $logger->write("[Controller] Calling " . get_class($this));
	}

}
