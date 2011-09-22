<div id="dialog" title="Rate"></div>
<div class="ratings">
	<div class="rating-L" >
		<div id="Form_ranking_vote"></div> 
		<% _t('PAGERATE', 'Page Rating:') %>$getRankingStars
		<div id="Form_ranking_info">
			<% if getRateSum %>
				(<a href="#" cur-page-id="$getCurrentPageID" id="rating-table"> + </a>)
				(<% _t('RATEAVERAGE', 'Average: ') %> $getRateAverage - <% _t('RATESUM', 'Votes: ') %> $getRateSum)
			<% end_if %>
		</div>
	</div>
	<% if isUnique %>
		<div class="rating-R"><% _t('RATETHISPAGE', 'Rate this Page:') %>$getRatingStars</div>
	<% end_if %>
</div>
