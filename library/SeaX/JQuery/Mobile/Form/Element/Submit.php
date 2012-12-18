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
 * Submit form element
 *
 * @category   Zend
 * @package    Zend_Form
 * @subpackage Element
 * @copyright  Copyright (c) 2005-2008 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: Submit.php 8585 2008-03-06 19:32:34Z matthew $
 */
class SeaX_JQuery_Mobile_Form_Element_Submit extends Zend_Form_Element_Submit
{
    /**
     * Default view helper to use
     * @var string
     */
    public $helper = 'formSubmit';

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
		
		$this->setDecorators(array(	array('SeaErrors'),
						           array('ViewHelper', array('placement' => 'APPEND')),
						            array('Description'),
						            array(array('data' => 'HtmlTag'), array('tag' => 'td', 'colspan' => '2', 'class' => 'formData')),
								    array(array('row' => 'HtmlTag'), array('tag' => 'tr', 'class' => 'formSubmitFooter'))));

   }
}
