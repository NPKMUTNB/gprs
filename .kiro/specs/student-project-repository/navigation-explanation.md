# Navigation Menu Explanation

## ความแตกต่างของเมนูสำหรับแต่ละ Role

### 👨‍🏫 Advisor (อาจารย์ที่ปรึกษา)

เมื่อ login เป็น Advisor จะเห็นเมนู 2 รายการที่เกี่ยวกับโปรเจกต์:

#### 1. **Assigned Projects** (โปรเจกต์ที่ได้รับมอบหมาย)
- **URL**: `/projects?advisor_id={advisor_id}`
- **แสดง**: เฉพาะโปรเจกต์ที่ Advisor คนนั้นเป็นที่ปรึกษา
- **วัตถุประสงค์**: ให้ Advisor ดูแลและจัดการโปรเจกต์ที่รับผิดชอบ
- **การใช้งาน**:
  - ดูโปรเจกต์ที่รอการอนุมัติ (Submitted)
  - อนุมัติ (Approve) หรือปฏิเสธ (Reject) โปรเจกต์
  - ประเมินโปรเจกต์
  - ติดตามความคืบหน้าของนักศึกษา

**ตัวอย่าง**: ถ้า Advisor มี ID = 5 จะเห็นเฉพาะโปรเจกต์ที่ `advisor_id = 5`

#### 2. **Browse Projects** (เรียกดูโปรเจกต์ทั้งหมด)
- **URL**: `/projects`
- **แสดง**: โปรเจกต์ทั้งหมดในระบบ (ตามสิทธิ์การเข้าถึง)
- **วัตถุประสงค์**: ให้ Advisor สามารถดูโปรเจกต์อื่นๆ ในระบบ
- **การใช้งาน**:
  - ดูโปรเจกต์ของ Advisor คนอื่น
  - ค้นหาโปรเจกต์ตามหมวดหมู่, ปี, เทอม
  - ดูโปรเจกต์ที่เผยแพร่แล้ว (Approved/Completed)
  - เรียนรู้จากโปรเจกต์อื่นๆ

**ตัวอย่าง**: จะเห็นโปรเจกต์ทั้งหมดที่มีสิทธิ์เข้าถึง พร้อมตัวกรองต่างๆ

---

### 👨‍🎓 Student (นักศึกษา)

เมื่อ login เป็น Student จะเห็นเมนู 2 รายการที่เกี่ยวกับโปรเจกต์:

#### 1. **My Projects** (โปรเจกต์ของฉัน)
- **URL**: `/projects?created_by={student_id}`
- **แสดง**: เฉพาะโปรเจกต์ที่ **นักศึกษาคนนั้นสร้าง**
- **วัตถุประสงค์**: ให้นักศึกษาจัดการโปรเจกต์ของตัวเอง
- **การใช้งาน**:
  - สร้างโปรเจกต์ใหม่
  - แก้ไขโปรเจกต์ (เฉพาะสถานะ Draft)
  - ส่งโปรเจกต์เพื่อขออนุมัติ (Submit)
  - ดูสถานะโปรเจกต์ (Draft, Submitted, Approved, Rejected, Completed)
  - แก้ไขโปรเจกต์ที่ถูกปฏิเสธ (Rejected)
  - เพิ่มสมาชิกในทีม
  - อัปโหลดไฟล์

**ตัวอย่าง**: ถ้านักศึกษามี ID = 10 จะเห็นเฉพาะโปรเจกต์ที่ `created_by = 10`

#### 2. **Browse Projects** (เรียกดูโปรเจกต์ทั้งหมด)
- **URL**: `/projects`
- **แสดง**: โปรเจกต์ที่ **เผยแพร่แล้ว** (Approved/Completed) - สำหรับ Guest และ Student
- **วัตถุประสงค์**: ให้นักศึกษาเรียนรู้จากโปรเจกต์ของคนอื่น
- **การใช้งาน**:
  - ดูโปรเจกต์ที่ผ่านการอนุมัติแล้ว
  - ค้นหาโปรเจกต์ตามหัวข้อที่สนใจ
  - กรองตามหมวดหมู่, ปี, เทอม
  - ดูคะแนนและความเห็นจาก Committee
  - ดาวน์โหลดไฟล์โปรเจกต์เพื่ออ้างอิง

