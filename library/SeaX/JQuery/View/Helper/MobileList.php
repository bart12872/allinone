<?php
require_once 'Zend/View/Exception.php';
/** 
 * @author fdorchy
 * 
 * construit une liste pour mobile
 * 
 */
class SeaX_JQuery_View_Helper_MobileList extends Zend_View_Helper_HtmlList {

	public function mobilelist(array $items, $ordered = false, $options = false, $escape = true) {
		
		// Constrcution de la liste
		if (!is_array($items)) {
            $e = new Zend_View_Exception('First param must be an array');
            $e->setView($this->view);
            throw $e;
        }
		//initialisation de la liste
        $list = '';
        
        foreach ($items as $item){
        	
       		//initialisation du depot
        	$item['item'] = '';
        	
			//si on trouve un item divider, c'est un separateur, on ne traite que lui        
        	if(isset($item['divider'])){ $list .='<li data-role="list-divider">' . $item['divider'] . '</li>' . self::EOL;}
        	
        	//si ce n'est pas un separator, on applique le style html à tous les elements du tableau.
        		if(isset($item['img'])){$item['item'] .= '<img class="ui-li-icon" src="'. $item['img'] .'"/>';}
	        	//element title
	        	if(isset($item['title'])){$item['item'] .=  $item['title'];}
	        	//element count
	        	if(isset($item['count'])){$item['item'] .= '<span class="ui-li-count ui-btn-up-c ui-btn-corner-all">'. $item['count'] .'</span>';}
	        	//element image
	        	
	        	//element txt
	        	if(isset($item['desc'])){$item['item'] .= '<p>'. $item['desc'] .'</p>';}
	        	//element link
	        	if(isset($item['link'])){$item['item'] = '<a href=' . $item['link'] . '>' . $item['item'] . '</a>'; }
	        	        
	            $list .= '<li >' . $item['item'] . '</li>' . self::EOL;
      		
        }

        if ($options) { $options = $this->_htmlAttribs($options);}else{$attribs = '';}
		
        $tag = 'ul';
        if ($ordered) {$tag = 'ol';}

        $content ='<div data-role="content"><' . $tag . $options . '>' . self::EOL . $list . '</' . $tag . '></div>' . self::EOL;

		return $content;
    }
}