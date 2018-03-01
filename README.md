# benchmark-php

This is a PHP benchmark script to compare the runtime speed of PHP and MySQL. 
This project is inspired by www.php-benchmark-script.com (Alessandro Torrisi) 
an www.webdesign-informatik.de. In my point of view this script is more 
correct and comparable for different servers.

## Screenshot

![benchmark_v12](https://user-images.githubusercontent.com/781074/36862772-286568de-1d88-11e8-98c5-6340f8ea3415.jpg)

## Setup

Upload benchmark.php and execute it:<br>
http://www.example.com/benchmark.php


## MySQL Setup (optional)

* Open benchmark.php
* Edit this lines

```php
$options['db.host'] = 'hostname';
$options['db.user'] = 'username';
$options['db.pw'] = 'password';
$options['db.name'] = 'database';
```

* Upload and run the script
