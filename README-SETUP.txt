KALI KHETRO SANTIPUR — QUICK START (XAMPP)

REQUIREMENTS
- XAMPP with Apache, MySQL and PHP 8+
- A modern browser

INSTALL
1. Extract this folder into C:\xampp\htdocs\kali-khetro-santipur
2. Open XAMPP Control Panel and start Apache and MySQL.
3. Visit http://localhost/phpmyadmin
4. Choose Import, select database/kali_khetro.sql, then click Import/Go.
   (The SQL file creates the database named kali_khetro_santipur.)
5. Check config/database.php. Default XAMPP values are localhost / root / blank password.
6. Open http://localhost/kali-khetro-santipur/setup-admin.php
7. Create your admin username and a strong password (10+ characters).
8. IMPORTANT: Delete setup-admin.php immediately after the admin account is created.
9. Open http://localhost/kali-khetro-santipur/admin/login.php
10. Add your Puja records, services, emergency contacts, partners, sponsors and team members.
11. Put three of your own landscape Kali Puja photos in assets/images named hero-1.jpg, hero-2.jpg and hero-3.jpg.

FEATURES
- English/Bengali text toggle for marked interface elements
- Automatic hero slideshow
- Search and category filter for published Puja records
- Manually managed Puja records and cover photos
- Local service / transport entries managed in dashboard
- SOS modal with 112 and admin-managed emergency contacts
- Partner, collaborator, sponsor and team sections
- Visitor feedback form and admin feedback view
- Password change, session login, CSRF checks and prepared statements

IMPORTANT
- This is a local starter build. Before public deployment, enable HTTPS, use a strong unique admin password, configure database credentials securely, back up the database, and review upload permissions.
- Emergency numbers and local service information must be verified by the site team. 112 is India's emergency response number; do not imply every local service has the same direct line.
- Local route links open OpenStreetMap at admin-entered coordinates. This starter does not calculate turn-by-turn routing automatically.
- Some text is bilingual-ready; content that admins enter should be supplied in both languages for complete translation.
- If your PHP build lacks the mbstring extension, enable it in php.ini or replace mb_strtolower/mb_substr uses with suitable alternatives.


NEW: PUJA RATINGS + IMPROVED FEEDBACK
- New installs: import database/kali_khetro.sql as usual. It includes the puja_ratings table and extra feedback fields.
- Existing installs: in phpMyAdmin select the kali_khetro_santipur database, open the Import tab, and import database/upgrade-ratings-feedback.sql ONCE. Do not run the migration twice because its ALTER TABLE statements add columns.
- Visitors can submit a 1-5 rating for each Puja. One rating per Puja per browser session is enforced; this is a basic duplicate-vote deterrent, not a secure identity check.
- Feedback now supports type, optional 1-5 experience rating, and is visible in the Admin Dashboard.
- The map still needs accurate latitude/longitude for each Puja/service entered by the admin.


GOOGLE MAPS LOCATION URL FOR EACH PUJA
1. Open Admin dashboard and use the Add a Puja form.
2. In Google Maps, search for the exact Puja/pandal, choose Share, then Copy link.
3. Paste that link into the Google Maps Location URL field and save the Puja.
4. Visitors will see a Google Maps link in the Puja details and on that Puja's map marker (when coordinates are also entered).

If you imported the database before this update, open phpMyAdmin, select kali_khetro_santipur, click Import, and import database/upgrade-google-maps-url.sql once. If creating a fresh database, import database/kali_khetro.sql instead; do not run the upgrade file as well.
