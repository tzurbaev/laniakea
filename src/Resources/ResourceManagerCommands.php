<?php

declare(strict_types=1);

namespace Laniakea\Resources;

use Illuminate\Container\Container;
use Laniakea\Resources\Interfaces\ResourceManagerCommandInterface;

readonly class ResourceManagerCommands
{
    public function __construct(
        private array $pagination,
        private array $list,
        private array $item,
    ) {
        //
    }

    /** @return ResourceManagerCommandInterface[] */
    public function getPaginationCommands(): array
    {
        return $this->getCommands($this->pagination);
    }

    /** @return ResourceManagerCommandInterface[] */
    public function getListCommands(): array
    {
        return $this->getCommands($this->list);
    }

    /** @return ResourceManagerCommandInterface[] */
    public function getItemCommands(): array
    {
        return $this->getCommands($this->item);
    }

    /** @return ResourceManagerCommandInterface[] */
    protected function getCommands(array $commands): array
    {
        $container = Container::getInstance();

        return collect($commands)
            ->map(function (string|ResourceManagerCommandInterface $command) use ($container) {
                return $command instanceof ResourceManagerCommandInterface
                    ? $command
                    : $container->make($command);
            })->all();
    }
}
