
# SPEC — Student Project Repository System (Laravel 12 + SQLite)
*(Web Application — No API Mode)*

## 0. System Overview
ระบบจัดเก็บโครงงานนักศึกษา (Student Project Repository System: SPRS) เป็นระบบเว็บแอปพลิเคชันที่ใช้สำหรับ **บันทึก ค้นหา ประเมิน และเผยแพร่ผลงานโครงงานของนักศึกษา** ผู้ใช้สามารถเข้าสู่ระบบด้วยบทบาทต่าง ๆ ได้แก่ นักศึกษา, อาจารย์ที่ปรึกษา, กรรมการ และผู้ดูแลระบบ ระบบถูกพัฒนาโดยใช้ **Laravel 12 + SQLite** และแสดงผลด้วย **Blade Template Engine**

## 1. Technology Stack
| หมวด | รายละเอียด |
|------|-------------|
| Framework | Laravel 12 (PHP ≥ 8.3) |
| Database | SQLite (`database/database.sqlite`) |
| Frontend | Laravel Blade, TailwindCSS |
| Authentication | Laravel Breeze / Laravel UI (Session-based Login) |
| Authorization | Laravel Gate & Policy |
| Storage | `storage/app/public` สำหรับไฟล์โครงงาน |
| Deployment | Local / Shared Hosting |
| Language | ไทย + อังกฤษ (รองรับ bilingual fields) |

## 2. User Roles
| Role | สิทธิ์การเข้าถึง |
|------|----------------|
| **Admin** | จัดการผู้ใช้ หมวดหมู่ โครงงานทั้งหมด |
| **Advisor** | อนุมัติ/ประเมินโครงงานของนักศึกษา |
| **Student** | สร้าง แก้ไข และอัปโหลดโครงงานของตนเอง |
| **Committee** | ตรวจสอบและประเมินโครงงาน |
| **Public (Guest)** | เข้าดูโครงงานที่เผยแพร่แล้วเท่านั้น |

## 3. Functional Requirements (Main Features)

### 3.1 ระบบผู้ใช้ (User Management)
- สมัครสมาชิก / เข้าสู่ระบบ / ออกจากระบบ  
- แก้ไขโปรไฟล์: ชื่อ, รหัสนักศึกษา, ภาควิชา, รูปภาพ  
- ผู้ดูแลระบบสามารถจัดการผู้ใช้ (เพิ่ม / ลบ / แก้ไขสิทธิ์)  
- Role-based Access Control (RBAC)  

### 3.2 ระบบจัดการโครงงาน (Project Management)
- เพิ่ม / แก้ไข / ลบ โครงงานนักศึกษา  
- ระบุข้อมูล: ชื่อโครงงาน (ไทย/อังกฤษ), ปีการศึกษา, ภาคเรียน, หมวดหมู่, แท็ก, บทคัดย่อ (Abstract)  
- แนบไฟล์ได้หลายประเภท: Proposal, Report, Presentation, Source Code  
- ระบุอาจารย์ที่ปรึกษา (Advisor)  
- สถานะโครงงาน: Draft → Submitted → Approved → Completed  

**Flow ตัวอย่าง:** นักศึกษา → สร้างโครงงาน (Draft) → ส่งให้อาจารย์ตรวจ (Submitted) → อาจารย์อนุมัติ (Approved) → ระบบเผยแพร่ (Completed)

### 3.3 ระบบสมาชิกในโครงงาน (Team Management)
- หัวหน้าทีมสามารถเพิ่มสมาชิกในทีมได้  
- ระบุบทบาท (หัวหน้าทีม / สมาชิก)  
- ระบบแสดงรายชื่อสมาชิกทั้งหมดในหน้าโครงงาน  

### 3.4 ระบบไฟล์แนบ (Project Files)
- นักศึกษาสามารถแนบไฟล์หลายประเภท  
- ระบบบันทึกชื่อไฟล์, ประเภท, วันที่อัปโหลด  
- ลบหรือแก้ไขชื่อไฟล์ได้  
- แสดงไฟล์ในหน้าโครงงาน  

### 3.5 ระบบประเมินผล (Evaluation)
- อาจารย์/กรรมการให้คะแนนตามเกณฑ์: Technical, Design, Documentation, Presentation  
- ระบบคำนวณคะแนนเฉลี่ยรวมอัตโนมัติ (Total Score)  
- สามารถเพิ่มความคิดเห็น (Comment) ประกอบการประเมิน  

### 3.6 ระบบความคิดเห็น (Comment & Feedback)
- ผู้ใช้ที่เข้าสู่ระบบสามารถแสดงความคิดเห็นในโครงงาน  
- แสดงความคิดเห็นทั้งหมดใต้รายละเอียดโครงงาน  
- ผู้ดูแลระบบสามารถลบความคิดเห็นที่ไม่เหมาะสมได้  

### 3.7 ระบบหมวดหมู่และแท็ก (Category & Tag)
- ผู้ดูแลระบบเพิ่ม / ลบ / แก้ไขหมวดหมู่ได้  
- นักศึกษาสามารถเลือกแท็กสำหรับโครงงานได้หลายแท็ก  
- ใช้ในการค้นหาและกรองโครงงาน  

