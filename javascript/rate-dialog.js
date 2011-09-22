jQuery(function() {
	jQuery('#dialog').dialog({
		autoOpen: false,			    
		modal: true,
		resizable: false,
		    open: function(event, ui) {
		    	jQuery('#dialog').load('rating/getratingsdata?pageid=' + jQuery('#rating-table').attr('cur-page-id'));
			}
	});
			
	jQuery('#rating-table').click(function() {
		jQuery('#dialog').dialog('open');
		return false;
	});
});