jQuery(document).ready( function($) {
	$('a.fts-instagram-link-target').each(function(e){
		let link = $(this).attr('href');
		link = link.replace('%09%09%09%09%09', '');
		$(this).attr('href', link);
	});
	
	$('.slidein-tab').click(function(e){
		e.preventDefault();

		const Target = $(this).parent();

		if (Target.hasClass('open')) Target.removeClass('open');
		else Target.addClass('open');

		if (Target.hasClass('slidein-pos-top'))
		{
			if (Target.hasClass('open')) {
				const Height = Target.find('.slidein-inner')[0].clientHeight;
				Target.css('top', Height);
			}
			else
			{
				Target.css('top', 0);
			}
		}
	});
});