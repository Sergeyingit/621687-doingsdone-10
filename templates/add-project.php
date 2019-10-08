<?= $navigation; ?>
<main class="content__main">
        <h2 class="content__main-heading">Добавление проекта</h2>

        <form class="form"  action="add-project.php" method="post" autocomplete="off">
          <div class="form__row">
            <label class="form__label" for="project_name">Название <sup>*</sup></label>

            <input class="form__input <?= isset($errors['date']) ? 'form__input--error' : ''; ?>" type="text" name="name" id="project_name" value="<?=strip_tags(get_post_val('date')); ?>" placeholder="Введите название проекта">
            <?= isset($errors['name']) ? '<p class = "form__message">' . $errors['name'] . '</p>' : ''; ?>
          </div>

          <div class="form__row form__row--controls">
            <input class="button" type="submit" name="" value="Добавить">
          </div>
        </form>
</main>
