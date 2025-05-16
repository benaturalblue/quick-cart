<?php
require 'vendor/autoload.php';

use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\WebDriverBy;

$serverUrl = 'http://localhost:50115';

$driver = RemoteWebDriver::create($serverUrl, DesiredCapabilities::chrome());

$driver->get('http://127.0.0.1:8000/register');

$driver->findElement(WebDriverBy::name('name'))->sendKeys('テスト');
$driver->findElement(WebDriverBy::name('nickname'))->sendKeys('testuser_' . rand(1000, 9999));
$driver->findElement(WebDriverBy::name('email'))->sendKeys('testuser' . rand(1000, 9999) . '@example.com');
$driver->findElement(WebDriverBy::name('address'))->sendKeys('東京都品川区');
$driver->findElement(WebDriverBy::name('number'))->sendKeys('08012345678');
$driver->findElement(WebDriverBy::name('password'))->sendKeys('password123');
$driver->findElement(WebDriverBy::name('password_confirmation'))->sendKeys('password123');

$driver->findElement(WebDriverBy::cssSelector('button[type=submit]'))->click();

sleep(2);

$currentUrl = $driver->getCurrentURL();
if (strpos($currentUrl, '/home') !== false) {
    echo "登録成功\n";
} else {
    echo "登録失敗\n";
}

$driver->quit();
