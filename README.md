> Need Norwegian stop words for SearchWP? I have [a plugin that adds them](https://github.com/soderlind/norwegian-stopwords-searchwp4)

# Autoactivate SearchWP default Index

This plugin will autoactivate SearchWP default index when the SearchWP plugin is activated. The plugin emulates the "Save" button on the SearchWP Engines tab.

I use this on a multisite installation where I automatically activate SearchWP on new sites, and I want the default index to be active whitout any manual intervention.

# Prerequisites

- [SearchWP](https://searchwp.com/)
- Define til SearchWP license key in wp-config.php
  ```php
  define( 'SEARCHWP_LICENSE_KEY', 'your license key' ); // On a multisite you need an agency license.
  ```

# Installation

1. Download the plugin
2. Upload the plugin to the must use plugins directory, usually `/wp-content/mu-plugins/`

# Copyright and License

Autoactivate SearchWP default Index is copyright 2023 Per Søderlind

Autoactivate SearchWP default Index is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 2 of the License, or (at your option) any later version.

Autoactivate SearchWP default Index is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

You should have received a copy of the GNU Lesser General Public License along with the Extension. If not, see http://www.gnu.org/licenses/.
