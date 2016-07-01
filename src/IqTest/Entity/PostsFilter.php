<?php
namespace IqTest\Entity;

class PostsFilter
{
    const DIRECTION_ASC = 'ASC';
    const DIRECTION_DESC = 'DESC';

    const DB_FIELD_USER_NAME = 'username';
    const DB_FIELD_EMAIL = 'email';
    const DB_FIELD_CREATED_AT = 'created_at';

    const PARAM_PAGE = 'page';
    const PARAM_FIELD = 'order-field';
    const PARAM_DIRECTION = 'order-direction';

    const DEFAULT_RECORDS_PER_PAGE_LIMIT = 3;
    const DEFAULT_ORDER_FIELD = self::DB_FIELD_CREATED_AT;
    const DEFAULT_ORDER_DIRECTION = self::DIRECTION_DESC;

    /**
     * @var string
     */
    private $orderDirection = self::DEFAULT_ORDER_DIRECTION;

    /**
     * @var string
     */
    private $orderField = self::DEFAULT_ORDER_FIELD;

    /**
     * @var int
     */
    private $limit = self::DEFAULT_RECORDS_PER_PAGE_LIMIT;

    /**
     * @var int
     */
    private $page = 1;

    /**
     * @var array
     */
    private $allowedDirections = [
        self::DIRECTION_ASC,
        self::DIRECTION_DESC,
    ];

    /**
     * @var array
     */
    private $allowedOrderFields = [
        self::DB_FIELD_USER_NAME,
        self::DB_FIELD_EMAIL,
        self::DB_FIELD_CREATED_AT
    ];

    /**
     * @param array|null $data
     */
    public function __construct($data)
    {
        $this->fillOrderDirection($data);
        $this->fillOrderField($data);
        $this->fillPage($data);
    }

    /**
     * @return string
     */
    public function getOrderDirection()
    {
        return $this->orderDirection;
    }

    /**
     * @return string
     */
    public function getOrderField()
    {
        return $this->orderField;
    }

    /**
     * @return int
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * @return int
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @return string
     */
    public function getQueryWithoutPage()
    {
        $queryData = [
            self::PARAM_FIELD => $this->getOrderField(),
            self::PARAM_DIRECTION => $this->getOrderDirection()
        ];
        return http_build_query($queryData);
    }

    /**
     * @param array|null $data
     */
    private function fillOrderDirection($data)
    {
        if (!isset($data[self::PARAM_DIRECTION])) {
            return;
        }

        $direction = $data[self::PARAM_DIRECTION];
        if (!in_array($direction, $this->allowedDirections)) {
            return;
        }

        $this->orderDirection = $direction;
    }

    /**
     * @param array|null $data
     */
    private function fillOrderField($data)
    {
        if (!isset($data[self::PARAM_FIELD])) {
            return;
        }

        $field = $data[self::PARAM_FIELD];
        if (!in_array($field, $this->allowedOrderFields)) {
            return;
        }

        $this->orderField = $field;
    }

    /**
     * @param array|null $data
     */
    private function fillPage($data)
    {
        if (!isset($data[self::PARAM_PAGE])) {
            return;
        }

        $page = $data[self::PARAM_PAGE];
        if (!is_numeric($page) || strlen((int) $page) !== strlen($page)) {
            return;
        }

        $this->page = $page;
    }
}