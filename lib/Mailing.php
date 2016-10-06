<?php
header('Content-Type: text/html; charset=utf-8');

require_once ('lib/PHPMailer/class.phpmailer.php');
require_once ('lib/Io.php');

class Mailing
{
	private $mailFrom = 'mailing@arteh.pl';
	private $mailSubject = 'Mailing ARTEH';

	public function __construct($mailFrom = '', $mailSubject = '')
	{
		if (!empty($mailFrom))
		{
			$this->mailFrom = $mailFrom;
		}
		
		if (!empty($mailSubject))
		{
			$this->mailSubject = $mailSubject;
		}
	}

	public function init()
	{
		if (!empty($_POST ['action']))
		{
			$_GET ['action'] = $_POST ['action'];
		}
		
		switch ($_GET ['action'])
		{
			case 'delete' :
				Io::deleteDirectory('tmp');
				$this->message('success', 'Katalog oprożniony');
				break;
			
			case 'send' :
				$this->send();
				break;
			
			default :
				$this->upload();
				break;
		}
	}

	private function message($class, $message)
	{
		echo '<div id="message" class="' . $class . '">' . $message . '</div>';
	}

	private function upload()
	{
		if (!empty($_FILES ["file"] ["tmp_name"]))
		{
			$file = md5(date("YmdHis") . '_' . $_FILES ["file"] ["name"]);
			$dir = "tmp/" . $file;
			$imgdir = 'http://' . $_SERVER ['HTTP_HOST'] . '/' . $dir . '/';
			
			Io::createDirectory($dir);
			move_uploaded_file($_FILES ["file"] ["tmp_name"], $dir . "/" . $file . ".zip");
			
			$zip = new ZipArchive();
			
			if ($zip->open($dir . "/" . $file . ".zip") === TRUE)
			{
				$zip->extractTo($dir . "/");
				$zip->close();
				
				unlink($dir . "/" . $file . ".zip");
				
				$data = file_get_contents($dir . "/mail.html");
				$data = str_replace('src="', 'src="' . $dir . "/", $data);
				file_put_contents($dir . "/mail.html", $data);
				
				header("Location: mail.php?plik=tmp/" . $file);
				exit();
			}
			else
			{
				$this->message('error', 'Błąd rozpakowania archiwum');
			}
		}
		else
		{
			
			if (!empty($_GET ['plik']))
			{
				echo '<form action="mail.php?plik=' . $_GET ['plik'] . '&action=send" method="post" style="text-align: center">
							Wyslij na adres: <input type="text" name="email" value="' . $_POST ['email'] . '"> <input type="submit" value="Wyslij" class="submit" />
					</form>';
				
				include ($_GET ['plik'] . "/mail.html");
			}
		}
	}

	private function send()
	{
		if (!file_exists($_GET ['plik'] . "/mail.html"))
		{
			die("Brak pliku mail.html");
		}
		else
		{
			$html = file_get_contents($_GET ['plik'] . "/mail.html");
		}
		
		if (file_exists($_GET ['plik'] . "/mail.txt"))
		{
			$txt = file_get_contents($_GET ['plik'] . "/mail.txt");
		}
		
		$mail = new PHPMailer();
		$mail->CharSet = 'UTF-8';
		$mail->Subject = $this->mailSubject;
		$mail->AltBody = $txt;
		$mail->MsgHTML($html);
		$mail->AddReplyTo($this->mailFrom);
		$mail->SetFrom($this->mailFrom);
		$mail->AddAddress($_POST ['email']);
		
		if (!$mail->Send())
		{
			$this->message('error', 'Błąd wysyłki: ' . $mail->ErrorInfo);
		}
		else
		{
			$this->message('success', 'Mailing wysłany');
		}
	}
}

?>