**ตัวอย่าง**: จะเห็นโปรเจกต์ที่เผยแพร่แล้วทั้งหมด (ไม่รวมโปรเจกต์ Draft/Submitted ของคนอื่น)

---

### 👔 Committee (กรรมการ)

เมื่อ login เป็น Committee จะเห็นเมนู 2 รายการที่เกี่ยวกับโปรเจกต์:

#### 1. **Evaluate** (ประเมิน)
- **URL**: `/projects?status=approved`
- **แสดง**: เฉพาะโปรเจกต์ที่ **พร้อมประเมิน** (สถานะ Approved)
- **วัตถุประสงค์**: ให้ Committee ประเมินโปรเจกต์ที่ผ่านการอนุมัติจาก Advisor แล้ว
- **การใช้งาน**:
  - ดูโปรเจกต์ที่พร้อมประเมิน
  - ให้คะแนนตามเกณฑ์ต่างๆ (Technical, Design, Documentation, Presentation)
  - เขียนความเห็นและข้อเสนอแนะ
  - ติดตามโปรเจกต์ที่ยังไม่ได้ประเมิน

**ตัวอย่าง**: จะเห็นเฉพาะโปรเจกต์ที่ Advisor อนุมัติแล้ว พร้อมประเมิน

#### 2. **Browse Projects** (เรียกดูโปรเจกต์ทั้งหมด)
- **URL**: `/projects`
- **แสดง**: โปรเจกต์ทั้งหมดที่มีสิทธิ์เข้าถึง (Submitted, Approved, Completed)
- **วัตถุประสงค์**: ให้ Committee สามารถดูโปรเจกต์ทั้งหมดในระบบ
- **การใช้งาน**:
  - ดูโปรเจกต์ทุกสถานะ
  - ค้นหาโปรเจกต์ตามหมวดหมู่, ปี, เทอม
  - ดูโปรเจกต์ที่ประเมินไปแล้ว
  - ดูคะแนนเฉลี่ยของโปรเจกต์

**ตัวอย่าง**: จะเห็นโปรเจกต์ทั้งหมด รวมถึงที่ยังไม่ได้อนุมัติ และที่ประเมินแล้ว

---

### 👨‍💼 Admin (ผู้ดูแลระบบ)

Admin ไม่มีเมนู "Assigned Projects" เพราะมีสิทธิ์เข้าถึงทุกอย่างอยู่แล้ว

#### เมนูของ Admin:
1. **Dashboard** - ภาพรวมระบบ
2. **Browse Projects** - โปรเจกต์ทั้งหมด
3. **Users** - จัดการผู้ใช้
4. **Categories** - จัดการหมวดหมู่
5. **Tags** - จัดการแท็ก
6. **Reports** - รายงานต่างๆ

---

## สรุปความแตกต่าง

| Role | เมนูที่ 1 | เมนูที่ 2 | ความแตกต่าง |
|------|-----------|-----------|-------------|
| **Advisor** | Assigned Projects<br>(กรองด้วย advisor_id) | Browse Projects<br>(ทั้งหมด) | เมนูแรกแสดงเฉพาะที่รับผิดชอบ<br>เมนูสองแสดงทั้งหมด |
| **Student** | My Projects<br>(กรองด้วย created_by) | Browse Projects<br>(เผยแพร่แล้ว) | เมนูแรกแสดงเฉพาะของตัวเอง (ทุกสถานะ)<br>เมนูสองแสดงที่เผยแพร่ (ของคนอื่น) |
| **Committee** | Evaluate<br>(กรองด้วย status=approved) | Browse Projects<br>(ทั้งหมด) | เมนูแรกแสดงเฉพาะที่พร้อมประเมิน<br>เมนูสองแสดงทั้งหมด |
| **Admin** | - | Browse Projects<br>(ทั้งหมด) | มีสิทธิ์เต็มอยู่แล้ว |

