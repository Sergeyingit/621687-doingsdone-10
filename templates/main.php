

            <main class="content__main">
                <h2 class="content__main-heading">Список задач</h2>

                <form class="search-form" action="index.php" method="get" autocomplete="off">
                    <input class="search-form__input" type="text" name="search" value="" placeholder="Поиск по задачам">

                    <input class="search-form__submit" type="submit" name="" value="Искать">
                </form>

                <div class="tasks-controls">
                    <nav class="tasks-switch">
                        <a href="index.php" class="tasks-switch__item <?=empty($_GET['tasks-filter']) ? 'tasks-switch__item--active' : '' ?>">Все задачи</a>
                        <a href="index.php?tasks-filter=today" class="tasks-switch__item <?=($_GET['tasks-filter'] == 'today') ? 'tasks-switch__item--active' : '' ?>">Повестка дня</a>
                        <a href="index.php?tasks-filter=tomorrow" class="tasks-switch__item <?=($_GET['tasks-filter'] == 'tomorrow') ? 'tasks-switch__item--active' : '' ?>">Завтра</a>
                        <a href="index.php?tasks-filter=past_due" class="tasks-switch__item <?=($_GET['tasks-filter'] == 'past_due') ? 'tasks-switch__item--active' : '' ?>">Просроченные</a>
                    </nav>

                    <label class="checkbox">
                        <!--добавить сюда атрибут "checked", если переменная $show_complete_tasks равна единице-->
                        <input class="checkbox__input visually-hidden show_completed" type="checkbox" <?=($show_complete_tasks) ? 'checked' : ''; ?>>
                        <span class="checkbox__text">Показывать выполненные</span>
                    </label>
                </div>

                <?php if($error_message) : ?>
                    <?=$error_message; ?>
                <?php else : ?>
                <table class="tasks">
                    <?php if ($show_complete_tasks): ?>
                        <tr class="tasks__item task task--completed">
                            <td class="task__select">
                                <label class="checkbox task__checkbox">
                                    <input class="checkbox__input visually-hidden" type="checkbox" checked>
                                    <span class="checkbox__text">Записаться на интенсив "Базовый PHP"</span>
                                </label>
                            </td>
                            <td class="task__date">10.10.2019</td>
                            <td class="task__controls"></td>
                        </tr>
                    <?php endif; ?>
                    <?php foreach ($tasks as $task): ?>
                        <?php if (!$task['is_complete'] or ($task['is_complete'] AND $show_complete_tasks)): ?>

                            <tr class="tasks__item task <?= $task['is_complete'] ? 'task--completed' : ''; ?> <?=checks_urgency($task['date']) ? 'task--important' : ''; ?>">
                                <td class="task__select">
                                    <label class="checkbox task__checkbox">
                                        <input class="checkbox__input visually-hidden task__checkbox" type="checkbox" value="<?=$task['id'] ?? ''; ?>" <?=$task['is_complete'] ? 'checked' : ''; ?>>
                                        <span class="checkbox__text"><?=strip_tags($task['task']); ?></span>
                                    </label>
                                </td>

                                <td class="task__file">
                                    <?php if(!empty($task['file'])): ?>
                                        <a class="download-link" href="uploads/<?=strip_tags($task['file']);?>"><?=strip_tags($task['file']); ?></a>
                                    <?php endif; ?>
                                </td>

                                <td class="task__date"><?=strip_tags($task['date']); ?></td>
                            </tr>
                        <?php endif; ?>
                    <?php endforeach; ?>
                    <!--показывать следующий тег <tr/>, если переменная $show_complete_tasks равна единице-->
                </table>
                <?php endif; ?>
            </main>
