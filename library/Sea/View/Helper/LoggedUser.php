<?php

class Sea_View_Helper_LoggedUser 
{    
    public $view;

    public function setView(Zend_View_Interface $view)
    {
        $this->view = $view;
    }
    
    public function loggedUser() 
    {
        $auth = Zend_Auth::getInstance();        
        $auth->setStorage(new Zend_Auth_Storage_Session('auth_ldap'));
        return $auth->getIdentity();
    }
    
}