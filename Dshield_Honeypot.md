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
                │   Raspberry Pi 3B  │
                │  DShield Honeypot │
                └─────────┬─────────┘
                          │
                          ▼
                 DShield / SANS ISC
                (data collection and reporting)
```

## Hardware

- Raspberry Pi 3B (1 GB version)
- microSD card (128 GB)
- A second computer for SSH access and configuration
- Internet connection

## Requirements

- A Raspberry Pi with Raspberry Pi OS Lite ready to use ([setup guide here](raspberry_pi_setup.md))
- An updated system and SSH access
- An account on [isc.sans.edu](https://isc.sans.edu) (you can find your email and API key in the *My Account* section)
  
Security and network isolation

The honeypot is exposed to the internet on purpose, so it is recommended to separate it from the rest of your network, for example with a VLAN, a DMZ, or firewall rules. DShield is a low-interaction honeypot, so an attacker does not get a real shell. But if the device is ever compromised, isolation stops the attacker from reaching other machines in your LAN. Isolation is not necessary for testing in a closed lab.

Recommended practices:

put the honeypot in a separate network (VLAN / DMZ / guest network),
block access from the honeypot to other networks and allow only outgoing traffic to the internet,
forward only the ports that should be exposed on your router,
do not expose the admin port (12222) to the internet.

📘 VLAN setup: **NOT ready yet**

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

This section summarizes the data collected by the honeypot during the monitored period of **24 hours**.

### Top attacker IP addresses and domains
<img width="1456" height="1064" alt="Screenshot From 2026-09-10 10-16-59" src="https://github.com/user-attachments/assets/f8e28c6f-d929-48e5-a4f5-06c9f36a7994" />


***The chart shows the most active source IPs, which include both legitimate scanning platforms such as Shodan or Censys that map exposed devices for statistics, and real attackers or botnet nodes. Individual addresses can be checked on services like VirusTotal or AbuseIPDB, where known scanners are labeled and malicious hosts usually have a history of reports.***

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

This Honeypot project is one of the most usefull ways to get data of attackers and their behavior, and it is also used in companies for data collection, blocking IPs and making decoys. 

## Notes

- The data in the DShield dashboard (*My Reports*) is updated with a delay of about 30 minutes.
- All testing was done only in a controlled lab environment.
- Before deploying be sure that its compleately safe and attackers can't gain access to your home LAN.
- If you find any mistakes in this tutorial, please let me know.

---

*This section will be extended with a detailed honeypot configuration and further analysis of the results.*
