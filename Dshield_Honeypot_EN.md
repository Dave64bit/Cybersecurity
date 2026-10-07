# Raspberry Pi Honeypot (DShield)

## Overview

This project describes how to set up a **DShield** honeypot (from the SANS Internet Storm Center) on a Raspberry Pi. The goal is to expose an intentionally vulnerable device to the internet or a test network and collect data about attacks and scanning attempts. The data is then sent to the DShield dashboard for analysis.

> ℹ️ This document starts with the installation of the honeypot itself. The Raspberry Pi preparation (writing the OS to the SD card, first boot, system updates) is described in a separate README.

## Architecture

```
                 Internet / test network
                          │
                          ▼
                ┌───────────────────┐
                │   Raspberry Pi 4  │
                │  DShield Honeypot │
                └─────────┬─────────┘
                          │
                          ▼
                 DShield / SANS ISC
                (data collection and reporting)
```

## Hardware

- Raspberry Pi 4 (1 GB version)
- microSD card (128 GB)
- A second computer for SSH access and configuration
- Internet connection

## Requirements

- A Raspberry Pi with Raspberry Pi OS Lite ready to use ([setup guide here](raspberry_pi_setup.md))
- An updated system and SSH access
- An account on [isc.sans.edu](https://isc.sans.edu) (you can find your email and API key in the *My Account* section)

## Installation

### 1. Install dependencies and the DShield honeypot

```bash
sudo apt install git -y
git clone https://github.com/DShield-ISC/dshield.git
cd dshield/
sudo ./install.sh
```

During the installation wizard, you need to:

- agree to turn the device into a honeypot,
- agree to take part in the DShield research project,
- allow automatic updates,
- enter your **email and API key** from your account on [isc.sans.edu](https://isc.sans.edu) (*My Account* section),
- keep the default settings for admin access and write down the assigned **admin port** (default: `12222`),
- set an *ignore rule* (a network whose activity will not be logged, usually your own internal network),
- fill in the details for the SSL certificate (any fake data is fine),
- confirm the restart of the Raspberry Pi when the installation is finished.

### 2. Check that everything works after installation

After the reboot, SSH access moves to the admin port:

```bash
ssh -p 12222 pi@<hostname>
ifconfig
```

To check that the honeypot exposes the expected (intentionally vulnerable) ports, scan it from another machine (for example, Kali Linux):

```bash
nmap <IP of the raspberry pi>
```

You should see many open ports (for example 22, 23, 80, 5555, 8000, 8080). This is normal, because the honeypot is set up to look vulnerable on purpose.

## Project status

| Component | Status |
|---|---|
| DShield honeypot | ✅ Installed |
| Reporting to DShield | ✅ Working (confirmed by a scan) |
| Rules and logging configuration | ⏳ To be added |
| Analysis of collected data | 🔄 In progress (see [Results and statistics](#results-and-statistics)) |

## Results and statistics

This section summarizes the data collected by the honeypot during the monitored period: **TO DO (from – to)**.

### Top attacker IP addresses and domains
<img width="1456" height="1064" alt="Screenshot From 2026-09-10 10-16-59" src="https://github.com/user-attachments/assets/f8e28c6f-d929-48e5-a4f5-06c9f36a7994" />


*Description: TO DO (for example, the most active source IPs, their reverse DNS / domains, and the organization or ASN).*

### SSH / Telnet passwords

<img width="1119" height="1004" alt="Screenshot From 2026-10-06 21-59-57" src="https://github.com/user-attachments/assets/1560ee4a-cc5e-4e1d-b6db-006b57eb8f04" />

*Description: TO DO (for example, the most common username and password combinations and typical dictionary attacks).*

### Countries of origin

<img width="1456" height="756" alt="Screenshot From 2026-09-10 10-15-58" src="https://github.com/user-attachments/assets/24a38a7a-1304-428b-8b76-559d2797f5c8" />


*Description: TO DO (for example, the top countries by number of attempts and their share).*

### Targeted ports

<img width="1456" height="823" alt="Screenshot From 2026-09-10 10-16-42" src="https://github.com/user-attachments/assets/ffafc8ca-4b23-43fd-bf63-b500aa27c113" />


*Description: TO DO (for example, the most scanned ports and the services the attackers expected to find on them).*

### Summary

TO DO – the main findings, interesting patterns, and any recommendations.

## Notes

- The data in the DShield dashboard (*My Reports*) is updated with a delay of about 30 minutes.
- All testing was done only in a controlled lab environment.

---

*This section will be extended with a detailed honeypot configuration and further analysis of the results.*
