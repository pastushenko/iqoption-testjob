<?php
namespace IqTest\Controller;

use IqTest\Repository\PostRepository;

class IndexController
{
    /**
     * @var PostRepository
     */
    private $postRepository;

    public function __construct(PostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    public function render()
    {
        $posts = $this->postRepository->getPosts();
        $this->renderLayout();
    }

    private function renderLayout()
    {
        require __DIR__ . '/../views/index.php';
    }
}