---

## ตัวอย่างการใช้งาน

### สำหรับ Advisor

### Scenario 1: ตรวจสอบโปรเจกต์ที่รอการอนุมัติ
1. คลิก **"Assigned Projects"**
2. จะเห็นเฉพาะโปรเจกต์ที่ตัวเองเป็นที่ปรึกษา
3. โปรเจกต์ที่สถานะ "Submitted" จะแสดงเด่น
4. สามารถ Approve หรือ Reject ได้ทันที

### Scenario 2: ดูโปรเจกต์ของ Advisor คนอื่น
1. คลิก **"Browse Projects"**
2. จะเห็นโปรเจกต์ทั้งหมดในระบบ
3. สามารถกรองตาม Advisor, Category, Year ได้
4. ดูโปรเจกต์เพื่อเรียนรู้หรืออ้างอิง

### Scenario 3: ค้นหาโปรเจกต์เฉพาะเรื่อง
1. คลิก **"Browse Projects"**
2. ใช้ Search box ค้นหาคำที่ต้องการ
3. ใช้ Filter เพิ่มเติม (Category, Year, Semester)
4. ดูรายละเอียดโปรเจกต์ที่สนใจ

---

### สำหรับ Student

### Scenario 1: สร้างและจัดการโปรเจกต์
1. คลิก **"My Projects"**
2. จะเห็นเฉพาะโปรเจกต์ของตัวเอง (ทุกสถานะ)
3. คลิก "Create Project" เพื่อสร้างโปรเจกต์ใหม่
4. แก้ไข, อัปโหลดไฟล์, เพิ่มสมาชิกทีม

### Scenario 2: ส่งโปรเจกต์เพื่อขออนุมัติ
1. คลิก **"My Projects"**
2. เลือกโปรเจกต์ที่สถานะ "Draft"
3. ตรวจสอบข้อมูลให้ครบถ้วน
4. คลิก "Submit for Review" เพื่อส่งให้ Advisor

### Scenario 3: แก้ไขโปรเจกต์ที่ถูกปฏิเสธ
1. คลิก **"My Projects"**
2. เห็นโปรเจกต์ที่สถานะ "Rejected"
3. อ่านความเห็นจาก Advisor
4. แก้ไขและส่งใหม่

### Scenario 4: เรียนรู้จากโปรเจกต์คนอื่น
1. คลิก **"Browse Projects"**
2. จะเห็นโปรเจกต์ที่เผยแพร่แล้ว
3. ค้นหาโปรเจกต์ที่สนใจ
4. ดูรายละเอียด, ดาวน์โหลดไฟล์เพื่ออ้างอิง

---

### สำหรับ Committee

### Scenario 1: ประเมินโปรเจกต์ที่พร้อมแล้ว
1. คลิก **"Evaluate"**
2. จะเห็นเฉพาะโปรเจกต์ที่สถานะ "Approved"
3. เลือกโปรเจกต์ที่ยังไม่ได้ประเมิน
4. คลิก "Evaluate" เพื่อให้คะแนน

### Scenario 2: ดูโปรเจกต์ที่ประเมินไปแล้ว
1. คลิก **"Browse Projects"**
2. จะเห็นโปรเจกต์ทั้งหมด
3. ดูคะแนนที่ตัวเองให้ไว้
4. ดูคะแนนเฉลี่ยจาก Committee คนอื่น

### Scenario 3: ค้นหาโปรเจกต์ตามหมวดหมู่
1. คลิก **"Browse Projects"**
2. เลือก Category ที่สนใจ
3. ดูโปรเจกต์ทั้งหมดในหมวดหมู่นั้น
4. เปรียบเทียบคุณภาพโปรเจกต์

---

## การปรับปรุงในอนาคต

### ข้อเสนอแนะ:

1. **เพิ่ม Badge แสดงจำนวน**:
   - "Assigned Projects (3)" - แสดงจำนวนโปรเจกต์ที่รอการอนุมัติ
   - "Browse Projects" - ไม่ต้องแสดงจำนวน

