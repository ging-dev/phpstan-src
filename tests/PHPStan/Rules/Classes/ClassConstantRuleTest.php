<?php declare(strict_types = 1);

namespace PHPStan\Rules\Classes;

use PHPStan\Rules\ClassCaseSensitivityCheck;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleLevelHelper;

class ClassConstantRuleTest extends \PHPStan\Rules\AbstractRuleTest
{

	protected function getRule(): Rule
	{
		$broker = $this->createBroker();
		return new ClassConstantRule($broker, new RuleLevelHelper($broker, true, false, true), new ClassCaseSensitivityCheck($broker));
	}

	public function testClassConstant()
	{
		$this->analyse(
			[
				__DIR__ . '/data/class-constant.php',
				__DIR__ . '/data/class-constant-defined.php',
			],
			[
				[
					'Class ClassConstantNamespace\Bar not found.',
					6,
				],
				[
					'Using self outside of class scope.',
					7,
				],
				[
					'Access to undefined constant ClassConstantNamespace\Foo::DOLOR.',
					10,
				],
				[
					'Access to undefined constant ClassConstantNamespace\Foo::DOLOR.',
					16,
				],
				[
					'Using static outside of class scope.',
					18,
				],
				[
					'Using parent outside of class scope.',
					19,
				],
				[
					'Access to constant FOO on an unknown class ClassConstantNamespace\UnknownClass.',
					21,
				],
				[
					'Class ClassConstantNamespace\Foo referenced with incorrect case: ClassConstantNamespace\FOO.',
					26,
				],
				[
					'Class ClassConstantNamespace\Foo referenced with incorrect case: ClassConstantNamespace\FOO.',
					27,
				],
				[
					'Access to undefined constant ClassConstantNamespace\Foo::DOLOR.',
					27,
				],
				[
					'Class ClassConstantNamespace\Foo referenced with incorrect case: ClassConstantNamespace\FOO.',
					28,
				],
			]
		);
	}

	/**
	 * @requires PHP 7.1
	 */
	public function testClassConstantVisibility()
	{
		$this->analyse([__DIR__ . '/data/class-constant-visibility.php'], [
			[
				'Access to private constant PRIVATE_BAR of class ClassConstantVisibility\Bar.',
				25,
			],
			[
				'Access to parent::BAZ but ClassConstantVisibility\Foo does not extend any class.',
				27,
			],
			[
				'Access to undefined constant ClassConstantVisibility\Bar::PRIVATE_FOO.',
				45,
			],
			[
				'Access to private constant PRIVATE_FOO of class ClassConstantVisibility\Foo.',
				46,
			],
			[
				'Access to private constant PRIVATE_FOO of class ClassConstantVisibility\Foo.',
				47,
			],
			[
				'Access to undefined constant ClassConstantVisibility\Bar::PRIVATE_FOO.',
				60,
			],
			[
				'Access to protected constant PROTECTED_FOO of class ClassConstantVisibility\Foo.',
				70,
			],
			[
				'Access to undefined constant ClassConstantVisibility\WithFooAndBarConstant&ClassConstantVisibility\WithFooConstant::BAZ.',
				105,
			],
			[
				'Access to undefined constant ClassConstantVisibility\WithFooAndBarConstant|ClassConstantVisibility\WithFooConstant::BAR.',
				109,
			],
			[
				'Access to constant FOO on an unknown class ClassConstantVisibility\UnknownClassFirst.',
				111,
			],
			[
				'Access to constant FOO on an unknown class ClassConstantVisibility\UnknownClassSecond.',
				111,
			],
			[
				'Cannot access constant FOO on int|string.',
				115,
			],
			[
				'Class ClassConstantVisibility\Foo referenced with incorrect case: ClassConstantVisibility\FOO.',
				121,
			],
			[
				'Access to private constant PRIVATE_FOO of class ClassConstantVisibility\Foo.',
				121,
			],
		]);
	}

}
