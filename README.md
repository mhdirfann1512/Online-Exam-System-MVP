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

**Postman(LOCAL)**

GET: http://127.0.0.1:8000/api/v1/exams
```bash
{
    "status": "success",
    "count": 2,
    "data": [
        {
            "id": 1,
            "title": "peperiksaan bahasa melayu kertas 1",
            "duration_minutes": 20,
            "start_time": "2026-04-29 11:21:00",
            "end_time": "2026-04-30 11:21:00"
        },
        {
            "id": 6,
            "title": "peperiksaan bahasa inggeris kertas 2",
            "duration_minutes": 20,
            "start_time": "2026-04-30 09:29:00",
            "end_time": "2026-05-01 09:29:00"
        }
    ]
}
```

POST: http://127.0.0.1:8000/api/v1/results/1
```bash
{
    "status": "success",
    "exam_id": 1,
    "results": [
        {
            "id": 20,
            "user_id": 2,
            "score": 20,
            "created_at": "2026-04-29T07:49:13.000000Z",
            "user": {
                "id": 2,
                "name": "MUHAMMAD IRFAN BIN APPRI"
            }
        }
    ]
}
```

GET: http://127.0.0.1:8000/api/v1/student/2/transcript
```bash
{
    "status": "success",
    "student_id": 2,
    "history": [
        {
            "exam_title": "peperiksaan bahasa melayu kertas 1",
            "score": 20,
            "date": "2026-04-29"
        },
        {
            "exam_title": "peperiksaan bahasa inggeris kertas 2",
            "score": 30,
            "date": "2026-04-30"
        }
    ]
}
```

---

## Cara Pemasangan (Local Setup)

Sistem ini telah dilengkapi dengan **Automation Script** untuk memudahkan proses pemasangan.

1.  **Clone Repository**
    ```bash
    git clone https://github.com/mhdirfann1512/Online-Exam-System-MVP.git
    cd Online-Exam-System-MVP
    ```

2.  **Setup Environment File**
    Salin fail `.env.example` dan generate application key:
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

3.  **Automated Setup**
    Jalankan command di bawah untuk *install dependencies*, *migrate database*, dan setup lain secara automatik:
    ```bash
    composer run setup
    ```

4.  **Jalankan Server**
    ```bash
    php artisan serve
    ```

5. **Frontend Assets (Vite)**
   Untuk compile dan run CSS/JS (styling & frontend assets), jalankan:
   ```bash
   npm install
   npm run dev
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