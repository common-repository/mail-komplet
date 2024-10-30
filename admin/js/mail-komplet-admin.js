(function( $ ) {
	'use strict';
	
	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

	$(function() {
		var pluginName = $('#plugin-name');
	
		var modulePath = $('#' + pluginName.val() + '-module-path');
	
		var apiKey = $('#' + pluginName.val() + '-api-key');
		var baseCrypt = $('#' + pluginName.val() + '-base-crypt');
		var mailingListId = $('#' + pluginName.val() + '-mailing-list-id_');
		var selectList = $('#' + pluginName.val() + '-mailing-list-id');
		
	    var strConnect = $('#' + pluginName.val() + '-str-connect');
	    var strConnecting = $('#' + pluginName.val() + '-str-connecting');
	    var strAjaxError = $('#' + pluginName.val() + '-str-ajax-error');
		
		var submitButton = $('#submit');
		var selectListGroup = selectList.closest('fieldset');
		
		var connectButtonShown = false;
	
	    // read mailing lists, if apiKey is empty?
	    if (apiKey.val() == '') {
	    	connectButtonShow();
	    }
	    
	    // read mailing lists on api key change (and base crypt must be set)
	    apiKey.change(function() {
	    	if (baseCrypt.val()) {
	    		connectButtonShow();
	    	}
	    });
	    
	    // read mailing lists on base crypt change (and api key must be set)
	    baseCrypt.change(function () {
	    	if (apiKey.val()) {
	    		connectButtonShow();
	    	}
	    });
	    
		if (apiKey.val() && baseCrypt.val()) {
			downloadLists();
		}
	    
	    // read mailing lists via api and fill appropriate select
	    function connectButtonShow() {
	    	if (!connectButtonShown) {
		        submitButton.hide();
		        selectListGroup.hide();
		        
		        $('<p class="submit"><input type="submit" name="submit" id="connectButton" class="button button-primary" value="' + strConnect.val() + '" style="display: inline-block;"></p>').insertAfter('#' + pluginName.val() + '-base-crypt-fieldset');
		        connectButtonShown = true;
	    	}
	    }
	    
	    $('form').on('click', '#connectButton', function(e) {
	    	e.preventDefault();
	    	$(this).val(strConnecting.val() + '...');
	    	downloadLists();
	    });
	    
	    function downloadLists() {
	    	
	        $.ajax({
	            url: modulePath.val() + 'mail-komplet-ajax.php',
	            type: 'get',
	            dataType: 'json',
	            data: {
	            	apiKey: apiKey.val(),
	            	baseCrypt: baseCrypt.val(),
	            },
	            success: function(data) {
	            	// sanitaze empty data
	            	if (data && 'data' in data) {
	                    selectList.html('');
	                    var selected;
	                    $.each(data.data, function(key, val) {
		                    selected = '';
		                    if ((mailingListId) && (val.mailingListId == mailingListId.val())) {
		                    	selected = ' selected="selected";'
		                    }
	                        selectList.append('<option value="' + val.mailingListId + '"' + selected + '>' + val.name + '</option>');
	                    });
	                    selectListGroup.show();
	                    submitButton.show();
	                    $('#connectButton').parents('.submit').remove();
	            	} else {
	            		$('#connectButton').val(strConnect.val());
	            		alert(strAjaxError.val());
	            	}
	            }
	        });
	    }
		
	});

})( jQuery );