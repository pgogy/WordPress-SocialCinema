function social_cinema_click_video(event){

	position = jQuery(event.currentTarget)
		.position();
		
	current_picture = jQuery("#social-cinema-current-" + jQuery(event.currentTarget).attr("post_id"))
		.css("display","block");

	target = jQuery("#social-cinema-current-" + jQuery(event.currentTarget).attr("post_id"))
		.position();
	
	current_picture = jQuery("#social-cinema-current-" + jQuery(event.currentTarget).attr("post_id"))
		.attr("video_image");
		
	current_video = jQuery("#social-cinema-current-" + jQuery(event.currentTarget).attr("post_id"))
		.attr("video_id");
		
	current_type = jQuery("#social-cinema-current-" + jQuery(event.currentTarget).attr("post_id"))
		.attr("video_type");
	
	jQuery("#other_social_cinema_videos_" + jQuery(event.currentTarget).attr("post_id"))
		.append("<div id='new_temp_video'><img style='width:100%; height:100%' src='" + jQuery(event.currentTarget).attr("src") + "' /></div>");
	
	jQuery("#new_temp_video")
		.css("z-index",100)
		.css("position", "absolute")
		.css("left", position.left)
		.css("top", position.top);	
	
	jQuery("#new_temp_video")
		.animate(
			{
				left: target.left,
				top: target.top,
				width: "+=" + (jQuery("#social-cinema-current-" + jQuery(event.currentTarget).attr("post_id")).width() - jQuery("#new_temp_video").width()),
				height: "+=" + (jQuery("#social-cinema-current-" + jQuery(event.currentTarget).attr("post_id")).height() - jQuery("#new_temp_video").height()),
			}, 2000, function() {
			
				if(jQuery(event.currentTarget).attr("video_type")=="youtube"){
					jQuery("#social-cinema-current-video-" + jQuery(event.currentTarget).attr("post_id"))
						.html('<iframe id="social-cinema-current-' + jQuery(event.currentTarget).attr("post_id") + '" src="//youtube.com/embed/' + jQuery(event.currentTarget).attr("video_id") + '"></iframe>');
				}
				if(jQuery(event.currentTarget).attr("video_type")=="vimeo"){
					jQuery("#social-cinema-current-video-" + jQuery(event.currentTarget).attr("post_id"))
						.html('<iframe id="social-cinema-current-' + jQuery(event.currentTarget).attr("post_id") + '" src="http://player.vimeo.com' + jQuery(event.currentTarget).attr("video_id") + '"></iframe>');
				}
				if(jQuery(event.currentTarget).attr("video_type")=="archive"){
					jQuery("#social-cinema-current-video-" + jQuery(event.currentTarget).attr("post_id"))
						.html('<video id="social-cinema-current-' + jQuery(event.currentTarget).attr("post_id") + '" controls style="width: 100%"><source src="' + jQuery(event.currentTarget).attr("video_id") + '" /></video>');
				}
				
				jQuery("#social-cinema-current-" + jQuery(event.currentTarget).attr("post_id"))
					.attr("video_image", jQuery(event.currentTarget).attr("src"));
				jQuery("#social-cinema-current-" + jQuery(event.currentTarget).attr("post_id"))
					.attr("video_id", jQuery(event.currentTarget).attr("video_id"));
				jQuery("#social-cinema-current-" + jQuery(event.currentTarget).attr("post_id"))
					.attr("video_type", jQuery(event.currentTarget).attr("video_type"));
					
				jQuery("#new_temp_video")
					.remove();
					
				jQuery(event.currentTarget)
					.attr("src", current_picture)
					.attr("video_id", current_video)
					.attr("video_type", current_type);
		  });
}

jQuery(document).ready(
	function(){
		jQuery(".other_social_cinema_videos img")
			.on("click", function(event){
					social_cinema_click_video(event);
				}
			);
	}
);
