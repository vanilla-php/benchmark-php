benchmark-php
=============

This is a PHP benchmark script to compare the runtime speed of PHP and MySQL. 
This project is inspired by www.php-benchmark-script.com (Alessandro Torrisi) 
an www.webdesign-informatik.de. In my point of view this script is more 
correct and comparable for different servers.

# Setup

Upload benchmark.php an execute it:<br>
http://www.example.com/benchmark.php


# MySQL Setup (optional)

- Open benchmark.php
- Edit this lines

```php
$arr_cfg['db.host'] = 'hostname';
$arr_cfg['db.user'] = 'username';
$arr_cfg['db.pw'] = 'password';
$arr_cfg['db.name'] = 'database';
```

- upload and run the script
