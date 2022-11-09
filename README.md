# phpinfo-compair compare 
I wrote it in 2020 out of necessity.

Both scripts require the curl extension.

**phpinfo_compair.php**
- On the servers you want to compare `<?php phpinfo();?>`  You must create a file.
- You have to edit the `phpinfo_compair.php` file. There must be at least 2 lines.
```
$server[] = 'http://127.0.0.1:80/info.php';
$server[] = 'http://127.0.0.1:81/info.php';
...
$server[] = 'http://127.0.0.1:82/info.php';
```
