<h2 class="content__main-heading">Список задач</h2>

<form class="search-form" action="index.php" method="post" autocomplete="off">
    <input class="search-form__input" type="text" name="" value="" placeholder="Поиск по задачам">

    <input class="search-form__submit" type="submit" name="" value="Искать">
</form>

<div class="tasks-controls">
    <nav class="tasks-switch">
        <a href="/" class="tasks-switch__item tasks-switch__item--active">Все задачи</a>
        <a href="/" class="tasks-switch__item">Повестка дня</a>
        <a href="/" class="tasks-switch__item">Завтра</a>
        <a href="/" class="tasks-switch__item">Просроченные</a>
    </nav>

    <label class="checkbox">
        <input class="checkbox__input visually-hidden show_completed" type="checkbox" <?php if
        ($showCompleteTasks === 1): ?>checked<?php endif; ?>>
        <span class="checkbox__text">Показывать выполненные</span>
    </label>
</div>

<table class="tasks">
    <?php foreach ($tasks as $task): ?>
        <?php if ((isset($task['done']) && ($task['done'] === false)) || ($showCompleteTasks !== 0))
            : ?>
            <tr class="tasks__item task <?php if(isset($task['done']) && $task['done']):
                ?>task--completed<?php endif; ?>">
                <td class="task__select">
                    <label class="checkbox task__checkbox">
                        <input class="checkbox__input visually-hidden task__checkbox"
                               type="checkbox"
                               value=""
                               <?php if((isset($task['done']) && $task['done']) === true): ?>checked<?php endif; ?>>
                        <span class="checkbox__text">
                            <?php if (isset($task['name'])): ?>
                                <?= strip_tags($task['name']) ?>
                            <?php endif; ?>
                        </span>
                    </label>
                </td>

                <td class="task__file">
                    <a class="download-link" href="#">Home.psd</a>
                </td>

                <td class="task__date">
                    <?php if (isset($task['date'])): ?>
                        <?= strip_tags($task['date']) ?>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endif; ?>
    <?php endforeach; ?>
</table>