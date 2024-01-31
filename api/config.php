<?php

if ($_SERVER['HTTP_HOST'] === 'gearcheck.localhost' || $_SERVER['HTTP_HOST'] === '127.0.0.1') {
    define("DB_HOST", "35.212.57.119");
} else {
    define("DB_HOST", "localhost");
}
define("DB_USER", "uje7jzk71kpcb");
define("DB_PASSWORD", "spwwi9qfgupu");
define("DB_NAME", "dbk5ecnkrhlady");
