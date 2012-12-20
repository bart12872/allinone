<?php
/**
 * genreation de code pour jquery noty
 * 
 * @url http://needim.github.com/noty/
 * 
 * @author jhouvion
 *
 */
class SeaX_JQuery_View_Helper_Noty {
	
	/**
	 * creation d'un notification
	 * 
	 * @param unknown_type $type
	 * @param unknown_type $text
	 * @param unknown_type $attribs
	 * @return string
	 */	
	public function noty($text, $type, $attribs = []) {
		
		$attribs += ['text' => $text, 'type' => $type];
        $params = ZendX_JQuery::encodeJson($attribs);

        return sprintf('noty(%s);', $params);
    }
}

?>