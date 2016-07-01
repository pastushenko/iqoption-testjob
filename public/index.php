<?php
use IqTest\Controller\IndexController;
use IqTest\Repository\PostRepository;
use IqTest\Service\PostValidator;

require_once __DIR__ . '/../vendor/autoload.php';

$postRepository = new PostRepository();
$postValidator = new PostValidator();
$indexController = new IndexController($postRepository, $postValidator);
$indexController->render();