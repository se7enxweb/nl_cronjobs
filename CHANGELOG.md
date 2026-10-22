NL Cronjobs changelog
=====================

The changes of each release, newest first.

1.5.0 (2026-10-21)
------------------

- Updated: The Cronjobs tab of the top menu shows the clock icon in the admin4 design
  (`Icon=clock` in [Topmenu_nlcronjobs] of menu.ini.append.php). Older admin designs ignore the
  setting.
- Fixed: Launching a part only runs a part that cronjob.ini defines and `[Forbidden] Parts[]`
  does not list; anything else is answered with 400. The part and the script path reach the
  command line quoted, and runcronjobs.php runs from the installation root instead of the web
  server's document root.
- Fixed: The launch request sends the form token (X-CSRF-Token) and encodes the part's name.
- Fixed: The log page escapes the logs before it shows them.
- Fixed: The logs are cleared only by the posted form of the Clear logs page.
- Fixed: module.ini lists the module as `cronjobs` (it said `nl_cronjobs`).
- Added: composer.json (se7enxweb/nl_cronjobs) and extension.xml; ezinfo.php states the release,
  the copyright, the license and the website.
- Added: The text of the GNU GPL v2 as LICENSE.
- Updated: The README is rewritten for new users: installation with Composer and by hand,
  settings, usage, permissions, known limits and troubleshooting.
- Added: This changelog.

The version follows ezinfo.php (1.4); the repository's older tags are stable-1.0 and stable-1.3.

