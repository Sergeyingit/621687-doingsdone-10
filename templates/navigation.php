<section class="content__side">
                <h2 class="content__side-heading">Проекты</h2>

                <nav class="main-navigation">
                    <ul class="main-navigation__list">
                        <?php foreach ($projects as $project): ?>
                        <li class="main-navigation__list-item <?= ($project['id'] == $_GET['id']) ? 'main-navigation__list-item--active' : '' ;?>">
                            <a class="main-navigation__list-item-link" href="index.php?id=<?= $project['id'] ?> "><?= strip_tags($project['name']); ?></a>
                            <span class="main-navigation__list-item-count"><?= get_sum_tasks($tasks, $project['name']); ?></span>
                        </li>

                         <?php endforeach; ?>
                    </ul>
                </nav>

                <a class="button button--transparent button--plus content__side-button"
                   href="add-form.php" target="project_add">Добавить проект</a>
            </section>
