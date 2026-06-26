<?php $s=isset($_GET["seccion"])?preg_replace("/[^a-z]/","",$_GET["seccion"]):"peliculas"; header("Location: index.php?ruta=admin&seccion=".$s); exit;
