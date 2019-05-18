<h2 class="content__main-heading">Список задач</h2>

<form class="search-form" action="" method="get" autocomplete="off">
    <input class="search-form__input" type="text" name="search" value="<?php if(isset($search)): ?><?= strip_tags($search) ?><?php endif; ?>" placeholder="Поиск по задачам">

    <input class="search-form__submit" type="submit" name="" value="Искать">
</form>

<div class="tasks-controls">
    <nav class="tasks-switch">
        <a href="/" class="tasks-switch__item<?php if(!isset($_GET['date'])): ?> tasks-switch__item--active<?php endif; ?>">Все задачи</a>
        <a href="/?date=today" class="tasks-switch__item<?php if(isset($_GET['date']) && ($_GET['date'] === 'today')): ?> tasks-switch__item--active<?php endif; ?>">Повестка дня</a>
        <a href="/?date=tomorrow" class="tasks-switch__item<?php if(isset($_GET['date']) && ($_GET['date'] === 'tomorrow')): ?> tasks-switch__item--active<?php endif; ?>">Завтра</a>
        <a href="/?date=last" class="tasks-switch__item<?php if(isset($_GET['date']) && ($_GET['date'] === 'last')): ?> tasks-switch__item--active<?php endif; ?>">Просроченные</a>
    </nav>

    <label class="checkbox">
        <input class="checkbox__input visually-hidden show_completed" type="checkbox" <?php if
        ($showCompleteTasks === 1): ?>checked<?php endif; ?>>
        <span class="checkbox__text">Показывать выполненные</span>
    </label>
</div>

<table class="tasks">
    <?php if(isset($tasks) && (!empty($tasks))): ?>
        <?php foreach ($tasks as $task): ?>
            <?php if ((isset($task['status']) && ($task['status'] === '0')) || ($showCompleteTasks !== 0))
                : ?>
                <tr class="tasks__item task <?php if (isset($task['status']) && $task['status']): ?>task--completed<?php endif; ?> <?php  if (isset($task['deadline']) && (checkExpiration($task['deadline']) < 0)): ?>task--important<?php endif; ?>">
                    <td class="task__select">
                        <label class="checkbox task__checkbox">
                            <input class="checkbox__input visually-hidden task__checkbox"
                                   type="checkbox"
                                   value="<?php if (isset($task['id'])): ?><?= $task['id'] ?><?php endif; ?>"
                                   <?php if((isset($task['status']) && $task['status']) === true): ?>checked<?php endif; ?>>
                            <span class="checkbox__text">
                            <?php if (isset($task['name'])): ?>
                                <?= strip_tags($task['name']) ?>
                            <?php endif; ?>
                        </span>
                        </label>
                    </td>

                    <td class="task__file">
                        <?php if (isset($task['file'])): ?>
                            <a class="download-link" href="/uploads/<?= strip_tags($task['file']) ?>">
                                <?= strip_tags($task['file']) ?>
                            </a>
                        <?php endif; ?>
                    </td>

                    <td class="task__date">
                        <?php if (isset($task['deadline'])): ?>
                            <?= date('Y-m-d', strtotime(strip_tags($task['deadline']))) ?>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php elseif(isset($search)): ?>
        Ничего не найдено по вашему запросу
    <?php else: ?>
        Нет задач
    <?php endif; ?>
</table>
