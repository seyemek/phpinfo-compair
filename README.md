# phpinfo-compair

I wrote it in 2020 out of necessity.

Both scripts require the curl extension.

phpinfo_compair.php
1- On the servers you want to compare <?php phpinfo(); You must create a ?> file.
2- You have to edit the phpinfo_compair.php file. There must be at least 2 lines.
         $url[] = 'http://127.0.0.1:80/info.php';
$url[] = 'http://127.0.0.1:81/info.php';
         ...
         ...
$url[] = 'http://127.0.0.1:82/info.php';

phpini_compair
1- You should upload php.php file on the server you want to compare.
2- You have to edit the phpini_compair.php file. There must be at least 2 lines.
         $url[] = 'http://127.0.0.1:80/info.php';
$url[] = 'http://127.0.0.1:81/info.php';
         ...
         ...
$url[] = 'http://127.0.0.1:82/info.php';

Hopefully it benefits your business.
