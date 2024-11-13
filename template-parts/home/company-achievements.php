<?php

$achievements = get_field('achievements', 'options');

if ( $achievements ) : ?>

	<style>

		#company-achievements {
			background-color: rgb(var(--secondary));
			color: #fff;
			overflow-x: scroll;
		}

		#company-achievements h2 {
			color: #fff;
		}
		
		#company-achievements .achievement-container {
			display: flex;
			gap: 2em;
			width: max-content;
			margin: 5em 0 2em;
			position: relative;
			background-image: url(/wp-content/uploads/2023/01/Line.svg);
			background-repeat-x: repeat;
			background-repeat-y: no-repeat;
			background-position: 50% 50%;
			background-size: 120vw;
		}
		
		 #company-achievements .achievement-container .cursor {
            --cursorSize: 90px;
            width: var(--cursorSize);
            height: var(--cursorSize);
            border-radius: 50%;
            position: absolute;
			top: -15%;
			left: 50vw;
            background-color: #fff;
            color: rgb(var(--secondary));
            line-height: 1;
            font-weight: 600;
            font-size: 15px;
            text-align: center;
            display: grid;
            place-items: center;
            transition: opacity 300ms, border-radius 1s;
			z-index: 99;
        } 
		
		#company-achievements .achievement-container .cursor.hover-image {
			border-radius: 0 50% 50% 50%;
		}
		
		#company-achievements .achievement-container:hover {
   			cursor: none;
		}
		
		#company-achievements .achievement-container .achievement {
			text-align: center;
			display: flex;
			flex-direction: column;
			width: 250px;
			justify-content: space-between;
			height: 500px;
		}

		#company-achievements .achievement-container .achievement:nth-child(even) {
			flex-direction: column-reverse;
		}
		
		#company-achievements .achievement-container .achievement img {
			margin: auto;
			max-height: 200px;
    		object-fit: cover;
		}
		
		#company-achievements .achievement-container .achievement .year {
			font-size: 40px;
			font-weight: 800;
			color: rgb(255 255 255 / 15%);
			margin: 0;
			flex: 1;
			display: flex;
			justify-content: center;
			align-items: center;
		}
		
		#company-achievements .achievement-container .achievement .ball-container {
			flex: 1;
			display: flex;
			justify-content: center;
			align-items: center;
		}
		
		#company-achievements .achievement-container .achievement .ball-container .ball {
			--ballSize: 50px;
			width: var(--ballSize);
			height: var(--ballSize);
			background-color: #C1D0ED;
			border-radius: 50%;
		}
		
		#company-achievements .achievement-container .achievement .date {
    		font-weight: 600;
			padding-top: 10px;
		}
		
		.lightbox {
			position: fixed;
			top: 0;
			left: 0;
			width: 100%;
			height: 100%;
			background-color: rgba(0, 0, 0, 0.8);
			display: flex;
			align-items: center;
			justify-content: center;
			z-index: 999;
		}

		.lightbox img {
			max-width: 90%;
			max-height: 90%;
		}


	</style>

	<h2>Our Company Achievements</h2>

	<div class="achievement-container">

		<?php foreach ( $achievements as $achievement ) :

			$year = $achievement['year'];
			$date = $achievement['achievement_date'];
			$achievementDescription = $achievement['achievement_description'];
			$image_id = $achievement['achievement_image'];
			$image_thumbnail = wp_get_attachment_image( $image_id, array('200 x 200'), true, array( "loading" => "lazy" ) );
			$image_large_url = wp_get_attachment_image_url( $image_id, 'large' );

		?>

		<div class="achievement">
			
			<p class="year"><?= $year ?></p>

			<div class="ball-container"><span class="ball"></span></div>

			<div class="meta">

				<?php if ( $image_id ) : ?>
					<div class="thumbnail">
						<?= str_replace( '<img', '<img data-src="' . esc_url( $image_large_url ) . '" data-hover-text="Open Image"', $image_thumbnail ); ?>
					</div>
				<?php endif; ?>
				<p class="date"><?= $date ?></p>
				<p><?= $achievementDescription ?></p>

			</div>

		</div>

		<?php endforeach; ?>
		
		<span class="cursor">Drag or Scroll</span>

	</div>

	<script>
		
		window.addEventListener('load', () => {
			
			var achievementContainer = document.querySelector(".achievement-container");
			var initialX;
			var finalX = 0;
			var offsetX;
			var isDragging = false;
			let moveEvent;
			
			achievementContainer.addEventListener("touchstart", dragStartMobile, { passive: true });
			document.addEventListener("touchend", dragEndMobile, { passive: true });
			document.addEventListener("touchmove", dragMobile, { passive: true });


			function dragStartMobile(event) {
				moveEvent = event;
			  event.preventDefault();
			  initialX = event.touches[0].clientX;
			  offsetX = finalX - initialX;
			  isDragging = true;
			}

			function dragEndMobile(event) {
				if (moveEvent) {
					finalX = moveEvent.touches[0].clientX + offsetX;
				}
				isDragging = false;
			}

			function dragMobile(event) {
			  if (!isDragging) {
				return;
			  }
			
				moveEvent = event;

			  let translateX = event.touches[0].clientX + offsetX;

			  if(event.touches[0].clientX + offsetX > 100) translateX = 100;
			  if(event.touches[0].clientX + offsetX < (achievementContainer.clientWidth - (window.innerWidth / 2)) / -1) translateX = (achievementContainer.clientWidth - (window.innerWidth / 2)) / -1;

			  achievementContainer.setAttribute('style', `transform: translateX(${translateX}px)`);
			}

			achievementContainer.addEventListener("mousedown", dragStart);
			document.addEventListener("mouseup", dragEnd);
			document.addEventListener("mousemove", drag);

			function dragStart(event) {
			  initialX = event.clientX;
			  offsetX = finalX - initialX;
			  isDragging = true;
			}

			function dragEnd(event) {
			  finalX = event.clientX + offsetX;
			  isDragging = false;
			}

			function drag(event) {
				if (!isDragging) {
					return;
				}
		
				let translateX = event.clientX + offsetX;
				
 				if(event.clientX + offsetX > 100) translateX = 100;
 				if(event.clientX + offsetX < (achievementContainer.clientWidth - (window.innerWidth / 2)) / -1) translateX = (achievementContainer.clientWidth - (window.innerWidth / 2)) / -1;
				
				achievementContainer.setAttribute('style', `transform: translateX(${translateX}px)`);
			}

			const moveCursor = (e) => {
				if(!isDragging) {
					e.stopPropagation();

					const target = e.currentTarget;
					const containerRect = target.getBoundingClientRect();
					const cursorSize = 90;
					const cursorOffset = (cursorSize / 2) - 50;

					let mouseY = e.clientY - containerRect.top - cursorOffset;
					let mouseX = e.clientX - containerRect.left - cursorOffset;

					const cursor = target.querySelector('.cursor');
					cursor.style.transform = `translate3d(${mouseX}px, ${mouseY}px, 0)`;
					cursor.style.left = '0';
					cursor.style.top = '0';
				}
			};


       		achievementContainer.addEventListener('mousemove', moveCursor);

			
			//Image Light Box

			function openLightbox(e) {
				const thumbnail = e.currentTarget;
				const largeImageUrl = thumbnail.dataset.src;
				const lightbox = document.createElement('div');
				lightbox.classList.add('lightbox');
				lightbox.innerHTML = `<img src="${largeImageUrl}" loading="lazy">`;
				lightbox.addEventListener('click', () => {
					lightbox.remove();
				});
				document.body.appendChild(lightbox);
			}

			const thumbnailImages = document.querySelectorAll('.thumbnail img');
			thumbnailImages.forEach(thumbnail => {
				thumbnail.addEventListener('click', openLightbox);
			});

			// Image Hover 
			function handleThumbnailHover(e) {
				const thumbnail = e.currentTarget;
				const cursor = achievementContainer.querySelector('.cursor');
				const isMouseOver = e.type === 'mouseover';

				if (isMouseOver) {
					cursor.textContent = thumbnail.dataset.hoverText;
					cursor.classList.add('hover-image');
				} else {
					cursor.textContent = 'Drag or Scroll';
					cursor.classList.remove('hover-image');
				}
			}

			thumbnailImages.forEach(thumbnail => {
				thumbnail.addEventListener('mouseover', handleThumbnailHover);
				thumbnail.addEventListener('mouseout', handleThumbnailHover);
			});

			
		})
		
	</script>

<?php endif; ?>