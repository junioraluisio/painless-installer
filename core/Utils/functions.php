<?php
/**
 * Created by PhpStorm.
 * User: Junior
 * Date: 09/03/2017
 * Time: 06:33
 */

/**
 * Altera o padrão do caminho do diretório
 *
 * @param string $dir
 * @param string $path
 *
 * @return mixed
 */
function path($dir, $path)
{
    $pathFinal = $dir . '_' . $path;
    
    return str_replace('_', DIRECTORY_SEPARATOR, $pathFinal);
}

/**
 * @return string
 */
function relativePath()
{
    $scheme = isset($_SERVER['REQUEST_SCHEME']) ? $_SERVER['REQUEST_SCHEME'] : 'http';
    $host   = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost';
    $uri    = isset($_SERVER['SCRIPT_NAME']) ? $_SERVER['SCRIPT_NAME'] : 'SCRIPT_NAME';
    
    $arrUri = explode('/', $uri);
    
    array_pop($arrUri);
    
    $url = implode('/', $arrUri);
    
    return $scheme . '://' . $host . $url;
}

/**
 * @param        $string
 * @param string $slug
 *
 * @return mixed|string
 */
function flag($string, $slug = '_')
{
    $string = strtolower(utf8_decode($string));
    
    // Código ASCII das vogais
    $ascii['a'] = range(224, 230);
    $ascii['A'] = range(192, 197);
    $ascii['e'] = range(232, 235);
    $ascii['E'] = range(200, 203);
    $ascii['i'] = range(236, 239);
    $ascii['I'] = range(204, 207);
    $ascii['o'] = array_merge(range(242, 246), [
        240,
        248
    ]);
    $ascii['O'] = range(210, 214);
    $ascii['u'] = range(249, 252);
    $ascii['U'] = range(217, 220);
    
    // Código ASCII dos outros caracteres
    $ascii['b'] = [223];
    $ascii['c'] = [231];
    $ascii['C'] = [199];
    $ascii['d'] = [208];
    $ascii['n'] = [241];
    $ascii['y'] = [
        253,
        255
    ];
    
    foreach ($ascii as $key => $item) {
        $acentos = '';
        foreach ($item AS $codigo) {
            $acentos .= chr($codigo);
        }
        $troca[$key] = '/[' . $acentos . ']/i';
    }
    
    $string = preg_replace(array_values($troca), array_keys($troca), $string);
    
    // Troca tudo que não for letra ou número por um caractere ($slug)
    $string = preg_replace('/[^a-z0-9]/i', $slug, $string);
    // Tira os caracteres ($slug) repetidos
    $string = preg_replace('/' . $slug . '{2,}/i', $slug, $string);
    $string = trim($string, $slug);
    $string = strtolower($string);
    
    return $string;
}

/**
 * @param string $text
 *
 * @return string
 */
function token(string $text)
{
    $salt = '?#ATEw_u6p@draHEyakeD32$eStAG7=$';
    
    $text = preg_replace('/[^0-9]/', '', $text);
    
    $txt = md5($text) . '.' . md5($salt);
    
    return sha1($txt);
}

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

function messageBuilderHelp()
{
    return messageBuilder('For help type: builder -h ou builder --help', 0, 2);
}

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
 * Download the temporary Zip to the given file.
 *
 * @param  string $zipFile
 * @param  string $version
 *
 * @return $this
 */
function download($zipFile, $version = 'master')
{
    switch ($version) {
        case 'develop':
            $filename = 'latest-develop.zip';
            break;
        case 'master':
            $filename = 'latest.zip';
            break;
    }
    $response = (new Client)->get('https://github.com/junioraluisio/painless/archive/master.zip');
    file_put_contents($zipFile, $response->getBody());
    
    return $this;
}

/**
 * Generate a random temporary filename.
 *
 * @return string
 */
function makeFilename()
{
    return getcwd() . '/painless_' . md5(time() . uniqid()) . '.zip';
}

/**
 * Extract the Zip file into the given directory.
 *
 * @param  string $zipFile
 * @param  string $directory
 *
 * @return $this
 */
function extractZip($zipFile, $directory, $destiny)
{
    $dirTemp   = $directory . DS . 'temp';
    $dirMaster = $dirTemp . DS . 'painless-master';
    
    $archive = new ZipArchive;
    $archive->open($zipFile);
    $archive->extractTo($dirTemp);
    
    copyDirectory($dirMaster, $destiny);
    copyArchives($dirMaster, $destiny);
    
    delTree($dirTemp);
    
    $archive->close();
}

/**
 * Clean-up the Zip file.
 *
 * @param  string $zipFile
 *
 * @return $this
 */
function cleanUp($zipFile)
{
    @chmod($zipFile, 0777);
    @unlink($zipFile);
}

function delTree($dir)
{
    $files = array_diff(scandir($dir), ['.', '..']);
    foreach ($files as $file) {
        (is_dir("$dir/$file")) ? delTree("$dir/$file") : unlink("$dir/$file");
    }
    
    return rmdir($dir);
}