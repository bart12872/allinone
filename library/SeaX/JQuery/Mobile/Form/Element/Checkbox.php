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
require_once 'Zend/Form/Element/Checkbox.php';

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
class SeaX_JQuery_Mobile_Form_Element_Checkbox extends Zend_Form_Element_Checkbox
{
    /**
     * Use formSelect view helper by default
     * @var string
     */
    public $helper = 'mobileFormCheckbox';
    
    
 	/**
     * surcharge du constructeur
     * 
     * @param unknown_type $spec
     * @param unknown_type $options
     * @param unknown_type $multi
     */
    public function __construct($spec, $label = '', $required = false ) {

    	// construction du parent
    	parent::__construct($spec);
    	
    	// traitement des paramètres
    	$this->setLabel($label)->setRequired($required)->setAllowEmpty(!$required);
    	
    	if ($required) {$this->addValidator('NotEmpty', false, Zend_Validate_NotEmpty::ZERO);}
    }

      /**
     * chargement des decorateurs par default
     * 
     * (non-PHPdoc)
     * @see Zend_Form_Element::loadDefaultDecorators()
     */
    public function loadDefaultDecorators() {
    	
		$this->setDecorators(array(	
									array('ViewHelper'),
									array('SeaLabel', array('class' => 'custom')),
									array(array('div' => 'HtmlTag'), array('tag' => 'div', 'data-role' => 'fieldcontain')),
									array('SeaErrors')));

	}
	
}
