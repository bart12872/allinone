<?php

require_once ('ZendX/JQuery/View/Helper/TabContainer.php');

class SeaX_JQuery_View_Helper_SeaTabContainer extends ZendX_JQuery_View_Helper_TabContainer {

    public function seaTabContainer($id = null, $params=array(), $attribs=array(), $name = false){
    	
    	// Pas de traitement si pas d'argument
        if(func_num_args() === 0) {return $this;}
        
        // formatge de l'id
        if(!isset($attribs['id'])) {$attribs['id'] = $id;}

        // initialisation du contenu
        $content = "";
        
        if(isset($this->_tabs[$id])) {
            $list = '<ul>'.PHP_EOL;
            $html = '';
            $fragment_counter = 1;
            foreach($this->_tabs[$id] AS $k => $v) {
            	
                $opts = $v['options'];
                
                // gestion de l'ancre
                $frag_name = !empty($opts['ank']) ? $opts['ank'] : sprintf('%s-frag-%d', $attribs['id'], $fragment_counter++);

                if(isset($opts['contentUrl'])) {
                    $list .= '<li><a href="'.$opts['contentUrl'].'"><span>'.$v['name'].'</span></a></li>'.PHP_EOL;
                } else {
                    $list .= '<li><a href="#'.$frag_name.'"><span>'.$v['name'].'</span></a></li>'.PHP_EOL;
                    $html .= '<div id="'.$frag_name.'" '.$this->_htmlAttribs($opts).' >'.$v['content'].'</div>'.PHP_EOL;
                }
            }
            $list .= '</ul>'.PHP_EOL;

            $content = $list.$html;
            unset($this->_tabs[$id]);
        }
        
        // on ecnode (JSON) les parametre pour qu'il soit pris en javascript
        $params = count($params) ? ZendX_JQuery::encodeJson($params) : '{}'; 
        
         // construction du javascript
        $js = sprintf('%s("#%s").tabs(%s);',
            ZendX_JQuery_View_Helper_JQuery::getJQueryHandler(),
            $attribs['id'],
            $params
        );
        
     	// gestion du nom
        if (!empty($name)) {$js = sprintf('var %s = %s', $name, $js);}
        
        // on ajoute l'evenement a la pile d'execution
        $this->jquery->addOnLoad($js);
        
        // inscription de l'html
        $html = '<div' . $this->_htmlAttribs($attribs) . '>'. PHP_EOL . $content . '</div>'.PHP_EOL;
        
        return $html;
    }
}

?>