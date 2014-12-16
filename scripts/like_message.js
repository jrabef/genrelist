
$( document ).ready(function() {

	$(".voting_wrapper .voting_btn").click(function (e) {
		// disable click
		$(this).unbind('click');
		$(this).parent().children().unbind('click');
		
		//get class name (down_button / up_button) of clicked element
		var clicked_button = $(this).children().attr('class');
		
		//get unique ID from voted parent element
		var unique_id   = $(this).parent().attr("id"); 
		
		if(clicked_button==='down_button') //user disliked the content
		{
			//prepare post content
			post_data = {'unique_id':unique_id, 'vote':'down'};
			
			//send our data to "vote_process.php" using jQuery $.post()
			$.post('vote_process.php', post_data, function(data) {
				
				// change button
				$(".down_button img").attr("src", "vote_down_orange.png");
				// remove class voting_btn
				$('#'+unique_id+' .voting_btn').removeClass("voting_btn");
				
				// increase down votes
				$('#'+unique_id+' .down_votes').text( parseInt($('#'+unique_id+' .down_votes').text()) + 1 );
				
			}).fail(function(err) { 
			
			//alert user about the HTTP server error
			alert(err.statusText); 
			});
		}
		else if(clicked_button==='up_button') //user liked the content
		{
			//prepare post content
			post_data = {'unique_id':unique_id, 'vote':'up'};
			
			//send our data to "vote_process.php" using jQuery $.post()
			$.post('vote_process.php', post_data, function(data) {
			
				// change button
				$(".up_button img").attr("src", "vote_up_orange.png");
				// remove class voting_btn
				$('#'+unique_id+' .voting_btn').removeClass("voting_btn");
				
				// increase votes
				$('#'+unique_id+' .up_votes').text( parseInt($('#'+unique_id+' .up_votes').text()) + 1 );
				
			}).fail(function(err) { 
			
			//alert user about the HTTP server error
			alert(err.statusText); 
			});
		}
		
	});
	//end

});
