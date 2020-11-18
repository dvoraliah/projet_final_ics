<!-- Formulaire d'édition des rdv. -->

<?php

require '../src/bootstrap.php';

//J'accede à ma base de donnée, et je crée un tableau d'erreur vide
$pdo = get_pdo();
$events = new Events($pdo);
$errors = [];
//Je renvoie un page d'erreur si l'id de mon rendezvouscontenu en get n'est pas connu, sinon je le stocke dans ma variable event sous forme de tableau
try {
    $event = $events->find($_GET['id'] ?? null);
} catch (\Exception $e) {
    e404();
} catch (\Error $e) {
    header('location: /404.php');
    e404();
}

//Je recherche le nom de mon chien, le nom de mon client et l'id de mon chien dans des variables
$nomChien = $events->findNomChien($event['id_chien']);
$nomClient = $events->findNomClient($event['id_client']);
$raceChien = $events->findRaceChien($event['id_chien']);


render('header', ['title' => $nomChien['nom'] . ' ' . $nomClient['nom'] . ' ' . $event['start']]);

//Je remplis mon tableau $data avec les éléments de mon event

$data = [
    'id' => $event['id'],
    'nomChien' => $nomChien['nom'],
    'nomClient' => $nomClient['nom'],
    'dates' => substr($event['start'], 0, 10),
    'start' => substr($event['start'], 11, 5),
    'end' => substr($event['end'], 11, 5),
    'description' => $event['description'],
    'race' => $raceChien['race']
];


//Si je suis en methode post, je verifie mes données et si je n'ai pas d'erreur, je modifie mes données pour qu'elles soient les memes que mon post puis je modifie mes données du rdv en bdd
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = $_POST;
    $validator = new EventValidator();
    $errors = $validator->validatesEdit($data);
    if (empty($errors)) {
        $event = new Event();
        $event->setId($data['id']);
        $events->hydrateEdit($event, $data);
        $events->update($event);
        header("Location: index.php?success=1");
        exit();
    }
}

?>

<div class="container">
    <h1> Editer l'évènement Rendez-vous : <small><?= h($data['nomChien']); ?></small></h1>
    <form action="" method="post" class="form">
        <input id="id" type="hidden" name="id" value="<?= $data['id'] ?>">
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="nomClient">Nom</label>
                    <input id="nomClient" type="text" class="form-control" name="nomClient" readonly required value="<?= isset($data['nomClient']) ? h($data['nomClient']) : ''; ?>">
                    <?php if (isset($errors['nomClient'])) : ?>
                        <small class="form-text text-muted"> <?= $errors['nomClient'] ?></p>
                        <?php endif; ?>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="nomChien">Nom Chien</label>
                    <input id="nomChien" type="text" class="form-control" name="nomChien" readonly required readonly value="<?= isset($data['nomChien']) ? h($data['nomChien']) : ''; ?>">
                    <?php if (isset($errors['nomChien'])) : ?>
                        <small class="form-text text-muted"> <?= $errors['nomChien'] ?></p>
                        <?php endif; ?>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="race">Race</label>
                    <input id="race" class="form-control" name="race" type="text" value="<?= isset($data['race']) ? h($data['race']) : ''; ?>" readonly>
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
        <button class="btn btn-primary ">Modifier</button>
    </form>
    <br>
    <form action="delete.php?id=<?= $event['id'] ?>" method="post">
        <button class="btn btn-danger">Supprimer</button>
    </form>
    <br>


</div>
</div>

<?php
render('footer');
?>