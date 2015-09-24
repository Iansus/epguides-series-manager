# epguides-series-manager
Stay tuned with the latest aired episodes of your favorite series &amp; keep track of where you left.

## Install

- Import database.sql to your favorite DBMS
- Rename `models/db-sample.class.php` to `models/db.class.php`
- Configure access to DMBS in this file

## Example

If you wich to add "The Flash" (2014 edition), you can use the following parameters:
- Name: `The Flash`
- Epguides: `flash_2014`
- Binsearch: `flash s{}e[]`

## Miscellaneous

There is no access control system for now. I use basic authentication (not included in this repo) to protect access to:
- sync-series.php
- add-serie.php
- delete-serie.php
- set-last-seen.php

If you have a newsgroup account, you can use the Binsearch feature included:
- {} is replaced with season number on two digits
- [] is replaced with episode number on two digits
