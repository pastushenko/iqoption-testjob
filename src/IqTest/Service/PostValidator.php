<?php
namespace IqTest\Service;

use IqTest\Entity\Post;

class PostValidator
{
    const FIELD_EMAIL = 'email';
    const FIELD_USERNAME = 'username';
    const FIELD_HOMEPAGE = 'homepage';
    const FIELD_TEXT = 'text';

    /**
     * @var array
     */
    private $data;

    /**
     * @var array
     */
    private $validationErrors = [];

    /**
     * @param array $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * @return bool
     */
    public function validate()
    {
        $this->clearValidationErrors();

        $this->validateEmail();
        $this->validateUsername();
        $this->validateHomepage();
        $this->validateText();

        return $this->isValid();
    }

    /**
     * @param string $fieldName
     * @return mixed|null
     */
    public function getField($fieldName)
    {
        return isset($this->data[$fieldName]) ? $this->data[$fieldName] : null;
    }

    /**
     * @return array
     */
    public function getValidationErrors()
    {
        return $this->validationErrors;
    }

    private function validateEmail()
    {
        $email = $this->getField(self::FIELD_EMAIL);
        if (!is_string($email)) {
            $this->validationErrors[self::FIELD_USERNAME] = 'Field email is invalid.';
            return;
        }

        $email = trim($email);
        if (!$email) {
            $this->validationErrors[self::FIELD_EMAIL] = 'Field email is required.';
            return;
        }

        if (strlen($email) > 255) {
            $this->validationErrors[self::FIELD_EMAIL] = 'Field email must contain less then 255 chars.';
            return;
        }

        if(! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->validationErrors[self::FIELD_EMAIL] = 'Field email is not valid.';
            return;
        }
    }

    private function validateUsername()
    {
        $username = $this->getField(self::FIELD_USERNAME);
        if (!is_string($username)) {
            $this->validationErrors[self::FIELD_USERNAME] = 'Field username is invalid.';
            return;
        }

        $username = trim($username);

        if (strlen($username) < 2) {
            $this->validationErrors[self::FIELD_USERNAME] = 'Field username must contain more then 3 chars.';
            return;
        }
        if (strlen($username) > 255) {
            $this->validationErrors[self::FIELD_USERNAME] = 'Field username must contain less then 256 chars.';
            return;
        }
    }

    private function validateHomepage()
    {
        $homepage = $this->getField(self::FIELD_HOMEPAGE);
        if (is_null($homepage)) {
            return;
        }

        if (!is_string($homepage)) {
            $this->validationErrors[self::FIELD_HOMEPAGE] = 'Field homepage is invalid.';
            return;
        }

        $homepage = trim($homepage);

        if (strlen($homepage) > 255) {
            $this->validationErrors[self::FIELD_HOMEPAGE] = 'Field homepage must contain less then 255 chars.';
            return;
        }

        if(! filter_var($homepage, FILTER_VALIDATE_URL)) {
            $this->validationErrors[self::FIELD_HOMEPAGE] = 'Field homepage is not valid.';
            return;
        }
    }

    private function validateText()
    {
        $text = $this->getField(self::FIELD_TEXT);
        if (!is_string($text)) {
            $this->validationErrors[self::FIELD_TEXT] = 'Field text is invalid.';
            return;
        }

        $text = trim($text);

        if (strlen($text) < 1) {
            $this->validationErrors[self::FIELD_TEXT] = 'Field must contain at least 1 char.';
            return;
        }
    }

    /**
     * @return Post
     */
    public function getPostEntity()
    {
        if (! $this->isValid()) {
            throw new \LogicException('Entity can be created from invalid data.');
        }
        $post = new Post();
        $post->setEmail(trim($this->getField(self::FIELD_EMAIL)));
        $post->setUsername(trim($this->getField(self::FIELD_USERNAME)));
        $post->setText(trim($this->getField(self::FIELD_TEXT)));
        $post->setHomepage(trim($this->getField(self::FIELD_HOMEPAGE)));

        return $post;
    }

    /**
     * @return bool
     */
    private function isValid()
    {
        if (count($this->validationErrors)) {
            return false;
        }

        return true;
    }

    private function clearValidationErrors()
    {
        $this->validationErrors = [];
    }
}