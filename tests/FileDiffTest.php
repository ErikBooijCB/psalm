<?php
namespace Psalm\Tests;

class FileDiffTest extends TestCase
{
    /**
     * @return void
     */
    public function getChanges()
    {
        return [
            'simpleChange' => [
                '<?php
                namespace Foo;

                class A {
                    public function foo() {
                        $a = 1;
                    }
                    public function bar() {
                        $b = 1;
                    }
                }',
                '<?php
                namespace Foo;

                class A {
                    public function foo() {
                        $a = 1;
                    }
                    public function bar() {
                        $b = 2;
                    }
                }',
                ['Foo\A::foo']
            ],
            'removeFunctionAtEnd' => [
                '<?php
                namespace Foo;

                class A {
                    public function foo() {
                        $a = 1;
                    }
                    public function bar() {
                        $b = 1;
                    }
                    public function bat() {
                        $c = 1;
                    }
                }',
                '<?php
                namespace Foo;

                class A {
                    public function foo() {
                        $a = 1;
                    }
                    public function bar() {
                        $b = 1;
                    }
                }',
                ['Foo\A::foo', 'Foo\A::bar']
            ],
            'removeFunctionAtBeginning' => [
                '<?php
                namespace Foo;

                class A {
                    public function foo() {
                        $a = 1;
                    }
                    public function bar() {
                        $b = 1;
                    }
                    public function bat() {
                        $c = 1;
                    }
                }',
                '<?php
                namespace Foo;

                class A {
                    public function bar() {
                        $b = 1;
                    }
                    public function bat() {
                        $c = 1;
                    }
                }',
                ['Foo\A::bar', 'Foo\A::bat']
            ],
            'removeFunctionInMiddle' => [
                '<?php
                namespace Foo;

                class A {
                    public function foo() {
                        $a = 1;
                    }
                    public function bar() {
                        $b = 1;
                    }
                    public function bat() {
                        $c = 1;
                    }
                }',
                '<?php
                namespace Foo;

                class A {
                    public function foo() {
                        $a = 1;
                    }
                    public function bat() {
                        $c = 1;
                    }
                }',
                ['Foo\A::foo', 'Foo\A::bat']
            ],
            'changeNamespace' => [
                '<?php
                namespace Bar;

                class A {
                    public function foo() {
                        $a = 1;
                    }
                    public function bar() {
                        $b = 1;
                    }
                }',
                '<?php
                namespace Foo;

                class A {
                    public function bar() {
                        $b = 2;
                    }
                }',
                []
            ],
            'removeNamespace' => [
                '<?php
                namespace Bar;

                class A {
                    public function foo() {
                        $a = 1;
                    }
                    public function bar() {
                        $b = 1;
                    }
                }',
                '<?php
                class A {
                    public function bar() {
                        $b = 2;
                    }
                }',
                []
            ],
        ];
    }

    /**
     * @return void
     */
    public function getNoChanges()
    {
        return [
            'sameFile' => [
                '<?php
                namespace Foo;

                class A {
                    public function foo() {
                        $a = 1;
                    }
                    public function bar() {
                        $b = 1;
                    }
                }',
                '<?php
                namespace Foo;

                class A {
                    public function foo() {
                        $a = 1;
                    }
                    public function bar() {
                        $b = 1;
                    }
                }',
                ['Foo\A::foo', 'Foo\A::bar']
            ],
            'newFunctionAtEnd' => [
                '<?php
                namespace Foo;

                class A {
                    public function foo() {
                        $a = 1;
                    }
                    public function bar() {
                        $b = 1;
                    }
                }',
                '<?php
                namespace Foo;

                class A {
                    public function foo() {
                        $a = 1;
                    }
                    public function bar() {
                        $b = 1;
                    }
                    public function bat() {
                        $c = 1;
                    }
                }',
                ['Foo\A::foo', 'Foo\A::bar']
            ],
            'newFunctionAtBeginning' => [
                '<?php
                namespace Foo;

                class A {
                    public function foo() {
                        $a = 1;
                    }
                    public function bar() {
                        $b = 1;
                    }
                }',
                '<?php
                namespace Foo;

                class A {
                    public function bat() {
                        $c = 1;
                    }
                    public function foo() {
                        $a = 1;
                    }
                    public function bar() {
                        $b = 1;
                    }
                }',
                ['Foo\A::foo', 'Foo\A::bar']
            ],
            'newFunctionInMiddle' => [
                '<?php
                namespace Foo;

                class A {
                    public function foo() {
                        $a = 1;
                    }
                    public function bar() {
                        $b = 1;
                    }
                }',
                '<?php
                namespace Foo;

                class A {
                    public function foo() {
                        $a = 1;
                    }
                    public function bat() {
                        $c = 1;
                    }
                    public function bar() {
                        $b = 1;
                    }
                }',
                ['Foo\A::foo', 'Foo\A::bar']
            ],
            'whiteSpaceOnly' => [
                '<?php
                namespace Foo;

                class A {
                    public function foo() {
                        $a = 1;
                    }
                    public function bar() {
                        $b = 1;
                    }
                }',
                '<?php
                    namespace Foo;
                 class A {
                    public function foo() {

                            $a  = 1  ;
                    }

                    public function bar() {
                          $b  =   1;

                    }
                }',
                ['Foo\A::foo', 'Foo\A::bar']
            ],
        ];
    }
}
