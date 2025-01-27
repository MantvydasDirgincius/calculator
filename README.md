# 📟 Skaičiuotuvas

**Skaičiuotuvas** – tai Laravel projektas su Vue.js ir Inertia.js integracija, skirtas matematinėms operacijoms atlikti ir jų istorijai išsaugoti.

---

## 🚀 Projekto funkcionalumas

-   ✅ Skaičiavimo funkcijos su įvairiomis operacijomis.
-   📝 Istorijos sekimas ir rodymas (paskutiniai 10 skaičiavimų).
-   🧮 Backend skaičiavimai, saugomi duomenų bazėje.

---

## 💻 Reikalavimai

Prieš pradedant naudoti projektą, įsitikinkite, kad jūsų sistema atitinka šiuos reikalavimus:

-   **PHP** 8.1 ar naujesnė versija
-   **Composer** (PHP paketų valdymo įrankis)
-   **Node.js** ir **npm** (JavaScript paketų valdymui)
-   **MySQL** arba kita palaikoma duomenų bazė
-   **MAMP** arba kita lokali serverio aplinka (jei naudojate lokaliai)

---

## 📂 Projekto paleidimas

Sekite šiuos žingsnius, kad paleistumėte projektą:

### 1️⃣ Projekto klonavimas

Klonuokite projektą iš savo „Git“ saugyklos:

```bash
git clone https://github.com/MantvydasDirgincius/calculator.git
cd projektas
```

### 2️⃣ .env failo nustatymas

Sukurkite .env failą pagal .env.example:

```bash
cp .env.example .env
```

Atnaujinkite .env failo reikšmes (pvz., duomenų bazės parametrus):

```bash
DB_DATABASE=skaiciuotuvas
DB_USERNAME=root
DB_PASSWORD=root
APP_URL=http://localhost:8000
```

### 3️⃣ Priklausomybių įdiegimas

Įdiekite projekto priklausomybes naudodami Composer ir npm:

```bash
composer install
npm install
```

### 4️⃣ Duomenų bazės migracijos

Paleiskite migracijas, kad sukurti lenteles duomenų bazėje:

```bash
php artisan migrate
```

### 5️⃣ Frontend resursų paruošimas

Norėdami paleisti projekto front-end dalį, naudokite šią komandą:

```bash
npm run dev
```

### 6️⃣ Projekto paleidimas

Paleiskite Laravel serverį:

```bash
php artisan serve
```

Atidarykite naršyklėje:

http://localhost:8000
