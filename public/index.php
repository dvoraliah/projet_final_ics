<?php
require '../src/bootstrap.php';
//J'accede à ma base de donnée et je crée un objet de type Events et un objet de type Month. Ceci m'est utile pour afficher mon calendrier ainsi que les rendez-vous que celui-ci contient.
//Je récupere le premier jour du mois, si le premier jour est 1 lundi il renvoit 1, 2 pour mardi, 3 pour mercredi, 4 pour jeudi, 5 pour vendredi, 6 pour samedi, 7 pour dimanche
//S'il est egal à un je le laisse tel quel, sinon je lui dis de remonter au lundi précendant afin que mon calendrier commence toujours par un lundi.
//Je récupère ensuite le nombre de semaine du mois en sélection. Puis j'établie la date de la dernière case de mon tableau.
//Je stocke mes rendez-vous entre 2 dates dans ma base de données.
$pdo = get_pdo();
$events = new Events($pdo);

$month = new Month($_GET['month'] ?? null, $_GET['year'] ?? null);
$start = $month->getFisrtDay();
$start = $start->format('N') === '1' ? $start : $month->getFisrtDay()->modify('last monday');
$weeks = $month->getWeeks();
$end = $start->modify('+' . (6 + 7 * ($weeks - 1)) . 'days');
$events = $events->getEventsBetweenByDay($start, $end);

require '../views/header.php';
?>

<div class="calendar">

    <?php if (isset($_GET['success'])) : ?>
        <?php if ($_GET['success'] === 'delete') : ?>
            <div class="container">
                <div class="alert alert-danger">
                    Suppression Effectuée.
                </div>
            </div>
            <?php elseif($_GET['success'] === 'create') : ?>
            <div class="container">
                <div class="alert alert-success">
                    Rendez-vous ajouté.
                </div>
            </div>
        <?php else : ?>
            <div class="container">
                <div class="alert alert-success">
                    Modification Effectuée.
                </div>
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <!-- Mise en place de mes fleches directionnelles pour naviguer dans mon calendrier, mois par mois -->

    <div class="d-flex flex-row align-items-center justify-content-between mx-sm-3">
        <h1><?= $month->toString(); ?></h1>
        <div>
            <a href="../public/index.php?month=<?= $month->previousMonth()->month; ?>&year=<?= $month->previousMonth()->year; ?>" class="btn btn-primary">&lt;</a>
            <a href="../public/index.php?month=<?= $month->nextMonth()->month; ?>&year=<?= $month->nextMonth()->year; ?>" class="btn btn-primary">&gt;</a>
        </div>
    </div>

    <!-- Je crée mon tableau html pour afficher mon calendrier, la date au format 'd' (qui affiche le jour du mois sur 2 chiffres) renvois vers un la page d'ajout de rendez-vous, qui conserve la date au format Y-m-d -->

    <table class="calendar__table calendar__table--<?= $weeks; ?>weeks">
        <?php for ($i = 0; $i < $weeks; $i++) : ?>
            <tr>
                <?php
                foreach ($month->day as $k => $day) :
                    $date = $start->modify("+" . ($k + $i * 7) . "days");
                    $eventsForDay = $events[$date->format('Y-m-d')] ?? [];
                    $isToday = date('Y-m-d') === $date->format('Y-m-d');
                ?>
                    <td class="<?= $month->withinMonth($date) ? '' : 'calendar__othermonth'; ?> <?= $isToday ? 'is-today' : ''; ?>">
                        <?php if ($i === 0) : ?>
                            <div class="calendar__weekday"><?= $day ?></div>
                        <?php endif; ?>
                        <a class="calendar__day" href="add.php?dates=<?= $date->format('Y-m-d') ?>"><?= $date->format('d') ?></a>

                        <!-- Pour chaque rendez-vous trouver par jour, je récupère l'id de mon chien, je me connecte à ma bdd, et je recherche le nom du chien qui correspond à l'id trouvé. Puis je les affiches dans mon calendrier à la date correspondante avec l'heure et le nom du chien -->
                        <!-- Si l'on clique sur le nom du chien, on est renvoyé vers une page d'edition de ce rendez-vous grâce à l'id du chien.-->
                        <?php foreach ($eventsForDay as $event) : ?>
                            <?php
                            $idChien = $event['id_chien'];

                            $NomEvent = new Events(get_pdo());
                            $nomChien = $NomEvent->showNameChien($idChien);

                            ?>
                            <div class="calendar__event">
                                <?= (new DateTime($event['start']))->format('H:i') ?> - <a href="../public/edit.php?id=<?= $event['id'] ?>" title="<?= 'Note chien : ' . $nomChien['description'] . ' // Note rdv : ' . $event['description'] ?>"> <?= h($nomChien['nom']); ?></a>
                            </div>
                        <?php endforeach; ?>
                    </td>

                <?php endforeach; ?>
            </tr>

        <?php endfor; ?>
    </table>

</div>

<!-- Bouton permettant d'ajouter un rendez directement -->

<a href="add.php" class="calendar__button">+</a>

<?php

require '../views/footer.php';
?>