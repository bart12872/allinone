<?php
/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    Zend_Form
 * @subpackage Element
 * @copyright  Copyright (c) 2005-2008 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */

/** Zend_Form_Element_Xhtml */
require_once 'Zend/Form/Element/Xhtml.php';

/**
 * Text form element
 *
 */
class SeaX_JQuery_Mobile_Form_Element_Search extends Zend_Form_Element_Xhtml
{
    /**
     * Default form view helper to use for rendering
     * @var string
     */
    public $helper = 'mobileFormSearch';
    
    /**
     * surcharge du constructeur
     * 
     * @param unknown_type $spec
     * @param unknown_type $options
     */
    public function __construct($spec, $label = '', $required = false, $hidden = false) {
    
    	// construction du parent
    	parent::__construct($spec);
    	
    	// traitement des paramètres
  		$this->setLabel($label)->setRequired($required)->setAllowEmpty(!$required);
  		
  		if($hidden){
  			$this->setAttribs(array('placeholder' => $label));
  			$this->removeDecorator('SeaLabel');
  		}

    	// on ajout le filtre trim par default
    	$this->addFilter('StringTrim');
    }
    
    /**
     * chargement des decorateurs par default
     * 
     * (non-PHPdoc)
     * @see Zend_Form_Element::loadDefaultDecorators()
     */
    public function loadDefaultDecorators() {
    	
    	$this->setDecorators(array(	array('SeaErrors'),
    								array('ViewHelper', array('placement' => 'APPEND')),
								   	array('SeaLabel', array('tag' => 'legend')),
    								array(array('div' => 'HtmlTag'), array('tag' => 'div', 'data-role' => 'fieldcontain'))));
    }
    
    public function removeContainer(){

    	$this->removeDecorator('div');
    	$this->addDecorator(array('div' => 'HtmlTag'), array('tag' => 'div', 'data-role' => 'content'));
    }
}
