/**
 * DATATABLE
 */

/**
 * renvoie la valeur d'un paramètre
 */
function fnGetKey( aoData, sKey ){
    for ( var i=0, iLen=aoData.length ; i<iLen ; i++ ){if ( aoData[i].name == sKey ){return aoData[i].value;}}
    return null;
}

/**
 * Set la valeur d'un paramètre
 * 
 * @param aoData
 * @param sKey
 * @param mValue
 */
function fnSetKey( aoData, sKey, mValue ){
	change = false;
    for ( var i=0, iLen=aoData.length ; i<iLen ; i++ ){if ( aoData[i].name == sKey ){aoData[i].value = mValue;change = true;}}
    if(!change) {aoData[aoData.length] = {'name' : sKey, 'value' : mValue};}
}

/**
 * active les element jquery dans le contenu du tableau
 * 
 * @param oSettings
 */
jQuery.fn.DataTable.defaults.fnDrawCallback = function(oSettings) {jQuery(oSettings.nTBody).initialize();};

var jWaiting = Array();// pile d'execution
var jProcessing = Array();// pile de lancement

/**
 * Surcharge du lancement de la recharge de donnée
 * 
 * @param sSource
 * @param aoData
 * @param fnCallback
 */
jQuery.fn.DataTable.defaults.fnServerData = function (sSource, aoData, fnCallback ) {
	
	// recuperation du model
	sToken = fnGetKey(aoData, 'sToken');

	// on inscrit la requete dans la pile
	fnSetKey(jWaiting, sToken, {"dataType": "json", "type": "POST", "url": sSource, "data": aoData,"success": fnCallback});
	
	// si une requete existe, on l'annule
	if (fnGetKey(jProcessing, sToken) != null) {fnGetKey(jProcessing, sToken).abort();}
	
	param = fnGetKey(jWaiting, sToken);
	fnSetKey(jWaiting, sToken, null);
	fnSetKey(jProcessing, sToken, jQuery.ajax(param).done(function() {fnSetKey(jProcessing, sToken, null);}));
	
    return;
};


jQuery.fn.dataTableExt.oApi.fnFilterColumns = function (oSettings) {
	/**
	 * gestion des filtres
	 * 
	 * 
	 */
    var _that = this;// on conserve l'objet prinpale
 
    this.each(function (i) {
        jQuery.fn.dataTableExt.iApiIndex = i;
        
        // on conserver la function original
        var fnServerDataOriginal = _that.fnSettings().fnServerData;
        
        //Ajout des evenements
       jQuery(_that.selector + ' th select').each(function() {jQuery(this).change(function() {jQuery.fn.dataTableExt.iApiIndex = i;_that.fnDraw();});});
       jQuery(_that.selector + ' th input').each(function() {
    	   jQuery(this).unbind('keyup').bind('keypress', function (e) {if (e.which == 13) {jQuery.fn.dataTableExt.iApiIndex = i;_that.fnDraw();}});
       });
        
        // surcharge de la fonction de rafraichissement
        _that.fnSettings().fnServerData = function (sSource, aoData, fnCallback) {
        	// on ajoute toute les valeurs pour les element de filtres
        	jQuery(_that.selector + ' th select').each(function() {fnSetKey(aoData, jQuery(this).attr('name'), jQuery(this).val());});
        	jQuery(_that.selector + ' th input').each(function() {fnSetKey(aoData, jQuery(this).attr('name'), jQuery(this).val());});
        	
        	// Appel de la fonction original
        	fnServerDataOriginal(sSource, aoData, fnCallback);
        };
    });
    return this;
};

jQuery.fn.dataTableExt.oApi.fnFilterOnReturn = function (oSettings) {
    /*
    * Usage:       $('#example').dataTable().fnFilterOnReturn();
    * Author:      Jon Ranes (www.mvccms.com)
    * License:     GPL v2 or BSD 3 point style
    * Contact:     jranes /AT\ mvccms.com
    */
    var _that = this;
 
    this.each(function (i) {
        jQuery.fn.dataTableExt.iApiIndex = i;
        var $this = this;
        var anControl = jQuery('input', _that.fnSettings().aanFeatures.f);
        anControl.unbind('keyup').bind('keypress', function (e) {
            if (e.which == 13) {
                jQuery.fn.dataTableExt.iApiIndex = i;
                _that.fnFilter(anControl.val());
            }
        });
        return this;
    });
    return this;
};


/**
 * Mise en pile des datatables
 * pour refresh
 * 
 */
var dataTableStack  = new Array();
function refreshDataTable() {console.log('ICI');for (i=0;i<dataTableStack.length;i++){console.log(dataTableStack[i]);dataTableStack[i].fnDraw();}}
jQuery.fn.dataTableExt.oApi.fnInsertStack = function () {dataTableStack[dataTableStack.length] = this;};