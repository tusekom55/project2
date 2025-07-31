<?php
// Veritabanı bağlantı ayarları
$DB_HOST = 'localhost';
$DB_USER = 'u225998063_tug01';
$DB_PASS = '123456Tubb';
$DB_NAME = 'u225998063_proje';

// PDO Bağlantı fonksiyonu (hata kontrolü ile)
function db_connect() {
    global $DB_HOST, $DB_USER, $DB_PASS, $DB_NAME;
    
    try {
        $dsn = "mysql:host={$DB_HOST};dbname={$DB_NAME};charset=utf8mb4";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
        ];
        
        $conn = new PDO($dsn, $DB_USER, $DB_PASS, $options);
        return $conn;
        
    } catch (PDOException $e) {
        // Production'da gerçek hata mesajını gösterme
        error_log('Database connection error: ' . $e->getMessage());
        
        // Test modu için detaylı hata, production'da generic mesaj
        if (defined('DEBUG_MODE') && DEBUG_MODE) {
            die(json_encode(['error' => 'Veritabanı bağlantı hatası: ' . $e->getMessage()]));
        } else {
            die(json_encode(['error' => 'Veritabanı bağlantısı kurulamadı']));
        }
    }
}

// Debug modu (test için)
define('DEBUG_MODE', true);

// Test modu session değerleri
if (!isset($_SESSION['user_id']) && defined('DEBUG_MODE') && DEBUG_MODE) {
    $_SESSION['user_id'] = 1; // Test kullanıcısı
    $_SESSION['role'] = 'user';
}

// Oturum yönetimi - çakışmaları önle
if (session_status() === PHP_SESSION_NONE) {
    session_start();
} 