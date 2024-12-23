# 安裝套件
composer install

# 讀取env
. ./.env

# 如果沒有設定，產生APP_KEY
if [ -z "$APP_KEY" ]; then
    php artisan key:generate
    echo "已設定APP_KEY"
else
    echo "APP_KEY已存在"
fi

# 如果沒有設定，產生檔案連結
if [ -L ./public/storage ]; then
    echo "檔案連結已存在"
else
    php artisan storage:link
    echo "已設定檔案連結"
fi

# 運行migration
php artisan migrate

# 啟動FPM
php-fpm