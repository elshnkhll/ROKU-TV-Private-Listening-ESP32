## Roku TV Private Listening using ESP32 proxy.

This is a firmware wich will turn ESP32 to a proxy device for listening a Roku TV in a Chrome browser tab on your PC (or MAC)

Works in Chrome.


<a href="http://www.youtube.com/watch?feature=player_embedded&v=5ItckdX7aOM
" target="_blank"><img src="http://img.youtube.com/vi/5ItckdX7aOM/sddefault.jpg" 
alt="Roku Remote Control" width="400" height="300" /></a>


### ESPTool (burner)

You can upload firmware to your ESP32 using a Python-based, open-source, platform-independent utility to communicate with the ROM bootloader in Espressif chips. Download it from  here: https://github.com/espressif/esptool/releases


### Driver 

You can connect ESP32 to the Wind PC using the USB data cable. If device driver does not install automatically, you can download driver for the USB to serial converter chip from this site: https://www.silabs.com/developers/usb-to-uart-bridge-vcp-drivers

### Terminal 

You can use this Serial Terminal to see serioal output of your ESP32, it is free: https://robocallz.com/TTY/

### Player

After restarting ESP32 you can use PHP code for the player web interface or just use one provided on my domain: 
navigate to http://RokuRC.com/PRVT_LSTN/V1_Mono_VU.php?MyESP32IP=192.168.2.47&MyRokuTVIP=#.#.#.# in Chrome, just replace #-s with your Roku TV IP address and enjoy.


[<img width="64px" src="https://www.robocallz.com/app75/images/recorder_icon_150x150.png">](https://robocallz.com)
