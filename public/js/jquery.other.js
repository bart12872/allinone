/**
 * centré un element
 * 
 */
jQuery.fn.center = function () {
    this.css("position","absolute");
    this.css("top", ( jQuery(window).height() - this.height() ) / 2+jQuery(window).scrollTop() + "px");
    this.css("left", ( jQuery(window).width() - this.width() ) / 2+jQuery(window).scrollLeft() + "px");
    return this;
};

/**
 * register global variable
 * 
 * @returns
 */
function glob(name, value) {
	
	// on test que name est correct
	if ((typeof name) != 'string') {return false;}

	// cas du getter
	if (value == undefined) {
		// si la variable existe on la renvoie
		eval("if ((typeof window." + name + ") != undefined) {data = window." + name + ";}");
		return data;
	// cas du setter
	} else {eval("window." + name + ' = value;');}
	
}

/**
 * 
 * Remplie un select a partir d'un requete json
 * 
 */
jQuery.fn.loadSelect = function (url, data) {
	var e = this;
	jQuery.getJSON(url,data,
			function(d){
				e.empty();
				jQuery.each(d.data, function(i,o){e.append(new Option( o.label,o.value));});
			}
		);
};

/**
 * 
 * Autocomplete un input
 * 
 */
jQuery.fn.loadAutocomplete = function (url, data) {
	var e = this;
	jQuery.getJSON(url,data,function(d){e.autocomplete({source: d.data, minLength : '0', autoFocus: 'true'});});
};


/**
 * rajout d'une fermeture sur un click exterieur pour les overlay
 * 
 */
(function( $, undefined ) {
	$.extend($.ui.dialog.prototype.options, {
				// on met par defaut une fermeture sur les fentre modal
				open: function () {
						if ($(this).dialog('option', 'modal') == true) {
							var that=this;
							$.each($.ui.dialog.overlay.instances, function() {$(this).click(function() {$(that).dialog('close');});});
						}
					}
			}
	);
}(jQuery));

/**
 * initialisation sur chargement ajax
 * 
 */
(function( $, undefined ) {
	$.extend($.ui.tabs.prototype.options, {
				load: function (e, ui) {$(ui.panel).initialize();}// iitialisation du panel chargé
			}
	);
}(jQuery));

/**
 * 
 * fait apparaitre un dialog
 * 
 */
function dialogurl(url, args, title, width, modal) {
	jQuery('<div />') .css('display', 'none')
	    .appendTo('body')
	    .load(url, args, function() {
	    	jQuery(this).initialize().dialog({
				modal : modal == undefined ? true : modal,
				title : title == undefined ? '' : title,
				minHeight   : '0px',
				width : width == undefined ? 300 : width,
				close       : function() {jQuery(this).dialog('destroy').remove(); },
			});
	    });
	return false;
};

/**
 * 
 * redirection  
 * 
 */
jQuery.extend({location : function (url) {jQuery(location).attr('href', url);}});

/**
 * inititlize un contenu
 * 
 * charge tout les element necessitant du javascript
 * 
 */
jQuery.fn.initialize = function () {
    
    jQuery(this).find('.dialog-ajax').each(function() {
    	jQuery(this).unbind('click').click(function() {
    		dialogurl(jQuery(this).attr('href'), null, jQuery(this).attr('title'), jQuery(this).attr('data-dialog-width') > 0 ? jQuery(this).attr('data-dialog-width') : 300, jQuery(this).attr('data-dialog-modal') === 'false' ? false : true);
    		return false;
    	});
    });
    
     /**
     * Buttons
     */
    jQuery(this).find('.button').each(function () {
        jQuery(this).button({
                icons : {
                    primary : jQuery(this).attr('data-icon-primary') ? jQuery(this).attr('data-icon-primary') : null, 
                    secondary : jQuery(this).attr('data-icon-secondary') ? jQuery(this).attr('data-icon-secondary') : null
                }, 
                text : jQuery(this).attr('data-icon-only') === 'true' ? false : true
            });
    });
    
    jQuery(this).find(".buttonset").buttonset();
    jQuery(this).find(".tooltip").tooltip();
    
    /**
     * bouton de log
     */
    jQuery(this).find('.logbutton').each(function () {
        jQuery(this).button({
                icons : {
                    primary : jQuery(this).attr('data-icon-primary') ? jQuery(this).attr('data-icon-primary') : null, 
                    secondary : jQuery(this).attr('data-icon-primary') ? jQuery(this).attr('data-icon-secondary') : null
                }, 
                text : jQuery(this).attr('data-icon-only') === 'true' ? false : true
            });
        jQuery(this).click(function() {$.get(jQuery(this).attr('href'), function(data) {alert(data);});return false;});
    });
    
     /**
     * Setup the Accordions
     */
    jQuery(this).find(".accordion").accordion();
    
     /**
     * Setup the Tabs
     */
    jQuery(this).find(".tabs").tabs().find('> .ui-tabs-nav').removeClass('ui-corner-all').addClass('ui-corner-top');
    jQuery(this).find(".sidebar-tabs").tabs().addClass('ui-tabs-vertical ui-helper-clearfix').find('li').removeClass('ui-corner-top').addClass('ui-corner-all');
    
    /**
     * setup progress bars
     */
    jQuery(this).find(".progress").each(function () {
        var val = parseInt(jQuery(this).attr("data-value"), 10);
        jQuery(this).progressbar({
                value : val
            }).filter('[data-show-value=true]').find('div').append('<b>' + val + '%</b>');
    });
    
    /**
     * form error
     */
	jQuery(this).find('input.invalid').each(function() {
		jQuery(this).mouseover(function() {jQuery(this).nextAll('div.form-error').css('display', 'block');});
		jQuery(this).mouseleave(function() {jQuery(this).nextAll('div.form-error').css('display', 'none');});
	});
    
    return this;
};

jQuery(function() {
	 /**
     * box de loading pour l'ajax
     */
    jQuery('div#ajax-loading').hide();
    jQuery('div#ajax-loading').ajaxStart(function () {jQuery(this).show();jQuery('.button.ui-button').button( "disable");});
    jQuery('div#ajax-loading').ajaxStop(function () {jQuery(this).hide();jQuery('.button.ui-button').button( "enable");});;
});