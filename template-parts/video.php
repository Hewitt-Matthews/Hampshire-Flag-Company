<?php

// Get the attributes passed from the shortcode
$video_attributes = get_query_var('video_attributes');
$high_quality = $video_attributes['high_quality'];
$medium_quality = $video_attributes['medium_quality'];
$thumbnail = $video_attributes['thumbnail'];

$uniqueID = rand(10000, 99999);

?>


<style>
    .video-container {
        width: 100%;
        position: relative;
        border-radius: 0em!important;
        aspect-ratio: 16 / 9;
        overflow: hidden;
    }

    .video-container video {
        aspect-ratio: 16 / 9;
    }

    .video-container .quality-selector {
        position: absolute; 
        bottom: 34px;
        left: 130px;
        background-color: rgba(0, 0, 0, 0.5);
        color: #fff!important;
        padding: 5px;
        border-radius: 5px;
    }

    .video-container img {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 1;
		object-fit: contain;
    }
	
	.video-container .et_pb_video_play {
		width: 100px!important;
		border-radius: 50%!important;
		height: 100px!important;
		display: block;
		position: absolute!important;
		z-index: 100!important;
		color: #fff!important;
		border: 5px solid #fff!important;
		left: 50%;
		top: 50%;
	}
	
	.video-container .et_pb_video_play::before {
		content: ""!important;
		width: 50px;
		height: 33px;
		position: absolute;
		left: 46%;
		top: 50%;
		transform: translate(-50%, -50%);
		background-color: #fff;
		clip-path: polygon(100% 50%, 40% 0, 40% 100%);
	}
	
</style>


<div class="video-container" id="video-container-<?= $uniqueID ?>">
    <img src="<?= $thumbnail ?>">
    <a href="#" class="et_pb_video_play"></a>
    <div id="control-container-<?= $uniqueID ?>">
        <video id="video-player-<?= $uniqueID ?>" controls width="100%" height="auto">
            <?php if($medium_quality) : ?>
                <source id="medium-quality-<?= $uniqueID ?>" data-src="<?= $medium_quality ?>" type="video/mp4">
            <?php endif; ?>
            <?php if($high_quality) : ?>
                <source id="high-quality-<?= $uniqueID ?>" data-src="<?= $high_quality ?>" type="video/mp4">
            <?php endif; ?>
        </video>
        <?php if($medium_quality && $high_quality) : ?>
            <select class="quality-selector" id="quality-selector-<?= $uniqueID ?>">
                <option value="medium">Low</option>
                <option value="high">High</option>
            </select>
        <?php endif; ?>
    </div>
</div>

<script>
const videoPlayer<?= $uniqueID ?> = document.getElementById(`video-player-<?= $uniqueID ?>`);
const qualitySelector<?= $uniqueID ?> = document.getElementById(`quality-selector-<?= $uniqueID ?>`);
const placeholder<?= $uniqueID ?> = videoPlayer<?= $uniqueID ?>.closest(`#video-container-<?= $uniqueID ?>`).querySelector('img');
const playButton<?= $uniqueID ?> = videoPlayer<?= $uniqueID ?>.closest(`#video-container-<?= $uniqueID ?>`).querySelector('.et_pb_video_play');

let timeoutId<?= $uniqueID ?>;

const toggleQualitySelector<?= $uniqueID ?> = (show) => {
    qualitySelector<?= $uniqueID ?>.style.display = show ? 'block' : 'none';
};

const resetTimeout<?= $uniqueID ?> = () => {
    clearTimeout(timeoutId<?= $uniqueID ?>);
    toggleQualitySelector<?= $uniqueID ?>(true);
    timeoutId<?= $uniqueID ?> = setTimeout(() => toggleQualitySelector<?= $uniqueID ?>(false), 300);
};

// Function to set video source
const setVideoSource<?= $uniqueID ?> = (quality) => {
    const sourceElement = document.getElementById(`${quality}-quality-<?= $uniqueID ?>`);
    videoPlayer<?= $uniqueID ?>.src = sourceElement.getAttribute('data-src');
};

// Play button click event
playButton<?= $uniqueID ?>.addEventListener('click', function(e) {
    e.preventDefault();
    setVideoSource<?= $uniqueID ?>('medium'); // Default to medium quality
    videoPlayer<?= $uniqueID ?>.load();
    videoPlayer<?= $uniqueID ?>.play();
    placeholder<?= $uniqueID ?>.style.display = 'none';
    playButton<?= $uniqueID ?>.style.display = 'none';
});

if (qualitySelector<?= $uniqueID ?>) {
    // Quality selector change event
    qualitySelector<?= $uniqueID ?>.addEventListener('change', function() {
        const currentTime = videoPlayer<?= $uniqueID ?>.currentTime;
        const isPaused = videoPlayer<?= $uniqueID ?>.paused;

        setVideoSource<?= $uniqueID ?>(this.value);

        videoPlayer<?= $uniqueID ?>.load();
        videoPlayer<?= $uniqueID ?>.currentTime = currentTime;

        if (!isPaused) {
            videoPlayer<?= $uniqueID ?>.play();
        }
    });
}

// Video play and pause events
videoPlayer<?= $uniqueID ?>.addEventListener('play', () => toggleQualitySelector<?= $uniqueID ?>(true));
videoPlayer<?= $uniqueID ?>.addEventListener('pause', () => toggleQualitySelector<?= $uniqueID ?>(true));

// Mouse enter and leave events to show/hide controls
const controlContainer<?= $uniqueID ?> = document.getElementById(`control-container-<?= $uniqueID ?>`);

controlContainer<?= $uniqueID ?>.addEventListener('mouseenter', () => toggleQualitySelector<?= $uniqueID ?>(true));
controlContainer<?= $uniqueID ?>.addEventListener('mouseleave', () => toggleQualitySelector<?= $uniqueID ?>(false));

// Stop propagation for quality selector
qualitySelector<?= $uniqueID ?>.addEventListener('mouseenter', (e) => e.stopPropagation());
qualitySelector<?= $uniqueID ?>.addEventListener('mouseleave', (e) => e.stopPropagation());


</script>
