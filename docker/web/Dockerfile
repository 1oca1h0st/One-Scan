# only for dev env
FROM php:8.3.1-cli

# 更新包索引，并安装一些依赖
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libssl-dev \
    pkg-config \
    curl \
    unzip \
    git

# 安装 PHP 扩展：PDO、MySQL、Zip、GD、Multibyte、XML、BC Math 等
RUN docker-php-ext-install pdo pdo_mysql mysqli mbstring exif pcntl bcmath gd zip

# 通过 PECL 安装 Redis 和 MongoDB 扩展并启用
RUN pecl install redis mongodb && \
    docker-php-ext-enable redis mongodb

# 清理安装缓存和临时文件
RUN apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# 设置工作目录
WORKDIR /var/www/html

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
