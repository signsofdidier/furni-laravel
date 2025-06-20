# 🛋️ Furni – E-commerce Eindproject - Didier Vanassche

Dit is het eindwerk voor het vak Webontwikkeling: een volledige Laravel-gebaseerde e-commerce webshop met Stripe-integratie, gebruikersauthenticatie, beheerderspaneel en productbeheer.

---

## ✅ Features

- Producten browsen en detailpagina’s  
- Winkelwagen en afrekenen  
- Stripe-betalingen (testmodus)  
- Gebruikersregistratie & login  
- Adminpaneel met productbeheer  
- E-mailmeldingen (via Mailpit of lokaal)  
- AI-integratie met Groq  
- Veel meer coole features om te ontdekken!

---

## ⚙️ Vereisten

Zorg dat volgende tools geïnstalleerd zijn:

- PHP >= 8.2  
- Composer  
- Node.js en NPM  
- MySQL  
- Laravel CLI  
- Stripe testaccount ([https://stripe.com](https://stripe.com))  
- Groq account ([https://console.groq.com](https://console.groq.com))  
- Mailpit ([https://github.com/axllent/mailpit](https://github.com/axllent/mailpit))

---

## 🚀 Installatie-instructies

### 1. Repository klonen

```bash
git clone https://github.com/<jouw-gebruikersnaam>/furni.git  
cd furni
```

### 2. Dependencies installeren

```bash
composer install  
npm install  
composer run dev
```

### 3. .env bestand instellen

```bash
cp .env.example .env
```

### 4. .env configureren

Pas het `.env` bestand aan zoals hieronder:

```env
APP_NAME="Furni"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ecommerce_eindwerk
DB_USERNAME=root
DB_PASSWORD=

STRIPE_KEY=pk_test_...
STRIPE_SECRET=sk_test_...

GROQ_API_KEY=gsk_...

MAIL_MAILER=smtp
MAIL_HOST=127.0.0.1
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=test@local.dev
MAIL_FROM_NAME="Furni Ture"
```

✅ **Tip:** Gebruik Mailpit via `http://localhost:8025` om lokaal e-mails te testen.

---

### 5. Applicatiesleutel genereren

```bash
php artisan key:generate
```

### 6. Database migreren + seeden

```bash
php artisan migrate:fresh --seed
```

### 7. Server starten

```bash
php artisan serve
```

Of start dev script:

```bash
composer run dev
```

---

## 🔐 Admin login

- E-mail: `admin@gmail.com`  
- Wachtwoord: `password`

Voor andere rollen: controleer de gebruikers in de database. Alle wachtwoorden zijn `password`.

---

## 💳 Stripe testgegevens

Gebruik deze testgegevens bij afrekenen:

- Kaartnummer: `4242 4242 4242 4242`  
- Vervaldatum: toekomstige datum  
- CVC: 3 cijfers  
- Postcode: willekeurig  

Zorg dat `STRIPE_KEY` en `STRIPE_SECRET` correct ingevuld zijn in `.env`.

---

## ℹ️ Opmerkingen

- Mail-functionaliteit werkt alleen als Mailpit draait of je een andere SMTP-provider hebt ingesteld.  
- Herstart `composer run dev` na wijzigingen in `.env`.  
- Voer `php artisan config:clear` uit bij cachingproblemen.

---
