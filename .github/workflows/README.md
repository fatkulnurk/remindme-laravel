## CI & CD

**Content**
- docker.yml (Docker Build Testing)
- laravel.yml (Laravel Tests)
- laravel-deploy.yml (Laravel Deployment)
- github secrets

### docker.yml (Docker Build Testing)
⚠️ no secret required 🥳

For testing docker compose:
- Development: `docker compose up --build`
- Test: `docker compose -f docker-compose.ci.yml up --build  --abort-on-container-exit`
- Production: `docker compose -f docker-compose.prod.yml up --build `

### laravel.yml (Laravel Tests)
no secret required 🥳

for testing laravel unit test & feature test inside docker compose test.

### laravel-deploy.yml (Laravel Deployment)
⚠️⚠️ secret required ⚠️⚠️

for deployment to server, use sshpass from https://github.com/matheusvanzan/sshpass-action . 
For my test, I'm using Contabo with spescific like this.

```text
H/W path            Device     Class      Description
=====================================================
                               system     Standard PC (i440FX + PIIX, 1996)
/0                             bus        Motherboard
/0/0                           memory     96KiB BIOS
/0/400                         processor  AMD EPYC 7282 16-Core Processor
/0/1000                        memory     8GiB System Memory
/0/1000/0                      memory     8GiB DIMM RAM
/0/100                         bridge     440FX - 82441FX PMC [Natoma]
/0/100/1                       bridge     82371SB PIIX3 ISA [Natoma/Triton II]
/0/100/1/0                     input      PnP device PNP0303
/0/100/1/1                     input      PnP device PNP0f13
/0/100/1/2                     storage    PnP device PNP0700
/0/100/1/3                     system     PnP device PNP0b00
/0/100/1.1                     storage    82371SB PIIX3 IDE [Natoma/Triton II]
/0/100/1.2                     bus        82371SB PIIX3 USB [Natoma/Triton II]
/0/100/1.2/1        usb1       bus        UHCI Host Controller
/0/100/1.3                     bridge     82371AB/EB/MB PIIX4 ACPI
/0/100/2                       display    VGA compatible controller
/0/100/3                       generic    Virtio memory balloon
/0/100/3/0                     generic    Virtual I/O device
/0/100/5                       storage    Virtio SCSI
/0/100/5/0          scsi2      generic    Virtual I/O device
/0/100/5/0/0.0.0    /dev/sda   disk       53GB QEMU HARDDISK
/0/100/5/0/0.0.0/1  /dev/sda1  volume     1023KiB BIOS Boot partition
/0/100/5/0/0.0.0/2  /dev/sda2  volume     2046MiB EXT4 volume
/0/100/5/0/0.0.0/3  /dev/sda3  volume     47GiB EXT4 volume
/0/100/12                      network    Virtio network device
/0/100/12/0         eth0       network    Ethernet interface
/0/100/1e                      bridge     QEMU PCI-PCI bridge
/0/100/1f                      bridge     QEMU PCI-PCI bridge
/1                  /dev/fb0   display    EFI VGA
/2                  input0     input      Power Button
/3                  input1     input      AT Translated Set 2 keyboard
/4                  input3     input      VirtualPS/2 VMware VMMouse
/5                  input4     input      VirtualPS/2 VMware VMMouse
```

### Github Secrets
🫡 For deployment, Don't forget to create secrets with keys:

| Name             | Key                        |
|------------------|----------------------------|
| HOST (SERVER IP) | DEPLOYMENT_SERVER_HOST     |
| USERNAME SSH     | DEPLOYMENT_SERVER_USER     |
| PASSWORD SSH     | DEPLOYMENT_SERVER_PASSWORD |
