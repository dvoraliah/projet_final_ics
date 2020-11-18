<?php
// Page d'erreur personnalisé, afin de renvoyé une page d'erreur 404 si la page demandée est introuvable, notamment si l'id inséré en barre d'adresse ne correspond à rien en bdd
http_response_code(404);
require '../views/header.php';

?>
<h1> Page Introuvable</h1>
<?php
require '../views/footer.php';
?>