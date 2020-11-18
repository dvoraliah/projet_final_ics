<!-- Mon formulaire d'ajout de rendez-vous, il permet de selectionner un client, les chiens qui lui sont associés, la date, l'heure de debut, l'heure de fin du rdv et facultativement une description de rendez-vous. -->
<?php
require_once '../src/bootstrap.php';

//Je stocke ma date, mon heure de debut et mon heure de fin dans un tableau $data
$data =  [
    'dates' => $_GET['dates'] ?? date('Y-m-d'),
    'start' => date('H:i'),
    'end' => date('H:i', strtotime('now +1 Hour'))
];

//Je verifie que la date est correcte, sinon je la remplace par la date du jour
$validator = new Validator($data);
if (!$validator->validate('dates', 'dates')) {
    $data['dates'] = date('Y-m-d');
};
//je crée un tableau d'erreur vide
$errors = [];

//Je suis bien en post, je reinitialise mon tableau $data avec les données de mon POST
//Si j'ai deja dans mon get une valeur pour idClient dans mon GET je l'assigne à mon $data['idClient], et je recherche l'id de mon chien. 
//Je verifie que je n'ai pas d'erreur d'entrée dans mon formulaire, puis je modifie mes données pour qu'elles soient les mêmes que mon POST et je crée le rendez vous avec ces elements en BDD
//Je redirige vers la base de donnée avec un message de succès.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = $_POST;
    if (isset($_GET['idClient'])) {
        $data['idClient'] = $_GET['idClient'];

        $chiens = new Events(get_pdo());
        $chien = $chiens->findChien($_GET['idClient']);
        $idChien = $chiens->findIdChien($data['chien'], $data['idClient']);
    }
    $data['idChien'] = $idChien['id'];

    $validator = new EventValidator();
    $errors = $validator->validates($_POST);
    if (empty($errors)) {
        $events = new Events(get_pdo());
        $event = $events->hydrate(new Event(), $data);
        $events->create($event);
        header("Location: index.php?success=create");
        exit();
    }
}
//Si j'ai deja dans mon get une valeur pour idClient dans mon GET je l'assigne à mon $data['idClient], et je recherche l'id de mon chien. 
if (isset($_GET['idClient'])) {
    $chiens = new Events(get_pdo());
    $chien = $chiens->findChien($_GET['idClient']);
    $idChien = $chiens->findIdChien(isset($data['chien']), isset($data['idClient']));
}

render('header', ['title' => 'Ajouter un Rendez-vous']);

?>

<!-- Formulaire d'ajout de rendez vous avec persitance des données -->
<div class="container">

    <?php if (!empty($errors)) : ?>
        <div class="alert alert-danger">
            Merci de corriger vos erreurs
        </div>
    <?php endif; ?>

    <h1>Ajouter un rendez-vous</h1>
    <form action="" method="post" class="form">
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="name">Nom de la personne </label> <small>*</small><br>
                    <?php if (empty($_GET['nomClient'])) : ?>
                        <a href="verifClientChien.php?dates=<?= $data['dates']; ?>&start=<?= $data['start']; ?>&end=<?= $data['end']; ?>">Trouvez un Client</a>
                    <?php else :
                        $name = $_GET['nomClient'];
                        $firstName = $_GET['prenomClient'];
                    ?>
                        <input id="name" type="text" class="form-control" name="name" required value="<?= isset($name) ? h($name) : ''; ?> <?= isset($firstName) ? h($firstName) : ''; ?>" readonly>
                        <?php if (isset($errors['name'])) : ?>
                            <small class="form-text text-muted"> <?= $errors['name'] ?></p>
                            <?php endif; ?>
                        <?php endif; ?>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <?php if (!empty($_GET['nomClient'])) : ?>
                        <label for="chien">Nom du Chien </label> <small>*</small><br>
                        <div class="ajoutchien">
                            <select id="chien" name="chien" class="form-control" required>
                                <option id="0"><?= isset($data['chien']) ? h($data['chien']) : '-- Veuillez choisir un chien --'; ?></option>
                                <?php
                                $idClient = $_GET['idClient'];
                                foreach ($chien as $key => $value) : ?>
                                    <option id="<?= $value['id']; ?>" value="<?= $value['nom']; ?>" race="<?= $value['race']; ?>"><?= $value['nom'] ?></option>

                                <?php endforeach; ?>
                            </select>
                            <?php if (isset($errors['chien'])) : ?>
                                <small class="form-text text-muted"> <?= $errors['chien'] ?></p>
                                <?php endif; ?>
                                <a href="addChien.php?idClient=<?= $idClient; ?>&nomClient=<?= $name; ?>&prenomClient=<?= $firstName; ?>&dates=<?= $data['dates']; ?>&start=<?= $data['start']; ?>&end=<?= $data['end']; ?>">
                                    <small>Ajouter un nouveau chien</small>
                                </a>
                        </div>

                    <?php endif; ?>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="race">Race </label> <small>*</small>
                    <input id="race" class="form-control" name="race" type="text" value="<?= isset($data['race']) ? h($data['race']) : ''; ?>" readonly>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="dates">Date </label> <small>*</small>
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
                    <label for="start">Début </label> <small>*</small>
                    <input id="start" type="time" class="form-control" name="start" placeholder="HH:MM" required value="<?= isset($data['start']) ? h($data['start']) : ''; ?>">
                    <?php if (isset($errors['start'])) : ?>
                        <small class="form-text text-muted"> <?= $errors['start'] ?></p>
                        <?php endif; ?>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="end">Fin </label> <small>*</small>
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
        <small class="form-groupe">Les champs marqué d'une * sont obligatoires</small>

        <div class="form-group">
            <button class="btn btn-primary">Ajouter</button>
        </div>
    </form>
</div>

<?php



render('footer');
?>