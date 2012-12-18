<?php

require_once 'Zend/Auth/Adapter/Interface.php';
require_once 'Sea/Ftp2.php';
require_once 'Zend/Auth/Result.php';

/** 
 * @author jhouvion
 * 
 * 
 */
class Sea_Auth_Adapter_Ftp implements Zend_Auth_Adapter_Interface
{
	/**
	 * identifiant
	 * 
	 * @var unknown_type
	 */
	protected $_identity;
	
	/**
	 * mot de passe
	 * @var unknown_type
	 */
	protected $_credential;
	
	/**
	 * adresse du serveur
	 * @var unknown_type
	 */
	protected $_host = 'localhost';
   
    public function authenticate() {
       
    	try {
    	    // connection ftp
    		Sea_Ftp2::connectWithCredential($this->getHost(), $this->getIdentity(), $this->getCredential());  
    		$auth = new Zend_Auth_Result(Zend_Auth_Result::SUCCESS, $this->getIdentity(), array("authentication successful"));  
    	}catch(Exception $e) {
    		$auth = new Zend_Auth_Result(Zend_Auth_Result::FAILURE, $this->getIdentity()); 
    	}
       	return $auth;
    }
	

	/**
	 * @return the $_host
	 */
	public function getHost() {
		return $this->_host;
	}

	/**
	 * @param unknown_type $_host
	 */
	public function setHost($_host) {
		$this->_host = $_host;
		return $this;
	}
	/**
	 * @return the $_identity
	 */
	public function getIdentity() {
		return $this->_identity;
	}

	/**
	 * @return the $_credential
	 */
	public function getCredential() {
		return $this->_credential;
	}

	/**
	 * @param unknown_type $_identity
	 */
	public function setIdentity($_identity) {
		$this->_identity = $_identity;
		return $this;
	}

	/**
	 * @param unknown_type $_credential
	 */
	public function setCredential($_credential) {
		$this->_credential = $_credential;
		return $this;
	}
}