<?php
require_once '../src/bootstrap.php';
require_once '../src/Date/EventValidator.php';
require_once '../src/App/Validator.php';
// require_once '../src/Date/Event.php';
// require_once '../src/Date/Events.php';

// $validator = new \App\Validator($data);
$errors = [];
$dataAdd = $_GET;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = $_POST;

    if ($_GET['dates']) {
        $dates = $_GET['dates'];
    }
    if ($_GET['start']) {
        $start = $_GET['start'];
    }
    if ($_GET['end']) {
        $end = $_GET['end'];
    }
    $validator = new EventValidator();
    $errors = $validator->validatesChienClient($_POST);
    // dd($data);
    // dd($errors);
    // dd($_POST['nomClient']);
    if (empty($errors)) {
        $events = new Events(get_pdo());
        $test = new Event();
        $event = $events->hydrateClientChien(new Event(), $data);

        $result2 = $events->searchIdClient($event->getName(), $event->getPrenom());
        // var_dump($result2);
        $result = $events->search($event);
        // $result2 = $events->searchIdClient($event->getName(), $event->getPrenom());
        if (isset($result2['id'])) {
            $idClient = $result2['id'];
        }
        // dd($data);

    }
}
// var_dump($result2); 
render('header', ['title' => 'Ajouter un Rendez-vous']);
// var_dump($result2);

?>
<div class="container">

    <?php if (!empty($errors)) : ?>
        <div class="alert alert-danger">
            Merci de corriger vos erreurs
        </div>
    <?php endif; ?>

    <h1>Selectionner un Client</h1>
    <form action="" method="post" class="form">
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="nomClient">Nom du client</label> <small>*</small>
                    <input id="nomClient" type="text" class="form-control" name="nomClient" required value="<?= isset($data['nomClient']) ? h($data['nomClient']) : ''; ?>">
                    <?php if (isset($errors['nomClient'])) : ?>
                        <small class="form-text text-muted"> <?= $errors['nomClient'] ?></p>
                        <?php endif; ?>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="prenomClient">Prénom du client</label>
                    <input id="prenomClient" type="text" class="form-control" name="prenomClient" value="<?= isset($data['prenomClient']) ? h($data['prenomClient']) : ''; ?>">
                    <?php if (isset($errors['prenomClient'])) : ?>
                        <small class="form-text text-muted"> <?= $errors['prenomClient'] ?></p>
                        <?php endif; ?>
                </div>
            </div>
        </div>
        <small class="form-groupe">Les champs marqué d'une * sont obligatoires</small>
        <div class="form-group">
            <button class="btn btn-primary" name="envoi">Rechercher le client</button>
        </div>
    </form>
    <?php dd($dataAdd) ?>

    <?php if (empty($_POST)) : ?>
        <a href="addClient.php?nomClient=&prenomClient=&dates=<?= $dataAdd['dates']; ?>&start=<?= $dataAdd['start']; ?>&end=<?= $dataAdd['end']; ?>" class="add__button">+ Ajouter un nouveau Client</a>
    <?php endif; ?>

    <?php if (isset($result) && $result === 0) : ?>
        Ce client n'existe pas, Voulez vous le créer ? <br>

        <a href="addClient.php?nomClient=<?= $data['nomClient']; ?>&prenomClient=<?= $data['prenomClient'] ? $data['prenomClient'] : ''; ?>&dates=<?= $dataAdd['dates']; ?>&start=<?= $dataAdd['start']; ?>&end=<?= $dataAdd['end']; ?>" class="add__button">+ Ajouter un nouveau Client</a>


        <?php else :

        if ($_SERVER['REQUEST_METHOD'] === 'POST') : ?>
            <?php if (!empty($result2)) : ?>
                <a href="addClient.php?nomClient=<?= $data['nomClient']; ?>&prenomClient=<?= $data['prenomClient'] ? $data['prenomClient'] : ''; ?>&dates=<?= $dataAdd['dates']; ?>&start=<?= $dataAdd['start']; ?>&end=<?= $dataAdd['end']; ?>" class="add__button">+ Ajouter un nouveau Client</a>
                <br><br>
                <h4>Client trouvé :</h4><br>
            <?php endif; ?>

            <?php foreach ($result2 as $result2) : ?>


                <?php if (isset($result2)) : ?>
                    <h4><?= h($result2['nom']) . ' ' . h($result2['prenom']); ?></h4>
                    <?= h($result2['adresse']) ?><br>
                    <?= h($result2['tel']) ?><br>
                    <?= h($result2['mail']); ?><br>

                    Souhaitez-vous utiliser ce client pour un Rendez-vous ?
                    <a href="add.php?nomClient=<?= $result2['nom']; ?>&prenomClient=<?= $result2['prenom']; ?>&idClient=<?= $result2['id']; ?>&nomClient=<?= $result2['nom']; ?>&dates=<?= $dataAdd['dates']; ?>&start=<?= $dataAdd['start']; ?>&end=<?= $dataAdd['end']; ?>" class="btn btn-outline-secondary">Oui</a>

                    <br>

                    Souhaitez-vous continuer vers la création d'une nouvelle fiche Chien ?
                    <a href="addChien.php?idClient=<?= $result2['id']; ?>&nomClient=<?= $result2['nom']; ?>&prenomClient=<?= $result2['prenom']; ?>&dates=<?= $dataAdd['dates']; ?>&start=<?= $dataAdd['start']; ?>&end=<?= $dataAdd['end']; ?>" class="btn btn-outline-secondary">Oui</a>
                    <a href="verifClientChien.php" class="btn btn-outline-secondary">Non, chercher à nouveau</a>
                    <hr>
                <?php endif; ?>
            <?php endforeach; ?>
            <a href="addClient.php?nomClient=<?= $data['nomClient']; ?>&prenomClient=<?= $data['prenomClient'] ? $data['prenomClient'] : ''; ?>&dates=<?= $dataAdd['dates']; ?>&start=<?= $dataAdd['start']; ?>&end=<?= $dataAdd['end']; ?>" class="add__button">+ Ajouter un nouveau Client</a>
            <br><br>

    <?php endif;
    endif;
    ?>
</div>