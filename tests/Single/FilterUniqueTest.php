<?php

declare(strict_types=1);

namespace IterTools\Tests\Single;

use IterTools\Single;
use IterTools\Tests\Fixture\ArrayIteratorFixture;
use IterTools\Tests\Fixture\GeneratorFixture;
use IterTools\Tests\Fixture\IteratorAggregateFixture;

class FilterUniqueTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider dataProviderForArray
     * @param        array $data
     * @param        bool $strict
     * @param        array $expected
     */
    public function testArray(array $data, bool $strict, array $expected): void
    {
        // Given
        $result = [];

        // When
        foreach (Single::filterUnique($data, $strict) as $datum) {
            $result[] = $datum;
        }

        // Then
        $this->assertEquals($expected, $result);
    }

    public function dataProviderForArray(): array
    {
        return [
            //  data                    strict      expected
            [   [],                     true,       []                          ],
            [   [],                     false,      []                          ],
            [   [1],                    true,       [1]                         ],
            [   [1],                    false,      [1]                         ],
            [   [1, 1],                 true,       [1]                         ],
            [   [1, 1],                 false,      [1]                         ],
            [   [1, '1'],               true,       [1, '1']                    ],
            [   [1, '1'],               false,      [1]                         ],
            [   ['1', 1],               true,       ['1', 1]                    ],
            [   ['1', 1],               false,      ['1']                       ],
            [   ['aa', 'bb', 'aa'],     true,       ['aa', 'bb']                ],
            [   ['aa', 'bb', 'aa'],     false,      ['aa', 'bb']                ],
            [   [1, 2, 1, 2, 3],        true,       [1, 2, 3]                   ],
            [   [1, 2, 1, 2, 3],        false,      [1, 2, 3]                   ],
            [   ['1', 2, '1', '2', 3],  true,       ['1', 2, '2', 3]            ],
            [   ['1', 2, '1', '2', 3],  false,      ['1', 2, '3']               ],
            [   [[1], [1], [1, 2]],     true,       [[1], [1, 2]]               ],
            [   [[1], [1], [1, 2]],     false,      [[1], [1, 2]]               ],
            [   [[1], ['1'], [1, 2]],   true,       [[1], ['1'], [1, 2]]        ],
            [   [[1], ['1'], [1, 2]],   false,      [[1], ['1'], [1, 2]]        ],
            [   [[1], 'a' => [1]],      true,       [[1]]                       ],
            [   [[1], 'a' => [1]],      false,      [[1]]                       ],
            [   [[1], 'a' => [1, 2]],   true,       [[1], [1, 2]]               ],
            [   [[1], 'a' => [1, 2]],   false,      [[1], [1, 2]]               ],
            [
                [(object)['a' => 1], ['a' => 1], (object)['a' => 1], 2, 2],
                true,
                [(object)['a' => 1], ['a' => 1], (object)['a' => 1], 2],
            ],
            [
                [(object)['a' => 1], ['a' => 1], (object)['a' => 1], 2, 2],
                false,
                [(object)['a' => 1], ['a' => 1], 2],
            ],
            [
                [(object)['a' => 1], ['a' => 1], (object)['a' => 2], 2, 2],
                true,
                [(object)['a' => 1], ['a' => 1], (object)['a' => 2], 2],
            ],
            [
                [(object)['a' => 1], ['a' => 1], (object)['a' => 2], 2, 2],
                false,
                [(object)['a' => 1], ['a' => 1], (object)['a' => 2], 2],
            ],
            [
                [($obj = (object)['a' => 1]), $obj, (object)['a' => 1], (object)['a' => 2], 2, 2],
                true,
                [(object)['a' => 1], (object)['a' => 1], (object)['a' => 2], 2],
            ],
            [
                [($obj = (object)['a' => 1]), $obj, (object)['a' => 1], (object)['a' => 2], 2, 2],
                false,
                [(object)['a' => 1], (object)['a' => 2], 2],
            ],
            [
                [$f1 = new ArrayIteratorFixture([]), $f2 = new ArrayIteratorFixture([]), 1, 1],
                true,
                [$f1, $f2, 1],
            ],
            [
                [$f1 = new ArrayIteratorFixture([]), new ArrayIteratorFixture([]), 1, 1],
                false,
                [$f1, 1],
            ],
            [
                [$f1 = new ArrayIteratorFixture([]), $f1, 1, 1],
                true,
                [$f1, 1],
            ],
            [
                [$f1 = new ArrayIteratorFixture([]), $f1, 1, 1],
                false,
                [$f1, 1],
            ],
        ];
    }

    /**
     * @dataProvider dataProviderForGenerators
     * @param        \Generator $data
     * @param        bool $strict
     * @param        array $expected
     */
    public function testGenerators(\Generator $data, bool $strict, array $expected): void
    {
        // Given
        $result = [];

        // When
        foreach (Single::filterUnique($data, $strict) as $datum) {
            $result[] = $datum;
        }

        // Then
        $this->assertEquals($expected, $result);
    }

    public function dataProviderForGenerators(): array
    {
        $gen = static function (array $data) {
            return GeneratorFixture::getGenerator($data);
        };

        return [
            //  data                          strict      expected
            [   $gen([]),                     true,       []                          ],
            [   $gen([]),                     false,      []                          ],
            [   $gen([1]),                    true,       [1]                         ],
            [   $gen([1]),                    false,      [1]                         ],
            [   $gen([1, 1]),                 true,       [1]                         ],
            [   $gen([1, 1]),                 false,      [1]                         ],
            [   $gen([1, '1']),               true,       [1, '1']                    ],
            [   $gen([1, '1']),               false,      [1]                         ],
            [   $gen(['1', 1]),               true,       ['1', 1]                    ],
            [   $gen(['1', 1]),               false,      ['1']                       ],
            [   $gen(['aa', 'bb', 'aa']),     true,       ['aa', 'bb']                ],
            [   $gen(['aa', 'bb', 'aa']),     false,      ['aa', 'bb']                ],
            [   $gen([1, 2, 1, 2, 3]),        true,       [1, 2, 3]                   ],
            [   $gen([1, 2, 1, 2, 3]),        false,      [1, 2, 3]                   ],
            [   $gen(['1', 2, '1', '2', 3]),  true,       ['1', 2, '2', 3]            ],
            [   $gen(['1', 2, '1', '2', 3]),  false,      ['1', 2, '3']               ],
            [   $gen([[1], [1], [1, 2]]),     true,       [[1], [1, 2]]               ],
            [   $gen([[1], [1], [1, 2]]),     false,      [[1], [1, 2]]               ],
            [   $gen([[1], ['1'], [1, 2]]),   true,       [[1], ['1'], [1, 2]]        ],
            [   $gen([[1], ['1'], [1, 2]]),   false,      [[1], ['1'], [1, 2]]        ],
            [   $gen([[1], 'a' => [1]]),      true,       [[1]]                       ],
            [   $gen([[1], 'a' => [1]]),      false,      [[1]]                       ],
            [   $gen([[1], 'a' => [1, 2]]),   true,       [[1], [1, 2]]               ],
            [   $gen([[1], 'a' => [1, 2]]),   false,      [[1], [1, 2]]               ],
            [
                $gen([(object)['a' => 1], ['a' => 1], (object)['a' => 1], 2, 2]),
                true,
                [(object)['a' => 1], ['a' => 1], (object)['a' => 1], 2],
            ],
            [
                $gen([(object)['a' => 1], ['a' => 1], (object)['a' => 1], 2, 2]),
                false,
                [(object)['a' => 1], ['a' => 1], 2],
            ],
            [
                $gen([(object)['a' => 1], ['a' => 1], (object)['a' => 2], 2, 2]),
                true,
                [(object)['a' => 1], ['a' => 1], (object)['a' => 2], 2],
            ],
            [
                $gen([(object)['a' => 1], ['a' => 1], (object)['a' => 2], 2, 2]),
                false,
                [(object)['a' => 1], ['a' => 1], (object)['a' => 2], 2],
            ],
            [
                $gen([($obj = (object)['a' => 1]), $obj, (object)['a' => 1], (object)['a' => 2], 2, 2]),
                true,
                [(object)['a' => 1], (object)['a' => 1], (object)['a' => 2], 2],
            ],
            [
                $gen([($obj = (object)['a' => 1]), $obj, (object)['a' => 1], (object)['a' => 2], 2, 2]),
                false,
                [(object)['a' => 1], (object)['a' => 2], 2],
            ],
            [
                $gen([$f1 = new ArrayIteratorFixture([]), $f2 = new ArrayIteratorFixture([]), 1, 1]),
                true,
                [$f1, $f2, 1],
            ],
            [
                $gen([$f1 = new ArrayIteratorFixture([]), new ArrayIteratorFixture([]), 1, 1]),
                false,
                [$f1, 1],
            ],
            [
                $gen([$f1 = new ArrayIteratorFixture([]), $f1, 1, 1]),
                true,
                [$f1, 1],
            ],
            [
                $gen([$f1 = new ArrayIteratorFixture([]), $f1, 1, 1]),
                false,
                [$f1, 1],
            ],
        ];
    }

    /**
     * @dataProvider dataProviderForIterators
     * @param        \Iterator $data
     * @param        bool $strict
     * @param        array $expected
     */
    public function testIterators(\Iterator $data, bool $strict, array $expected): void
    {
        // Given
        $result = [];

        // When
        foreach (Single::filterUnique($data, $strict) as $datum) {
            $result[] = $datum;
        }

        // Then
        $this->assertEquals($expected, $result);
    }

    public function dataProviderForIterators(): array
    {
        $iter = static function (array $data) {
            return new \ArrayIterator($data);
        };

        return [
            //  data                           strict      expected
            [   $iter([]),                     true,       []                          ],
            [   $iter([]),                     false,      []                          ],
            [   $iter([1]),                    true,       [1]                         ],
            [   $iter([1]),                    false,      [1]                         ],
            [   $iter([1, 1]),                 true,       [1]                         ],
            [   $iter([1, 1]),                 false,      [1]                         ],
            [   $iter([1, '1']),               true,       [1, '1']                    ],
            [   $iter([1, '1']),               false,      [1]                         ],
            [   $iter(['1', 1]),               true,       ['1', 1]                    ],
            [   $iter(['1', 1]),               false,      ['1']                       ],
            [   $iter(['aa', 'bb', 'aa']),     true,       ['aa', 'bb']                ],
            [   $iter(['aa', 'bb', 'aa']),     false,      ['aa', 'bb']                ],
            [   $iter([1, 2, 1, 2, 3]),        true,       [1, 2, 3]                   ],
            [   $iter([1, 2, 1, 2, 3]),        false,      [1, 2, 3]                   ],
            [   $iter(['1', 2, '1', '2', 3]),  true,       ['1', 2, '2', 3]            ],
            [   $iter(['1', 2, '1', '2', 3]),  false,      ['1', 2, '3']               ],
            [   $iter([[1], [1], [1, 2]]),     true,       [[1], [1, 2]]               ],
            [   $iter([[1], [1], [1, 2]]),     false,      [[1], [1, 2]]               ],
            [   $iter([[1], ['1'], [1, 2]]),   true,       [[1], ['1'], [1, 2]]        ],
            [   $iter([[1], ['1'], [1, 2]]),   false,      [[1], ['1'], [1, 2]]        ],
            [   $iter([[1], 'a' => [1]]),      true,       [[1]]                       ],
            [   $iter([[1], 'a' => [1]]),      false,      [[1]]                       ],
            [   $iter([[1], 'a' => [1, 2]]),   true,       [[1], [1, 2]]               ],
            [   $iter([[1], 'a' => [1, 2]]),   false,      [[1], [1, 2]]               ],
            [
                $iter([(object)['a' => 1], ['a' => 1], (object)['a' => 1], 2, 2]),
                true,
                [(object)['a' => 1], ['a' => 1], (object)['a' => 1], 2],
            ],
            [
                $iter([(object)['a' => 1], ['a' => 1], (object)['a' => 1], 2, 2]),
                false,
                [(object)['a' => 1], ['a' => 1], 2],
            ],
            [
                $iter([(object)['a' => 1], ['a' => 1], (object)['a' => 2], 2, 2]),
                true,
                [(object)['a' => 1], ['a' => 1], (object)['a' => 2], 2],
            ],
            [
                $iter([(object)['a' => 1], ['a' => 1], (object)['a' => 2], 2, 2]),
                false,
                [(object)['a' => 1], ['a' => 1], (object)['a' => 2], 2],
            ],
            [
                $iter([($obj = (object)['a' => 1]), $obj, (object)['a' => 1], (object)['a' => 2], 2, 2]),
                true,
                [(object)['a' => 1], (object)['a' => 1], (object)['a' => 2], 2],
            ],
            [
                $iter([($obj = (object)['a' => 1]), $obj, (object)['a' => 1], (object)['a' => 2], 2, 2]),
                false,
                [(object)['a' => 1], (object)['a' => 2], 2],
            ],
            [
                $iter([$f1 = new ArrayIteratorFixture([]), $f2 = new ArrayIteratorFixture([]), 1, 1]),
                true,
                [$f1, $f2, 1],
            ],
            [
                $iter([$f1 = new ArrayIteratorFixture([]), new ArrayIteratorFixture([]), 1, 1]),
                false,
                [$f1, 1],
            ],
            [
                $iter([$f1 = new ArrayIteratorFixture([]), $f1, 1, 1]),
                true,
                [$f1, 1],
            ],
            [
                $iter([$f1 = new ArrayIteratorFixture([]), $f1, 1, 1]),
                false,
                [$f1, 1],
            ],
        ];
    }

    /**
     * @dataProvider dataProviderForTraversables
     * @param        \Traversable $data
     * @param        bool $strict
     * @param        array $expected
     */
    public function testTraversables(\Traversable $data, bool $strict, array $expected): void
    {
        // Given
        $result = [];

        // When
        foreach (Single::filterUnique($data, $strict) as $datum) {
            $result[] = $datum;
        }

        // Then
        $this->assertEquals($expected, $result);
    }

    public function dataProviderForTraversables(): array
    {
        $trav = static function (array $data) {
            return new IteratorAggregateFixture($data);
        };

        return [
            //  data                           strict      expected
            [   $trav([]),                     true,       []                          ],
            [   $trav([]),                     false,      []                          ],
            [   $trav([1]),                    true,       [1]                         ],
            [   $trav([1]),                    false,      [1]                         ],
            [   $trav([1, 1]),                 true,       [1]                         ],
            [   $trav([1, 1]),                 false,      [1]                         ],
            [   $trav([1, '1']),               true,       [1, '1']                    ],
            [   $trav([1, '1']),               false,      [1]                         ],
            [   $trav(['1', 1]),               true,       ['1', 1]                    ],
            [   $trav(['1', 1]),               false,      ['1']                       ],
            [   $trav(['aa', 'bb', 'aa']),     true,       ['aa', 'bb']                ],
            [   $trav(['aa', 'bb', 'aa']),     false,      ['aa', 'bb']                ],
            [   $trav([1, 2, 1, 2, 3]),        true,       [1, 2, 3]                   ],
            [   $trav([1, 2, 1, 2, 3]),        false,      [1, 2, 3]                   ],
            [   $trav(['1', 2, '1', '2', 3]),  true,       ['1', 2, '2', 3]            ],
            [   $trav(['1', 2, '1', '2', 3]),  false,      ['1', 2, '3']               ],
            [   $trav([[1], [1], [1, 2]]),     true,       [[1], [1, 2]]               ],
            [   $trav([[1], [1], [1, 2]]),     false,      [[1], [1, 2]]               ],
            [   $trav([[1], ['1'], [1, 2]]),   true,       [[1], ['1'], [1, 2]]        ],
            [   $trav([[1], ['1'], [1, 2]]),   false,      [[1], ['1'], [1, 2]]        ],
            [   $trav([[1], 'a' => [1]]),      true,       [[1]]                       ],
            [   $trav([[1], 'a' => [1]]),      false,      [[1]]                       ],
            [   $trav([[1], 'a' => [1, 2]]),   true,       [[1], [1, 2]]               ],
            [   $trav([[1], 'a' => [1, 2]]),   false,      [[1], [1, 2]]               ],
            [
                $trav([(object)['a' => 1], ['a' => 1], (object)['a' => 1], 2, 2]),
                true,
                [(object)['a' => 1], ['a' => 1], (object)['a' => 1], 2],
            ],
            [
                $trav([(object)['a' => 1], ['a' => 1], (object)['a' => 1], 2, 2]),
                false,
                [(object)['a' => 1], ['a' => 1], 2],
            ],
            [
                $trav([(object)['a' => 1], ['a' => 1], (object)['a' => 2], 2, 2]),
                true,
                [(object)['a' => 1], ['a' => 1], (object)['a' => 2], 2],
            ],
            [
                $trav([(object)['a' => 1], ['a' => 1], (object)['a' => 2], 2, 2]),
                false,
                [(object)['a' => 1], ['a' => 1], (object)['a' => 2], 2],
            ],
            [
                $trav([($obj = (object)['a' => 1]), $obj, (object)['a' => 1], (object)['a' => 2], 2, 2]),
                true,
                [(object)['a' => 1], (object)['a' => 1], (object)['a' => 2], 2],
            ],
            [
                $trav([($obj = (object)['a' => 1]), $obj, (object)['a' => 1], (object)['a' => 2], 2, 2]),
                false,
                [(object)['a' => 1], (object)['a' => 2], 2],
            ],
            [
                $trav([$f1 = new ArrayIteratorFixture([]), $f2 = new ArrayIteratorFixture([]), 1, 1]),
                true,
                [$f1, $f2, 1],
            ],
            [
                $trav([$f1 = new ArrayIteratorFixture([]), new ArrayIteratorFixture([]), 1, 1]),
                false,
                [$f1, 1],
            ],
            [
                $trav([$f1 = new ArrayIteratorFixture([]), $f1, 1, 1]),
                true,
                [$f1, 1],
            ],
            [
                $trav([$f1 = new ArrayIteratorFixture([]), $f1, 1, 1]),
                false,
                [$f1, 1],
            ],
        ];
    }
}
