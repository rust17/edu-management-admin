# 教务管理系统

基于 Laravel 和 Laravel-admin 开发的教务管理系统，提供课程管理、学生管理、教师管理等功能。

## 主要特点

### 1. 基于角色的权限管理
- 系统管理员：可以管理所有功能
- 教师：可以管理学生信息
- 基于自定义的权限管理中间件
- 菜单和路由的统一权限控制

### 2. 用户管理
- 教师管理：仅系统管理员可访问
- 学生管理：系统管理员和教师可访问
- 用户信息的增删改查

### 3. 课程管理
- 课程信息的完整管理
- 教师与课程的关联
- 学生选课管理
- 课程费用管理

### 4. 账单管理
- 学生课程费用账单
- 支付状态跟踪
- 账单历史记录

### 5. 界面定制
- 响应式设计
- 清晰的数据展示
- 简洁的操作流程

## 技术栈

- PHP 7.1+
- Laravel 5.5
- Laravel-admin 1.8
- PostgreSQL 12+

## 安装

1. 克隆代码

2. 安装依赖
```bash
composer install --no-dev
```

3. 配置环境
```bash
cp .env.example .env
php artisan key:generate
```

4. 配置数据库
```bash
echo "DB_CONNECTION=pgsql
DB_HOST=your_host
DB_PORT=your_port
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password" >> .env

php artisan migrate
php artisan db:seed # 如果需要初始化数据就执行
```

5. laravel-admin 配置强制 HTTPS
```bash
ADMIN_HTTPS=true
```

6. 初始化菜单数据
```sql
1. 初始化管理后台超级管理员用户
insert into "public"."admin_users" ("avatar", "created_at", "id", "name", "password", "remember_token", "role", "updated_at", "user_id", "username") values (NULL, '2025-01-15 12:41:11', 1, 'Administrator', '$2y$10$sxUptBd288bndbZkEV7JQu6XmwO891hX3PGHwczBkObh44.kLNPdO', NULL, 'admin', '2025-01-15 12:41:11', NULL, 'admin')
2. 初始化菜单数据
insert into "public"."admin_menu" ("created_at", "icon", "id", "order", "parent_id", "permission", "title", "updated_at", "uri") values ('2025-01-11 16:31:34', 'fa-book', 1, 0, 0, 'admin', '教师管理', '2025-01-11 23:00:32', NULL), ('2025-01-11 16:32:04', 'fa-bars', 2, 0, 1, 'admin', '教师列表', '2025-01-11 23:00:51', 'teachers'), ('2025-01-11 16:32:14', 'fa-users', 3, 0, 0, 'admin,teacher', '学生管理', '2025-01-11 23:03:03', NULL), ('2025-01-11 16:32:21', 'fa-bars', 4, 0, 3, 'admin,teacher', '学生列表', '2025-01-11 16:32:21', 'students')
3. 如果是 PostgreSQL 需要手动调整序列避免主键冲突
SELECT setval('admin_users_id_seq', (SELECT MAX(id) FROM admin_users) + 1);
SELECT setval('admin_menu_id_seq', (SELECT MAX(id) FROM admin_menu) + 1);
```

7. 访问服务

nginx 配置域名指向 public/index.php，访问 http://your_domain 即可

## Docker 部署

1. **构建镜像**
```bash
docker build -t edu-management-admin .
```

2. **运行容器**
部署：
```bash
docker run -d \
    --name edu-admin \
    -p 8081:80 \
    -v $(pwd)/.env:/var/www/html/.env \ <------- 可以通过挂载 .env 文件提供 Laravel 所需的环境变量
    -e INIT_ADMIN_PASS={password} \     <------- 如果需要初始化管理员密码
    -e INIT_ADMIN_MENU=true \           <------- 如果需要初始化管理后台菜单
    -e APP_NAME={APP_NAME}              <------- 也可以通过 -e 提供环境变量
    -e APP_KEY={APP_KEY}                <------- 也可以通过 -e 提供环境变量
    -e APP_ENV=production               <------- 也可以通过 -e 提供环境变量
    -e APP_DEBUG=false                  <------- 也可以通过 -e 提供环境变量
    -e 你的环境变量...
    edu-management-admin
```

3. **访问服务**
服务将在 http://localhost:8081 上运行。

4. **查看初始化日志**
```bash
docker logs edu-admin
```

### 部署说明

- 通过设置 `INIT_ADMIN_PASS`、`INIT_ADMIN_MENU` 环境变量来初始化管理员密码和菜单
- 既可以通过挂载 `.env` 文件来提供环境变量，也可以通过 docker 的 `-e` 参数来提供环境变量，这些环境变量会覆盖 `.env` 文件中的配置

## License

The MIT License (MIT).
