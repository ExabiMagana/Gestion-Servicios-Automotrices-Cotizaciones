<form action="#" method="get">
	<input type='submit' required name='make' value='Generar'>
</form>
<?php
require('../makefont/makefont.php');

// Convertir la fuente FreeSerif.ttf
if (isset($_GET['make'])) {
	MakeFont('FreeSerifItalic.ttf', 'ISO-8859-1');
}
?>