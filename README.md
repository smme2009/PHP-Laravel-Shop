# 專案資訊

## 版本資訊

laravel: 10  
php: 8.3.\*

## 本地環境

### 初始化(首次啟動時使用)

```bash
composer install # 安裝套件
php artisan key:generate # 產生加密金鑰
php artisan storage:link # 產生檔案符號連結
php artisan migrate # 執行Migration
```

### 啟動本地環境

```bash
php artisan serve
```

## 部屬

### 新增 env 並設定參數

```bash
cp .env.example .env
```

複製完成後記得設定 DB 帳密相關的參數

### 啟動專案

```bash
docker-compose up
```
