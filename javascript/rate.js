    	jQuery(".ratings").show();
    	jQuery(function(){
    		jQuery("#Form_ranking").children().not(":radio").hide();
    		jQuery("#Form_ranking").stars({			
				cancelShow: false,
				disabled: true
			});				
			
    		jQuery("#Form_ratings").children().not(":radio").hide();
    		jQuery("#Form_ratings").stars({	
				cancelShow: false,	
				callback: function(ui, type, value)
				{
					var pid = jQuery("#Form_ratings_pageid").val();
					jQuery.post("rating/saveratings", {rate: value, pageid: pid}, function(data)
					{
						
						var obj = jQuery.parseJSON(data);


						// -- Update Ranking	
						
						
						jQuery("#Form_ranking").stars("select", obj.average);
						jQuery("#Form_ranking_vote").html(value==ui.options.cancelValue ? "Rating removed" : "Rating saved! ("+value+")").stop().css("opacity", 1).fadeIn(30);
						
				         setTimeout(function(){ jQuery("#Form_ranking_vote").fadeOut(1000) }, 2000);
				         jQuery("#Form_ranking_info").html("Average: " + obj.average + " Votes: " + obj.votes );			
				         // -- Hide Star Rate
				        // jQuery(".rating-R").hide();
					});
				}
			});
		});
    	