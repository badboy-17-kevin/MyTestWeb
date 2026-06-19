# 使用 PHP 7.4 官方镜像（带 Apache）
FROM php:7.4-apache

# 安装 MySQL 扩展
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

# 启用 Apache 的 mod_rewrite
RUN a2enmod rewrite

# 复制所有 PHP 文件到容器的网站根目录
COPY . /var/www/html/

# 设置权限
RUN chown -R www-data:www-data /var/www/html/

# 暴露 80 端口
EXPOSE 80