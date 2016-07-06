function social_cinema_resize(){

	width = jQuery(".social-cinema-holder-wall")
				.width();
	jQuery(".social-cinema-holder-wall")
		.each(
			function(index,value){
				jQuery(value)
					.css("height", (width / 1.78));
			}
		);

	width = jQuery("#social-cinema-current")
					.width();
					
	jQuery("#social-cinema-current")
		.css("height", (width / 1.78));
		
	jQuery(".other_social_cinema_videos")
		.each(
			function(index, value){
			
				width = jQuery(value).width();
						
				remainder = (width % 120);
				per_row = Math.round(width / 120);
				
				jQuery(value)
					.children()
					.each(
						function(index,value){
							jQuery(value)
								.css("margin", (remainder/(per_row * 2)));
						}
					);
			
			}
		);
		
}

jQuery(document).ready(
	function(){
		social_cinema_resize();
	}
);

jQuery( window ).resize(function() {
  social_cinema_resize();
});
