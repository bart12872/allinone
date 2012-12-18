<?php
/** 
 * @author jhouvion
 * 
 * 
 */
class ToolController extends Sea_Controller_Action {

	/**
	 * Server side action for datable
	 *
	 */
	public function datatableAction() {
		
		// on force le format de sortie
		$this->getRequest()->setParam('format', 'json');
		$this->_helper->contextSwitch()->addActionContext('datatable', 'json')->initContext();
		
		// recuperation de l'identifiant de l'article
		$required = array('presence' => 'required');
		$validator = array('sToken' => $required,  'iDisplayLength' => $required);
		$input = new Zend_Filter_Input(array(), $validator, $this->getRequest()->getParams());
		if (!$input->isValid()) {throw new Sea_Exception('Erreur sur les paramètres');}
	
		// recuperation des paramètres
		$params = $this->getRequest()->getParams();
	
		// création du model
		$model = Application_Model_Datatable::load($input->sToken, $this->getRequest()->getPost());
		$model->setItemCountPerPage($input->iDisplayLength);
		$model->setCurrentPageNumber(empty($input->iDisplayLength) ? 1 : ($params['iDisplayStart'] + $input->iDisplayLength) / $input->iDisplayLength);
	
		$this->view ->assign('sEcho', (int) $params['sEcho'])
		->assign('iTotalRecords', $model->getTotalItemCount())
		->assign('iTotalDisplayRecords', $model->getTotalItemCount())
		->assign('aaData', $model->toArray());
	}
}