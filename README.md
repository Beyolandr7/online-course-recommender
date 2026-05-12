# 🎓 Online Course Recommendation System (PJK-GM078)

Sebuah platform web cerdas berbasis **Microservices Architecture** yang memanfaatkan _Artificial Intelligence_ (AI) untuk merekomendasikan kursus daring (_online course_) secara personal. Sistem ini dibangun untuk mengatasi kebingungan pengguna di tengah melimpahnya pilihan _platform e-learning_ serta menekan angka putus belajar (_dropout rate_).

Proyek ini merupakan hasil kolaborasi tim **PJK-GM078** untuk program Capstone Project Pijar x IBM SkillsBuild.

---

## ✨ Fitur Utama

- **Cross-Platform Catalog:** Menggabungkan dataset publik dari Coursera dan Udemy untuk menghasilkan rekomendasi yang bervariasi dan utuh.
- **Microservice Architecture:** Pemisahan tugas secara efisien antara Laravel (logika web & autentikasi pengguna) dan FastAPI (pemrosesan analitik dan _Machine Learning_).

---

## 🛠️ Teknologi yang Digunakan

**Web Platform (Main Backend & Frontend)**

- [Laravel 12](https://laravel.com/) (PHP)
- Laravel Blade & Tailwind CSS (UI/UX)
- MySQL / SQLite (Database)

**AI Service (Machine Learning Backend)**

- [FastAPI](https://fastapi.tiangolo.com/) (Python)
- Pandas & Scikit-Learn (Data Wrangling & Similarity Engine)
- Uvicorn (ASGI Server)

---

## 📂 Struktur Repositori (Monorepo)

    online-course-recommender/
    ├── web-platform/       # Proyek Web Laravel (UI & Logic)
    ├── ai-service/         # Microservice API berbasis FastAPI
    ├── datasets/           # Folder dataset Kaggle (Coursera & Udemy)
    └── docs/               # Dokumen Project Plan & Project Brief

---

## 🚀 Panduan Instalasi (Localhost)

Pastikan laptop Anda sudah terinstal **PHP 8.2+**, **Composer**, **Python 3.10+**, dan **Git**.

### 1. Clone Repositori

    git clone https://github.com/username-anda/nama-repo-anda.git
    cd online-course-recommender

### 2. Setup AI Service (FastAPI)

Buka terminal baru dan masuk ke folder servis AI:

    cd ai-service

    # Buat virtual environment agar sistem utama tetap bersih
    python -m venv .venv

    # Aktifkan virtual environment (Windows)
    .venv\Scripts\activate

    # Instal semua dependensi Machine Learning
    pip install -r requirements.txt

    # Jalankan server FastAPI
    uvicorn main:app --reload --port 8001

_API Machine Learning sekarang berjalan di `http://localhost:8001` (Cek dokumentasi interaktif di `http://localhost:8001/docs`)._

### 3. Setup Web Platform (Laravel 12)

Buka tab terminal baru (biarkan server FastAPI tetap berjalan), dan masuk ke folder web:

    cd web-platform

    # Instal dependensi PHP
    composer install

    # Salin file environment dan atur konfigurasi database
    cp .env.example .env

    # Generate application key
    php artisan key:generate

    # Jalankan server Laravel
    php artisan serve

_Aplikasi web utama sekarang bisa diakses melalui browser di `http://localhost:8000`._

---

## 👥 Tim Pengembang (AI Engineer Learning Path)

1. **Darren Stanford Soputra** (APC320D6Y0012) - Koordinasi Tim, Preprocessing & Evaluasi
2. **Chellyne Yuwono** (APC320D6X0127) - Ekstraksi Fitur Teks & Similarity Engine
3. **Brian Kristanto Alim** (APC320D6Y0104) - Eksplorasi Data & Optimasi Model
4. **Ristya Sedana** (APC320D6X0410) - Frontend Integration & System Testing
5. **Vanensia Mbiliyora** (APC320D6X0380) - Integrasi FastAPI & Backend Routing
