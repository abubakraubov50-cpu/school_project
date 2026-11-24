<div class="card">
    <div class="card-header">
        <h4 class="card-header-title">Добавить информацию о школе</h4>
    </div>
    <div class="card-body">
        <form action="pages/school_info/insert.php" method="POST">

            <div class="mb-3">
                <label class="form-label">Описание школы</label>
                <textarea name="description" class="form-control" rows="4" placeholder="Краткая информация о школе, истории, направлениях обучения..." required></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Адрес</label>
                <input type="text" name="address" class="form-control" placeholder="Например: г. Худжанд, ул. Исмоили Сомони, 15" required>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Телефон</label>
                    <input type="text" name="phone" class="form-control" placeholder="Например: +992 (3422) 6-00-00" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" placeholder="Например: info@school.tj" required>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Сохранить</button>
            <a href="index.php?page=school_info" class="btn btn-white">Отмена</a>
        </form>
    </div>
</div>