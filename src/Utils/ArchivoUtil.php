<?php


namespace TesGen\Generador\Utils;


use InvalidArgumentException;

class ArchivoUtil {

    public static function createFile($path, $fileName, $contents) {
        if (!file_exists($path)) {
            mkdir($path, 0755, true);
        }

        $path = $path . '\\' . $fileName;

        file_put_contents($path, $contents);
    }

    public static function crearDirectorioSiNoExiste($path, $replace = false) {
        if (file_exists($path) && $replace) {
            rmdir($path);
        }

        if (!file_exists($path)) {
            mkdir($path, 0755, true);
        }
    }

    public static function deleteFile($path, $fileName) {
        if (file_exists($path . '//' . $fileName)) {
            return unlink($path . '//' . $fileName);
        }

        return false;
    }

    public static function deleteFolderWithFiles($dir) {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (is_dir($dir. DIRECTORY_SEPARATOR .$object) && !is_link($dir."/".$object))
                        self::deleteFolderWithFiles($dir. DIRECTORY_SEPARATOR .$object);
                    else
                        unlink($dir. DIRECTORY_SEPARATOR .$object);
                }
            }
            rmdir($dir);
        }
    }

}
