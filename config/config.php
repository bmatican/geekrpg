<?php

// CONSTANTS
define( 'DELIVERY_TYPE_FULL', 1 );
define( 'DELIVERY_TYPE_CONTENT', 2 );

define( 'DS'        , DIRECTORY_SEPARATOR  );
define( 'WEB_ROOT'  , dirname(__FILE__) . DS . ".." . DS );

// DEBUG
define( 'DEBUG', true );

// GENERAL CONFIG
define( 'PATH_APPLICATIONS' , WEB_ROOT . 'applications' . DS);
define( 'PATH_CORE'         , WEB_ROOT . 'library' . DS . 'core' .DS);
define( 'PATH_CONFIG'       , WEB_ROOT . 'config' . DS);
define( 'PATH_TEMPLATES'    , WEB_ROOT . 'templates' . DS);
define( 'PATH_VIEWS'        , WEB_ROOT . 'views' . DS);

// DATABASE SPECIFIC
define( 'DB_HOST'     , 'localhost');
define( 'DB_USER'     , 'geek' );
define( 'DB_PASSWORD' , 'geekPassword' );
define( 'DB_DATABASE' , 'geekrpg');
 
// CUSTOMIZATIONS
define( 'MIN_LENGTH_USERNAME' , 5 );
define( 'MAX_LENGTH_USERNAME' , 20 );
define( 'MAX_LENGTH_EMAIL'    , 60 );
define( 'MIN_LENGTH_PASSWORD' , 6 );
define( 'MAX_LENGTH_PASSWORD' , 30 );

// TEMPLATE
define( 'PATH_CURRENT_TEMPLATE', 'Default' . DS );
define( 'CURRENT_TEMPLATE', 'DefaultTemplate' );

// SITE SPECIFIC

?>
