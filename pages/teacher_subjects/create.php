<?php
require_once __DIR__ . '/../../includes/conn.php';

$teachers = $conn->query("SELECT id, full_name FROM users WHERE role = 'teacher' ORDER BY full_name");
$subjects = $conn->query("SELECT id, title FROM subjects ORDER BY title");
?>

<div class="card">
    <div class="card-header">
        <h4 class="card-header-title">Назначить предмет учителю</h4>
    </div>
    <div class="card-body">
        <form action="pages/teacher_subjects/insert.php" method="POST">

            <div class="mb-3">
                <label class="form-label">Учитель</label>
                <select name="teacher_id" class="form-select" required>
                    <option value="">Выберите учителя...</option>
                    <?php while ($t = $teachers->fetch_assoc()): ?>
                        <option value="<?= $t['id'] ?>"><?= htmlspecialchars($t['full_name']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Предмет</label>

                <!-- Select -->
                <div class="tom-select-custom tom-select-custom-with-tags">
                    <select name="subjects[]" class="js-select form-select" autocomplete="off" multiple
                            data-hs-tom-select-options='{
            "placeholder": "Select a person..."
          }'>
                        <option value="">Select a person...</option>
                        <?php while ($s = $subjects->fetch_assoc()): ?>
                            <option value="<?= $s['id'] ?>"><?= htmlspecialchars($s['title']) ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>

            </div>

            <button type="submit" class="btn btn-primary">Сохранить</button>
            <a href="index.php?page=teacher_subjects" class="btn btn-white">Отмена</a>
        </form>
    </div>
</div>
