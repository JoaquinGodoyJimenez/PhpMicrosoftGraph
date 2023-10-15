<?php
session_start();
include_once('./header.php');
include('../includes/microsoftGraph.php'); 

// Recuperar datos de la sesión
$username = $_SESSION['USERNAME'];
$email = $_SESSION['EMAIL'];
$photo = $_SESSION['PHOTO'];
$accessToken = $_COOKIE['TOKEN'];

$receivedEmails = getReceivedEmails($accessToken);
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <div class="card">
                <div class="card-header">
                    <h1 class="text-center">Bienvenido</h1>
                </div>
                <div class="card-body">
                    <img class="userPhoto" src="data:image/jpeg;base64,<?php echo base64_encode($photo); ?>" alt="Foto del usuario">
                    <p><b>Nombre de usuario: </b><?php echo $username; ?></p>
                    <p><b>Correo electrónico: </b><?php echo $email; ?></p>
                    <a href="../includes/logout.php" class="btn btn-danger">Cerrar sesión</a>
                </div>
            </div>
            <br>
            <div class="card">
                <div class="card-header">
                    <h1 class="text-center">Correos recibidos</h1>
                </div>
                <div class="card-body">
                    <?php 
                    if (is_array($receivedEmails)) {
                        foreach ($receivedEmails as $email) {
                            echo "<b>" . $email['subject'] . "</b><br>";
                            echo "De: " . $email['from'] . "<br>";
                            echo "Recibido: " . $email['receivedDateTime'] . "<br>";
                            echo "<a href='" . $email['link'] . "'>Abrir correo</a><br><br>";
                        }
                    } else {
                        echo $receivedEmails; // Muestra un mensaje de error si no se pudieron obtener los correos.
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include_once('./footer.php');
?>
