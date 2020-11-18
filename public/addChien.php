<!-- Mon formulaire d'ajout de Chien, il permet après qu'un client ait été selectionné dans le formulaire add.php, d'ajouter des chiens à un client afin de les selectionner par la suite. -->

<?php
require_once '../src/bootstrap.php';
// Je crée un tableau d'erreurs vide, et un tableau $dataAdd qui recupere les données stockées dans mon GET. Afin de simplifier mon code par la suite, je récupére directement dans des variables certaines données de mon GET.
$errors = [];
$dataAdd = $_GET;

$idClient = $_GET['idClient'];
$nomClient = $_GET['nomClient'];
$prenomClient = $_GET['prenomClient'];
$dates = $_GET['dates'];
$start = $_GET['start'];
$end = $_GET['end'];

//Si la methode POST est enregistrée dans ma variable $_SERVER, je crée un tableau $data3 avec les données de mon POST. Je me sers de ma variable $idClient pour l'ajouter en tant qu'index 'id' dans mon tableau $data3, cela me sert à remplir mon champs id_client en bdd, afin d'associer mes chiens et clients par la suite. Je crée une objet EventValidator pour vérifier que les données de mon POST sont correctes, si ce n'est pas le cas, il retournera un tableau d'erreurs.
//Si mon tableau d'erreur est vide je modifie mes données pour qu'elles correspondent à mon POST et j'envois mes données en bdd
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data3 = $_POST;
    $data3['id'] = $idClient;
    $validator = new EventValidator();
    $errors = $validator->validatesChien($_POST);

    if (empty($errors)) {
        $events = new Events(get_pdo());
        $event = $events->hydrateChien(new Event(), $data3);
        $events->createChien($event);
    }
}
render('header', ['title' => 'Ajouter un Rendez-vous']);

?>

<!-- Formulaire d'ajout de chien, avec persistance des données récupérées dans mon $data3 -->
<div class="container">
    <h1>Ajouter un Chien</h1>

    <form action="" method="post" class="form">
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="nomChien">Nom du Chien</label> <small>*</small><br>
                    <input id="nomChien" type="text" class="form-control" name="nomChien" required value="<?= isset($data3['nomChien']) ? h($data3['nomChien']) : ''; ?>">
                    <?php if (isset($errors['nomChien'])) : ?>
                        <small class="form-text text-muted"> <?= $errors['nomChien'] ?></p>
                        <?php endif; ?>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="age">Age</label> <small>*</small>
                    <input id="age" type="text" class="form-control" name="age" required value="<?= isset($data3['age']) ? h($data3['age']) : ''; ?>">
                    <?php if (isset($errors['age'])) : ?>
                        <small class="form-text text-muted"> <?= $errors['age'] ?></p>
                        <?php endif; ?>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="race">Race</label> <small>*</small>
                    <input id="race" type="text" class="form-control" name="race" required value="<?= isset($data3['race']) ? h($data3['race']) : ''; ?>">
                    <?php if (isset($errors['race'])) : ?>
                        <small class="form-text text-muted"> <?= $errors['race'] ?></p>
                        <?php endif; ?>
                </div>
            </div>


            <div class="col-sm-12">

                <label for="description">Description</label>
                <textarea name="description" id="description" class="form-control"><?= isset($data3['description']) ? h($data3['description']) : ''; ?></textarea>

            </div>
        </div>


        <small class="form-groupe">Les champs marqué d'une * sont obligatoires</small>
        <div class="form-group">
            <button class="btn btn-primary">Ajouter</button>
        </div>

        <!-- Si la variable $events a été créee, et que l'objet events renvoie true après la fonction create, j'affiche un lien permettant de retourner vers l'ajout de rendez-vous en gardant mes données utiles dans ma barre d'adresse, sinon je peux créer un nouveau chien-->

            <?php if (isset($events) AND $events->createChien($event) === true) : ?>
                <fieldset>Chien ajouté avec succès, vous pouvez ajouter un nouveau chien ou <a href="add.php?nomClient=<?= $_GET['nomClient']; ?>&idClient=<?= $idClient; ?>&prenomClient=<?= $prenomClient; ?>&dates=<?= $dataAdd['dates']; ?>&start=<?= $dataAdd['start']; ?>&end=<?= $dataAdd['end']; ?>">retourner à l'ajout de rendez-vous ?</a> </fieldset>

            <?php endif; ?>

    </form>
</div>