NL Cronjobs for Exponential
===========================

NL Cronjobs adds a **Cronjobs** tab to the Exponential administration. It lists the cronjob parts
of `cronjob.ini` with their scripts, launches a part in the background with one click, and shows
(and clears) the output and error logs of what it launched.

It is for administrators who need to run a cronjob now (after an import, to send a newsletter, to
publish scheduled content) without a shell, and for sites where the crontab is not yet set up or
not under their control. It does not replace the crontab: it runs the same `runcronjobs.php` the
crontab runs, on demand.

Contents
--------

- [Features](#features)
- [Requirements](#requirements)
- [Installation](#installation)
- [Configuration](#configuration)
- [Usage](#usage)
- [Permissions](#permissions)
- [Upgrading](#upgrading)
- [Known limits](#known-limits)
- [Troubleshooting](#troubleshooting)
- [Contributing](#contributing)
- [License](#license)
- [Credits and history](#credits-and-history)

Features
--------

- **The list of cronjob parts** (`/cronjobs/list`): the global scripts of
  `[CronjobSettings] Scripts[]` and every `[CronjobPart-<part>]` of cronjob.ini, each with its
  scripts.
- **Launch a part** with a click: `php runcronjobs.php <part>` starts in the background, from the
  installation root, and the page shows a loader until it has finished.
- **Logs** (`/cronjobs/logs`): the standard output and the errors of the launched cronjobs.
- **Clear logs** (`/cronjobs/clear`): empty either log.
- **Forbidden parts**: parts listed in nlcronjobs.ini are neither shown nor launched.
- **Its own admin tab**, "Cronjobs". In the admin4 design the tab shows the clock icon before its
  name.
- English and French texts.

Requirements
------------

- Exponential 6.x, with an administration siteaccess that loads jQuery (the launch link uses it)
- PHP 8.0 or later, with `proc_open` allowed
- A PHP command line binary on the server, runnable by the user the web server runs PHP as

Installation
------------

### With Composer

From the root of the Exponential installation:

```bash
composer require se7enxweb/nl_cronjobs
```

The Exponential legacy installer places the package in `extension/nl_cronjobs`.

### By hand

1. Download the latest release from https://github.com/se7enxweb/nl_cronjobs/releases (or
   `git clone https://github.com/se7enxweb/nl_cronjobs.git`).
2. Copy the folder to `extension/nl_cronjobs`. The folder name must be `nl_cronjobs`.

### Activate it

Activate it for the admin siteaccess, in
`settings/siteaccess/<admin siteaccess>/site.ini.append.php`:

```ini
[ExtensionSettings]
ActiveAccessExtensions[]=nl_cronjobs
```

or for every siteaccess, in `settings/override/site.ini.append.php`:

```ini
[ExtensionSettings]
ActiveExtensions[]=nl_cronjobs
```

Then regenerate the autoloads and clear the caches, from the installation root:

```bash
php bin/php/ezpgenerateautoloads.php -e
php bin/php/ezcache.php --clear-all
```

Check `PhpCliPath` (below) before the first launch.

Configuration
-------------

The settings are in `extension/nl_cronjobs/settings/nlcronjobs.ini.append.php`. Override them in
`settings/override/nlcronjobs.ini.append.php`:

```ini
<?php /* #?ini charset="utf-8"?

[ProcessSettings]
# The PHP command line binary. Use the full path when "php" is not in the PATH of the web server,
# for example /usr/bin/php8.3 or /opt/plesk/php/8.3/bin/php.
PhpCliPath=php
# Seconds to wait after starting a script.
SleepTime=2
# Kept with each process; the extension does not stop a process that runs longer.
MaxExecutionTime=3600
# Where the output and the errors of the launched cronjobs are appended, relative to the
# installation root.
OutputFile=var/log/process_output.log
ErrorFile=var/log/process_error.log

[Forbidden]
# Parts that are neither listed nor launched. "global" stands for the scripts of
# [CronjobSettings], which runcronjobs.php runs without a part.
Parts[]
Parts[]=cluster_maintenance
Parts[]=unlock
#Parts[]=frequent
#Parts[]=global

*/ ?>
```

The parts and their scripts come from Exponential's own `cronjob.ini`; a part added there, for
example by an extension, appears in the list without further settings:

```ini
[CronjobSettings]
ExtensionDirectories[]=myextension

[CronjobPart-newsletter]
Scripts[]=send_newsletter.php
```

The extension also adds to `menu.ini` (the Cronjobs tab), `module.ini` (the module `cronjobs`),
`design.ini` (its templates and `cronjobs.css`), `layout.ini` (a blank layout) and `site.ini`
(translations).

Usage
-----

1. Sign in to the administration and open the **Cronjobs** tab.
2. The list shows **Global scripts** and each part with its scripts.
3. Click the name of a part to launch it. A loader turns while it runs; "Success" appears when
   the request has finished, and an alert when it failed.
4. Open **Logs** in the left menu to read what the cronjob printed, and its errors.
5. Open **Clear logs**, check the logs to empty, and press **Clear**.

The launch is a POST to `/cronjobs/launch` with the part's name (`part=frequent`, or
`part=global`). Only a part that cronjob.ini defines and that `[Forbidden] Parts[]` does not list
is launched; anything else is answered with `400 Bad Request`.

Permissions
-----------

The module `cronjobs` defines no policy functions of its own, so access is decided on the module:
give the roles that may run cronjobs the policy **cronjobs / all functions** in **User accounts →
Roles and policies**. The administrator role has it. Running a cronjob runs code on the server
with the web server's rights: give it only to people you would give a shell.

Upgrading
---------

1. Replace the extension's folder with the new release (`composer update se7enxweb/nl_cronjobs`,
   or copy the new files over the old ones).
2. Regenerate the autoloads and clear the caches (see [Activate it](#activate-it)).
3. Read [CHANGELOG.md](CHANGELOG.md). From 1.4: roles that named the module `nl_cronjobs` in a
   policy never matched anything; the module is `cronjobs`.

Known limits
------------

- A launched cronjob runs inside the web request until it ends, so a long part keeps one PHP
  worker busy; the web server's own timeouts may stop it first. `MaxExecutionTime` is stored
  but not enforced.
- The logs grow until they are cleared on the Clear logs page.
- Two clicks launch the part twice; there is no lock beyond what the cronjobs themselves do.
- The pages are made for the admin designs.

Troubleshooting
---------------

- **The tab is missing.** Check that the extension is active for the admin siteaccess, clear the
  caches, and give your role the `cronjobs` module.
- **The tab has no icon.** Icons are drawn by the admin4 design; older admin designs show the
  name only.
- **"Success", but nothing happened.** Read the Logs page. "php: not found" means `PhpCliPath`
  needs the full path of the PHP command line binary.
- **"Cronjob ... error" alert.** The request failed: a 400 means the part is unknown or
  forbidden, a refusal that the form token or the permission is missing, a timeout that the part ran
  longer than the web server allows.
- **A part is missing from the list.** It is in `[Forbidden] Parts[]`, or not defined as
  `[CronjobPart-<part>]` in cronjob.ini.

Bug reports: https://github.com/se7enxweb/nl_cronjobs/issues

Contributing
------------

Pull requests are welcome at https://github.com/se7enxweb/nl_cronjobs. Translations go in
`translations/<locale>/translation.ts` (context `extension/nlcronjobs`). The API documentation in
`doc/apidocs` was generated with phpDocumentor (`doc/build.xml`); the original notes are in
[doc/README](doc/README).

License
-------

NL Cronjobs is licensed under the GNU General Public License v2.0; see [LICENSE](LICENSE).

Credits and history
-------------------

Nicolas Lescure wrote NL Cronjobs between 2011 and 2014 for eZ Publish, the content management
system that preceded Exponential, and published it on projects.ez.no and GitHub
(https://github.com/nlescure). Brookins Consulting moved its files to the repository root. 7x
maintains it for Exponential.

- Copyright (C) 2011 - 2014 Nicolas Lescure
- Copyright (C) 1998 - 2026 7x
