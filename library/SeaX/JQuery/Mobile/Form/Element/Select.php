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

/** Zend_Form_Element_Multi */
require_once 'Zend/Form/Element/Multi.php';

/**
 * Select.php form element
 *
 * @category   Zend
 * @package    Zend_Form
 * @subpackage Element
 * @copyright  Copyright (c) 2005-2008 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: Select.php 8064 2008-02-16 10:58:39Z thomas $
 */
class SeaX_JQuery_Mobile_Form_Element_Select extends Zend_Form_Element_Multi
{
    /**
     * Use formSelect view helper by default
     * @var string
     */
    public $helper = 'formSelect';
    
    /**
     * surcharge du constructeur
     * 
     * @param unknown_type $spec
     * @param unknown_type $options
     * @param unknown_type $multi
     */
    public function __construct($spec, $label = '' ,$multi = array(), $required = false ) {
    	
    	// construction du parent
    	parent::__construct($spec);
    	// on ajoute les options
    	$this->addMultiOptions($multi);
    	
    	$this->loadDefaultDecorators();
    	
    	if($required){$label = $label.'*';}
    	
    	// traitement des paramètres
    	$this->setLabel($label)->setRequired($required)->setAllowEmpty(!$required);
    }

    /**
     * chargement des decorateurs par default
     * 
     * (non-PHPdoc)
     * @see Zend_Form_Element::loadDefaultDecorators()
     */
    public function loadDefaultDecorators() {

		/* Remplace les decorateurs par default */
		$this->setDecorators(array(
    								array('ViewHelper'),
								    array('SeaLabel', array('class' => 'custom')),
									array(array('fieldset' => 'HtmlTag'), array('tag' => 'fieldset', 'data-role' => 'controlgroup')),
									array('SeaErrors')));
		
		

	}
	
	public function setNoLabel($bool = false){

		$options = $this->getMultiOptions();
		$options[''] = $this->getLabel();
		$this->addMultiOptions($options);
		$this->removeDecorator('SeaLabel');
	}
	
}
