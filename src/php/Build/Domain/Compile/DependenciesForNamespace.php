<?php

declare(strict_types=1);

namespace Phel\Build\Domain\Compile;

use Phel\Build\Domain\Extractor\NamespaceExtractor;
use Phel\Build\Domain\Extractor\NamespaceInformation;

use function count;
use function in_array;

final class DependenciesForNamespace
{
    public function __construct(private NamespaceExtractor $namespaceExtractor)
    {
    }

    /**
     * @return list<NamespaceInformation>
     */
    public function getDependenciesForNamespace(array $directories, array $ns): array
    {
        $namespaceInformation = $this->namespaceExtractor->getNamespacesFromDirectories($directories);

        $index = [];
        $queue = [];
        foreach ($namespaceInformation as $info) {
            $index[$info->getNamespace()] = $info;
            if (in_array($info->getNamespace(), $ns)) {
                $queue[] = $info->getNamespace();
            }
        }

        $requiredNamespaces = [];
        while (count($queue) > 0) {
            $currentNs = array_shift($queue);
            if (!isset($requiredNamespaces[$currentNs])) {
                foreach ($index[$currentNs]->getDependencies() as $depNs) {
                    $queue[] = $depNs;
                }
            }
            $requiredNamespaces[$currentNs] = true;
        }

        $result = [];
        foreach ($namespaceInformation as $info) {
            if (isset($requiredNamespaces[$info->getNamespace()])) {
                $result[] = $info;
            }
        }

        return $result;
    }
}