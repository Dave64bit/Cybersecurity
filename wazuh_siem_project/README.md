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

<img width="639" height="70" alt="image" src="https://github.com/user-attachments/assets/6cb672fc-5126-441e-9486-755d9f6d60fc" />


### 2. Wazuh Installation

Wazuh was installed directly on the Kali Linux VM.

The installation includes:

* Wazuh Manager
* Wazuh Indexer
* Wazuh Dashboard

```bash
curl -sO https://packages.wazuh.com/4.14/wazuh-install.sh && sudo bash ./wazuh-install.sh -a
```

After installation, the dashboard was successfully accessible.

```text
https://<WAZUH-SERVER-IP>
```
<img width="2145" height="733" alt="ChatGPT Image Aug 11, 2026, 06_42_54 PM" src="https://github.com/user-attachments/assets/5e33d1eb-94ae-48f7-a4d6-128bdf230165" />


---

### 3. Raspberry Pi Agent

A Wazuh agent was installed on the Raspberry Pi and configured to communicate with the Wazuh server.

```bash
wget https://packages.wazuh.com/4.x/apt/pool/main/w/wazuh-agent/wazuh-agent_4.14.7-1_arm64.deb && sudo WAZUH_MANAGER='manager_IP' WAZUH_AGENT_GROUP='default' WAZUH_AGENT_NAME='agent_name' dpkg -i ./wazuh-agent_4.14.7-1_arm64.deb

sudo systemctl daemon-reload
sudo systemctl enable wazuh-agent
sudo systemctl start wazuh-agent
```

The Raspberry Pi was successfully registered as an active Linux endpoint.

---

### 4. Windows 11 Agent

The Wazuh agent was then installed on the Windows 11 workstation.

```powershell
Invoke-WebRequest -Uri https://packages.wazuh.com/4.x/windows/wazuh-agent-4.14.7-1.msi -OutFile $env:tmp\wazuh-agent; msiexec.exe /i $env:tmp\wazuh-agent /q WAZUH_MANAGER='manager_IP' WAZUH_AGENT_NAME='agent_name'

NET START Wazuh
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


## 5. SSH Brute-Force Detection Test with Hydra 🐉

To verify that Wazuh correctly detects suspicious authentication activity, a controlled SSH brute-force simulation was performed against the Raspberry Pi.

The test environment consisted of:

* **Source:** Kali Linux VM
* **Target:** Raspberry Pi 3B
* **Service:** SSH
* **Monitoring:** Wazuh Agent installed on the Raspberry Pi
* **Connection:** Tailscale network

Using brute force tool Hydra v9.7 I generated SSH login attempts with command: 
```bash
hydra -l admin -P pass.txt -t 4 ssh://<Raspberry IP adress>
```
The Raspberry Pi recorded the failed login attempts in its authentication logs, which were collected and forwarded to the Wazuh Manager by the Wazuh Agent.

### Detection in Wazuh

The generated authentication activity was successfully detected by Wazuh and appeared in the Wazuh Dashboard as SSH-related security events.

<img width="1575" height="546" alt="Screenshot_2026-08-12_04_36_39" src="https://github.com/user-attachments/assets/7a82f55d-63ad-46e2-9450-c20ec5ba914b" />


This confirms that:

* the Raspberry Pi agent is communicating correctly with the Wazuh Manager,
* Linux authentication logs are being collected,
* Wazuh rules are processing failed SSH authentication attempts,
* security events from the remote Raspberry Pi are visible in the central Wazuh Dashboard.

### Result

**-SSH brute-force detection: Successful ✅**

**-Comment: only a few visible ssh tries are caused by low computing capacity of RPI 3B.**


> The test was performed only against systems within the controlled lab environment.

## 6. Web Login Brute-Force Detection Test (Custom Rules) 🔐

To extend detection coverage beyond SSH, a custom web login lab was built to test Wazuh's ability to detect brute-force attempts against a web application using custom decoders and rules.

The test environment consisted of:

Target: Apache web server (hosted on raspberry pi) hosting a custom PHP login form
Service: HTTP login (index.php)
Monitoring: Wazuh Agent with a custom <localfile> log source
Log format: Custom JSON login log

A custom PHP login form with JSON-based attempt logging was built for this test. Setup details are documented separately:[Login Lab](/login-lab-README.md)

**6.1 Wazuh Agent Configuration**

The Wazuh agent was configured to collect and forward the new log source.

Installation / configuration of the Wazuh agent
Wazuh Manager IP configured
<localfile> block added to ossec.conf:
xml
<localfile>
  <log_format>json</log_format>
  <location>/var/log/login-lab.log</location>
</localfile>
JSON log format set for correct field parsing
Wazuh agent restarted:
bash
sudo systemctl restart wazuh-agent
**6.2 Custom Wazuh Rules**

Custom detection rules were created on the Wazuh Manager to classify login events:

Rule for a successful login
Rule for a failed login
Rule for repeated failed login attempts (brute-force correlation)
Wazuh Manager restarted:
bash
sudo systemctl restart wazuh-manager
Detection in Wazuh

**6.3 Hydra brute force attempt**

Using Hydra v9.7 I tested if siem will respond with the pre-configured rules.
```bash
hydra -l admin -P pass.txt 192.168.3.136 http-post-form "/login.php:username=^USER^&password=^PASS^:F=Invalid username or password "
```
<img width="2841" height="117" alt="image" src="https://github.com/user-attachments/assets/3de0eab4-8661-4402-bfce-eb03c58676aa" />

Alert verified in the Wazuh Dashboard
Alert confirmed with rule.id 100101, level 7
Correlation of multiple failed login attempts tested to confirm brute-force detection

This confirms that:

the custom login log is being collected correctly by the Wazuh Agent,
the custom decoder correctly parses the JSON login events,
the custom rules correctly classify successful vs. failed login attempts,
repeated failed logins are correlated and escalated as brute-force alerts.
Result

-Web login brute-force detection: Successful ✅

-Comment: rule.id 100101 (level 7) triggers reliably on failed logins, with correlation confirmed across multiple repeated attempts.
