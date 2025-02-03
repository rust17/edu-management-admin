# Education Management System

An education management system developed with Laravel and Laravel-admin, providing course management, student management, teacher management, and other features.

## Key Features

### 1. Role-based Permission Management
- System Administrator: Can manage all features
- Teachers: Can manage student information
- Custom permission management middleware
- Unified permission control for menus and routes

### 2. User Management
- Teacher Management: Accessible only by system administrators
- Student Management: Accessible by system administrators and teachers
- CRUD operations for user information

### 3. Course Management
- Complete course information management
- Teacher-course association
- Student course enrollment management
- Course fee management

### 4. Invoice Management
- Student course fee invoices
- Payment status tracking
- Invoice history

### 5. Interface Customization
- Responsive design
- Clear data presentation
- Streamlined operation process

## Tech Stack

- PHP 7.1+
- Laravel 5.5
- Laravel-admin 1.8
- PostgreSQL 12+

## Installation

1. Clone the repository

2. Install dependencies
```bash
composer install --no-dev
```

3. Configure environment
```bash
cp .env.example .env
php artisan key:generate
```

4. Configure database
```bash
echo "DB_CONNECTION=pgsql
DB_HOST=your_host
DB_PORT=your_port
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password" >> .env

php artisan migrate
php artisan db:seed # Run if you need to initialize data
```

5. Configure Laravel-admin force HTTPS
```bash
ADMIN_HTTPS=true
```

6. Initialize menu data
```sql
1. Initialize admin super administrator user
insert into "public"."admin_users" ("avatar", "created_at", "id", "name", "password", "remember_token", "role", "updated_at", "user_id", "username") values (NULL, '2025-01-15 12:41:11', 1, 'Administrator', '$2y$10$sxUptBd288bndbZkEV7JQu6XmwO891hX3PGHwczBkObh44.kLNPdO', NULL, 'admin', '2025-01-15 12:41:11', NULL, 'admin')

2. Initialize menu data
insert into "public"."admin_menu" ("created_at", "icon", "id", "order", "parent_id", "permission", "title", "updated_at", "uri") values
('2025-01-11 16:31:34', 'fa-book', 1, 0, 0, 'admin', 'Teacher Management', '2025-01-11 23:00:32', NULL),
('2025-01-11 16:32:04', 'fa-bars', 2, 0, 1, 'admin', 'Teacher List', '2025-01-11 23:00:51', 'teachers'),
('2025-01-11 16:32:14', 'fa-users', 3, 0, 0, 'admin,teacher', 'Student Management', '2025-01-11 23:03:03', NULL),
('2025-01-11 16:32:21', 'fa-bars', 4, 0, 3, 'admin,teacher', 'Student List', '2025-01-11 16:32:21', 'students')

3. For PostgreSQL, manually adjust sequences to avoid primary key conflicts
SELECT setval('admin_users_id_seq', (SELECT MAX(id) FROM admin_users) + 1);
SELECT setval('admin_menu_id_seq', (SELECT MAX(id) FROM admin_menu) + 1);
```

7. Access the service

Configure nginx to point the domain to public/index.php, then access http://your_domain

## Docker Deployment

1. **Build Image**
```bash
docker build -t edu-management-admin .
```

2. **Run Container**
Deploy:
```bash
docker run -d \
    --name edu-admin \
    -p 8081:80 \
    -v $(pwd)/.env:/var/www/html/.env \ <------- Mount .env file to provide Laravel environment variables
    -e INIT_ADMIN_PASS={password} \     <------- Initialize admin password if needed
    -e INIT_ADMIN_MENU=true \           <------- Initialize admin menu if needed
    -e APP_NAME={APP_NAME}              <------- Can also provide environment variables via -e
    -e APP_KEY={APP_KEY}                <------- Can also provide environment variables via -e
    -e APP_ENV=production               <------- Can also provide environment variables via -e
    -e APP_DEBUG=false                  <------- Can also provide environment variables via -e
    -e YOUR_ENV_VARS...
    edu-management-admin
```

3. **Access Service**
The service will run at http://localhost:8081

4. **View Initialization Logs**
```bash
docker logs edu-admin
```

### Deployment Notes

- Initialize admin password and menu by setting `INIT_ADMIN_PASS` and `INIT_ADMIN_MENU` environment variables
- Environment variables can be provided either by mounting the `.env` file or using docker's `-e` parameter. These environment variables will override the configurations in the `.env` file

## License

The MIT License (MIT).
