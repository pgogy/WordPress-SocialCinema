jQuery(document).ready(
	function(){
		jQuery(".social_cinema_load_more_tag")
			.on("click", function(event){
			
					var data = {
						nonce : wordpress_social_cinema_load_more.answerNonce,
						action : jQuery(event.currentTarget).attr("action"),
						post : jQuery(event.currentTarget).attr("post_id"),
						list : jQuery(event.currentTarget).attr("list"),
						term : jQuery(event.currentTarget).attr("term"),
						token : jQuery(event.currentTarget).attr("next_page"),
						page_number : jQuery(event.currentTarget).attr("next_page_number"),
						total : jQuery(event.currentTarget).attr("page_total"),
						source : jQuery(event.currentTarget).attr("source"),
					};
		
					jQuery.post(wordpress_social_cinema_load_more.ajaxurl, data, function(response) {
					
						margin = jQuery("#other_social_cinema_videos_" + jQuery(event.currentTarget).attr("post_id"))
							.children()
							.first()
							.css("margin");
						
						data = JSON.parse(response);
						
						jQuery(event.currentTarget)
							.attr("next_page", data.next_page);
							
						jQuery(event.currentTarget)
							.attr("next_page_number", jQuery(event.currentTarget).attr("next_page_number") + 1);
						
						if(jQuery(event.currentTarget).attr("action")=="social-cinema-load-more-playlist-random" || jQuery(event.currentTarget).attr("action")=="social-cinema-load-more-tag-random"){
						
							video = data.data[Math.floor(Math.random() * data.data.length)];
							
							if(video[0]=="youtube"){
								jQuery("#social-cinema-current-video-" + jQuery(event.currentTarget).attr("post_id"))
									.html("<iframe src='https://youtube.com/embed/" + video[1] + "' />");
							}else if(video[0]=="vimeo"){
								jQuery("#social-cinema-current-video-" + jQuery(event.currentTarget).attr("post_id"))
									.html("<iframe src='https://player.vimeo.com" + video[1] + "' />");
							}else if(video[0]=="archive"){
								jQuery("#social-cinema-current-video-" + jQuery(event.currentTarget).attr("post_id"))
									.html("<video controls><source src='" + video[1] + "' /></video>");
							}
						
						}else{
						
							for(result in data.data){
								jQuery("#other_social_cinema_videos_" + jQuery(event.currentTarget).attr("post_id"))
									.append("<img post_id='" + jQuery(event.currentTarget).attr("post_id") + "' style='margin:" + margin + "' video_type='" + data.data[result][0] + "' video_id='" + data.data[result][1] + "' src='" + data.data[result][2] + "' />");
							}
							
							jQuery("#other_social_cinema_videos_" + jQuery(event.currentTarget).attr("post_id") + " img")
								.on("click", function(event){
								
										social_cinema_click_video(event);
										
									}
								);
								
						}
						
					});
					
				}
			);
	}
);
