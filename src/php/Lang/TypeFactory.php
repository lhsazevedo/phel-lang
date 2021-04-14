<?php

declare(strict_types=1);

namespace Phel\Lang;

use Phel\Lang\Collections\HashMap\PersistentArrayMap;
use Phel\Lang\Collections\HashMap\PersistentHashMap;
use Phel\Lang\Collections\HashMap\PersistentHashMapInterface;
use Phel\Lang\Collections\HashSet\PersistentHashSet;
use Phel\Lang\Collections\LinkedList\EmptyList;
use Phel\Lang\Collections\LinkedList\PersistentList;
use Phel\Lang\Collections\LinkedList\PersistentListInterface;
use Phel\Lang\Collections\Vector\PersistentVector;
use Phel\Lang\Collections\Vector\PersistentVectorInterface;

class TypeFactory
{
    private HasherInterface $hasher;
    private EqualizerInterface $equalizer;
    private static ?TypeFactory $instance = null;

    public function __construct()
    {
        $this->hasher = new Hasher();
        $this->equalizer = new Equalizer();
    }

    public static function getInstance(): TypeFactory
    {
        if (self::$instance === null) {
            self::$instance = new TypeFactory();
        }

        return self::$instance;
    }

    public function emptyPersistentHashMap(): PersistentHashMapInterface
    {
        return PersistentArrayMap::empty($this->hasher, $this->equalizer);
    }

    /**
     * @param mixed[] $kvs
     */
    public function persistentHashMapFromKVs(...$kvs): PersistentHashMapInterface
    {
        return $this->persistentHashMapFromArray($kvs);
    }

    public function persistentHashMapFromArray(array $kvs): PersistentHashMapInterface
    {
        if (count($kvs) <= PersistentArrayMap::MAX_SIZE) {
            return PersistentArrayMap::fromArray($this->hasher, $this->equalizer, $kvs);
        }

        return PersistentHashMap::fromArray($this->hasher, $this->equalizer, $kvs);
    }

    /*public function emptyPersistentHashSet(): PersistentHashSet
    {
        return new PersistentHashSet($this->hasher, null, $this->emptyPersistentHashMap());
    }*/

    public function emptyPersistentList(): EmptyList
    {
        return PersistentList::empty($this->hasher, $this->equalizer);
    }

    public function persistentListFromArray(array $values): PersistentListInterface
    {
        return PersistentList::fromArray($this->hasher, $this->equalizer, $values);
    }

    public function emptyPersistentVector(): PersistentVectorInterface
    {
        return PersistentVector::empty($this->hasher, $this->equalizer);
    }

    public function persistentVectorFromArray(array $values): PersistentVectorInterface
    {
        return PersistentVector::fromArray($this->hasher, $this->equalizer, $values);
    }

    public function symbol(string $name): Symbol
    {
        return Symbol::create($name);
    }

    public function symbolForNamespace(?string $namespace, string $name): Symbol
    {
        return Symbol::createForNamespace($namespace, $name);
    }

    public function keyword(string $name): Keyword
    {
        return Keyword::create($name);
    }
}
