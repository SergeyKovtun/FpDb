<?php

namespace FpDbTest;

use Exception;
use mysqli;

class Database implements DatabaseInterface
{
    public const SKIP = '--skip--';

    private array $handlers;

    private mysqli $mysqli;

    public function __construct(mysqli $mysqli)
    {
        $this->mysqli = $mysqli;
        $this
            ->addHandlers(new ArrayPlaceholderHandler())
            ->addHandlers(new DecimalPlaceholderHandler())
            ->addHandlers(new FloatPlaceholderHandler())
            ->addHandlers(new EmptyPlaceholderHandler())
            ->addHandlers(new IdentifierPlaceholderHandler());
    }

    public function buildQuery(string $query, array $args = []): string
    {
        $queryParts = explode('?', $query);

        if (count($queryParts) == 1) {
            return $query;
        }

        $compiledQueryParts = [array_shift($queryParts)];
        foreach ($queryParts as $i => $queryPart) {
            $type = $queryPart[0];
            $queryPart = substr($queryPart, 1);
            $value = $args[$i];
            foreach ($this->getHandlersForSpecifier($type) as $handler) {
                $value = $handler->doHandle($value);
            }

            $compiledQueryParts[] = $value . $queryPart;
        }

        $compiledQuery = implode('', $compiledQueryParts);

        return $this->handleConditionalBlocks($compiledQuery);
    }

    public function skip()
    {
        return self::SKIP;
    }

    public function getHandlers(): array
    {
        return $this->handlers;
    }

    public function addHandlers(AbstractPlaceholderHandler $handler): Database
    {
        $this->handlers[$handler->getSpecifier()][] = $handler;

        return $this;
    }

    private function getHandlersForSpecifier(string $specifier): array
    {
        if (isset($this->handlers[$specifier])) {
            return $this->handlers[$specifier];
        }

        throw new Exception("No handlers for specifier $specifier");
    }

    private function handleConditionalBlocks(string $query)
    {
        $query = preg_replace("/\{.*" . self::SKIP . ".*\}/", '', $query);

        return str_replace(['{', '}'], '', $query);
    }
}
