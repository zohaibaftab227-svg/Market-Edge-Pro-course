# Market Edge Pro - Complete PHP Website

## Admin Login
- URL: `http://localhost/marketedgepro/admin/login.php`
- Username: `admin`
- Password: `admin123`

## Setup Instructions

### Step 1: Database Setup
1. Open phpMyAdmin
2. Click "Import"
3. Upload `database.sql`
4. Click "Go"

### Step 2: Configure Site URL
Open `includes/config.php` and update:
```php
define('SITE_URL', 'http://localhost/marketedgepro'); // Change to your domain
define('DB_USER', 'root');  // Your DB username
define('DB_PASS', '');      // Your DB password
```

### Step 3: Upload Files
Place this folder in `htdocs` (XAMPP) or `www` (WAMP):
- XAMPP: `C:/xampp/htdocs/marketedgepro/`
- Live server: `/public_html/marketedgepro/`

### Step 4: Access the Site
- Website: `http://localhost/marketedgepro/`
- Admin: `http://localhost/marketedgepro/admin/login.php`

## File Structure
```
marketedgepro/
├── index.php          - Home page
├── courses.php        - All courses with cart
├── blog.php           - Blog listing
├── blog-single.php    - Blog post detail
├── contact.php        - Contact form
├── cart.php           - Shopping cart
├── enroll.php         - Enrollment form
├── enroll-success.php - Success page
├── database.sql       - Run this in phpMyAdmin
├── includes/
│   ├── config.php     - DB config, functions
│   ├── header.php     - Navbar + head
│   └── footer.php     - Footer + WhatsApp
├── admin/
│   ├── login.php      - Admin login
│   ├── dashboard.php  - Stats & overview
│   ├── courses.php    - CRUD for courses
│   ├── enrollments.php - Manage enrollments
│   ├── blog.php       - Blog CRUD
│   └── contacts.php   - View messages
└── assets/
    ├── css/style.css  - All styles
    └── js/main.js     - All JS (fixed)
```

## Default Admin Credentials
- **Username:** admin
- **Password:** admin123
