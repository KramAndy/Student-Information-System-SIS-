<?php
require_once __DIR__ . '/data.php';


function getStudents()
{
    global $students;
    $deleted = getDeletedIds();
    $all = array_merge($students, getStoredStudents());

    return array_values(array_filter($all, function ($student) use ($deleted) {
        return !in_array((int) $student['id'], $deleted, true);
    }));
}

function getStoredStudents()
{
    $file = __DIR__ . '/students.json';

    if (!is_file($file)) {
        return [];
    }

    $contents = file_get_contents($file);
    $stored = json_decode($contents, true);

    return is_array($stored) ? $stored : [];
}

function saveStoredStudents($students)
{
    $file = __DIR__ . '/students.json';
    $json = json_encode(array_values($students), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

    return file_put_contents($file, $json . PHP_EOL, LOCK_EX) !== false;
}

function getDeletedIds()
{
    $file = __DIR__ . '/deleted.json';

    if (!is_file($file)) {
        return [];
    }

    $contents = file_get_contents($file);
    $ids = json_decode($contents, true);

    return is_array($ids) ? array_map('intval', $ids) : [];
}

function saveDeletedIds($ids)
{
    $file = __DIR__ . '/deleted.json';
    $ids = array_values(array_unique(array_map('intval', $ids)));
    $json = json_encode($ids, JSON_PRETTY_PRINT);

    return file_put_contents($file, $json . PHP_EOL, LOCK_EX) !== false;
}

function deleteStudent($id)
{
    $id = (int) $id;
    $stored = getStoredStudents();

    $remaining = array_values(array_filter($stored, function ($student) use ($id) {
        return (int) $student['id'] !== $id;
    }));

    if (count($remaining) !== count($stored)) {
        return saveStoredStudents($remaining);
    }

    $deleted = getDeletedIds();

    if (!in_array($id, $deleted, true)) {
        $deleted[] = $id;
    }

    return saveDeletedIds($deleted);
}

function nextStudentId()
{
    $ids = array_map(function ($student) {
        return (int) $student['id'];
    }, getStudents());

    $ids = array_merge($ids, getDeletedIds());
    $next = count($ids) > 0 ? max($ids) + 1 : 0;

    return max($next, 23000440200);
}

function addStudent($name, $email, $program, $year, $grades)
{
    $student = [
        'id'      => nextStudentId(),
        'name'    => formatName($name),
        'email'   => strtolower(trim($email)),
        'program' => trim($program),
        'year'    => (int) $year,
        'grades'  => $grades,
    ];

    $stored = getStoredStudents();
    $stored[] = $student;

    if (!saveStoredStudents($stored)) {
        return false;
    }

    return $student;
}

function formatName($name)
{
    return ucwords(strtolower(trim($name)));
}


function getInitials($name)
{
    $parts = explode(' ', trim($name));
    $initials = '';

    foreach ($parts as $part) {
        if ($part !== '') {
            $initials .= strtoupper(substr($part, 0, 1));
        }
    }

    return $initials;
}
function studentNumber($id)
{
    return sprintf('S-%03d', $id);
}


function computeAverage($grades)
{
    $values = array_values($grades);

    if (count($values) === 0) {
        return 0;
    }

    return array_sum($values) / count($values);
}

function getLetterGrade($average)
{
    if ($average >= 90) {
        return 'A';
    } elseif ($average >= 80) {
        return 'B';
    } elseif ($average >= 70) {
        return 'C';
    } elseif ($average >= 60) {
        return 'D';
    }

    return 'F';
}

function isPassing($average)
{
    return $average >= 75;
}


function findStudentById($students, $id)
{
    foreach ($students as $student) {
        if ($student['id'] === (int) $id) {
            return $student;
        }
    }

    return null;
}

function getStats($students)
{
    $averages = array_map(function ($student) {
        return computeAverage($student['grades']);
    }, $students);

    $programs = array_map(function ($student) {
        return $student['program'];
    }, $students);

    return [
        'total'    => count($students),
        'average'  => count($averages) > 0 ? array_sum($averages) / count($averages) : 0,
        'passing'  => count(array_filter($averages, 'isPassing')),
        'programs' => count(array_unique($programs)),
    ];
}


function e($text)
{
    return htmlspecialchars((string) $text, ENT_QUOTES, 'UTF-8');
}

function renderStudentRow($student)
{
    $average = computeAverage($student['grades']);
    $status  = isPassing($average) ? 'Passing' : 'Failing';
    $class   = isPassing($average) ? 'pass' : 'fail';

    $html  = '<tr>';
    $html .= '<td><a class="student-link" href="student-details.php?id=' . (int) $student['id'] . '">'
           . e(studentNumber($student['id'])) . '</a></td>';
    $html .= '<td>' . e(formatName($student['name'])) . '</td>';
    $html .= '<td>' . e($student['email']) . '</td>';
    $html .= '<td>' . e($student['program']) . '</td>';
    $html .= '<td>Year ' . (int) $student['year'] . '</td>';
    $html .= '<td>' . e(number_format($average, 1)) . '</td>';
    $html .= '<td><span class="badge ' . $class . '">' . $status . '</span></td>';
    $html .= '<td class="actions">'
           . '<form method="post" action="delete-student.php" class="delete-form"'
           . ' onsubmit="return confirm(\'Delete this student record?\');">'
           . '<input type="hidden" name="id" value="' . (int) $student['id'] . '">'
           . '<button type="submit" class="delete-button">Delete</button>'
           . '</form></td>';
    $html .= '</tr>';

    return $html;
}

function renderStudentTable($students)
{
    if (count($students) === 0) {
        return '<p class="empty">No student records found.</p>';
    }

    $html  = '<table>';
    $html .= '<thead><tr>'
           . '<th>Student No.</th><th>Name</th><th>Email</th>'
           . '<th>Program</th><th>Year Level</th><th>Average</th><th>Status</th><th class="actions">Actions</th>'
           . '</tr></thead><tbody>';

    foreach ($students as $student) {
        $html .= renderStudentRow($student);
    }

    $html .= '</tbody></table>';

    return $html;
}
