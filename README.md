
admin_panel.php
all_snacks.php
cart.php
checkout.php
completed_orders.php
contact.php
customer_dashboard.php
customer_dashboard_view.php
db_connect.php
delete_drink.php
delete_food.php
delete_snack.php
delete_user.php
drinks.php
edit_drink.php
edit_food.php
edit_snack.php
edit_user.php
index.php
login.php
logout.php
menu.php
orders.php
pending_orders.php
cafe.sql
...
```

## Database

- The database schema is provided in [`cafe.sql`](cafe.sql).
- Main tables: `users`, `menu_items`, `orders`, etc.

## Setup Instructions

1. **Clone or Download the Project**

2. **Database Setup**
   - Import the [`cafe.sql`](cafe.sql) file into your MySQL server.

3. **Configure Database Connection**
   - Edit [`db_connect.php`](db_connect.php) with your MySQL credentials.

4. **Image Uploads**
   - Ensure the `images/` directory exists and is writable for menu/user images.

5. **Run the Application**
   - Place the project in your web server directory (e.g., `htdocs` for XAMPP).
   - Access via `http://localhost/masenocafeteria-main/index.php`.

## Default Admin Login

- Create an admin user directly in the database or via the registration form (if available).
- Admin users have `role = 'admin'` in the `users` table.

## File Overview

- [`add_food.php`](add_food.php), [`add_drink.php`](add_drink.php), [`add_snack.php`](add_snack.php): Add menu items.
- [`edit_food.php`](edit_food.php), [`edit_drink.php`](edit_drink.php), [`edit_snack.php`](edit_snack.php): Edit menu items.
- [`delete_food.php`](delete_food.php), [`delete_drink.php`](delete_drink.php), [`delete_snack.php`](delete_snack.php): Delete menu items.
- [`add_user.php`](add_user.php), [`edit_user.php`](edit_user.php), [`delete_user.php`](delete_user.php): Manage users.
- [`cart.php`](cart.php), [`checkout.php`](checkout.php): Customer cart and checkout.
- [`admin_panel.php`](admin_panel.php), [`admin_dashboard.php`](admin_dashboard.php): Admin dashboard and controls.

## Dependencies

- PHP 7.x or above
- MySQL/MariaDB
- Web server (Apache recommended)
- [Bootstrap 4](https://getbootstrap.com/) (CDN included in HTML)

## License

This project is for educational purposes.

---

**Developed for Maseno University Cafeteria**
