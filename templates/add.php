

      <?= $navigation; ?>

      <main class="content__main">
        <h2 class="content__main-heading">Добавление задачи</h2>

        <form class="form"  action="add.php" method="post"  enctype="multipart/form-data" autocomplete="off">
          <div class="form__row">
            <label class="form__label" for="name">Название <sup>*</sup></label>

            <input class="form__input <?= isset($errors['name']) ? 'form__input--error' : ''; ?>" type="text" name="name" id="name" value="<?=get_post_val('name'); ?>" placeholder="Введите название">
            <?= isset($errors['name']) ? '<p class = "form__message">' . $errors['name'] . '</p>' : ''; ?>
          </div>

          <div class="form__row">
            <label class="form__label" for="project">Проект <sup>*</sup></label>

            <select class="form__input form__input--select <?= isset($errors['project']) ?  'form__input--error' : ''; ?>" name="project" id="project">
            <option value=""></option>
            <?php foreach($projects as $project): ?>
              <option value="<?= $project['id']; ?>">
                <!-- <?= $project['name']; ?> -->
              </option>
              <?php endforeach; ?>
            </select>
            <?= isset($errors['project']) ? '<p class = "form__message">' . $errors['project'] . '</p>' : ''; ?>
          </div>

          <div class="form__row">
            <label class="form__label" for="date">Дата выполнения</label>

            <input class="form__input form__input--date <?= isset($errors['date']) ? 'form__input--error' : ''; ?>" type="text" name="date" id="date" value="<?=get_post_val('date'); ?>" placeholder="Введите дату в формате ГГГГ-ММ-ДД">
            <?= isset($errors['date']) ? '<p class = "form__message">' . $errors['date'] . '</p>' : ''; ?>
          </div>

          <div class="form__row">
            <label class="form__label" for="file">Файл</label>

            <div class="form__input-file">
              <input class="visually-hidden" type="file" name="file" id="file" value="">

              <label class="button button--transparent" for="file">
                <span>Выберите файл</span>
              </label>
            </div>
          </div>

          <div class="form__row form__row--controls">
            <input class="button" type="submit" name="" value="Добавить">
          </div>
        </form>
      </main>


