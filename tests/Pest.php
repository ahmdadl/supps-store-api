<?php

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "pest()" function to bind a different classes or traits.
|
*/

use Modules\Guests\Models\Guest;

use function Pest\Laravel\actingAs;

pest()
    ->extend(Tests\TestCase::class)
    ->use(Illuminate\Foundation\Testing\RefreshDatabase::class)
    ->in("../Modules/*/tests/**/*Test.php");

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

expect()->extend("toBeOne", function () {
    return $this->toBe(1);
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

function something()
{
    // ..
}

/**
 * login as a guest
 */
function asGuest(?Guest $guest = null): void
{
    actingAs($guest ?? Guest::factory()->create(), "guest");
}

/**
 * generate fake egyptian phone
 */
function fakePhone(): string
{
    $prefixes = ["010", "011", "012"];

    /** @var string */
    $prefix = $prefixes[array_rand($prefixes)];

    $number = sprintf("%08d", rand(0, 99999999));

    return $prefix . $number;
}
