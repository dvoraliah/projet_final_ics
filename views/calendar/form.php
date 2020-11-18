<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            <label for="name">Titre</label>
            <input id="name" type="text" class="form-control" name="name" required value="<?= isset($data['name']) ? h($data['name']) : ''; ?>">
            <?php if (isset($errors['name'])) : ?>
                <small class="form-text text-muted"> <?= $errors['name'] ?></p>
                <?php endif; ?>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label for="dates">Date</label>
            <input id="dates" type="date" class="form-control" name="dates" required value="<?= isset($data['dates']) ? h($data['dates']) : ''; ?>">
            <?php if (isset($errors['dates'])) : ?>
                <small class="form-text text-muted"> <?= $errors['dates'] ?></p>
                <?php endif; ?>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            <label for="start">Début</label>
            <input id="start" type="time" class="form-control" name="start" placeholder="HH:MM" required value="<?= isset($data['start']) ? h($data['start']) : ''; ?>">
            <?php if (isset($errors['start'])) : ?>
                <small class="form-text text-muted"> <?= $errors['start'] ?></p>
                <?php endif; ?>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label for="end">Fin</label>
            <input id="end" type="time" class="form-control" name="end" placeholder="HH:MM" required value="<?= isset($data['end']) ? h($data['end']) : ''; ?>">
            <?php if (isset($errors['end'])) : ?>
                <p class="form-text text-muted"> <?= $errors['end'] ?></p>
            <?php endif; ?>
        </div>
    </div>
</div>
<div class="form-group">
    <label for="description">Description</label>
    <textarea name="description" id="description" class="form-control"><?= isset($data['description']) ? h($data['description']) : ''; ?></textarea>
</div>