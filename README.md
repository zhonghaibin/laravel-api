### php版本
需要 PHP 7.3.0 或以上
### 安装依赖包
composer install
### nginx 配置
网站根目录目录指向 pubic  

设置nginx伪静态

`
location / {  
    try_files $uri $uri/ /index.php$is_args$query_string;  
}  
`
### 配置数据库等信息
cp .env.example .env
### 数据库迁移
php artisan migrate

###生成jwt secret
php artisan jwt:secret
