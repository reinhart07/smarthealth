# 🏥 SmartHealth — Setup Guide
## INFEST Hackathon 2026 | AI & Data Track

---

## 📋 Prasyarat
- XAMPP (PHP 8.0+, MySQL, Apache)
- Python 3.8+
- pip packages: flask flask-cors scikit-learn numpy pickle5

---

## 🚀 Cara Setup

### 1. Setup PHP (XAMPP)
```
Letakkan folder `smarthealth/` di:
C:/xampp/htdocs/smarthealth/
```

### 2. Setup Database
```sql
-- Buka phpMyAdmin → Import file:
smarthealth/smarthealth.sql
```

### 3. Setup Python & Flask API
```bash
cd smarthealth/api/

# Install dependencies
pip install flask flask-cors scikit-learn numpy

# Jalankan Flask API (setelah model .pkl tersedia dari Colab)
python predict.py
```

### 4. Salin file dari Colab ke folder api/
Setelah menjalankan notebook Colab, download dan letakkan di `smarthealth/api/`:
- `smarthealth_model.pkl`
- `smarthealth_scaler.pkl`
- `encoding_map.json`

### 5. Jalankan Aplikasi
```
Buka browser: http://localhost/smarthealth/
```

---

## 📁 Struktur File
```
smarthealth/
├── index.php              ← Landing page
├── config.php             ← Konfigurasi DB & URL
├── smarthealth.sql        ← Schema database
├── auth/
│   ├── login.php
│   ├── register.php
│   └── logout.php
├── pages/
│   ├── dashboard.php      ← Dashboard + charts
│   ├── predict.php        ← Form prediksi
│   ├── result.php         ← Hasil + rekomendasi + PDF
│   └── history.php        ← Riwayat prediksi
├── includes/
│   ├── db.php
│   ├── header.php
│   ├── footer.php
│   └── auth_check.php
└── api/
    ├── predict.py          ← Flask ML API (jalankan terpisah)
    ├── predict_handler.php ← PHP bridge ke Flask
    ├── smarthealth_model.pkl   ← (dari Colab)
    ├── smarthealth_scaler.pkl  ← (dari Colab)
    └── encoding_map.json       ← (dari Colab)
```

---

## 🔑 Login Default (Admin)
- **Email:** admin@smarthealth.id
- **Password:** password

*Catatan: Password default di SQL menggunakan hash Laravel. Ganti via register atau update manual.*

---

## ✅ Checklist Sebelum Demo
- [ ] XAMPP Apache & MySQL berjalan
- [ ] Database `smarthealth` sudah di-import
- [ ] File .pkl sudah ada di folder `api/`
- [ ] Flask API berjalan: `python api/predict.py`
- [ ] Cek health endpoint: http://localhost:5000/health
- [ ] Buka aplikasi: http://localhost/smarthealth/
