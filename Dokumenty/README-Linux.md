# Řízení softwarových projektů

Návod pro zprovoznění na některých linuxových dsitribucích.

Instalace jednotlivých kompoment dosti záleží na zvolené distribuci a používaném balíčkovém systému. Obecně je ale většina potřebných nástrojů dostupná napříč distribucemi.

## Potřebný SW

    Git
        sudo apt install git (Ubuntu, Debian),
        sudo dnf install git (Fedora)

    Symfony v 4.4.12 
        https://symfony.com/download
    Yarn 
        https://classic.yarnpkg.com/en/docs/install/#debian-stable
        sudo dnf install nodejs-yarn
    PHP
        sudo dnf install php php-mysql
    MariadDB
        sudo apt install mariadb-server (Ubuntu)
        sudo systemctl start mariadb (Ubuntu)
        sudo dnf install mariadb mariadb-server (Fedora)
        sudo systemctl start mariadb (Fedora)

## Volitelně SW
    
### Gitg 
Grafická nadstavba pro git, primáně používám pouze na zobrazení grafu gitu.
    
    Instalace
        sudo apt install gitg
        sudo dnf install gitg
    Konfigurace
        Není potřeba
    Použtítí
        Stačí se přesunout do adreáře kde máme git repozítář a spusit příkaz gitg. 
        Popřípadě je možno oetvřít přes GUI.

### PHP Storm
Existuje jako snap balík popřípadě se dá stahnout jako tar archiv z oficiálních stránek.

 - https://www.jetbrains.com/phpstorm/
 - https://www.jetbrains.com/phpstorm/nextversion/
 - https://snapcraft.io/phpstorm


    Instalace:
        Snap balíček viz google.com dá se najít plno návodů.
        Archiv z oficiálních stránke stačí rozbalit a sputit script bin/phpstorm.sh
    Konfigrace
        Je možné si načíst sdílenou konfiguraci viz data na společném githubu. Osobně nedoporučuji změnilo mi to i barevní schéma atp. Asi záleží na každém. Jinak stejný postutp jako na Windows.

### Apache, Nginx – webové servery

    Instalace
        sudo dnf install httpd
        sudo dnf install nginx

### MySQL Workbecnh, Adminer, CLI client, Php Storm, Dbeaver ... - správa databáze
Nástrojů pro správu databáze je poměrně velké množstí, já osobně používám Dbeaver. Jejich nastavení zde popisovat nebudu. Jelikož každý nejspíše bude používat jemu nejbližší nástroj. Propojení Php Stormu a databáze je již zmíněno v návodu pro  Windows.

## Zprovoznění

 1) Mít připravené programy které bude chtít každý jednotlivec využívat.
 2) Naklonovat nebo stáhnout repo z Gthubu.
    1) git clone git@github.com:novak55/rsp.git
    2) Je možné využít klonování v Php Stormu. 
 3) Vytvoření databáze ze složky SQL/init.sql 
 4) Otevření zdrojových kódů v Php Stormu.
 5) Přejitít do složky projektu.
 6) Překopírovat .env.local soubor do rootu projektu
 7) composer install
 8) yarn install
 9) yarn dev
 10) Lokální CA: "symfony server:ca:install" (není potřeba)
 11) Spuštění symfony v terminálu: "symfony server:start" 