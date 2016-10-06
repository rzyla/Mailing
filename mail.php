<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<link href="/style.css" rel="stylesheet" media="screen">
		<title>Mailing ARTEH</title>
	</head>
	<body>
		<div id="body">
		
			<div id="content">
				<?php 
				
					include("lib/Mailing.php");
					
					$mailing = new Mailing();
					$mailing->init();

				?>
			</div>

			<div id="add">
				<form action="mail.php" method="post" enctype="multipart/form-data">
					Dodaj nowy mailing (ZIP): <input type="file" name="file"> <input type="submit" value="Dalej" class="submit" />
				</form>
			</div>
			
			<div id="foot">
				<h3 class="color">Zasada tworzenie archiwum:</h3>
				<ul>
					<li>Kodowanie wiadomości: UTF-8</li>
					<li>Obrazki zawarte w wiadomości muszą być w głownym katalogu</li>
					<li>Plik z treścią HTML: mail.html</li>
					<li>Plik z treścią TXT: mail.txt</li>
					<li>Calość należy spakować ZIPem i przesłać przez formularz. Archiwum nie może zawierać katalogów.</li>
				</ul>
			</div>
			
			<div id="tmp">
				<a href="mail.php?action=delete"><img src="/img/delete.jpg" alt="" /> Opróżnij katalog tmp</a>
			</div>
		</div>
	</body>
</html>