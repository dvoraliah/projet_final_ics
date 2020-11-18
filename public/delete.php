<!-- Si dans mon formulaire d'édition de rendez vous je clique sur supprimer, cette page est chargée. Elle permet de supprimer le rendez-vous puis de rediriger vers l'index -->

<?php

require '../src/bootstrap.php';

$pdo = get_pdo();
$events = new Events($pdo);
$errors = [];

try {
    $event = $events->find($_GET['id'] ?? null);
} catch (\Exception $e) {
    e404();
} catch (\Error $e) {
    header('location: /404.php');
    e404();
}

$data = [
    'id' => $event['id'],


];

        $event = new Event();
        $event->setId($data['id']);
        $events->delete($event);
        header("Location: index.php?success=delete");
        exit();
