<?php

class SeaX_JQuery_Mobile_Form_Decorator_SeaLabel extends Zend_Form_Decorator_Label
{
    /**
     * Render a label
     *
     * @param  string $content
     * @return string
     */
    public function render($content)
    {
        $element = $this->getElement();
        $view    = $element->getView();
        if (null === $view) {
            return $content;
        }

        
        $label     = $this->getLabel();
        $separator = $this->getSeparator();
        $placement = $this->getPlacement();
        $tag       = $this->getTag();
        $id        = $this->getId();
        $class     = $this->getClass();
        $options   = $this->getOptions();
		
        
       $pattern = 'required';              
       if(preg_match("/".$pattern."/",$class)!=0){ 
       		$label.='*';     		
       }

        if (empty($label) && empty($tag)) {return $content;}

     	if (!empty($label)) {
     		$formLabel = new SeaX_JQuery_View_Helper_MobileFormLabel();
     		$label = $formLabel->mobileFormLabel($element->getFullyQualifiedName(), trim($label), $options);
     	} 
        else {$label = '&nbsp;';}

        if (null !== $tag) {       
            require_once 'Zend/Form/Decorator/HtmlTag.php';
            $decorator = new Zend_Form_Decorator_HtmlTag();
            
            $decorator->setOptions(array('tag' => $tag,
                                         'id'  => $this->getElement()->getName() . '-label',
                                         'class' => $class));			
            $label = $decorator->render($label);
        }       
        switch ($placement) {
            case self::APPEND:
                return $content . $separator . $label;
            case self::PREPEND:
                return $label . $separator . $content;
        }
    }
}