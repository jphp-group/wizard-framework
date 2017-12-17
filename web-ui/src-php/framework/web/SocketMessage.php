<?php
namespace framework\web;
use php\format\JsonProcessor;
use php\http\WebSocketSession;

/**
 * Class SocketMessage
 * @package framework\web
 */
class SocketMessage
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $type;

    /**
     * @var array
     */
    private $data;

    /**
     * SocketMessage constructor.
     * @param array $rawData
     */
    public function __construct(array $rawData)
    {
        ['id' => $this->id, 'type' => $this->type] = $rawData;

        unset($rawData['id'], $rawData['type']);
        $this->data = $rawData;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @param WebSocketSession $session
     * @param array $data
     * @param callable $callback
     */
    public function reply(WebSocketSession $session, array $data, callable $callback = null)
    {
        $data['type'] = $this->getType();
        $data['id'] = $this->getId();

        $session->sendText((new JsonProcessor())->format($data), $callback);
    }

}