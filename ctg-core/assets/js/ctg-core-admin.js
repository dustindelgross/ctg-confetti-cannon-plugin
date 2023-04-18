jQuery(document).ready( function($) {
	let urlParameters = window.location.search.replaceAll('%2F','/').replaceAll('&', '/').replace('?', '').split('/').filter(param => param.startsWith('page=ctg'));
	
	

	if ( urlParameters.includes('page=ctg-settings') ) {

		const feedbackContainer = $('#ctg_admin_feedback_container');
		const feedback = $('#ctg_admin_feedback');
		const feedbackClose = $('#ctg_admin_feedback_close');
		const apiKeyInput = $('#ctg_gm_api_key');
		const pixelKeyInput = $('#ctg_pixel_verification_key');
		const adminForm = $('#ctg_admin_general_settings_form');

		function formatMessage(type = '') {
			let retval;
			switch ( type ) {
				case 'error':
					retval = '#ffad87';
					break;
				case 'success':
					retval = '#62cc8f';
					break;
				case 'warning':
					retval = '#dbb778';
					break;
				default:
					retval = '#2a2a2a';
			}
			return retval;
		}

		function showMessage(m = {}) {
			feedbackContainer.slideDown(500, function(){
				feedbackContainer.css('display', 'flex');
				feedback.text(m.text).css('color', formatMessage(m.type));
			});
		}

		function hideMessage() {
			feedbackContainer.slideUp(500,function(){
				feedback.text('').css('color','inherit');
			});
		}

		feedbackClose.on('click', function(){
			hideMessage();
		});

		adminForm.on('submit', async function(e) {
			e.preventDefault();

			await $.ajax({
				url: ctg.update_options,
				method: 'POST',
				data: { 
					api_key: apiKeyInput.val(),
					pixel_key: pixelKeyInput.val(),
					nonce: ctg.core_nonce
				},
				success: function (e) {
					let data = JSON.parse(e);
					showMessage(data);
				},
				error: function (e) {
					console.log(e);
				},
				complete: function (e) {

				},
			});

		});
	}
});