# phpinfo-compair

I wrote it in 2020 out of necessity.

Both scripts require the curl extension.

**phpinfo_compair.php**
- On the servers you want to compare `<?php phpinfo();?>`  You must create a file.
- You have to edit the `phpinfo_compair.php` file. There must be at least 2 lines.
```
$url[] = 'http://127.0.0.1:80/info.php';
$url[] = 'http://127.0.0.1:81/info.php';
...
$url[] = 'http://127.0.0.1:82/info.php';
```



**phpini_compair**
- You should upload php.php file on the server you want to compare.
- You have to edit the `phpini_compair.php` file. There must be at least 2 lines.
```
$url[] = 'http://127.0.0.1:80/info.php';
$url[] = 'http://127.0.0.1:81/info.php';
...
$url[] = 'http://127.0.0.1:82/info.php';
```
Hopefully it benefits your business.


phpinfo_compair.php
![resim](https://user-images.githubusercontent.com/103988602/177530829-3dccbb0b-3e8a-42a4-8f4e-96113a3be668.png)


phpini_compair.php
![resim](https://user-images.githubusercontent.com/103988602/177530964-216ecf2a-0923-4bfe-a0c6-530b03773ac7.png)
