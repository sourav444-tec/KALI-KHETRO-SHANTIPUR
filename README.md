# Kali Khetro Santipur

A bilingual (English and Bengali) community information website highlighting the Kali Puja heritage of Santipur, Nadia, West Bengal.

## Main features

- English and Bengali language interface
- Kali Puja listings with descriptions, locations, map links and photographs
- Puja search and category filtering
- Emergency contacts and local service information
- Media partners, collaborators, sponsors and team sections
- Puja rating and visitor feedback forms
- Admin login and dashboard for managing website content
- Image uploads for Puja, partner and team information
- Responsive dark-themed layout

## Technology

- HTML, CSS and JavaScript
- PHP 8+
- MySQL / MariaDB
- PDO database access

## Local setup with XAMPP

1. Install and start Apache and MySQL from XAMPP.
2. Copy the project folder into `C:\\xampp\\htdocs\\KALI-KHETRO-SANTIPUR`.
3. Open phpMyAdmin and create/import the database using `database/kali_khetro.sql`. The SQL file creates and selects the `kali_khetro_santipur` database.
4. Configure database credentials in `config/database.php`. Do not commit this real configuration file to a public repository.
5. Visit `http://localhost/KALI-KHETRO-SANTIPUR/`.
6. Create the first administrator using `setup-admin.php`, then immediately delete `setup-admin.php` from the server.
7. Sign in at `/admin/login.php` and change the administrator password after the first login.

## Existing database upgrades

The main `database/kali_khetro.sql` file already includes the current schema for a fresh installation. The following scripts are only for upgrading older databases where the corresponding columns or table are not already present:

- `database/upgrade-google-maps-url.sql`
- `database/upgrade-ratings-feedback.sql`

Do not run an upgrade script twice. Back up the database before applying any migration.

## Deployment and security notes

- Keep `config/database.php`, database credentials, `.env` files and backups private. Never commit secrets to GitHub.
- `config/database.example.php` is a safe template; copy it to `config/database.php` and fill in your own credentials where needed.
- Use a strong, unique administrator password.
- Delete `setup-admin.php` immediately after creating the first administrator.
- Keep Apache/PHP/MySQL updated and use HTTPS on the live host.
- Ensure the `uploads` directory is writable by PHP but does not execute uploaded scripts.
- Back up the database and uploaded images before updates.
- Test admin login, Puja create/edit/delete, image uploads, feedback and ratings on the live host before announcing the site.

## Project credits

**Developed by ROYAL TECH**  
**Collaborated by ABOUT SERAMPORE PAGE**

© Kali Khetro Santipur. All rights reserved.


### Collaboration

About Serampore Page: https://www.instagram.com/about_serampore_/
