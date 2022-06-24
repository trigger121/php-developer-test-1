<?php


namespace App\Services\Api\Users;

use App\Services\Api\Interfaces\Request as RequestInterface;
use Exception;
use Illuminate\Http\Client\Response;
use App\Services\Api\Users\Response as UsersResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class Request implements RequestInterface
{
    private int $page;
    private Response $response;
    private string $url;

    /**
     * UsersRequest constructor.
     * @param int $page
     */
    public function __construct(int $page = 1, string $url = '')
    {
        $this->page = $page;
        $this->url = $url;
    }

    /**
     * @return string
     * @throws Exception
     */
    public function getUrl(): string
    {
        if (!$this->validateUrl()) {
            throw new Exception('No Url set, please provide a url to call');
        }

        $this->url = sprintf($this->url.'?page=%s', $this->page);
        return $this->url;
    }

    /**
     *
     * @throws Exception
     */
    public function call(): self
    {
        $this->response = Http::get($this->getUrl());
        return $this;
    }

    /**
     * @return int
     */
    public function getPage(): int
    {
        return $this->page;
    }

    /**
     * returns the actual raw body content (JSON)
     * @return string
     * @throws Exception
     */
    public function getResponseBody(): string
    {
        if (!$this->response instanceof Response) {
            throw new Exception('No Response, please run call()');
        }
        return $this->response->body();
    }

    /**
     * @throws Exception
     */
    public function getResponse(): UsersResponse
    {
        return new UsersResponse($this->getResponseBody());
    }

    /**
     * Validate the URL used with the request
     * @throws Exception
     */
    public function validateUrl(): bool
    {
        $validator = Validator::make(
            ['url' => $this->url],
            ['url' => 'required|url']
        );

        if ($validator->fails()) {
            throw new \Exception('Url Failed Validation');
        }
        return true;
    }

}
