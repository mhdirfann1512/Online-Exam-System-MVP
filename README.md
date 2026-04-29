# Online Exam System (MVP)

Sistem Pengurusan Peperiksaan Online yang moden, ringan, dan efisien. Dibina menggunakan **Laravel 12** untuk menguruskan peperiksaan secara atas talian

---

## Tech Stack

Sistem ini dibina dengan integrasi teknologi terkini untuk memastikan kelajuan dan sekuriti:

### **Backend & Core**
* **Framework:** Laravel 12.x (Latest)
* **PHP:** ^8.2
* **Authentication:** Laravel Breeze (Session) & Laravel Sanctum (API Token)
* **Database:** MySQL / SQLite

### **Frontend & Interactivity**
* **Bundler:** Vite 7.0 (Ultra-fast build tool)
* **CSS Framework:** Tailwind CSS dengan `@tailwindcss/forms`
* **Interactivity:** Alpine.js 3.x (Lightweight JavaScript framework)

### **Document & Reporting Engines**
* **Excel:** Maatwebsite Excel 3.1 (Import/Export data peperiksaan)
* **PDF:** Barryvdh DomPDF (Penjanaan arkib soalan format PDF)

---

## Ciri-Ciri Utama

1.  **Pengurusan Peperiksaan (CRUD):** Admin boleh mendaftar, mengemaskini, dan memantau tempoh masa peperiksaan.
2.  **Real-time Search:** Carian tajuk peperiksaan secara pantas di *client-side* menggunakan **Alpine.js**.
3.  **Master Bank Soalan:** Pusat arkib soalan yang boleh dimuat turun dalam format Excel dan PDF secara automatik.
4.  **API Integration:** Sedia untuk integrasi aplikasi luar melalui endpoint API yang dilindungi oleh **Bearer Token (Sanctum)**.
5.  **Modern UX:** Penggunaan modal untuk proses kemaskini bagi mengelakkan gangguan aliran kerja (*page reload*).
6.  **Anti-Cheat:** Pelajar tidak boleh copy and paste semasa menjawab soalan dan mendapat amaran apabila menukar tab(bukan audit trail).
7.  **Auto Submit** Sistem secara automatik akan simpan jawapan sebaik saja masa tamat.


---

## Seni Bina Sistem (architecture)

Sistem ini mengikut piawaian **MVC (Model-View-Controller)**:

* `app/Http/Controllers`: Menguruskan logic perniagaan dan aliran data.
* `app/Models`: Definisi hubungan (Relationships) data.
* `database/migrations`: Blueprint database yang membolehkan replikasi sistem tanpa fail SQL manual.
* `resources/views`: UI yang dibina menggunakan Blade Templates dan Tailwind CSS.

---

## Dokumentasi API (V1)

Semua request API memerlukan header `Accept: application/json` dan `Authorization: Bearer {token}`.

| Endpoint | Method | Fungsi |
| :--- | :--- | :--- |
| `/api/v1/exams` | `GET` | Mendapatkan senarai semua peperiksaan yang diterbitkan. |
| `/api/v1/results/{id}` | `POST` | Mendapatkan data keputusan bagi peperiksaan tertentu. |
| `/api/v1/student/{id}/transcript` | `GET` | Mendapatkan sejarah keputusan akademik pelajar. |

---

## Cara Pemasangan (Local Setup)

Sistem ini telah dilengkapi dengan **Automation Script** untuk memudahkan proses pemasangan.

1.  **Clone Repository**
    ```bash
    git clone [https://github.com/mhdirfann1512/Online-Exam-System-MVP.git](https://github.com/mhdirfann1512/Online-Exam-System-MVP.git)
    cd Online-Exam-System-MVP
    ```

2.  **Automated Setup**
    Jalankan command di bawah untuk *install dependencies*, *generate key*, dan *migrate database* secara automatik:
    ```bash
    composer run setup
    ```

3.  **Jalankan Server**
    ```bash
    php artisan serve
    ```

---

## Deployment

Sistem ini dihoskan di **Railway** (free trial 30 hari) menggunakan Docker containerization untuk memastikan persekitaran *production* yang stabil. 

* **URL Live:** `https://online-exam-system-mvp-production.up.railway.app/`
* **Environment:** Production (APP_DEBUG=false)

---

## Nota Pembangunan
Sistem ini dibina sebagai **Minimum Viable Product (MVP)** untuk mendigitalkan proses pengurusan bank soalan. Fokus utama adalah pada kelajuan capaian data dan kemudahan penggunaan bagi pihak pentadbir dan pelajar.

---

**Disediakan oleh:** [Muhammad Irfan]
