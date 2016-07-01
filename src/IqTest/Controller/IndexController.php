<?php
namespace IqTest\Controller;

use IqTest\Entity\Post;
use IqTest\Entity\PostsFilter;
use IqTest\Repository\PostRepository;
use IqTest\Service\PostValidator;

class IndexController
{
    /**
     * @var array
     */
    static public $layoutVars;

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
        $isPostAdded = false;
        $isHttpPostRequest = false;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $isHttpPostRequest = true;
            $this->postValidator->setData($_POST);
            if ($this->postValidator->validate()) {
                $post = $this->postValidator->getPostEntity();
                $this->addAdditionFieldsToPost($post);
                $isPostAdded = $this->postRepository->addPost($post);
                $this->postValidator->clearData();
            }
        }

        $postsFilter = new PostsFilter($_GET);
        $posts = $this->postRepository->getPosts($postsFilter);

        $postsCount = $this->postRepository->getPostsCount();

        self::$layoutVars = [
            'validator' => $this->postValidator,
            'posts' => $posts,
            'postsFilter' => $postsFilter,
            'postsCount' => $postsCount,
            'isPostAdded' => $isPostAdded,
            'isHttpPostRequest' => $isHttpPostRequest
        ];

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
        //TODO Mode to some helper getting those parameters
        $post->setIp($this->getUserIp());
        $post->setCreatedAt(date('Y-m-d H:i:s'));
        $post->setUserAgent($_SERVER['HTTP_USER_AGENT']);
    }

    /**
     * @return string
     */
    private function getUserIp()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            return $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        }

        return $_SERVER['REMOTE_ADDR'];
    }
}