<<<<<<< HEAD
# Student-Information-System-SIS-
A student information system that records name, email, course, year level, average and pass/fail status
=======
# Student Information System

A simple information management application built with **PHP, HTML and CSS**.
It displays a collection of student records stored in PHP arrays, with summary
statistics, a search feature and a per-student details page.

## Features

- Displays a collection of 13 student records in a table
- Add new students through a form (`add-student.php`), saved to `includes/students.json`
- Delete a student with the button on the right of each row
- Information is organized in a **multidimensional PHP array** (`includes/data.php`)
- Uses **PHP functions** for displaying and processing information (`includes/functions.php`):
  - `getStudents()`, `searchStudents()`, `findStudentById()` — data access
  - `addStudent()`, `getStoredStudents()`, `saveStoredStudents()` — saving new records
  - `deleteStudent()`, `getDeletedIds()`, `saveDeletedIds()` — removing records
  - `computeAverage()`, `getLetterGrade()`, `isPassing()` — grade processing
  - `renderStudentTable()`, `renderStudentRow()` — HTML display
  - `getStats()` — summary statistics (total, overall average, passing count, programs)
- Applies **PHP string functions** where appropriate:
  - `trim()`, `strtolower()`, `ucwords()` — cleaning/formatting names and search input
  - `strtoupper()`, `substr()` — building student initials
  - `sprintf()` — formatting student numbers (`23000440200` → `S-23000440200`)
  - `number_format()` — formatting averages and counts
  - `str_contains()` — case-insensitive search
  - `implode()`, `explode()`, `htmlspecialchars()` — array/text handling and safe output
- Presented with **HTML and CSS** (simple, clean, responsive table layout with
  pass/fail badges and stat cards)

## Project Structure

```
student-information-system/
├── index.php               # Front page: stats, search form, record table
├── student-details.php     # One student's full record (?id=N)
├── add-student.php         # Form for adding a new student
├── delete-student.php      # Handles deleting a student (POST)
├── includes/
│   ├── data.php            # Student records (multidimensional array)
│   ├── students.json       # Students added through the form
│   ├── deleted.json        # IDs of deleted base records
│   ├── functions.php       # Processing + display functions
│   ├── header.php          # Shared page header
│   └── footer.php          # Shared page footer
├── css/
│   └── style.css           # All styling
└── README.md
```

## How to Run

### Option A — XAMPP / WAMP

1. Copy the `student-information-system` folder into your web root
   (e.g. `C:\xampp\htdocs\`).
2. Start Apache.
3. Open `http://localhost/student-information-system/` in your browser.

### Option B — PHP built-in server

```bash
cd student-information-system
php -S localhost:8000
```

Then open `http://localhost:8000` in your browser.

## How to Submit via GitHub

1. Create a new **public** repository on GitHub (e.g. `student-information-system`).
   Do not initialize it with a README.
2. In the project folder, run:

```bash
git init
git add .
git commit -m "Add Student Information System (PHP, HTML, CSS)"
git branch -M main
git remote add origin https://github.com/<your-username>/student-information-system.git
git push -u origin main
```

3. Paste the repository URL as your submission.

## Passing Grade

A student passes with an average grade of **75 and above**. This threshold is
set in `isPassing()` inside `includes/functions.php` and can be changed there.
>>>>>>> cbffffc (SIS system v1)
