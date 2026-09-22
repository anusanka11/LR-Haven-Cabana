# Lion Rock Haven Cabana — Website + Booking Database

A responsive HTML/CSS/JavaScript + PHP/MySQL website for Lion Rock Haven Cabana, Sigiriya.

## Included
- Modern responsive landing page
- Property description and facilities
- Photo gallery + Sigiriya video embed
- Booking.com button
- WhatsApp booking form with check-in/check-out calendar inputs
- Booking request saved to MySQL database
- Admin login and booking dashboard
- Booking status management + CSV export + delete
- Mobile-friendly navigation and lightweight JS

## Local setup (XAMPP / WAMP)
1. Copy the project folder into your web server folder, for example `C:/xampp/htdocs/lion-rock-haven-cabana/`.
2. Start Apache and MySQL.
3. Open phpMyAdmin and import `sql/database.sql`.
4. Check `config.php`. Default XAMPP values are already set (`root` with a blank password).
5. Open: `http://localhost/lion-rock-haven-cabana/`
6. Admin: `http://localhost/lion-rock-haven-cabana/admin/login.php`

## Admin login
Username: `Admin`
Password: `Admin`

The password is stored as a PHP password hash in the database. Change the credentials before putting the site on a public server.

## Live hosting
- Create a MySQL database and import `sql/database.sql`.
- Put the website files into your hosting public web directory.
- Update DB credentials in `config.php`.
- Enable HTTPS.
- Keep the `/admin` area private and change the sample admin password.

## Property media
The first version uses selected Sigiriya destination images from Wikimedia Commons and a YouTube aerial video as sample media. Replace the image/video URLs in `index.html` with your own property photos/videos before the public launch.
