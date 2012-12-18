<?php

require_once ('ZendX/JQuery/View/Helper/UiWidget.php');

class SeaX_JQuery_View_Helper_Tab extends ZendX_JQuery_View_Helper_UiWidget {

 	public function tab(SeaX_JQuery_Tab $tabs, $data = array(), $params = array()) {
 		
 		
 		$id = $tabs->getId();// recuperation de l'id
 		$pane = $tabs->getPane();// objet de generation des pane
 		$container = $tabs->getContainer();// objet de genration des container
 		$model = false;// si on a des model en pane 
     
		// construction des panes
 		foreach ($tabs->getTabs() as $tab) {
 			$content = '';// initialisation du contenu
 			$options = array('title' => $tab['title']);// mise en place du titre 
 			$options += array('ank' => $tab['ank'] ? $tab['ank'] : false);// mise en place due l'identifiant
 			
 			if (!empty($tab['content'])) {$content = $tab['content'];} 
 			elseif (!empty($tab['model'])) {
 				$model = true;
 				$options['model'] = $tab['model'];
 				$options['args'] = serialize($tab['args']);
 				if (!empty($tab['load'])) {
 					$datagrid = $tab["model"];
 					eval('$content = new $datagrid("' . implode('","', $tab['args']) . '");');
 				}
 			}
 			
 			$content = !empty($content) ? $content : '';
 			$this->view->$pane($id,$content, $options);
 		}
 		
 		// chargement des model en ajax
 		if ($model) {
	 		$js = "\$j('#$id').bind('tabsselect', function(event, ui) {
						div = ui.tab.hash;
						id = ui.panel.id;
						if (\$j(div).is(':empty') && \$j(div + '[model]')) {
							\$j(div).html('<table id=\"'+id.replace(new RegExp('\-', 'g'), '_')+'\" align=\"center\" width=\"100%\"><tr><td>&nbsp;</td><td width=\"220\"><br /><img src=\"/images/ajax-loader.gif\" alt=\"ajax loading\" /><br /><br /></td><td>&nbsp;</td></tr></table>');
							xajax_Datagrid_Html(id.replace(new RegExp('\-', 'g'), '_'), \$j(ui.panel).attr('model'), 1, null, \$j(ui.panel).attr('args'));
						}
					});";
	
	 		$this->jquery->addOnLoad($js);
 		}
 		
 		return $this->view->$container($id, $data, $params);
    }
}
?>