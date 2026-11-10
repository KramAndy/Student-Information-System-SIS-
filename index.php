<?php
require_once __DIR__ . '/includes/functions.php';

$searchTerm  = isset($_GET['q']) ? trim($_GET['q']) : '';
$allStudents = getStudents();
$students    = $searchTerm !== '' ? searchStudents($allStudents, $searchTerm) : $allStudents;
$stats       = getStats($students);

$pageTitle = 'Student Records | Student Information System';
require __DIR__ . '/includes/header.php';
?>

<section class="stats">
    <div class="card">
        <span class="card-value"><?php echo number_format($stats['total']); ?></span>
        <span class="card-label">Total Students</span>
    </div>
    <div class="card">
        <span class="card-value"><?php echo number_format($stats['average'], 1); ?></span>
        <span class="card-label">Overall Average</span>
    </div>
    <div class="card">
        <span class="card-value"><?php echo number_format($stats['passing']); ?></span>
        <span class="card-label">Passing (75+)</span>
    </div>
</section>

<?php if (isset($_GET['added'])): ?>
    <p class="notice">Student <?php echo e($_GET['added']); ?> added successfully.</p>
<?php elseif (isset($_GET['deleted'])): ?>
    <p class="notice">Student <?php echo e($_GET['deleted']); ?> deleted successfully.</p>
<?php elseif (isset($_GET['delete_error'])): ?>
    <p class="notice error">The student record could not be deleted.</p>
<?php endif; ?>

<div class="toolbar">
    <a class="button" href="add-student.php">+ Add New Student</a>
</div>

<section>
    <?php echo renderStudentTable($students); ?>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>

