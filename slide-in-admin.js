jQuery( document ).ready( function( $ ) {
	let file_frame;
	let wp_media_post_id = wp.media.model.settings.post.id;
	let set_to_post_id;
	let FieldName;

	$('#slidein-image-bg').click(function(e)
	{
		$('.row-image-tab').slideToggle();
	});

	$('#slidein-image-content-enable').click(function(e)
	{
		$('.row-image-content').slideToggle();
	});

	$('.upload_image_button').on('click', function( event ){
		event.preventDefault();

		FieldName = jQuery(this).data('field');
		set_to_post_id = $('#' + FieldName).val();

		if ( file_frame ) {
			file_frame.uploader.uploader.param( 'post_id', set_to_post_id );
			file_frame.open();
			return;
		}

		wp.media.model.settings.post.id = set_to_post_id;

		file_frame = wp.media.frames.file_frame = wp.media({
			title: 'Select a image to upload',
			button: {
				text: 'Use this image',
			},
			multiple: false
		});

		file_frame.on( 'select', function() {
			attachment = file_frame.state().get('selection').first().toJSON();

			console.log(FieldName);

			$( '.image-preview[data-field="' + FieldName + '"]' ).attr( 'src', attachment.url ).css( 'width', 'auto' );
			$( '#' + FieldName ).val( attachment.id );

			wp.media.model.settings.post.id = wp_media_post_id;
		});

		file_frame.open();
	});

	$( 'a.add_media' ).on( 'click', function() {
		wp.media.model.settings.post.id = wp_media_post_id;
	});
});