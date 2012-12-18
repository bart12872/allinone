<?php
/**
 * @see ZendX_JQuery_View_Helper_UiWidget
 */
require_once "ZendX/JQuery/View/Helper/UiWidget.php";

/** 
 * @author jhouvion
 * 
 * 
 */
class SeaX_JQuery_View_Helper_Button extends ZendX_JQuery_View_Helper_UiWidget{
 	/**
     * Create a jQuery button
     *
     * @link   http://docs.jquery.com/UI/Button
     * @param  string $id
     * @param  string $value
     * @param  array  $params
     * @param  array  $attribs
     * @param  array  $events
     * @return string
     */
    public function button($id, $value, array $params = array(), array $attribs = array(), array $events = array())
    {
        if(!isset($attribs['id'])) {
            $attribs['id'] = $id;
        }

        $jqh = ZendX_JQuery_View_Helper_JQuery::getJQueryHandler();
        $params = ZendX_JQuery::encodeJson($params);
        $events = $this->getEvents($events);

        $js = sprintf('%s("#%s").button(%s)%s;', $jqh, $attribs['id'], $params, $events);
        $this->jquery->addOnLoad($js);

        $html = "<button ".$this->_htmlAttribs($attribs)." >";
        $html .= $value;
        $html .= '</button>';

        return $html;
    }

    public function getEvents($events)
    {
        if(!count($events)) return '';
        else {
            $retour = '';
            foreach ($events as $event => $function) {
                $retour .= '.'.$event.'('.$function.')';
            }
            return $retour;
        }
    }

}

?>