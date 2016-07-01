<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 01.07.16
 * Time: 14:26
 */

namespace IqTest\Repository;


use IqTest\Entity\Post;

class PostRepository
{
    /** @var  \PDO */
    private $pdo;
    
    public function __construct()
    {
        $this->pdo = new \PDO("pgsql:dbname=iqtest2;host=localhost", 'iqtest2', 'iqtest2');
    }

    /**
     * @return Post[]
     */
    public function getPosts()
    {
        $rawData = $this->pdo->query('
            SELECT 
              id, username, email, homepage, text, created_at, ip, useragent 
            FROM posts')->fetchAll();

        $posts = [];
        foreach ($rawData as $postData) {
            $posts[] = $this->createPostFromData($postData);
        }

        return $posts;
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

    /**
     * @param Post $post
     * @return bool
     */
    public function addPost(Post $post)
    {
        $statement = $this->pdo->prepare("
            INSERT INTO posts (username, email, text, created_at) VALUES (:username, :email, :text, :createdAt);
        ");

        $statement->bindParam(':username', $post->getUsername());
        $statement->bindParam(':email', $post->getEmail());
        $statement->bindParam(':text', $post->getText());
        $statement->bindParam(':createdAt', $post->getCreatedAt());
        $statement->bindParam(':ip', $post->getIp());
        
        try {
            $statement->execute();
            return true;
        } catch (\PDOException $ex) {
            var_dump($ex->getMessage());
            //add loger
            return false;
        }
        
    }
}