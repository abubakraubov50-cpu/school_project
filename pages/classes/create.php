<div class="card">
    <div class="card-header">
        <h4 class="card-header-title">Добавить новый класс</h4>
    </div>
    <div class="card-body">
        <form action="pages/classes/insert.php" method="POST">
            <div class="mb-3">
                <label for="title" class="form-label">Название класса</label>
                <input type="text" class="form-control" id="title" name="title" placeholder="Например: 10А" required>
            </div>

            <button type="submit" class="btn btn-primary">Сохранить класс</button>
            <a href="index.php?page=classes" class="btn btn-white">Отмена</a>
        </form>
    </div>
</div>
