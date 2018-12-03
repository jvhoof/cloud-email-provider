<?php

class CloudProvider extends Controller {

  protected $cloudprovider;
  protected $error;

	function __construct($f3) {
    parent::__construct($f3);
    $this->render = false;
    $string = file_get_contents($f3->get("cloudproviderdb"));
    $this->cloudprovider = json_decode($string, true);
	}

	//! HTTP route pre-processor
	function beforeroute($f3) {
		parent::beforeroute($f3);
	}
		
	function checkmx($f3) {
    $this->error = FALSE;
    $resultProvider = FALSE;
    $resultProviderList = array();
    $resultMxFound = FALSE;
    $resultMxFoundList = array();

		$this->logger->write( '[CloudProvider->checkmx] Start' );

    $f3->scrub($_POST,"");

    if( array_key_exists('domain', $_POST) ) {
      $domain=$_POST['domain'];
    } else {
      $domain = $f3->get('PARAMS.domain');
  		$f3->scrub($domain,"");
    }
    set_error_handler( array($this, 'warning_handler'), E_WARNING);
    $foundMxRecords = dns_get_record($domain, DNS_MX );
    restore_error_handler();

    if( $foundMxRecords ) {
      $this->logger->write( '[CloudProvider->checkmx] mx found' );
      $resultMxFound = True;
      foreach( $foundMxRecords as &$foundMxRecord) {
        array_push($resultMxFoundList, $foundMxRecord["target"]);
        foreach( $this->cloudprovider as $cloudProviderKey => $cloudProviderValue ) {
            foreach($this->cloudprovider[$cloudProviderKey]["pattern"] as $pattern ) {
              if (strpos($foundMxRecord["target"], $pattern) !== false) {
                $resultProvider = TRUE;
                if(!in_array($this->cloudprovider[$cloudProviderKey], $resultProviderList, true)){
                  array_push($resultProviderList, $this->cloudprovider[$cloudProviderKey]);
                }
                $this->logger->write( '[CloudProvider->checkmx] cloudprovider [' . $cloudProviderKey . '] found' );
                break;  
              }
            }
        }
      }
      $resultDMARCFound = FALSE;
      $resultDMARCFoundList = array();
  
      $this->logger->write( '[CloudProvider->checkdmarc] Start' );
  
      set_error_handler( array($this, 'dmarc_warning_handler'), E_WARNING);
      $foundDMARCRecords = dns_get_record("_DMARC.".$domain, DNS_TXT );
      restore_error_handler();
      $this->logger->write( '[CloudProvider->checkdmarc] ' . $foundDMARCRecords);
  
      if( $foundDMARCRecords ) {
        $this->logger->write( '[CloudProvider->checkdmarc] DMARC found' );
        $resultDMARCFound = True;
        foreach( $foundDMARCRecords as &$foundDMARCRecord) {
          array_push($resultDMARCFoundList, $foundDMARCRecord["entries"]);
        }
      } else {
        $this->logger->write( '[CloudProvider->checkdmarc] DMARC not found' );
      }
      $resultSPFFound = FALSE;
      $resultSPFFoundList = array();
  
      $this->logger->write( '[CloudProvider->checkspf] Start' );
  
      set_error_handler( array($this, 'spf_warning_handler'), E_WARNING);
      $foundSPFRecords = dns_get_record($domain, DNS_TXT );
      restore_error_handler();
      $this->logger->write( '[CloudProvider->checkspf] ' . $foundSPFRecords);
  
      if( $foundSPFRecords ) {
        foreach( $foundSPFRecords as &$foundSPFRecord) {
          $this->logger->write( '[CloudProvider->checkspf] SPF? ' . $foundSPFRecord["txt"]);
          if (strpos($foundSPFRecord["txt"], "v=spf1") !== false) {
            $this->logger->write( '[CloudProvider->checkspf] SPF found' );
            $resultSPFFound = True;
            array_push($resultSPFFoundList, $foundSPFRecord["entries"]);
          }
        }
      } else {
        $this->logger->write( '[CloudProvider->checkspf] SPF not found' );
      }
    } else {
      $this->logger->write( '[CloudProvider->checkmx] MX not found' );
    }

    if( $this->error ) {
		  echo json_encode(array('error' => $this->error));
    } else {
		  echo json_encode(array('mx' => $resultMxFound, 'mxlist' => $resultMxFoundList, 'cloudprovider' => $resultProvider, 'cloudproviderlist' => $resultProviderList, 'dmarc' => $resultDMARCFound, 'dmarclist' => $resultDMARCFoundList, 'spf' => $resultSPFFound, 'spflist' => $resultSPFFoundList ));
    }
  }

  function warning_handler($errno, $errstr) { 
      $this->logger->write( '[CloudProvider->checkmx] ERROR [' . $errno . '] ' . $errstr );
      $this->error = TRUE;
  }
  function dmarc_warning_handler($errno, $errstr) { 
      $this->logger->write( '[CloudProvider->checkdmarc] ERROR [' . $errno . '] ' . $errstr );
      $this->error = TRUE;
  }
  function spf_warning_handler($errno, $errstr) { 
      $this->logger->write( '[CloudProvider->checkspf] ERROR [' . $errno . '] ' . $errstr );
      $this->error = TRUE;
  }
}