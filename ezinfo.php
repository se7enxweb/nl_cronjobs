<?php
/**
 * NL Cronjobs extension information, shown on the about page of the administration.
 *
 * @copyright Copyright (C) 2011 - 2014 Nicolas Lescure, 1998 - 2026 7x
 * @license GNU General Public License v2.0
 * @package nl_cronjobs
 */

class nl_cronjobsInfo
{
    public static function info()
    {
        return array(
            'Name'      => '<a href="https://github.com/se7enxweb/nl_cronjobs">NL Cronjobs</a>',
            'Version'   => '1.4',
            'Author'    => 'Nicolas Lescure and 7x',
            'Copyright' => 'Copyright &copy; 2011 - 2014 Nicolas Lescure and 1998 - ' . date( 'Y' ) . ' 7x',
            'License'   => 'GNU General Public License v2.0',
            'Info_url'  => 'https://github.com/se7enxweb/nl_cronjobs'
        );
    }
}
