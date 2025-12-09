	<!DOCTYPE html>
	<html>
		<head>
			<style>
				#button-container, #output-container {
					display: flex;
					flex-wrap: nowrap;
					flex-direction: row;
				}
				#button-container > button {
					margin-right: 8px;
				}
			</style>
		</head>
		<body>
			<div id="button-container">
				<button id="info">INFO</button>
				<button id="register">START REGISTER</button>
				<button id="verify">VERIFY TEMPLATE</button>
				<button id="template">GET TEMPLATE</button>
				<button id="cancel">CANCEL CAPTURE</button>
			</div>
			<div id="output-container">
				<div style="display: flex; flex-direction: column;">
					<h5 style="margin-bottom: 4px">OUTPUT</h5>
					<textarea id="output-text" rows="30" style="width: 600px"></textarea>
				</div>
				<div style="display: flex; flex-direction: column; margin-left: 45px">
					<h5 style="margin-bottom: 4px">FINGER IMAGE</h5>
					<canvas id="mein-canvas" width="150" height="200" style="border:1px solid silver; border-radius: 50%;">
					</canvas>
				</div>
			</div>

			<script>
				var info 	 = document.getElementById('info'),
					register = document.getElementById('register'),
					verify 	 = document.getElementById('verify'),
					template = document.getElementById('template'),
					canvas	 = document.getElementById('mein-canvas'),
					cancel 	 = document.getElementById('cancel');
				var outputText = document.getElementById('output-text');
				var polling = null;

				// TEST INFO
				info.addEventListener('click', e => {
					fetchData('http://localhost:24008/ISSOnline/info');
				});
				// TEST REGISTER
				register.addEventListener('click', e => {
					fetchData('http://localhost:24008/ISSOnline/beginCapture?type=1');
					// GET IMAGE
					polling = setInterval(() => {
							fetch('http://localhost:24008/ISSOnline/getImage')
							.then(reslv => {
								return reslv.json();
							})
							.then(reslv => {
								canvas.innerHTML = null;
								if(reslv.ret !== 0) {
									return false
								}
								var ctx = canvas.getContext('2d');
							});
						}, 5000);
				
				});
				// TEST VERIFY
				verify.addEventListener('click', e => {
					if(polling !== null)
						clearInterval(polling);

					fetchData('http://localhost:24008/ISSOnline/beginCapture?type=2');
				});
				// GET TEMPLATE
				template.addEventListener('click', e => {
					if(polling !== null)
						clearInterval(polling);
					fetchData('http://localhost:24008/ISSOnline/getTemplate');
				});
				// TEST CANCEL
				cancel.addEventListener('click', e => {
					if(polling !== null)
						clearInterval(polling);
					fetchData('http://localhost:24008/ISSOnline/cancelCapture');
				});
				
				

				function fetchData(url) {
					fetch(url)
					.then(reslv => {
						return reslv.json();
					})
					.then(reslv => {
						if(outputText.value !== null || outputText.value !== '')
							outputText.value = null;
						outputText.value = JSON.stringify(reslv, null, 2);		
					})
				}

			</script>
		</body>
	</html>