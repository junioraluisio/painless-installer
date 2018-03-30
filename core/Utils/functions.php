<?php
/**
 * Created by PhpStorm.
 * User: Junior
 * Date: 09/03/2017
 * Time: 06:33
 */

/**
 * @param $msg
 * @param $eolF
 * @param $eolL
 *
 * @return string
 */
function messageBuilder($msg, $eolF, $eolL)
{
    $txt = '';
    
    for ($i = 1; $i <= $eolF; $i++) {
        $txt .= PHP_EOL;
    }
    $txt .= $msg;
    
    for ($i = 1; $i <= $eolL; $i++) {
        $txt .= PHP_EOL;
    }
    
    return $txt;
}

/**
 * @return string
 */
function messageBuilderHelp()
{
    return messageBuilder('For help type: builder -h ou builder --help', 0, 2);
}

/**
 * @param $timeStart
 * @param $timeEnd
 *
 * @return string
 */
function messageBuilderTime($timeStart, $timeEnd)
{
    $elapsedTime = number_format($timeEnd - $timeStart, 2, ',', '');
    
    $msgElapsedTime = 'Time spent:  ' . $elapsedTime . ' seconds.';
    $msgMemoryUsed  = 'Memory used: ' . round(((memory_get_peak_usage(true) / 1024) / 1024) . 2) . 'Mb';
    
    $msg = messageBuilder($msgElapsedTime, 1, 0);
    $msg .= messageBuilder($msgMemoryUsed, 1, 2);
    
    return $msg;
}

/**
 * Simple function to replicate PHP 5 behaviour
 */
function microtimeFloat()
{
    list($usec, $sec) = explode(" ", microtime());
    
    return ((float)$usec + (float)$sec);
}

/**
 * Copia o Diretório Fonte dado com todos seus sub-diretórios e
 * arquivos para o Diretório Destino indicado:
 *
 * @param string $dirFont
 * @param string $dirDestiny
 */
function copyDirectory($dirFont, $dirDestiny)
{
    if (!file_exists($dirDestiny)) {
        mkdir($dirDestiny);
    }
    
    if ($dd = opendir($dirFont)) {
        while (false !== ($arq = readdir($dd))) {
            if ($arq != "." && $arq != "..") {
                $pathIn  = "$dirFont/$arq";
                $pathOut = "$dirDestiny/$arq";
                
                if (is_dir($pathIn)) {
                    copyDirectory($pathIn, $pathOut);
                } elseif (is_file($pathIn)) {
                    copy($pathIn, $pathOut);
                }
            }
        }
        
        closedir($dd);
    }
}

/**
 * @param $dir
 * @param $path
 */
function copyArchives($dir, $path)
{
    $directory = scandir($dir);
    
    $forbidden = ['.gitignore', 'composer.lock', 'install', '.env'];
    
    $archives = [];
    
    foreach ($directory as $item) {
        $pathItem = $dir . DS . $item;
        
        if (!is_dir(($pathItem)) && !in_array($item, $forbidden)) {
            $archives[] = $item;
        }
    }
    
    foreach ($archives as $archive) {
        $source     = $dir . DS . $archive;
        $destiny    = $path . DS . $archive;
        $destinyEnv = $path . DS . '.env';
        
        ($archive == '.env.example') ? copy($source, $destinyEnv) : copy($source, $destiny);
    }
}

/**
 * Generate a random temporary filename.
 *
 * @return string
 */
function makeFilename($dir)
{
    return $dir . DS . 'temp' . DS . 'painless_' . md5(time() . uniqid()) . '.zip';
}

/**
 * @param $zipFile
 * @param $directory
 * @param $destiny
 */
function extractZip($zipFile, $directory, $destiny)
{
    $dirTemp   = $directory . DS . 'temp';
    $dirMaster = $dirTemp . DS . 'painless-painless-master';
    
    $archive = new ZipArchive;
    $archive->open($zipFile);
    $archive->extractTo($dirTemp);
    
    copyDirectory($dirMaster, $destiny);
    copyArchives($dirMaster, $destiny);
    
    $archive->close();
    
    delTree($dirTemp);
    
    
}

/**
 * @param $zipFile
 */
function cleanUp($zipFile)
{
    @chmod($zipFile, 0777);
    @unlink($zipFile);
}

/**
 * @param $dir
 *
 * @return bool
 */
function delTree($dir)
{
    $files = array_diff(scandir($dir), ['.', '..']);
    
    foreach ($files as $file) {
        $dirFile = $dir . DS . $file;
        (is_dir($dirFile)) ? delTree($dirFile) : unlink($dirFile);
    }
    
    return rmdir($dir);
}

function createEnvFile($path)
{
    $fileEnv = $path . '/.env';
    
    if(file_exists($fileEnv)){
        unlink($fileEnv);
    }
    
    $fp = fopen($fileEnv, "w");
    
    $configs = [
        '# Database',
        'DB_CONNECTION=mysql',
        'DB_HOST=127.0.0.1',
        'DB_PORT=3306',
        'DB_NAME=',
        'DB_USER=',
        'DB_PASS=' . PHP_EOL,
        '# Session',
        'SESSION_NAME=nkNluAFsFRjXFmkS' . PHP_EOL,
        '# Templates folders',
        'VIEW_PATH=public/views',
        'VIEW_CACHE=tmp/cache/views' . PHP_EOL,
        '# Token information',
        'JWT_ISSUER=http://domainname.com',
        'JWT_AUDIENCE=http://domainname.com',
        'JWT_ID=bjZW3jmxyu2co3dx',
        'JWT_SECRET=oyEgqTZinAIlD3dcuirXQuo86ZeNg5ZpbMQtECxhb9KsDHGvZv' . PHP_EOL,
        '# Paths Auth',
        'PATH_PROTECTED=/api|/app',
        'PATH_PASSTHROUGH=/auth'
    ];
    
    foreach ($configs as $config) {
        fwrite($fp, $config . PHP_EOL);
    }
    
    fclose($fp);
    
    return true;
}