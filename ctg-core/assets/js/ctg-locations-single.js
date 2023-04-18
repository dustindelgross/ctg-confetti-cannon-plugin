jQuery(document).ready(function ($) {

	/**
	 * CTG Confetti Canvas
	 *
	 * All the code below is for the interactive
	 * confetti canvas on the CTG Locations pages.
	 */
	// Grab the canvas element
	const canvas = document.getElementById("ctg-confetti-canvas");
	// Get the 2D context for the canvas
	const ctx = canvas.getContext("2d", { desynchronized: true, alpha: false });

	// Create an array of particles
	let particles = [];
	// Declare the width and height of the canvas
	let w, h;
	let mouse = {
		x: null,
		y: null,
		radius: (canvas.height / 20) * (canvas.width / 20)
	};
	document.getElementById('ctg-confetti-canvas-container').addEventListener('mousemove', event => {
		mouse.x = event.clientX;
		mouse.y = event.clientY;
	});

	/**
	 * Resize the canvas to the size of the window
	 */
	function resize() {
		w = canvas.width = window.innerWidth;
		h = canvas.height = window.innerHeight;
	}

	/**
	 * Animate the canvas
	 *
	 * This function is called on every frame of the animation
	 * and draws the particles on the canvas.
	 *
	 * It updates the position of the particles and checks
	 * if they have hit the edge of the canvas.
	 *
	 * If they have, the particle's velocity is reversed.
	 *
	 * It also checks if the mouse is within the radius of the
	 * particle and if it is, it moves the particle away from
	 * the mouse.
	 *
	 */
	function animate() {

		// Call the animate function on the next frame
		requestAnimationFrame(animate);

		// Clear the canvas
		ctx.clearRect(0, 0, w, h);

		// Loop through all the particles
		for (let i = 0; i < particles.length; i++) {

			let p = particles[i];

			// Draw the particle
			ctx.save();
			ctx.translate(p.x + p.width / 2, p.y + p.height / 2);
			ctx.fillStyle = "#dbb778";
			// Create the illusion of depth by changing the opacity.
			ctx.globalAlpha = p.opacity;
			// Rotate the particle
			ctx.rotate(p.angle);
			// Draw the particle
			ctx.fillRect(-p.width / 2, -p.height / 2, p.width, p.height);
			ctx.restore();

			// Update the particle's position
			p.x += p.vx;
			p.y += p.vy;
			p.z += p.vz;
			/**
			 * Update the particle's height to create
			 * the illusion of falling in 3D space
			 */
			p.height -= p.hv;

			// Check if the particle has hit the minimum/maximum height (0-8)
			if (p.height > 8 || p.height < 0) {
				p.hv = -p.hv;
			}

			// Check if the particle has hit the edge of the canvas
			if (p.x < 0 || p.x > w) {
				p.vx = -p.vx;
			}

			if (p.y < 0 || p.y > h) {
				p.vy = -p.vy;
			}

			// Check if the particle has hit the minimum/maximum "depth" (0-8)
			if (p.z < -20 || p.z > 20) {
				p.vz = -p.vz;
			}

			// Check if the particle is within the radius of the mouse
			let dx = mouse.x - p.x;
			let dy = mouse.y - p.y;
			let distance = Math.floor(Math.sqrt(dx * dx + dy * dy));
			if (distance < mouse.radius) {

				if (mouse.x < p.x && p.x < canvas.width) {
					p.x += 5;
					p.vx = -p.vx;
				}
				if (mouse.x > p.x && p.x > 0) {
					p.x -= 5;
					p.vx = -p.vx;
				}
				if (mouse.y < p.y && p.y < canvas.height) {
					p.y += 5;
					p.vy = -p.vy;
				}
				if (mouse.y > p.y && p.y > 0) {
					p.y -= 5;
					p.vy = -p.vy;
				}
			}

			if (p.rd < .49) {
				p.angle += Math.PI / 30;
			} else {
				p.angle -= Math.PI / 30;
			}

			p.opacity = (p.z / 100);

		}

		// Create a new particle every 10 frames
		if (particles.length < 250) {
			let p = {
				x: Math.floor(Math.random() * w),
				y: Math.floor(Math.random() * h),
				z: Math.floor(Math.random() * h),
				hv: Math.floor(Math.random() * 4 - 2),
				vx: Math.floor(Math.random() * 2 - 1),
				vy: Math.floor(Math.random() * 2 - 1),
				vz: Math.floor(Math.random() * 4 - 3),
				width: 16,
				height: 8,
				angle: Math.floor(Math.random() * 360),
				opacity: 1,
				rd: Math.random()
			};

			particles.push(p);
		}
	}

	resize();
	animate();

	window.addEventListener("resize", resize);

	// Text Rotation
	const rotationHeading = $('.ctg-confetti-text-rotation-heading');

	rotationHeading.each(function(e) {
		const firstSpan = $('<span class="ctg-confetti-text-start">');
		const midSpan = $('<span class="ctg-confetti-text-rotating-text">');
		const lastSpan = $('<span class="ctg-confetti-text-end">');
		const rotateText = $(this).data('rotatingText').split(/,[\s]*/);
		const outDelay = 2000;
		let textIndex = 0;

		firstSpan.text($(this).data('startText'));
		lastSpan.text($(this).data('endText'));

		$(this).append(firstSpan);
		$(this).append(midSpan);
		$(this).append(lastSpan);
		midSpan.append(rotateText[rotateText.length - 1]);
		function animateString(string) {

			midSpan.empty();
			// Split the string into an array of letters
			const letters = string.split("");

			// Loop through each letter
			for (let i = 0; i < letters.length; i++) {
				// Create a span element for the letter
				const span = $("<span>").text(letters[i]);

				// Add the 'letter' class to the span element for animation
				span.addClass("ctg-confetti-rotating-letter");

				// Append the span element to the rotating text element
				midSpan.append(span);
			}

			// Get all the letter spans in the string
			const letterSpans = midSpan.find(".ctg-confetti-rotating-letter");

			// Loop through each letter span
			letterSpans.each(function (i) {
				const letterSpan = $(this);

				// Delay the animation of each letter span by a multiple of 50ms
				setTimeout(function () {
					letterSpan.addClass("active");
				}, i * 50);
			});

			setTimeout(function () {
				letterSpans.each(function (i) {
					const letterSpan = $(this);

					// Delay the animation of each letter span by a multiple of 50ms
					setTimeout(function () {
						letterSpan.removeClass("active");
						letterSpan.addClass("out");
					}, i * 50);
				});
			}, outDelay);
		}

		// Function to rotate to the next keyword
		function rotateKeyword() {
			// Get the next keyword from the array
			const nextKeyword = rotateText[textIndex];

			// Clear the rotating text element
			midSpan.empty();

			// Animate each letter in the next keyword
			animateString(nextKeyword);

			// Increment the current keyword index
			textIndex++;

			// Wrap back to the beginning of the array if at the end
			if (textIndex >= rotateText.length) {
				textIndex = 0;
			}
		}

		// Start the keyword rotation interval
		setInterval(rotateKeyword, 4000);
	});

	let sbh = $('.ctg-scroll-based-heading');
	sbh.each(function (e) {
		let sbhWords = $(this).attr('data-text').split(" ");
		$(this).empty();
		let sbhLastWord = sbhWords.pop();
		let sbhSpan = $('<span>').css({ color: '#dbb778', textTransform: 'uppercase', whiteSpace: 'pre' }).text(" " + sbhLastWord);
		sbhWords = sbhWords.join(" ");
		let container = $("<div style='width:100%;text-align:center;'>");
		$(container).append(sbhWords);
		$(container).append(sbhSpan);
		$(this).append(container);
	});

	function scrollEffect(inClass, outClass, e) {
		let $e = $(e);
		let position = $e.parent().offset().top;
		let scroll = $(window).scrollTop();
		let windowHeight = $(window).height();
		let animateInHeight = windowHeight / 2;
		let animateOutHeight = -(windowHeight / 4);

		if (position - scroll < animateInHeight && position - scroll > animateOutHeight) {
			$e.removeClass(outClass);
			$e.addClass(inClass);
		} else if (position - scroll < animateOutHeight) {
			$e.removeClass(inClass);
			$e.addClass(outClass);
		} else {
			$e.removeClass(inClass);
			$e.addClass(outClass);
		}
	}

	function scrollEffectOnce(inClass, e, i) {
		let $e = $(e);
		let position = $e.parent().offset().top;
		let scroll = $(window).scrollTop();
		let windowHeight = $(window).height();
		let animateInHeight = windowHeight / 2;

		setTimeout( () => {
			if (position - scroll < animateInHeight ) {
				$e.addClass(inClass);
			}
		}, i * 50 );

	}

	function scrollElement( e ) {

		let scroll = $(window).scrollTop();

		e.each( function( i, el ) {

			el = $(el);
			let c = el.parent().parent();

			if ( el.data('scrollText') ) {
				el.text(el.data('scrollText'));
				el.css({
					opacity: '.5',
				});
			}

			el.css({
				position: "relative",
				left: i % 2 === 1 ? "-100vw" : "100vw",
			});

			if (scroll > c.offset().top - $(window).height()) {
				let current = scroll - c.offset().top;
				let target = c.height() + c.offset().top - ($(window).height());
				let percent = current / target;

				el.css({
					left: i % 2 === 1 ? percent * 100 + "vw" : - percent * 100 + "vw",
				});
			}

		});

	}

	// Add a class to the element when it enters the viewport
	$(window).scroll(function () {
		$('.ctg-scroll-based-heading').each(function (i, e) {
			scrollEffect('in', 'out', e);
		});

		$('.ctg-content-card').each(function (i, e) {
			scrollEffect('ctg-anim float-in-bottom', 'ctg-anim float-out-top', e);
		});

		$('.ctg-headshot-card').each(function (i, e) {
			scrollEffect('ctg-anim float-in-top', 'ctg-anim float-out-bottom', e);
		});

		$('.ctg-member-contact-card-container').each(function(i,e) {
			scrollEffectOnce('ctg-anim float-in-bottom', e, i);
		});

		scrollElement( $('#ctg-net-sheets-app-image-container img'), $('#ctg-net-sheets-app-image-container').parent().parent() );
		scrollElement( $('.ctg-scrollable-text'), $('.ctg-scrollable-text').parent() );
	});


	$('.ctg-expandable').on('click', function(e) {
		let content = $(this).find('.ctg-expandable-content');
		content.slideToggle(500);
	});

	$('.ctg-flip-callout').on('click', function (e) {
		let i = 0;
		for (i; i < 100; i++) {
			let particle = $("<div class='ctg-confetti-particle'>");
			let ry = Math.random() * 500 - 250;
			let rx = Math.random() * 500 - 250;
			$('.ctg-owner-headshot-container').append(particle);

			particle.animate({
				opacity: 0
			}, {
				duration: 550,
				step: function (now, fx) {
					$(this).css({
						transform: `translate(${rx}px,${ry}px)`
					});
				}
			});
		}

		setTimeout(function () {
			$('.ctg-confetti-particle').remove();
		}, 1000);

	});
/*
	const membersSection = $('#ctg-members');

	const members = async () => {
		let res = await $.get(
			ctg.get_members,
			{

			},
			(e) => {
				return console.log(JSON.parse(e));
			}
		);
	};
	
	let map;
	let service;
	let infowindow;


	function initMap() {
		const houston = new google.maps.LatLng(29.7604, -95.3698);

		infowindow = new google.maps.InfoWindow();

		map = new google.maps.Map(document.getElementById("ctg-location-1"), {
			zoom: 150
		});

		const request = {
			query: "Celebration Title Group 946 Heights Blvd Houston TX 77008",
			fields: ["name", "geometry"],
		};

		service = new google.maps.places.PlacesService(map);
		service.findPlaceFromQuery(request, (results, status) => {
			if (status === google.maps.places.PlacesServiceStatus.OK && results) {
				for (let i = 0; i < results.length; i++) {
					createMarker(results[i]);
				}

				map.setCenter(results[0].geometry.location);
			}
		});

	}

	function createMarker(place) {
		if (!place.geometry || !place.geometry.location) return;

		const marker = new google.maps.Marker({
			map,
			position: place.geometry.location,
		});

		google.maps.event.addListener(marker, "click", () => {
			infowindow.setContent(place.name || "");
			infowindow.open(map);
		});
	}
*/
	//	window.initMap = initMap;
});