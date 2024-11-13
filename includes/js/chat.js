const globalChat = () => {

  const runTawkPlugin = (src) => {
    var Tawk_API = Tawk_API || {},
      Tawk_LoadStart = new Date();

    (function() {
      var s1 = document.createElement("script"),
        s0 = document.getElementsByTagName("script")[0];
      s1.async = true;
      s1.src = src;
      s1.charset = 'UTF-8';
      s1.setAttribute('crossorigin', '*');
      s0.parentNode.insertBefore(s1, s0);
    })();
  }

  function generateMeluScript(id, src) {
    var script = document.createElement('script');
    script.type = 'text/javascript';
    script.id = id;
    script.src = src;

    var body = document.getElementsByTagName('head')[0];
    body.appendChild(script);
  }

	const currentTime = new Date();
	const currentDay = currentTime.getDay();
	const currentHours = currentTime.getHours();
	const currentMinutes = currentTime.getMinutes();
	const isWeekday = currentDay >= 1 && currentDay <= 5; // Monday to Friday
	const isWorkingHours = currentHours > 8 || (currentHours === 8 && currentMinutes >= 30) && currentHours < 17 || (currentHours === 17 && currentMinutes < 30); // 8.30am to 5.29pm

	if (isWeekday && isWorkingHours) {
	  runTawkPlugin('https://embed.tawk.to/5df35e7b43be710e1d21ee4d/1du7u1rlh');
	} else {
	  generateMeluScript('3b63884f3d4bb3ee9fbdc0c603f818d0', 'https://meluchat.com/livechat/script.php?id=3b63884f3d4bb3ee9fbdc0c603f818d0');
	}

	
}

window.addEventListener('load', globalChat);
