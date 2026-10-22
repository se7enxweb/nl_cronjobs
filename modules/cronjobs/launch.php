<?php
/**
 * Get cronjobs part and launch it
 *
 * Only a part that cronjob.ini defines ([CronjobPart-<part>], or "global"
 * for [CronjobSettings]) and that nlcronjobs.ini [Forbidden] Parts[] does not
 * list may run, and it reaches the command line as one quoted argument.
 */

$Module = $Params['Module'];

$tpl = eZTemplate::factory();
$tpl->setVariable( 'module', $Module );

$http = eZHTTPTool::instance();

//get the part
if ( $http->hasPostVariable( 'part' ) )
{
    $part = trim( (string)$http->postVariable( 'part' ) );
}
else
{
    $part = '';
}

$forbiddenParts = eZINI::instance( 'nlcronjobs.ini' )->variable( 'Forbidden', 'Parts' );
$cronjobIni = eZINI::instance( 'cronjob.ini' );

if ( $part === '' || $part === 'global' )
{
    // "global" means no part for runcronjobs.php
    $allowed = !in_array( 'global', $forbiddenParts );
    $arguments = '';
}
else
{
    $allowed = preg_match( '/^[A-Za-z0-9_.-]+$/', $part )
        && $cronjobIni->hasGroup( 'CronjobPart-' . $part )
        && !in_array( $part, $forbiddenParts );
    $arguments = ' ' . escapeshellarg( $part );
}

if ( !$allowed )
{
    header( $_SERVER['SERVER_PROTOCOL'] . ' 400 Bad Request' );
    eZDebug::writeWarning( 'Refused to launch the cronjob part "' . $part . '"', 'nl_cronjobs' );
    echo 'This cronjob part does not exist or may not be launched here.';
    eZExecution::cleanExit();
}

//create the manager, add scripts, and launch them
$manager = ProcessManager::instance();
$manager->addScript( 'runcronjobs.php' . $arguments );
$manager->execAll();

eZExecution::cleanExit();

?>
