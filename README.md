# Student Information System

A small web application for managing student records. It is built with plain PHP, HTML, and CSS, and it does not need a database.

With this app you can view all students in a table, see a quick summary of the class, open a student's full record, add a new student, and delete a student.

## What the app can do

- Show a list of students with their student number, name, email,  year level, average grade, and whether they are passing or failing.
- Display summary cards above the table with the total number of students, the overall average, and the number of passing students.
- Open a details page for any student. The page shows the student's information, all subject grades, the average, and the letter grade.
- Add a new student using a form. The form checks that the name, email, year level, and grades are filled in correctly before saving.
- Delete a student using the Delete button on the right side of each row. A confirmation message appears first so records are not removed by accident.

## Requirements

You only need PHP to run this project. PHP 7.4 or newer is recommended.

## Running the app

### Using XAMPP or WAMP

1. Copy the project folder into your web server's root folder. For XAMPP this is usually `C:\xampp\htdocs\`.
2. Start Apache from the XAMPP or WAMP control panel.
3. Open `http://localhost/student-information-system/` in your browser.

### Using PHP's built-in server

Open a terminal in the project folder and run:

```bash
php -S localhost:8000
```

Then visit `http://localhost:8000` in your browser.

## Project structure

```
student-information-system/
├── index.php               The main page: summary cards and the student table
├── student-details.php     The full record for one student
├── add-student.php         The form for adding a new student
├── delete-student.php      Handles deleting a student
├── includes/
│   ├── data.php            The original student records
│   ├── students.json       Students added through the form
│   ├── deleted.json        IDs of original records that were deleted
│   ├── functions.php       Functions that process data and build the page
│   ├── header.php          The shared top part of each page
│   └── footer.php          The shared bottom part of each page
├── css/
│   └── style.css           All of the styling
└── README.md
```

The starter records are kept in `includes/data.php`. Students added through the form are saved to `includes/students.json`, and deleted starter records are remembered in `includes/deleted.json`, so changes remain after you refresh or restart the app. The `includes` folder must be writable for adding and deleting students to work.

## Passing grade

A student passes when their average grade is 75 or higher. This rule is set in the `isPassing()` function inside `includes/functions.php`, so you can change the passing grade there if needed.
