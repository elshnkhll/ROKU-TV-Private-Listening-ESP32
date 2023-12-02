<?php

	// http://rokurc.com/PRVT_LSTN/v1.php?MyRokuTVIP=192.168.2.19
	
	// TO DO: get ESP32 IP from web server

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Roku Private Listening</title>
</head>
<body>

<div class="porble">
<svg id="headphones" fill="#FFFFFF" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" 	 width="20px" height="20px" viewBox="0 0 287.386 287.386"	 xml:space="preserve"><g>	<g>		<path d="M62.743,155.437v98.42c0,5.867,4.741,10.605,10.605,10.605c5.854,0,10.605-4.738,10.605-10.605v-98.42			c0-5.856-4.751-10.605-10.605-10.605C67.484,144.832,62.743,149.576,62.743,155.437z"/>		<path d="M29.456,264.582h23.351v-116.85c0.064-0.56,0.166-1.119,0.166-1.693c0-50.412,40.69-91.42,90.698-91.42			c50.002,0,90.692,41.008,90.692,91.42c0,0.771,0.113,1.518,0.228,2.263v116.28h23.354c16.254,0,29.442-13.64,29.442-30.469			v-60.936c0-13.878-8.989-25.57-21.261-29.249c-1.129-66.971-55.608-121.124-122.45-121.124			c-66.86,0-121.347,54.158-122.465,121.15C8.956,147.638,0,159.32,0,173.187v60.926C0,250.932,13.187,264.582,29.456,264.582z"/>		<path d="M203.454,155.437v98.42c0,5.867,4.748,10.605,10.604,10.605s10.604-4.738,10.604-10.605v-98.42			c0-5.856-4.748-10.605-10.604-10.605C208.191,144.832,203.454,149.576,203.454,155.437z"/>	</g></g></svg>
</div>

<style>

	body, html{
		margin: 0;
		padding: 0;
		position: relative;
	}
	.porble {
		position: absolute;
		top: 1px;
		left: 1px;
		background-color: black;
		border: 5px solid #A56BD1; /* #662D91; */
		border-radius: 50%;
		width: 35px;
		height: 35px;
		margin: 0px 0px;
		padding: 0px 0px;
		
    display: flex;
    justify-content: center;
    align-items: center;
    max-height: 45px;
		
		
		
	}

</style>

<script type="text/javascript" src="player/pcm-player.js"></script>

<script>

	var tv_IP = "<?php echo $_GET['MyRokuTVIP']; ?>";

	var ESP32_IP =  "<?php echo $_GET['MyESP32IP']; ?>";

	console.log('Roku TV IP: ' + tv_IP);


	var player;
	var diff = 0;

	var init_msg = {type: 'init', config: {sampleRate: 48000, channels: 2}};

	
	var w_l = 3;
	var workers = [];


	async function do_handle_mssg(e) {
				switch (e.data.type) {
					case 'error' :
						console.log('decoding error ' + e.data.error);
						break;
					case 'data' :
						if( diff < 0 ) diff = 0;
						if( diff > 4 ){
							diff = diff - 2;
							console.log( "=" );
							break;
						}
						diff = diff - 1;
						//*************************
						player.feed(e.data.payload);
						break;
					default:
				}
	};
	
	
	
	for (var j = 0; j < 10; j++) {

			var worker = new Worker('workers/decoder.js');
			
			workers.push(worker);
				
			worker.onmessage = do_handle_mssg;

			worker.postMessage( init_msg );
			
	}

	window.onclick = do_onclick;
	
	var crrnt_worker = 0;
	var ws;
	
	function do_onclick() {
	 
		console.log("onClick");
		document.getElementById("headphones").style.display = 'none';

		ws = new WebSocket('ws://' + ESP32_IP + ':881');
		ws.binaryType = 'arraybuffer';
		ws.onmessage = async function(event) {
			var dt = new Uint8Array(event.data);
			// console.log( dt );
			//********************************
			workers[crrnt_worker].postMessage({
				type: 'decode',
				buffer: dt
			});
			crrnt_worker = crrnt_worker + 1;
			if( crrnt_worker > 9) {
				crrnt_worker = 0;
			}
				
			
			// console.log( crrnt_worker );
			diff = diff + 1; 
		};
        ws.onopen    = function(evt) { 
			console.log( "WBSCK OPEN" );
		    ws.send( tv_IP );              // Sending Roku TV IP adress to ESP32
			var d = new Date();
			console.log( d.toLocaleTimeString() );
		};
        ws.onclose   = function(evt) {
          if (evt.wasClean) {
            console.log(`WBSCK CLOSED cleanly, code=${evt.code} reason=${evt.reason}`);
          } else {
            console.log( "WBSCK DIED" );
			do_onclick();
          }
        };
		ws.onerror   = function(evt) { 
			console.log( "WBSCK ERROR: ", evt ); 
		};
		
		player = new PCMPlayer({
			encoding: '32bitFloat',
			channels: 2,
			sampleRate: 48000,
			flushingTime: 100
		});
		
		window.onclick = function(){ 
			window.location.reload(); // = null;
		}; 
		
	}
	

	var skip = 0;
	async function do_display_VU( c, vu ){
		return;
		// https://www.w3schools.com/charsets/ref_utf_geometric.asp
		skip++;
		if( skip < 5 ) return;
		skip = 0;
		var n = Math.min( 10, vu );
		window.top.document.title = '-' + '\u25AE'.repeat( n ) + '\u25Af'.repeat( 10 - n ) + '+';
	}
 
</script>
</body>
</html>
