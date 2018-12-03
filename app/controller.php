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
        $logger = new \Log("log/events.log");
		$this->logger=$logger;

		//$f3=Base::instance();
		// Connect to the database
//        $logger->write( "[Controller] Connecting to DB : " . $f3->get('dburi') . " Username: " . $f3->get('authlogin') );
//		$db=new DB\SQL($f3->get('dburi'), $f3->get('authlogin'), $f3->get('authpwd'));
//#		// Use database-managed sessions
//		new DB\SQL\Session($db);
//		// Save frequently used variables
//		$this->db=$db;

        $logger->write("[Controller] Calling " . get_class($this));

//        \Template::instance()->extend('pagebrowser','\Pagination::renderTag');
	}

}
