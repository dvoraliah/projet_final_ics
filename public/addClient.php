<!-- Mon formulaire d'ajout de Client, permettant de choisir un client par la suite en ajout de rendez-vous -->

<?php
require_once '../src/bootstrap.php';
//Je crée un tableau d'erreur vide et un tableau data dans lequel je renseigne les nom et prenom de mes clients, puis un tableau $dataAdd dans lequel je stocke le contenue de mon GET, Si mon GET contient les index dates, start et end, je les stocke dans les variables $dates, $start et $end afin de pouvoir rediriger avec via ma fonction header
$errors = [];
$data['nom'] = $_GET['nomClient'];
$data['prenom'] = $_GET['prenomClient'];
$dataAdd = $_GET;
if ($_GET['dates']) {
    $dates = $_GET['dates'];
}
if ($_GET['start']) {
    $start = $_GET['start'];
}
if ($_GET['end']) {
    $end = $_GET['end'];
}

// Si la method POST est contenue dans ma variable $_SERVER, je crée un tableau $data dans lequel je stocke le contenue de mon POST. Je valide mes données via l'objet EventValidator, et afin de m'en servir dans mon header, je stocke le contenue de mon tableau $data index nom et prenom dans les variables $nomClient et $prenomClient.
//Si je n'ai pas d'erreur, je me connecte à ma bdd, je modifie les données afin de les passer en bdd en créant mon client.

//Je recherche l'id associé à mon client et je redirige vers la page de prise de rdv
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = $_POST;
    $validator = new EventValidator();
    $errors = $validator->validatesClient($_POST);
    $nomClient = $data['nom'];
    $prenomClient = $data['prenom'];
    if (empty($errors)) {
        $events = new Events(get_pdo());
        $event = $events->hydrateClient(new Event(), $data);
        $events->createClient($event);

        $id = $events->findIdClient($nomClient, $prenomClient);
        $id = $id['id'];
        header("Location: add.php?nomClient=$nomClient&prenomClient=$prenomClient&idClient=$id&dates=$dates&start=$start&end=$end");
        exit();
    }
}

render('header', ['title' => 'Ajouter un Rendez-vous']);


?>
<!-- Mon formulaire d'ajout de Client avec persistance des données -->
<div class="container">

    <?php if (!empty($errors)) : ?>
        <div class="alert alert-danger">
            Merci de corriger vos erreurs
        </div>
    <?php endif; ?>

    <h1>Ajouter un Client</h1>
    <form action="" method="post" class="form">
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="nom">Nom</label> <small>*</small><br>
                    <input id="nom" type="text" class="form-control" name="nom" required value="<?= isset($data['nom']) ? h($data['nom']) : ''; ?>">
                    <?php if (isset($errors['nom'])) : ?>
                        <small class="form-text text-muted"> <?= $errors['nom'] ?></p>
                        <?php endif; ?>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="prenom">Prénom</label> <small>*</small>
                    <input id="prenom" type="text" class="form-control" name="prenom" required value="<?= isset($data['prenom']) ? h($data['prenom']) : ''; ?>">
                    <?php if (isset($errors['prenom'])) : ?>
                        <small class="form-text text-muted"> <?= $errors['prenom'] ?></p>
                        <?php endif; ?>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="adresse">Adresse</label>
                    <input id="adresse" type="text" class="form-control" name="adresse" value="<?= isset($data['adresse']) ? h($data['adresse']) : ''; ?>">
                    <?php if (isset($errors['adresse'])) : ?>
                        <small class="form-text text-muted"> <?= $errors['adresse'] ?></p>
                        <?php endif; ?>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="tel">Téléphone</label> <small>*</small>
                    <input id="tel" type="tel" class="form-control" name="tel" required value="<?= isset($data['tel']) ? h($data['tel']) : ''; ?>">
                    <?php if (isset($errors['tel'])) : ?>
                        <small class="form-text text-muted"> <?= $errors['tel'] ?></p>
                        <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="mail">E-Mail</label> <small>*</small>
                    <input id="mail" type="mail" class="form-control" name="mail" placeholder="mail@mail.com" required value="<?= isset($data['mail']) ? h($data['mail']) : ''; ?>">
                    <?php if (isset($errors['mail'])) : ?>
                        <small class="form-text text-muted"> <?= $errors['mail'] ?></p>
                        <?php endif; ?>
                </div>
            </div>
        </div>
        <small class="form-groupe">Les champs marqué d'une * sont obligatoires</small>
        <div class="form-group">
            <button class="btn btn-primary">Ajouter</button>
        </div>
    </form>
</div>