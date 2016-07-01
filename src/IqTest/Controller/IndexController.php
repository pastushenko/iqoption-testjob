<?php
namespace IqTest\Controller;

use IqTest\Entity\Post;
use IqTest\Repository\PostRepository;
use IqTest\Service\PostValidator;

class IndexController
{
    /**
     * @var PostRepository
     */
    private $postRepository;

    /**
     * @var PostValidator
     */
    private $postValidator;

    /**
     * @param PostRepository $postRepository
     * @param PostValidator $postValidator
     */
    public function __construct(PostRepository $postRepository, PostValidator $postValidator)
    {
        $this->postRepository = $postRepository;
        $this->postValidator = $postValidator;
    }

    public function render()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->postValidator->setData($_POST);
            if ($this->postValidator->validate()) {
                $post = $this->postValidator->getPostEntity();
                $this->addAdditionFieldsToPost($post);
                $this->postRepository->addPost($post);

            }
        }
        $posts = $this->postRepository->getPosts();
        $this->renderLayout();
    }

    private function renderLayout()
    {
        require __DIR__ . '/../views/index.php';
    }

    /**
     * @param Post $post
     */
    private function addAdditionFieldsToPost(Post $post)
    {
        $post->setIp('8.8.8.8');
        $post->setCreatedAt('2016.01.01 10:10:10');
    }
}