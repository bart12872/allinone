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
require_once 'Zend/Form/Element/Textarea.php';

/**
 * Textarea form element
 *
 * @category   Zend
 * @package    Zend_Form
 * @subpackage Element
 * @copyright  Copyright (c) 2005-2008 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: Textarea.php 8064 2008-02-16 10:58:39Z thomas $
 */
class SeaX_JQuery_Mobile_Form_Element_Textarea extends Zend_Form_Element_Textarea
{
	
	/**
	 * surcharge du constructeur
	 * 
	 * @param unknown_type $spec
	 * @param unknown_type $options
	 */
	public function __construct($spec, $label,  $required = false, $hidden = false) {
		
		parent::__construct($spec);
		
	    	// traitement des paramètres
  		$this->setLabel($label)->setRequired($required)->setAllowEmpty(!$required);
  		
  		if($hidden){
  			$this->setAttribs(array('placeholder' => $label));
  			$this->removeDecorator('SeaLabel');
  		}
	}
	
	/**
	 * pour les textarea, on enleve les slashes(non-PHPdoc)
	 * @see Zend_Form_Element::setValue()
	 */
	public function setValue($value) {
		parent::setValue(stripslashes($value));
	}
	
	 /**
     * chargement des decorateurs par default
     * 
     * (non-PHPdoc)
     * @see Zend_Form_Element::loadDefaultDecorators()
     */
    public function loadDefaultDecorators() {

		/* on ajoute un emplacement e decorateurs à l'élément */
		$this->addPrefixPath('Sea_Form_Decorator', 'Sea/Form/Decorator', 'decorator');

		/* Remplace les decorateurs par default */
		$this->setDecorators(array(	
						           array('ViewHelper', array('placement' => 'APPEND')),
						            array('Description'),
						            array(array('data' => 'HtmlTag'), array('tag' => 'td', 'class' => 'formData')),
								    array('SeaLabel', array('tag' => 'td', 'class' => 'formLabel')),
								    array(array('row' => 'HtmlTag'), array('tag' => 'tr', 'class' => 'formRow')),
									array('SeaErrors')));

	}

}