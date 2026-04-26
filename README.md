# vypozicovna hier
    Jednoduchý systém na správu a vypožičiavanie hier vyvinutý v PHP a MySQL s využitím Bootstrap frameworku. Projekt umožňuje používateľom registrovať sa, prehliadať katalóg hier, pridávať nové tituly a spravovať svoje výpožičky.

Funkcie
    Autentifikácia: Kompletný systém registrácie, prihlásenia a odhlásenia používateľov.

    Správa hesiel: Možnosť resetovania/zmeny hesla pri strate prístupu.

    Katalóg hier: Prehľadná tabuľka všetkých dostupných hier s informáciami o platforme a stave.

    Systém výpožičiek: * Používateľ si môže vypožičať hru, ktorá je v stave "voľné".

    Používateľ môže vrátiť iba tú hru, ktorú má aktuálne požičanú.

    Ostatní používatelia vidia, kto má hru momentálne vypožičanú.

Pridávanie obsahu: Možnosť pridávať nové hry do databázy priamo cez webové rozhranie.

Technické požiadavky
    Web server: Apache (odporúčané cez XAMPP, WAMP alebo MAMP).

    Databáza: MySQL / MariaDB.

    Jazyk: PHP 7.4+.

    Frontend: Bootstrap 5 (cez CDN).

Štruktúra súborov
    database.sql – Definícia tabuliek (users, hry, vypozicky) a testovacie dáta.

    index.php – Hlavná stránka (Login / Dashboard s katalógom).

    Register.php – Registračný formulár pre nových členov.

    zmena_hesla.php – Funkcia na obnovu a zmenu hesla.

    pridaj_hru.php – Backend logika pre spracovanie formulára na pridanie novej hry.

Inštalácia
    Import databázy:

    Otvorte rozhranie phpMyAdmin.

    Vytvorte novú databázu s názvom pozicovna.

    Importujte súbor database.sql.

Konfigurácia pripojenia:

    V súboroch index.php, Register.php, zmena_hesla.php a pridaj_hru.php skontrolujte údaje k pripojeniu k databáze:

    PHP
    mysqli_connect("localhost", "root", "root", "pozicovna");
        (Poznámka: Ak používate XAMPP na Windows, heslo je zvyčajne prázdny reťazec "").

Spustenie:

    Skopírujte priečinok s projektom do priečinka htdocs vášho servera.

    Do prehliadača zadajte localhost/nazov_priečinka/index.php.

Databázová schéma
Aplikácia využíva tri prepojené tabuľky:

        users: Uchováva údaje o používateľoch (meno, email, telefón, heslo).

        hry: Obsahuje zoznam hier, ich žáner, platformu a aktuálny stav (voľné/vypožičané).

        vypozicky: Prepájacia tabuľka, ktorá spája user_id s hra_id a zaznamenáva čas výpožičky.

Dôležité upozornenie (Bezpečnosť)
    Tento projekt slúži ako školské/vzdelávacie zadanie a v súčasnej podobe obsahuje niekoľko bezpečnostných rizík, ktoré by sa v produkčnej aplikácii nemali vyskytovať:

    SQL Injection: Dotazy nie sú ošetrené cez prepared statements.

    Plain-text heslá: Aplikácia ukladá heslá v čitateľnej podobe (hoci index.php čiastočne počíta s password_verify).

    Cookies: Autentifikácia prebieha cez nezašifrované cookies, čo je náchylné na zneužitie.

Autori: Maxim Milaňák, Samuel Seman