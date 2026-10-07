# Raspberry Pi Honeypot (DShield)

## Přehled

Tento projekt dokumentuje nasazení honeypotu **DShield** (SANS Internet Storm Center) na Raspberry Pi. Cílem je vystavit uměle zranitelné zařízení do internetu / testovací sítě a sbírat data o útocích a skenovacích pokusech, které jsou následně odesílány do DShield dashboardu k analýze.

> ℹ️ Tento dokument začíná až instalací samotného honeypotu. Příprava Raspberry Pi (zápis OS na SD kartu, první spuštění, aktualizace systému) je popsána v samostatném README.

## Architektura

```
                 Internet / testovací síť
                          │
                          ▼
                ┌───────────────────┐
                │   Raspberry Pi 4  │
                │  DShield Honeypot │
                └─────────┬─────────┘
                          │
                          ▼
                 DShield / SANS ISC
                  (sběr a report dat)
```

## Použitý hardware

- Raspberry Pi 4 (1 GB verze)
- MicroSD karta (128 GB)
- Druhý počítač pro SSH přístup a konfiguraci
- Připojení k internetu

## Předpoklady

- Připravené Raspberry Pi s Raspberry Pi OS Lite (viz samostatné README)
- Aktualizovaný systém a SSH přístup
- Účet na [isc.sans.edu](https://isc.sans.edu) (e-mail a API klíč najdete v sekci *My Account*)

## Instalace

### 1. Instalace závislostí a DShield honeypotu

```bash
sudo apt install git -y
git clone https://github.com/DShield-ISC/dshield.git
cd dshield/
sudo ./install.sh
```

Během instalačního průvodce je potřeba:

- potvrdit souhlas s přeměnou zařízení na honeypot,
- potvrdit zapojení do výzkumného projektu DShield,
- povolit automatické aktualizace,
- zadat **e-mail a API klíč** z účtu na [isc.sans.edu](https://isc.sans.edu) (sekce *My Account*),
- ponechat výchozí nastavení pro admin přístup a poznamenat si přidělený **admin port** (výchozí `12222`),
- nastavit *ignore rule* (síť, ze které se aktivita nebude logovat – běžně vlastní interní síť),
- vyplnit informace pro SSL certifikát (libovolné/dekorační údaje),
- potvrdit restart Raspberry Pi po dokončení instalace.

### 2. Ověření dostupnosti po instalaci

Po rebootu je SSH přístup přesunut na admin port:

```bash
ssh -p 12222 pi@<hostname>
ifconfig
```

Ověření, že honeypot vystavuje očekávané (uměle zranitelné) porty, lze provést skenem z jiného stroje (např. Kali Linux):

```bash
nmap <IP raspberry pi>
```

Očekává se velké množství otevřených portů (např. 22, 23, 80, 5555, 8000, 8080), protože honeypot je záměrně nakonfigurován tak, aby působil zranitelně.

## Stav projektu

| Komponenta | Stav |
|---|---|
| DShield honeypot | ✅ Nainstalováno |
| Reportování do DShield | ✅ Funkční (ověřeno skenem) |
| Konfigurace pravidel / logování | ⏳ Bude doplněno |
| Analýza sbíraných dat | 🔄 Probíhá (viz [Výsledky a statistiky](#výsledky-a-statistiky)) |

## Výsledky a statistiky

Níže jsou shrnuty data nasbíraná honeypotem za sledované období: **DOPLNIT (od – do)**.

### Hlavní IP adresy a domény útočníků
<img width="1456" height="1064" alt="Screenshot From 2026-09-10 10-16-59" src="https://github.com/user-attachments/assets/f8e28c6f-d929-48e5-a4f5-06c9f36a7994" />


*Popis: DOPLNIT (např. nejaktivnější zdrojové IP, jejich reverzní DNS / domény, případná organizace či ASN).*

### SSH / Telnet hesla

![Nejčastěji zkoušená SSH/Telnet hesla](images/ssh-telnet-passwords.png)

*Popis: DOPLNIT (např. nejčastější kombinace uživatelských jmen a hesel, typické slovníkové útoky).*

### Země původu útoků

![Země původu útoků](images/countries.png)

*Popis: DOPLNIT (např. top země podle počtu pokusů a jejich podíl).*

### Cílené porty

![Nejčastěji cílené porty](images/ports.png)

*Popis: DOPLNIT (např. nejčastěji skenované porty a služby, které na nich útočníci očekávali).*

### Shrnutí

DOPLNIT – hlavní zjištění, zajímavé pozorované vzorce a případná doporučení.

## Poznámky

- Data se v DShield dashboardu (*My Reports*) aktualizují s prodlevou přibližně 30 minut.
- Testování bylo prováděno pouze v kontrolovaném laboratorním prostředí.

---

*Tato sekce bude rozšířena o detailní konfiguraci honeypotu a další analýzu sbíraných výsledků.*
