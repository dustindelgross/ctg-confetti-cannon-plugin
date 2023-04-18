jQuery(document).ready( function($) {

	const searchHeadingContainer = $('#ctg_member_search_heading_container');
	const searchHeading = $('<h2>');
	const memberSearchWrapper = $('#ctg_member_search_wrapper');
	const memberSearchContainer = $('<div id="ctg_member_search_container">');
	const memberSearchInputEl = $('<input type="text" id="ctg_member_search_terms" name="ctg_member_search_terms">');
	const locationsCollectionWrapper = $('#ctg_locations_wrapper');
	const locationsHeading = $('<h3>');
	const locationsArchiveLinkContainer = $('<div>');
	const locationsArchiveLink = $('<a href="https://celebrationtitlegroup.com/category/locations/">');
	let memberSearchInput = $('#ctg_member_search_terms');
	const memberSearchResultsWrapper = $('#ctg_member_search_results_wrapper');
	const memberCollection = $('<div class="ctg-member-contact-card-collection">');
	let members = [];
	
	searchHeadingContainer.append(searchHeading);
	searchHeading.css({
		textAlign: 'center'
	});

	locationsArchiveLinkContainer.css({
		display: 'block',
		width: '100%',
		textAlign: 'center'
	});


	locationsArchiveLink.text(`Find a Celebration near you.`);
	locationsArchiveLink.css({
		textAlign: 'center',
		textTransform: 'capitalize',
	});
	locationsArchiveLinkContainer.append(locationsArchiveLink);
	locationsCollectionWrapper.append(locationsHeading);
	locationsCollectionWrapper.append(locationsArchiveLinkContainer);
	locationsHeading.css({
		textAlign: 'center',
		textTransform: 'capitalize',		
	});

	memberSearchContainer.append(memberSearchInputEl);
	memberSearchWrapper.append(memberSearchContainer);
	memberSearchResultsWrapper.append(memberCollection);

	async function setMembers(memberArray = []) {

		// Cleanup
		memberCollection.children().remove();

		if ( typeof memberArray[0] === 'string' ) {
			return;
		}

		memberArray.forEach( async (member) => { 

			// Declare all variables
			let locationData = {};
			let contactCardContainer = $('<div class="ctg-member-contact-card-container ctg-flex-item quarter ctg-anim float-in-bottom">');
			let contactCardHeadshotContainer = $('<div class="ctg-member-contact-card-headshot-container">');
			let contactCardHeadshot = $(`<img
decoding="async" 
width="150"
height="150"
src="${member.headshot_url}"
class=""
alt="${member.first_name}'s Headshot" 
loading="lazy"
/>`);
			let nameContainer = $('<div class="ctg-member-contact-card-name-container">');
			let name = $('<h3 class="ctg-member-contact-card-name">');
			let jobTitleContainer = $('<div class="ctg-member-contact-card-job-title-container">');
			let jobTitle = $('<h4 class="ctg-member-contact-card-job-title">');
			let locationName = $('<h5 class="ctg-member-contact-card-location-name">');
			let emailContainer = $('<div class="ctg-member-contact-card-email-container">');
			let email = $(`<a 
class="ctg-member-contact-card-email" 
href="mailto:${member.email}">`);

			if ( member.location_id == 0 ) {
				locationName.text(`Remote`);
			} else {
				await $.ajax({
					method: 'GET',
					url: ctg.get_location,
					data: {
						id: member.location_id
					},
					success: function (response) {
						let data = JSON.parse(response).text;
						locationData = JSON.parse(data)[0];
					}
				});
				locationName.text(`${locationData.location_name}`);
			}

			// Modify as necessary
			name.text(`${member.first_name} ${member.last_name}`);
			jobTitle.text(`${member.job_title}`);

			email.text(`Email Me`);

			// Append to containers
			contactCardHeadshotContainer.append(contactCardHeadshot);
			nameContainer.append(name);
			jobTitleContainer.append(jobTitle);
			jobTitleContainer.append(locationName);
			emailContainer.append(email);

			contactCardContainer.append(contactCardHeadshotContainer);
			contactCardContainer.append(nameContainer);
			contactCardContainer.append(jobTitleContainer);
			contactCardContainer.append(emailContainer);

			memberCollection.append(contactCardContainer);

		});
	}

	function memberSearch(terms) {

		$.ajax(
			{
				url: ctg.members_search,
				method: 'GET',
				data: {
					ctg_member_search_terms: terms
				},
				success: function(response) {
					let object = JSON.parse(response).text;
					let memberArray = JSON.parse(object);

					setMembers(memberArray);
				}
			}
		);
	}

	$(document).on('input change', 'input#ctg_member_search_terms',function(e) {
		memberSearch(e.target.value);
	});


	$.ajax({
		url: ctg.total_members,
		method: 'GET',
		data: {},
		success: function(response) {
			response = JSON.parse(response)[0].total_count;
			searchHeading.text(`Search for any one of our ${response} amazing Team Members`);
		},
		error: function(response) {
			console.log(response)
		}
	});

	$.ajax({
		url: ctg.total_locations,
		method: 'GET',
		data: {},
		success: function(response) {
			response = JSON.parse(response).text;
			let data = JSON.parse(response)[0];
			let totalLocations = data.total_count;
			let totalStates = data.total_states;
			locationsHeading.text(`Find us celebrating in ${totalLocations} locations across ${totalStates} states`);
		}
	});

	$.ajax({
		url: ctg.locations_meta,
		method: 'GET',
		data: {},
		success: function(response) {
			//			console.log(JSON.parse(response));
		}
	});

});