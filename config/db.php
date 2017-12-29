<?php

return [
    'class' => 'zlakomanov\oraclepack\Connection',
    'dsn' => 'oci8:dbname=NEWCONTACTBASE_DEV',
    'username' => 'RUBACHOK',
    'password' => '12345',
    'enableSchemaCache' => true,
    'charset' => 'UTF8',
    'attributes' => [PDO::ATTR_STRINGIFY_FETCHES => true]

    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
];