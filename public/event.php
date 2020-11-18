<?php

// use App\Date\Events;


require '../src/bootstrap.php';
require '../src/Date/Events.php';

$pdo = get_pdo();
$events = new Events($pdo);
if (!isset($_GET['id'])) {
    header('location: /404.php');
}

try {
    $event = $events->find($_GET['id'] ?? null);
} catch (\Exception $e) {
    e404();
}
render('header', ['title' => $event['name']]);


?>
<h1><?= h($event['name']); ?></h1>
<ul>
    <li>
        Date: <?= (new DateTime($event['start']))->format('d/m/Y'); ?>
    </li>
    <li>
        Heure de Debut: <?= (new DateTime($event['start']))->format('H:i'); ?>
    </li>
    <li>
        Date de fin: <?= (new DateTime($event['end']))->format('H:i'); ?>
    </li>
    <li>
        Description: <br> 
        <?php 
        if(null !== $event['description']){
            h($event['description']);
        }
        else {
            {echo "<i>Pas de description</i>";}
        }
        
            ?>
    </li>
</ul>
<?php






require '../views/footer.php';
?>