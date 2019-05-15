<h2 class="content__main-heading">Добавление задачи</h2>

<form class="form"  action="" method="post" enctype="multipart/form-data" autocomplete="off">
    <div class="form__row">
        <label class="form__label" for="name">Название <sup>*</sup></label>

        <input class="form__input<?php if (isset($errors) && isset($errors['name'])): ?> form__input--error<?php endif; ?>" type="text" name="name" id="name" value="<?php if(isset($task) && isset($task['name'])): ?><?= strip_tags($task['name']) ?><?php endif; ?>" placeholder="Введите название">

        <?php if (isset($errors) && isset($errors['name'])): ?>
            <p class="form__message"><?= $errors['name'] ?></p>
        <?php endif; ?>
    </div>

    <div class="form__row">
        <label class="form__label" for="project">Проект <sup>*</sup></label>

        <select class="form__input form__input--select<?php if (isset($errors) && isset($errors['project'])): ?> form__input--error<?php endif; ?>" name="project" id="project">
            <?php foreach ($projects as $project): ?>
                <?php if (isset($project['id'])): ?>
                    <option value="<?= strip_tags($project['id']) ?>" <?php if(isset($task) && isset($task['project']) && ($task['project'] === strval($project['id']))): ?>selected<?php endif; ?>><?= strip_tags($project['name']) ?></option>
                <?php endif; ?>
            <?php endforeach; ?>
        </select>

        <?php if (isset($errors) && isset($errors['project'])): ?>
            <p class="form__message"><?= $errors['project'] ?></p>
        <?php endif; ?>
    </div>

    <div class="form__row">
        <label class="form__label" for="date">Дата выполнения</label>

        <input class="form__input form__input--date<?php if (isset($errors) && isset($errors['date'])): ?> form__input--error<?php endif; ?>" type="text" name="date" id="date" value="<?php if(isset($task) && isset($task['date'])): ?><?= strip_tags($task['date']) ?><?php endif; ?>" placeholder="Введите дату в формате ГГГГ-ММ-ДД">

        <?php if (isset($errors) && isset($errors['date'])): ?>
            <p class="form__message"><?= $errors['date'] ?></p>
        <?php endif; ?>
    </div>

    <div class="form__row">
        <label class="form__label" for="file">Файл</label>

        <div class="form__input-file">
            <input class="visually-hidden" type="file" name="file" id="file" value="<?php if(isset($task) && isset($task['file'])): ?><?= strip_tags($task['file']) ?><?php endif; ?>">

            <label class="button button--transparent" for="file">
                <span>Выберите файл</span>
            </label>

            <?php if (isset($errors) && isset($errors['file'])): ?>
                <p class="form__message"><?= $errors['file'] ?></p>
            <?php endif; ?>
        </div>
    </div>

    <div class="form__row form__row--controls">
        <input class="button" type="submit" name="" value="Добавить">
    </div>
</form>
