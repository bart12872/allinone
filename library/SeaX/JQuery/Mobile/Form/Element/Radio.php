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
class SeaX_JQuery_Mobile_Form_Element_Radio extends Zend_Form_Element_Multi
{
    /**
     * Use formSelect view helper by default
     * @var string
     */
    public $helper = 'formRadio';


    public function __construct($spec, $label = '' ,$multi = array(), $required = false ) {
    	
    	// construction du parent
    	parent::__construct($spec);
    	
    	// on ajoute les options
    	$this->addMultiOptions($multi);
    	
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

		//on retire le separateur par defaut.
    	$this->setSeparator('');
    	
		$this->setDecorators(array(	
						            array('ViewHelper', array('placement' => 'APPEND')),
									array('SeaLabel', array('tag' => 'legend')),
								    array(array('fieldset' => 'HtmlTag'), array('tag' => 'fieldset', 'data-role' => 'controlgroup')),
									array('SeaErrors')));
								
		
	}
	
	/**
	 * chargement les decorateurs pour rendre la radio horizontal
	 *
	 * (non-PHPdoc)
	 * @see SeaX_JQuery_Form_Element::setHorizontal()
	 */
	
	public function setHorizontal($b =  true) {
		
		if($b){$this->getDecorator('fieldset')->setOption('data-type','horizontal');}
		else{$this->getDecorator('fieldset')->removeOption('data-type','horizontal');}
	
	}
}
