while true; do
    # 執行Laravel的排程
    php artisan schedule:run

    # 每分鐘執行一次
    sleep 60
done