### 3.8 ระบบค้นหาและเรียกดูโครงงาน (Search & Browse)
- ค้นหาโครงงานตาม: ชื่อ, หมวดหมู่, ปีการศึกษา, อาจารย์ที่ปรึกษา, แท็ก  
- กรอง (Filter) ตามสถานะหรือคะแนน  
- แสดงผลโครงงานในรูปแบบ Grid / List  
- หน้ารายละเอียดโครงงาน (Detail Page): แสดงข้อมูลโครงงาน, สมาชิกทีม, อาจารย์, ไฟล์แนบ, คะแนน, คอมเมนต์  

### 3.9 ระบบรายงานและสถิติ (Reports & Analytics)
- รายงานจำนวนโครงงานแยกตามปี / หมวดหมู่  
- สรุปคะแนนเฉลี่ยโครงงาน  
- รายงานจำนวนโครงงานต่ออาจารย์ที่ปรึกษา  
- สามารถดาวน์โหลดรายงานเป็น Excel / PDF  

### 3.10 ระบบแจ้งเตือน (Notification – Optional)
- แจ้งเตือนใน Dashboard เมื่อโครงงานได้รับการอนุมัติ / ถูกประเมิน  
- ใช้ Table notifications หรือ Laravel Flash Message  

## 4. Database Design (SQLite Schema)
ตารางหลัก: `users`, `projects`, `project_members`, `project_files`, `evaluations`, `comments`, `categories`, `tags`, `project_tag`, `activity_logs`

### ตัวอย่าง SQL 
```sql
CREATE TABLE users (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  name TEXT NOT NULL,
  email TEXT NOT NULL UNIQUE,
  password TEXT NOT NULL,
  role TEXT NOT NULL CHECK(role IN ('admin','advisor','student','committee')),
  department TEXT,
  profile_pic TEXT,
  created_at TEXT, updated_at TEXT
);

CREATE TABLE categories (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  name TEXT NOT NULL,
  description TEXT
);

CREATE TABLE projects (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  title_th TEXT NOT NULL,
  title_en TEXT NOT NULL,
  abstract TEXT NOT NULL,
  year INTEGER NOT NULL,
  semester TEXT NOT NULL CHECK(semester IN ('1','2','3')),
  status TEXT NOT NULL CHECK(status IN ('draft','submitted','approved','rejected','completed')),
  category_id INTEGER,
  created_by INTEGER NOT NULL,
  advisor_id INTEGER NOT NULL,
  created_at TEXT, updated_at TEXT,
  FOREIGN KEY(category_id) REFERENCES categories(id),
  FOREIGN KEY(created_by) REFERENCES users(id),
  FOREIGN KEY(advisor_id) REFERENCES users(id)
);

CREATE TABLE project_members (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  project_id INTEGER NOT NULL,
  user_id INTEGER NOT NULL,
  role_in_team TEXT NOT NULL CHECK(role_in_team IN ('leader','member')),
  created_at TEXT,
  FOREIGN KEY(project_id) REFERENCES projects(id),
  FOREIGN KEY(user_id) REFERENCES users(id)
);

CREATE TABLE project_files (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  project_id INTEGER NOT NULL,
  file_name TEXT NOT NULL,
  file_type TEXT NOT NULL CHECK(file_type IN ('proposal','report','presentation','code','other')),
  file_path TEXT NOT NULL,
  uploaded_at TEXT,
  FOREIGN KEY(project_id) REFERENCES projects(id)
);

CREATE TABLE evaluations (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  project_id INTEGER NOT NULL,
  evaluator_id INTEGER NOT NULL,
  technical_score REAL NOT NULL,
  design_score REAL NOT NULL,
  documentation_score REAL NOT NULL,
  presentation_score REAL NOT NULL,
  total_score REAL NOT NULL,
  comment TEXT,
  evaluated_at TEXT,
  FOREIGN KEY(project_id) REFERENCES projects(id),
  FOREIGN KEY(evaluator_id) REFERENCES users(id)
);

CREATE TABLE comments (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  project_id INTEGER NOT NULL,
  user_id INTEGER NOT NULL,
  content TEXT NOT NULL,
  created_at TEXT,
  FOREIGN KEY(project_id) REFERENCES projects(id),
  FOREIGN KEY(user_id) REFERENCES users(id)
);

CREATE TABLE tags (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  name TEXT NOT NULL
);

CREATE TABLE project_tag (
  project_id INTEGER NOT NULL,
  tag_id INTEGER NOT NULL,
  PRIMARY KEY(project_id, tag_id),
  FOREIGN KEY(project_id) REFERENCES projects(id),
  FOREIGN KEY(tag_id) REFERENCES tags(id)
);

CREATE TABLE activity_logs (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  user_id INTEGER NOT NULL,
  action TEXT NOT NULL,
  detail TEXT,
  created_at TEXT,
  FOREIGN KEY(user_id) REFERENCES users(id)
);
```

