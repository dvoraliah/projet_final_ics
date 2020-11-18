<!-- Formulaire de recherche d'un client en bdd -->
<?php
require_once '../src/bootstrap.php';
// J'instancie un tableau d'erreur vide, et je récupère les données contenues dans mon GET dans un tableau $dataAdd
$errors = [];
$dataAdd = $_GET;

//Si je suis en POST je recupere les données de mon post dans un tableau $data, si il y a dans mon get les index dates, start et end je les stocke dans les variables $dates, $start et $end
//Je verifie les données de mon post grâce à mon objet $validator et à ma fonction validatesChienClient
//Si mon tableau d'erreur est vide, je crée un objet Event pour acceder à ma bdd, je modifie les données contenues de mon objet event pour qu'elles soient les meme que mon POST, je recherche l'id de mon client, et je regarde combien de lignes sont trouvées. Je crée une variable idClient pour y stocker l'id trouvé.
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

    if (empty($errors)) {
        $events = new Events(get_pdo());
        // $test = new Event();
        $event = $events->hydrateClientChien(new Event(), $data);
        $result2 = $events->searchIdClient($event->getName(), $event->getPrenom());
        $result = $events->search($event);
        if (isset($result2['id'])) {
            $idClient = $result2['id'];
        }
    }
}
render('header', ['title' => 'Ajouter un Rendez-vous']);

?>
<!-- Formulaire de recherche -->
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
<!-- Si je ne suis pas en POST je propose d'ajouter un nouveau client -->
    <?php if (empty($_POST)) : ?>
        <a href="addClient.php?nomClient=&prenomClient=&dates=<?= $dataAdd['dates']; ?>&start=<?= $dataAdd['start']; ?>&end=<?= $dataAdd['end']; ?>" class="add__button">+ Ajouter un nouveau Client</a>
    <?php endif; ?>
<!-- Si je ne trouve pas de resultat je propose de créer le client en récupérant le nom et prenom inscrit pour faciliter la completion du formulaire d'ajout  Sinon si je suis bien en POST, et que mon tableau $result2 n'est pas vide, je propose d'abord de créer un client, puis j'affiche les données contenue dans mon tableau $result2, je propose de l'utiliser pour ajouter un Rendez-vous ou pour la création d'un nouveau Chien -->
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
                    <hr>
                <?php endif; ?>
            <?php endforeach; ?>
            <!-- Je propose une fois de plus d'ajouter un nouveau client, afin de ne pas avoir a remonter en haut de page -->
            <a href="addClient.php?nomClient=<?= $data['nomClient']; ?>&prenomClient=<?= $data['prenomClient'] ? $data['prenomClient'] : ''; ?>&dates=<?= $dataAdd['dates']; ?>&start=<?= $dataAdd['start']; ?>&end=<?= $dataAdd['end']; ?>" class="add__button">+ Ajouter un nouveau Client</a>
            <br><br>

    <?php endif;
    endif;
    ?>
</div>