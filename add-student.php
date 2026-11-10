<?php
require_once __DIR__ . '/includes/functions.php';

$gradeFields = [
    'programming' => 'Programming',
    'web_systems' => 'Web Systems',
    'database'    => 'Database',
];

$programs = [
    'BS Information Technology',
    'BS Computer Science',
    'BS Accountancy',
];

$values = [
    'name'        => '',
    'email'       => '',
    'program'     => 'BS Information Technology',
    'year'        => '4',
    'programming' => '',
    'web_systems' => '',
    'database'    => '',
];

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($values as $field => $default) {
        if (isset($_POST[$field])) {
            $values[$field] = trim((string) $_POST[$field]);
        }
    }

    if ($values['name'] === '') {
        $errors['name'] = 'Name is required.';
    }

    if ($values['email'] === '') {
        $errors['email'] = 'Email is required.';
    } elseif (filter_var($values['email'], FILTER_VALIDATE_EMAIL) === false) {
        $errors['email'] = 'Enter a valid email address.';
    }

    if (!in_array($values['program'], $programs, true)) {
        $errors['program'] = 'Select a valid program.';
    }

    if (!in_array($values['year'], ['1', '2', '3', '4'], true)) {
        $errors['year'] = 'Select a year level from 1 to 4.';
    }

    $grades = [];

    foreach ($gradeFields as $field => $subject) {
        $value = $values[$field];

        if ($value === '' || !is_numeric($value) || $value < 0 || $value > 100) {
            $errors[$field] = 'Enter a grade from 0 to 100.';
        } else {
            $grades[$subject] = (float) $value;
        }
    }

    if (count($errors) === 0) {
        $student = addStudent(
            $values['name'],
            $values['email'],
            $values['program'],
            (int) $values['year'],
            $grades
        );

        if ($student !== false) {
            header('Location: index.php?added=' . urlencode(studentNumber($student['id'])));
            exit;
        }

        $errors['general'] = 'The student record could not be saved. Check that includes/students.json is writable.';
    }
}

$pageTitle = 'Add Student | Student Information System';
require __DIR__ . '/includes/header.php';
?>

<div class="toolbar">
    <h2>New Student Entry</h2>
    <a class="button-secondary" href="index.php">&larr; Back to all students</a>
</div>

<?php if (isset($errors['general'])): ?>
    <p class="notice error"><?php echo e($errors['general']); ?></p>
<?php endif; ?>

<form method="post" action="add-student.php" class="entry-form">
    <div class="form-grid">
        <div class="field">
            <label for="name">Full Name</label>
            <input type="text" id="name" name="name" value="<?php echo e($values['name']); ?>" required>
            <?php if (isset($errors['name'])): ?>
                <span class="error"><?php echo e($errors['name']); ?></span>
            <?php endif; ?>
        </div>

        <div class="field">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?php echo e($values['email']); ?>" required>
            <?php if (isset($errors['email'])): ?>
                <span class="error"><?php echo e($errors['email']); ?></span>
            <?php endif; ?>
        </div>

        <div class="field">
            <label for="program">Program</label>
            <select id="program" name="program" required>
                <?php foreach ($programs as $program): ?>
                    <option value="<?php echo e($program); ?>" <?php echo $values['program'] === $program ? 'selected' : ''; ?>>
                        <?php echo e($program); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <?php if (isset($errors['program'])): ?>
                <span class="error"><?php echo e($errors['program']); ?></span>
            <?php endif; ?>
        </div>

        <div class="field">
            <label for="year">Year Level</label>
            <select id="year" name="year" required>
                <?php foreach ([1, 2, 3, 4] as $year): ?>
                    <option value="<?php echo $year; ?>" <?php echo $values['year'] === (string) $year ? 'selected' : ''; ?>>
                        Year <?php echo $year; ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <?php if (isset($errors['year'])): ?>
                <span class="error"><?php echo e($errors['year']); ?></span>
            <?php endif; ?>
        </div>

        <?php foreach ($gradeFields as $field => $subject): ?>
            <div class="field">
                <label for="<?php echo e($field); ?>"><?php echo e($subject); ?> Grade</label>
                <input type="number" id="<?php echo e($field); ?>" name="<?php echo e($field); ?>"
                       value="<?php echo e($values[$field]); ?>" min="0" max="100" step="1" required>
                <?php if (isset($errors[$field])): ?>
                    <span class="error"><?php echo e($errors[$field]); ?></span>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="form-actions">
        <button type="submit">Save Student</button>
        <a class="button-secondary" href="index.php">Cancel</a>
    </div>
</form>

<?php require __DIR__ . '/includes/footer.php'; ?>
