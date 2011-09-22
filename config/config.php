<?php

// DEBUG
define( 'DEBUG', true );

// GENERAL CONFIG
define( 'DS'                , DIRECTORY_SEPARATOR  );
define( 'PATH_APPLICATIONS' , WEB_ROOT . DS . 'applications' . DS);
define( 'PATH_CORE'         , WEB_ROOT . DS . 'library' . DS . 'core' .DS);
define( 'PATH_CONFIG'       , WEB_ROOT . DS . 'config' . DS);
define( 'PATH_TEMPLATES'    , WEB_ROOT . DS . 'templates' . DS);
define( 'PATH_VIEW'         , WEB_ROOT . DS . 'config' . DS);

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
define( 'PATH_CURRENT_TEMPLATE', 'Default/' . DS );
define( 'CURRENT_TEMPLATE', 'Default_Template' );

?>
