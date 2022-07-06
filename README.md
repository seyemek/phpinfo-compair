# phpinfo-compair

ihtiyaçtan ötürü 2020 yılında yazmıştım.

her iki script içinde curl elklentisi gerekmekte.

phpinfo_compair.php
1- Karşılaştırma yapmak istediğiniz sunucularda <?php phpinfo();  ?> dosyası oluşturmalısınız.
2- phpinfo_compair.php dosyasını editlemlisiniz. en az 2 satır olmalı.
        $url[] = 'http://127.0.0.1:80/info.php';
	$url[] = 'http://127.0.0.1:81/info.php';
        ...
        ...
	$url[] = 'http://127.0.0.1:82/info.php';

phpini_compair
1- Karşılaştırma yapmak istediğiniz sunucuta  php.php dosyası upload etmelisiniz.
2- phpini_compair.php dosyasını editlemlisiniz. en az 2 satır olmalı.
        $url[] = 'http://127.0.0.1:80/info.php';
	$url[] = 'http://127.0.0.1:81/info.php';
        ...
        ...
	$url[] = 'http://127.0.0.1:82/info.php';

Umarım işinize yarar.
