<?php
require_once __DIR__ . '/includes/functions.php';

$id      = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$student = findStudentById(getStudents(), $id);

$pageTitle = 'Student Details | Student Information System';
require __DIR__ . '/includes/header.php';
?>

<?php if ($student === null): ?>
    <p class="empty">Student not found. <a href="index.php">Back to all students</a></p>
<?php else: ?>
    <?php
    $average = computeAverage($student['grades']);
    $status  = isPassing($average) ? 'Passing' : 'Failing';
    $class   = isPassing($average) ? 'pass' : 'fail';
    ?>

    <p><a href="index.php">&larr; Back to all students</a></p>

    <section class="profile">
        <div class="avatar"><?php echo e(getInitials($student['name'])); ?></div>
        <div class="profile-info">
            <h2><?php echo e(formatName($student['name'])); ?></h2>
            <p class="muted"><?php echo e(studentNumber($student['id'])); ?></p>
            <p><strong>Program:</strong> <?php echo e($student['program']); ?></p>
            <p><strong>Year Level:</strong> Year <?php echo (int) $student['year']; ?></p>
            <p><strong>Email:</strong> <?php echo e($student['email']); ?></p>
            <p><strong>Status:</strong> <span class="badge <?php echo $class; ?>"><?php echo $status; ?></span></p>
        </div>
    </section>

    <section>
        <h3>Subject Grades</h3>
        <table>
            <thead>
                <tr><th>Subject</th><th>Grade</th></tr>
            </thead>
            <tbody>
                <?php foreach ($student['grades'] as $subject => $grade): ?>
                    <tr>
                        <td><?php echo e($subject); ?></td>
                        <td><?php echo e(number_format($grade, 0)); ?></td>
                    </tr>
                <?php endforeach; ?>
                <tr class="total-row">
                    <td><strong>Average</strong></td>
                    <td>
                        <strong><?php echo e(number_format($average, 1)); ?></strong>
                        (<?php echo e(getLetterGrade($average)); ?>)
                    </td>
                </tr>
            </tbody>
        </table>
    </section>
<?php endif; ?>

<?php require __DIR__ . '/includes/footer.php'; ?>