## 5. Page Structure (Blade + Controller)
| หน้า | Controller | Route | สิทธิ์ |
|-------|-------------|--------|---------|
| Login / Register | AuthController | `/login`, `/register` | Guest |
| Dashboard | DashboardController | `/dashboard` | Auth |
| Project List | ProjectController@index | `/projects` | All |
| Create Project | ProjectController@create | `/projects/create` | Student |
| Edit Project | ProjectController@edit | `/projects/{id}/edit` | Owner/Advisor |
| Project Detail | ProjectController@show | `/projects/{id}` | All |
| File Upload | ProjectFileController | `/projects/{id}/files` | Student |
| Evaluation | EvaluationController | `/projects/{id}/evaluate` | Advisor/Committee |
| Comment | CommentController | `/projects/{id}/comment` | Auth |
| Report | ReportController | `/reports` | Admin/Advisor |

## 6. Example Page Flow
**Student Flow:** Login → Dashboard → Create Project → Upload Files → Submit → View Evaluation  
**Advisor Flow:** Login → Assigned Projects → Review → Evaluate → Approve/Reject  
**Admin Flow:** Manage Users/Categories/Tags → Reports → Monitor Activities  

## 7. Business Rules
- นักศึกษาสามารถลบโครงงานได้เฉพาะสถานะ Draft  
- อาจารย์เท่านั้นที่สามารถอนุมัติหรือประเมินโครงงาน  
- คะแนนเฉลี่ยคำนวณจาก (tech + design + doc + presentation) ÷ 4  
- ระบบต้องเก็บประวัติการแก้ไข (activity_logs)  

## 8. Non-Functional Requirements
| หมวด | รายละเอียด |
|-------|-------------|
| **Security** | Hash Password (bcrypt), CSRF Token, Role-based Access |
| **Performance** | ใช้ Pagination ในการแสดงรายการโครงงาน |
| **Usability** | Responsive Design, TailwindCSS, Multi-language Support |
| **Maintainability** | Code แยกเป็น Controller + Blade + Model |
| **Reliability** | SQLite DB สำรองได้ง่าย, Migration ใช้งานได้ทุกสภาพแวดล้อม |
| **Accessibility** | ใช้ Semantic HTML และ Alt Text สำหรับรูปภาพ |

## 9. Folder Structure (Laravel MVC)
```
app/
  Http/
    Controllers/
      AuthController.php
      DashboardController.php
      ProjectController.php
      ProjectFileController.php
      EvaluationController.php
      CommentController.php
  Models/
    User.php
    Project.php
    ProjectMember.php
    ProjectFile.php
    Evaluation.php
    Comment.php
    Category.php
    Tag.php
resources/
  views/
    layouts/app.blade.php
    auth/login.blade.php
    dashboard/index.blade.php
    projects/
      index.blade.php
      create.blade.php
      show.blade.php
      edit.blade.php
    evaluations/create.blade.php
database/
  migrations/
  seeders/
routes/
  web.php
```

## 10. Example Routes (web.php)
```php
use App\Http\Controllers\{
  AuthController, DashboardController, ProjectController,
  ProjectFileController, EvaluationController, CommentController
};

Route::get('/', [ProjectController::class, 'index'])->name('home');

Route::middleware(['auth'])->group(function() {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('projects', ProjectController::class);
    Route::post('/projects/{id}/submit', [ProjectController::class, 'submit'])->name('projects.submit');
    Route::post('/projects/{id}/approve', [ProjectController::class, 'approve'])->name('projects.approve');
    Route::post('/projects/{id}/evaluate', [EvaluationController::class, 'store'])->name('projects.evaluate');
    Route::post('/projects/{id}/comment', [CommentController::class, 'store'])->name('projects.comment');
    Route::post('/projects/{id}/files', [ProjectFileController::class, 'store'])->name('projects.files.store');
    Route::delete('/projects/{id}/files/{fileId}', [ProjectFileController::class, 'destroy'])->name('projects.files.destroy');
});
Auth::routes();
```

## 11. Reports
- รายงานสรุปจำนวนโครงงานแต่ละปี  
- รายงานคะแนนเฉลี่ยโครงงาน  
- รายงานจำนวนนักศึกษา/อาจารย์ที่ปรึกษา  
- แสดงผลในตารางและสามารถพิมพ์หรือดาวน์โหลดเป็น PDF  

## 12. Future Enhancements
- ระบบเปรียบเทียบความซ้ำของชื่อโครงงาน (AI Similarity)  
- ระบบค้นหาด้วย Full-text Search  
- การเชื่อมต่อ GitHub สำหรับอัปโหลด Source Code  
- Export รายงานอัตโนมัติรายเดือน  

## 13. Acceptance Criteria
- นักศึกษาสามารถสร้างโครงงานใหม่และส่งให้อาจารย์อนุมัติได้  
- อาจารย์สามารถประเมินและให้คะแนนได้  
- ผู้ดูแลระบบสามารถดูรายงานสรุปได้  
- ผู้ใช้ทั่วไปสามารถเข้าชมโครงงานที่เผยแพร่ได้  
