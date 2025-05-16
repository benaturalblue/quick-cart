<?php
require 'vendor/autoload.php';

use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\WebDriverBy;

$serverUrl = 'http://localhost:50115';

$driver = RemoteWebDriver::create($serverUrl, DesiredCapabilities::chrome());

$driver->get('http://127.0.0.1:8000/login');

$driver->findElement(WebDriverBy::name('email'))->sendKeys('test1@example.com');
$driver->findElement(WebDriverBy::name('password'))->sendKeys('password123');

$driver->findElement(WebDriverBy::cssSelector('button[type=submit]'))->click();

sleep(2);

$currentUrl = $driver->getCurrentURL();
if (strpos($currentUrl, '/home') !== false) {
    echo "ログイン成功\n";
} else {
    echo "ログインに失敗\n";
}

$driver->quit();