2. **เพิ่ม Quick Filter**:
   - ใน Assigned Projects: แสดงเฉพาะ "Submitted" โดยอัตโนมัติ
   - มีปุ่ม "Show All" เพื่อดูทุกสถานะ

3. **เพิ่ม Notification**:
   - แจ้งเตือนเมื่อมีโปรเจกต์ใหม่ที่รอการอนุมัติ
   - แสดง dot สีแดงที่เมนู "Assigned Projects"

4. **เพิ่ม Dashboard Widget**:
   - แสดงสถิติโปรเจกต์ที่รับผิดชอบ
   - แสดงโปรเจกต์ที่ต้องดำเนินการ

---

## Technical Implementation

### Navigation Code

#### Advisor

```php
// Assigned Projects - กรองด้วย advisor_id
<x-nav-link :href="route('projects.index', ['advisor_id' => Auth::id()])" 
            :active="request()->routeIs('projects.*') && request('advisor_id') == Auth::id()">
    {{ __('messages.assigned_projects') }}
</x-nav-link>

// Browse Projects - ไม่กรอง
<x-nav-link :href="route('projects.index')" 
            :active="request()->routeIs('projects.index')">
    {{ __('messages.browse_projects') }}
</x-nav-link>
```

#### Student

```php
// My Projects - กรองด้วย created_by
<x-nav-link :href="route('projects.index', ['created_by' => Auth::id()])" 
            :active="request()->routeIs('projects.*') && request('created_by') == Auth::id()">
    {{ __('messages.my_projects') }}
</x-nav-link>

// Browse Projects - ไม่กรอง (แต่ Controller จะกรองให้เห็นเฉพาะ Approved/Completed สำหรับ Guest/Student)
<x-nav-link :href="route('projects.index')" 
            :active="request()->routeIs('projects.index')">
    {{ __('messages.browse_projects') }}
</x-nav-link>
```

#### Committee

```php
// Evaluate - กรองด้วย status=approved
<x-nav-link :href="route('projects.index', ['status' => 'approved'])" 
            :active="request()->routeIs('projects.*') && request('status') == 'approved'">
    {{ __('messages.evaluate') }}
</x-nav-link>

// Browse Projects - ไม่กรอง
<x-nav-link :href="route('projects.index')" 
            :active="request()->routeIs('projects.index')">
    {{ __('messages.browse_projects') }}
</x-nav-link>
```

### Controller Filter

```php
// ProjectController@index

// Filter by advisor
if ($request->filled('advisor_id')) {
    $query->where('advisor_id', $request->advisor_id);
}

// Filter by creator (for students)
if ($request->filled('created_by')) {
    $query->where('created_by', $request->created_by);
}

// Filter by status
if ($request->filled('status') && auth()->check()) {
    $query->where('status', $request->status);
}
```

### URL Examples

#### Student
- **My Projects**: `http://localhost:8000/projects?created_by=10`
- **Browse Projects**: `http://localhost:8000/projects`

#### Advisor
- **Assigned Projects**: `http://localhost:8000/projects?advisor_id=5`
- **Browse Projects**: `http://localhost:8000/projects`

#### Committee
- **Evaluate**: `http://localhost:8000/projects?status=approved`
- **Browse Projects**: `http://localhost:8000/projects`

#### General
- **Browse with Filter**: `http://localhost:8000/projects?category_id=2&year=2024`

---

## สรุป

**Assigned Projects** และ **Browse Projects** มีความแตกต่างที่สำคัญ:

✅ **Assigned Projects** = โปรเจกต์ที่ **รับผิดชอบ** (กรองแล้ว)
✅ **Browse Projects** = โปรเจกต์ **ทั้งหมด** (ไม่กรอง)

การแยกเมนูนี้ช่วยให้ Advisor:
- เข้าถึงโปรเจกต์ที่ต้องดูแลได้รวดเร็ว
- ไม่สับสนกับโปรเจกต์อื่นๆ
- ยังสามารถดูโปรเจกต์ทั้งหมดได้เมื่อต้องการ
