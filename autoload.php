<?php
/**
 * Class autoloading
 *
 * @link      https://prowebcode.ru/
 * @since     1.0.0
 * @package plugin WPTelmessage
 * 
 */
 
defined( 'ABSPATH' ) || die();

spl_autoload_register( function ( $className ) {
    $namespace = 'Hrcode\WpTelMessage\\';
    $baseDir = HRCWTM_DIR . 'includes/';
    
    if (0 !== strpos($className, $namespace)) {
    return;
  }

    $classPath = str_replace( $namespace, '', $className );
    $filePath = $baseDir . str_replace( '\\', DIRECTORY_SEPARATOR, $classPath ) . '.php';

    require_once $filePath;
});
