Deployment link: https://online-exam-system-mvp-production.up.railway.app/

- TEST CREDENTIAL
- for admin
- email: admin@test.com
- password: password123

- for student:
- email: student1@test.com
- password: password123


# Mini LMS API Documentation

## Authentication
This API uses **Laravel Sanctum**. All requests must include a Bearer Token in the header:
`Authorization: Bearer 1|DygqRBXmtZYERIOxvySfG7V4kXWgWW3wpIUBzEas2216c000`

## Endpoints

### 1. List All Published Exams
- **URL:** `/api/v1/exams`
- **Method:** `GET`
- **Description:** Returns a list of all exams where `is_published` is true.

### 2. Get Exam Results
- **URL:** `/api/v1/results/{exam_id}`
- **Method:** `POST`
- **Description:** Returns the scores and student names for a specific exam.

### 3. Student Transcript
- **URL:** `/api/v1/student/{student_id}/transcript`
- **Method:** `GET`
- **Description:** Returns the exam history (exam title, score, and date) for a specific student.

- TEST CREDENTIAL
- for admin
- email: admin@test.com
- password: password123

- for student:
- email: student1@test.com
- password: password123

- 
