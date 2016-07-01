<?php
use IqTest\Controller\IndexController;
use IqTest\Repository\PostRepository;

require_once __DIR__ . '/../vendor/autoload.php';

$postRepository = new PostRepository();
$indexController = new IndexController($postRepository);
$indexController->render();