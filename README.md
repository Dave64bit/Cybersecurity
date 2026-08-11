# Wazuh SIEM Lab

## Overview

This project documents the deployment of a small **Wazuh SIEM environment** used to monitor multiple endpoints.

The current setup includes:

* **Kali Linux VM** running the Wazuh server and dashboard
* **Raspberry Pi 3B** connected as a Linux agent
* **Windows 11 PC** connected as a Windows agent

Both endpoints are successfully connected and visible in the Wazuh Dashboard.

---

## Architecture

```text
                    Kali Linux VM
                  ┌─────────────────┐
                  │   Wazuh Server  │
                  │ Wazuh Dashboard │
                  └────────┬────────┘
                           │
               ┌───────────┴───────────┐
               │                       │
               ▼                       ▼
        Raspberry Pi              Windows 11
        Wazuh Agent               Wazuh Agent
```

---

## Deployment

### 1. Kali Linux VM

A Kali Linux virtual machine was deployed as the central Wazuh server using Oracle Virtualbox.

```bash
# Commands used during VM setup
# TODO
```
<img width="1314" height="118" alt="image" src="https://github.com/user-attachments/assets/a042753d-5422-4119-ba9e-4d046d986b4f" />

---

### 2. Wazuh Installation

Wazuh was installed directly on the Kali Linux VM.

The installation includes:

* Wazuh Manager
* Wazuh Indexer
* Wazuh Dashboard

```bash
# Wazuh installation commands
# TODO
```

After installation, the dashboard was successfully accessible.

```text
https://<WAZUH-SERVER-IP>
```

---

### 3. Raspberry Pi Agent

A Wazuh agent was installed on the Raspberry Pi and configured to communicate with the Wazuh server.

```bash
# Raspberry Pi agent installation
# TODO
```

The Raspberry Pi was successfully registered as an active Linux endpoint.

---

### 4. Windows 11 Agent

The Wazuh agent was then installed on the Windows 11 workstation.

```powershell
# Windows agent installation
# TODO
```

The Windows PC was successfully connected and appeared as an active endpoint in the dashboard.

---

## Current Result

The current Wazuh environment is fully functional.

| Device        | Role          | Status    |
| ------------- | ------------- | --------- |
| Kali Linux VM | Wazuh Server  | ✅ Running |
| Raspberry Pi  | Linux Agent   | ✅ Active  |
| Windows 11 PC | Windows Agent | ✅ Active  |

Both monitored endpoints are sending data to the central Wazuh server.

---

## Screenshots

```text
screenshots/
├── wazuh-dashboard.png
├── raspberry-pi-agent.png
└── windows-agent.png
```

Example:

```markdown
![Wazuh Dashboard](screenshots/wazuh-dashboard.png)
```

---

## Next Steps

Planned improvements:

* Analyze Wazuh alerts
* Review detected vulnerabilities
* Configure File Integrity Monitoring
* Test security event detection
* Create custom detection rules
* Perform basic attack simulations
* Document troubleshooting and network configuration

---

## Status

🚧 **Work in Progress**

Current milestone:

> Wazuh server successfully deployed with Raspberry Pi and Windows 11 endpoints connected and monitored.
