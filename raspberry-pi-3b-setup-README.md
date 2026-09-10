# Raspberry Pi 3B – Headless Setup

## Overview

This project documents the initial **headless setup of a Raspberry Pi 3B**, from flashing the OS image to a fully updated, SSH-accessible system ready for further use (e.g. as a [Wazuh agent](wazuh_siem_project/README.md)).

The setup includes:

* **Raspberry Pi OS Lite** (64-bit, CLI only)
* **Wi-Fi and SSH** pre-configured before first boot
* **Remote access** from an admin PC over SSH
* **System update** to bring the Pi to the latest packages

---

## Requirements

* Raspberry Pi 3B + power adapter
* micro SD card
* Admin PC (used to flash the image and connect via SSH)

---

## Setup

### 1. Flash the OS Image

Raspberry Pi Imager was used to prepare the SD card.

* Device: **Raspberry Pi 3B**
* OS: **Raspberry Pi OS Lite (64-bit)** — CLI only, no desktop environment
* Storage: target micro SD card

Before flashing, Wi-Fi and SSH were pre-configured in the imager's advanced options so the Pi would be reachable on first boot with no monitor or keyboard attached.

* Wi-Fi SSID and password
* SSH enabled with a password set

The image was then flashed to the SD card.

### 2. First Boot and Remote Login

The SD card was inserted and the Pi powered on. Once it joined the network, it was accessed remotely from the admin PC:

```bash
ssh dave@192.168.3.10
```

### 3. System Update

With a shell session open, the system packages were brought up to date:

```bash
sudo apt update && sudo apt upgrade
```

The Pi was then rebooted to apply any pending changes, and reconnected via SSH to confirm it came back online:

```bash
sudo reboot
```

---

## Current Result

The Raspberry Pi 3B is fully set up, updated, and reachable over SSH — ready to be used as a base for further projects.

| Component            | Status       |
| --------------------- | ------------ |
| OS image flashed       | ✅ Working    |
| Wi-Fi / SSH pre-config | ✅ Working    |
| Remote SSH access      | ✅ Working    |
| System update          | ✅ Working    |
