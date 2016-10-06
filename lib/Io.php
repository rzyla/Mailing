<?php 
	
class Io
{
	public static function createDirectory($dir)
	{
		mkdir($dir, 0755);
	}
	
	public static function deleteDirectory($dir, $rmdir = false)
	{
		if(!empty($dir) && is_dir($dir))
		{
			$it = new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS);
			$files = new RecursiveIteratorIterator($it, RecursiveIteratorIterator::CHILD_FIRST);
			
			foreach($files as $file)
			{
				if ($file->isDir())
				{
					rmdir($file->getRealPath());
				}
				else 
				{
					unlink($file->getRealPath());
				}
			}
			
			if($rmdir == true)
			{
				rmdir($dir);
			}
		}
	}
}

?>