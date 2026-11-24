<div class="card">
  <div class="card-header">
    <h4 class="card-header-title">Добавить новый предмет</h4>
  </div>
  <div class="card-body">
    <form action="pages/subjects/insert.php" method="POST">
      <div class="mb-3">
        <label for="title" class="form-label">Название предмета</label>
        <input type="text" class="form-control" id="title" name="title" placeholder="Например: Математика" required>
      </div>
      <button type="submit" class="btn btn-primary">Сохранить предмет</button>
      <a href="index.php?page=subjects" class="btn btn-white">Отмена</a>
    </form>
  </div>
</div>