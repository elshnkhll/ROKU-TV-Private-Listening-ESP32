esptool --chip esp32 --port "/dev/cu.usbserial-01A8EF48" --baud 921600  --before default_reset --after hard_reset write_flash  -z --flash_mode dio --flash_freq 80m --flash_size 4MB 0x1000 s_RK_TV_PL_PRX_V6.h.bootloader.bin 0x8000 s_RK_TV_PL_PRX_V6.h.partitions.bin 0xe000 boot_app0.bin 0x10000 s_RK_TV_PL_PRX_V6.h.bin
:                                              ^- put com port name here'