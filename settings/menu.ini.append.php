<?php /*

[NavigationPart]
Part[nlcronjobnavigationpart]=NL Cronjobs

[TopAdminMenu]
Tabs[]=nlcronjobs

[Topmenu_nlcronjobs]
# Icon - the icon the admin4 design draws before the tab's name: a name of design.ini
#        [AdminDesignSettings] IconList[] (Bootstrap Icons); older admin designs ignore it.
Icon=clock
NavigationPartIdentifier=nlcronjobnavigationpart
Name=Cronjobs
Tooltip=Cronjobs dashboard
URL[]
URL[default]=cronjobs/list
Enabled[]
Enabled[default]=true
Enabled[browse]=false
Enabled[edit]=false
Shown[]
Shown[default]=true
Shown[edit]=true
Shown[navigation]=true
# We don't show it in browse mode
Shown[browse]=true
PolicyList[]=cronjobs/list
*/ ?>
