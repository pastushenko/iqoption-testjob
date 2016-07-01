<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 01.07.16
 * Time: 14:26
 */

namespace IqTest\Repository;


use IqTest\Entity\Post;
use IqTest\Entity\PostsFilter;

class PostRepository
{
    /**
     * @var \PDO
     */
    private $pdo;
    
    public function __construct()
    {
        //TODO move to config
        $this->pdo = new \PDO("pgsql:dbname=iqtest2;host=localhost", 'iqtest2', 'iqtest2');
    }

    /**
     * @param PostsFilter $postsFilter
     * @return Post[]
     */
    public function getPosts(PostsFilter $postsFilter)
    {
        $rawData = $this->pdo->query(
            sprintf(
            'SELECT 
                  id, 
                  username, 
                  email, 
                  homepage, 
                  text, 
                  created_at, 
                  ip, 
                  useragent 
            FROM posts
            ORDER BY %s %s
            LIMIT %d
            OFFSET %d
            ',
            $postsFilter->getOrderField(),
            $postsFilter->getOrderDirection(),
            $postsFilter->getLimit(),
            ($postsFilter->getPage() - 1) * $postsFilter->getLimit()
        ))->fetchAll();

        $posts = [];
        foreach ($rawData as $postData) {
            $posts[] = $this->createPostFromData($postData);
        }

        return $posts;
    }

    /**
     * @param Post $post
     * @return bool
     */
    public function addPost(Post $post)
    {
        $statement = $this->pdo->prepare("
            INSERT INTO posts (username, email, text, created_at, ip, useragent, homepage) VALUES (:username, :email, :text, :created_at, :ip, :user_agent, :homepage);
        ");

        $statement->bindParam(':username', $post->getUsername());
        $statement->bindParam(':email', $post->getEmail());
        $statement->bindParam(':text', $post->getText());
        $statement->bindParam(':homepage', $post->getHomepage());
        $statement->bindParam(':created_at', $post->getCreatedAt());
        $statement->bindParam(':ip', $post->getIp());
        $statement->bindParam(':user_agent', $post->getUserAgent());

        try {
            $statement->execute();
            return true;
        } catch (\Exception $ex) {
            var_dump($ex->getMessage());
            //add loger
            return false;
        }

    }

    /**
     * @return int
     */
    public function getPostsCount()
    {
        return $this->pdo->query('SELECT COUNT(id) FROM posts;')->fetchColumn();
    }

    /**
     * @param array $postData
     * @return Post
     */
    private function createPostFromData(array $postData)
    {
        $post = new Post();
        $post->setEmail($postData['email']);
        $post->setCreatedAt($postData['created_at']);
        $post->setHomepage($postData['homepage']);
        $post->setId($postData['id']);
        $post->setIp($postData['ip']);
        $post->setText($postData['text']);
        $post->setUsername($postData['username']);
        $post->setUserAgent($postData['useragent']);

        return $post;
    }
}