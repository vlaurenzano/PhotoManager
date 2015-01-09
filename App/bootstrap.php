<?php
namespace App;

/**
 * our configs
 */
$configs = parse_ini_file('config.ini', TRUE);

//our environment
$env = $configs['env'];
$dbConfig = $configs[$env . "_database"];

/**
 * show errors if we're on dev
 */
if( $env === 'dev' ){
  error_reporting(E_ALL | E_STRICT);  
  register_shutdown_function( function(){       
    if( $error = error_get_last()){ // it's okay to put a single = in a conditional once in a blue
     var_dump($error);
    }
  });
} else {
  set_error_handler( function( $errno, $errstr, $errfile, $errline, $errcontext ){
    //probably want to email these to us or write to log if prod
  });
  register_shutdown_function( function(){   
    //going to want to catch fatal errors as well. check if any occured
  });
}

/**
 * Was planning on just using the default spl_autoload_register, but on linix ( which I'm on at the moment ), all your file names hve to
 * be lower case. I grabbed another generic method from another project on the machine because I didn't want to lowercase my classes 
 * 
 */
//if lowercase or windows, default implementation will be faster
spl_autoload_register();
spl_autoload_register(
  function ( $class_name )
    {    /* use if you need to lowercase first char *
        $class_name  =  implode( DIRECTORY_SEPARATOR , array_map( 'lcfirst' , explode( '\\' , $class_name ) ) );/* else just use the following : */
        $class_name  =  implode( DIRECTORY_SEPARATOR , explode( '\\' , $class_name ) );
        static $extensions  =  array();
        if ( empty($extensions ) )
            {
                $extensions  =  array_map( 'trim' , explode( ',' , spl_autoload_extensions() ) );
            }
        static $include_paths  =  array();
        if ( empty( $include_paths ) )
            {
                $include_paths  =  explode( PATH_SEPARATOR , get_include_path() );
            }
        foreach ( $include_paths as $path )
            {
                $path .=  ( DIRECTORY_SEPARATOR !== $path[ strlen( $path ) - 1 ] ) ? DIRECTORY_SEPARATOR : '';
                foreach ( $extensions as $extension )
                    {
                        $file  =  $path . $class_name . $extension;
                        if ( file_exists( $file ) && is_readable( $file ) )
                            {
                                require $file;
                                return;
                            }
                    }
            }
        throw new \Exception( _( 'class ' . $class_name . ' could not be found.' ) );
    } , TRUE , FALSE       
);

/**
 * Load the registry which will manage our dependencies
 */
$registry = new Library\Registry();       

/**
 * Register db as a singleton
 * normally we would have another level of abstraction, but we'll use raw pdo here
 */
$registry->register('db', function() use ($dbConfig) {
  static $db = false;
  if( $db ){
    return $db;
  }
  $db = new \PDO('mysql:host=localhost;dbname=' . $dbConfig['dbname'], $dbConfig['username'], $dbConfig['password']);
  return $db;
});

/**
 * register our server clas
 */
$registry->register( 'server', function() {
  static $server;
  if( $server ){
    return $server;
  }
  $server = new Library\Server();
  return $server;
});

/**
 * register our server clas
 */
$registry->register( 'defaultController', function() use ( $registry ) {   
  return new Library\Controller($registry);
});


$router = new Library\Router( $registry );

return $router->route();

