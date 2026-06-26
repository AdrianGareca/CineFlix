<?php $id=isset($_GET["id"])?(int)$_GET["id"]:0; header("Location: index.php?ruta=vermas&id=".$id); exit;
