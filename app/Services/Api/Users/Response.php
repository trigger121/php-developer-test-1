<?php


namespace App\Services\Api\Users;


use App\Models\User;
use Exception;

class Response
{
    private string $raw;

    /**
     * Response constructor.
     * @param string $raw
     */
    public function __construct(string $raw)
    {
        $this->raw = $raw;
    }


    /**
     * @return string
     */
    public function getRaw(): string
    {
        return $this->raw;
    }

    /**
     * @return bool
     */
    public function isJson(): bool
    {
        return (bool)$this->getDecodedData();
    }

    /**
     * @return User[]|null
     * @throws Exception
     */
    public function getData(): array
    {
        if (!$this->hasData()) {
            throw new Exception('No Data');
        }
        return $this->getDecodedData()['data'];
    }

    /**
     * @return array|false
     */
    private function getDecodedData()
    {
        return json_decode($this->getRaw(), true);
    }

    /**
     * @return bool
     */
    public function hasData(): bool
    {
        if (!$this->isJson()) {
            return false;
        }

        if (!array_key_exists('data', $this->getDecodedData())) {
            return false;
        }

        return true;
    }
}
