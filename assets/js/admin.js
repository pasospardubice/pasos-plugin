jQuery( function($){

	// Chosen selects
	jQuery("select.chosen_select").chosen({
		disable_search_threshold: 10
	});


	jQuery("select.chosen_select_dob").chosen({
		disable_search_threshold: 32
	});


	jQuery("select.chosen_select_nostd").chosen({
		allow_single_deselect: 'true'
	});


	jQuery('.pasos_stats-tabs a').click(function(){
		var t = jQuery(this).attr('href');	
		jQuery(this).parent().addClass('tabs').siblings('li').removeClass('tabs');
		jQuery(this).parent().parent().parent().find('.tabs-panel').hide();
		jQuery(t).show();
		return false;
	});


	jQuery('.post-type-wpcm_match #poststuff #post-body-content').hide();
	jQuery('#wpclubmanager-match-result').on('change', '#wpcm_played', function() {
		played = jQuery(this).prop('checked');
		if (played) {
			jQuery('.post-type-wpcm_match #poststuff #post-body-content').show();
			jQuery('#wpclubmanager-match-result #results-table').show();
			jQuery('.post-type-wpcm_match #poststuff #postexcerpt').hide();
		} else {
			jQuery('#wpclubmanager-match-result #results-table').hide();
			jQuery('.post-type-wpcm_match #poststuff #post-body-content').hide();
		}
	});
	jQuery('#wpclubmanager-match-result #wpcm_played').change();


	jQuery('#pasos_players table .names input[type="checkbox"]').on('change', function() {					
		player_id = jQuery(this).attr('data-player');
		jQuery(this).closest('tr').find('input[type="number"]').prop('disabled', !jQuery(this).prop('checked'));
		jQuery(this).closest('tr').find('input[data-card="yellow"], input[data-card="red"]').prop('disabled', !jQuery(this).prop('checked'));
		jQuery(this).closest('tr').find('select').prop('disabled', !jQuery(this).prop('checked'));
		jQuery(this).closest('tr').find('input[type="radio"]').prop('disabled', !jQuery(this).prop('checked'));
	});


	jQuery('#pasos_players table td.mvp input[type="radio"]').click(function() {
	    jQuery('td.mvp input[type="radio"]').prop('checked', false);
	    jQuery(this).prop('checked', true);        
	});
	jQuery('#pasos_players table td.captain input[type="radio"]').click(function() {
	    jQuery('td.captain input[type="radio"]').prop('checked', false);
	    jQuery(this).prop('checked', true);        
	});


	wpcm_filter_team_players = function(team) {
		var team = jQuery('#wpcm_match_team').val();
		
		if( team == null ) {
			jQuery('#pasos_players table tbody tr').show().find('input');
		} else if( team != '0' ) {
			jQuery('#pasos_players table tbody tr').hide().find('input');
			jQuery('#pasos_players table tbody tr.team_' + team).show().find('input');
		} else {
			jQuery('#pasos_players table tbody tr').show().find('input');
		}
		
	}

	wpcm_filter_team_players();

	jQuery('#wpcm_match_team').on('change', function() {
		wpcm_filter_team_players();
	});

	// jQuery('table#wpcm-match-selection tbody').sortable({ cursor: "move" });
	// jQuery( 'table#wpcm-match-selection tbody' ).disableSelection();

	updateCounter = function() {
    	var len = jQuery("#wpcm_lineup input.player-select:checked").length;
    	var sublen = jQuery("#wpcm_subs input.player-select:checked").length;
		if(len>0) {
			jQuery("#wpcm_lineup .counter").text(''+len+'');
		}
		else {
			jQuery("#wpcm_lineup .counter").text('0');
		}
		if(sublen>0) {
			jQuery("#wpcm_subs .counter").text(''+sublen+'');
		}
		else {
			jQuery("#wpcm_subs .counter").text('0');
		}
	}
	jQuery("#wpcm_lineup .counter, #wpcm_subs .counter").text(function() {
		updateCounter();
	});
	jQuery("#wpcm_lineup input:checkbox, #wpcm_subs input:checkbox").on("change", function() {
		updateCounter();
	});

	jQuery('#wpclubmanager-club-stats input').change(function() {
		index = jQuery(this).attr('data-index');
		value = 0;					
		jQuery(this).closest('table').find('tbody tr').each(function() {						
			value += parseInt(jQuery(this).find('input[data-index="' + index + '"]').val());
		});					
		jQuery(this).closest('table').find('tfoot tr input[data-index="' + index + '"]').val(value);		
		jQuery('#leaguetable #totalstats-p').val(
			Number(jQuery('#leaguetable #autostats-p').val()) +
			Number(jQuery('#leaguetable #manualstats-p').val())
		);
		jQuery('#leaguetable #totalstats-w').val(
			Number(jQuery('#leaguetable #autostats-w').val()) +
			Number(jQuery('#leaguetable #manualstats-w').val())
		);
		jQuery('#leaguetable #totalstats-d').val(
			Number(jQuery('#leaguetable #autostats-d').val()) +
			Number(jQuery('#leaguetable #manualstats-d').val())
		);
		jQuery('#leaguetable #totalstats-l').val(
			Number(jQuery('#leaguetable #autostats-l').val()) +
			Number(jQuery('#leaguetable #manualstats-l').val())
		);
		jQuery('#leaguetable #totalstats-f').val(
			Number(jQuery('#leaguetable #autostats-f').val()) +
			Number(jQuery('#leaguetable #manualstats-f').val())
		);
		jQuery('#leaguetable #totalstats-a').val(
			Number(jQuery('#leaguetable #autostats-a').val()) +
			Number(jQuery('#leaguetable #manualstats-a').val())
		);
		jQuery('#leaguetable #totalstats-gd').val(
			Number(jQuery('#leaguetable #autostats-gd').val()) +
			Number(jQuery('#leaguetable #manualstats-gd').val())
		);
		jQuery('#leaguetable #totalstats-pts').val(
			Number(jQuery('#leaguetable #autostats-pts').val()) +
			Number(jQuery('#leaguetable #manualstats-pts').val())
		);
	});

	
	/*var itemList = jQuery('.wpcm-sortable');

    itemList.sortable({
    	cursor: 'move',
        update: function(event, ui) {
            jQuery('#loading-animation').show(); // Show the animate loading gif while waiting

            opts = {
                url: ajaxurl, // ajaxurl is defined by WordPress and points to /wp-admin/admin-ajax.php
                type: 'POST',
                async: true,
                cache: false,
                dataType: 'json',
                data:{
                    action: 'item_sort', // Tell WordPress how to handle this ajax request
                    order: itemList.sortable('toArray').toString() // Passes ID's of list items in  1,3,2 format
                },
                success: function(response) {
                    jQuery('#loading-animation').hide(); // Hide the loading animation
                    return; 
                },
                error: function(xhr,textStatus,e) {  // This can be expanded to provide more information
                    alert(e);
                    alert('There was an error saving the updates');
                    jQuery('#loading-animation').hide(); // Hide the loading animation
                    return; 
                }
            };
            jQuery.ajax(opts);
        }
    });*/
    
});

function sortTable(f,n){
	var rows = $('.seznam-hracu-tabulka tbody  tr').get();

	rows.sort(function(a, b) {

		var A = getVal(a);
		var B = getVal(b);

		if(A < B) {
			return -1*f;
		}
		if(A > B) {
			return 1*f;
		}
		return 0;
	});

	function getVal(elm){
		var v = $(elm).children('td').eq(n).text().toUpperCase();
		if($.isNumeric(v)){
			v = parseInt(v,10);
		}
		return v;
	}

	$.each(rows, function(index, row) {
		$('.seznam-hracu-tabulka').children('tbody').append(row);
	});
}
var f_sl = 1;
var f_nm = 1;
$("#name").click(function(){
    f_sl *= -1;
    var n = $(this).prevAll().length;
    sortTable(f_sl,n);
});
$("#ab").click(function(){
    f_nm *= -1;
    var n = $(this).prevAll().length;
    sortTable(f_nm,n);
});