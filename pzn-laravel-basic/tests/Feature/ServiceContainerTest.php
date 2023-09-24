<?php

namespace Tests\Feature;

use App\Data\Foo;
use App\Data\Bar;
use App\Data\Person;
use App\Services\HelloService;
use App\Services\HelloServiceIndonesia;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ServiceContainerTest extends TestCase
{
    public function testDependency()
    {
        // $foo = new Foo();
        // ada cara pemanggilan new baru
        $foo1 = $this->app->make(Foo::class); // new Foo()
        $foo2 = $this->app->make(Foo::class); // new Foo()

        self::assertEquals('Foo', $foo1->foo());
        self::assertEquals('Foo', $foo2->foo());
        self::assertNotSame($foo1, $foo2);
    }

    // untuk memanggil objek yang beda
    public function testBind()
    {
        $this->app->bind(Person::class, function ($app) {
            return new Person("Rivaldo", "Ismir");
        });

        $person1 = $this->app->make(Person::class);
        $person2 = $this->app->make(Person::class);

        self::assertEquals('Rivaldo', $person1->firstName); // closure()  // new Person("Rivaldo", "Ismir");
        self::assertEquals('Rivaldo', $person2->firstName); // closure()  // new Person("Rivaldo", "Ismir");
        self::assertNotSame($person1, $person2);
    }

    // untuk memanggil objek yang sama
    public function testSingleton()
    {
        $this->app->singleton(Person::class, function ($app) {
            return new Person("Rivaldo", "Ismir");
        });

        $person1 = $this->app->make(Person::class);
        $person2 = $this->app->make(Person::class);

        self::assertEquals('Rivaldo', $person1->firstName); // closure()  // new Person("Rivaldo", "Ismir"); if not exists
        self::assertEquals('Rivaldo', $person2->firstName); // return existing
        self::assertSame($person1, $person2);
    }

    // untuk memanggil objek yang sama namun tidak memakai closure lagi,
    public function testInstance()
    {
        $person = new Person("Rivaldo", "Ismir");
        $this->app->instance(Person::class, $person);

        $person1 = $this->app->make(Person::class); // $person
        $person2 = $this->app->make(Person::class); // $person
        $person3 = $this->app->make(Person::class); // $person
        $person4 = $this->app->make(Person::class); // $person

        self::assertEquals('Rivaldo', $person1->firstName); // closure()  // new Person("Rivaldo", "Ismir"); if not exists
        self::assertEquals('Rivaldo', $person2->firstName); // return existing
        self::assertSame($person1, $person2);
    }


    public function testDependencyInjection()
    {
        $this->app->singleton(Foo::class, function ($app) {
            return new Foo();
        });

        $this->app->singleton(Bar::class, function ($app) {
            $foo = $app->make(Foo::class);
            return new Bar($foo);
        });

        $foo = $this->app->make(Foo::class);
        $bar1 = $this->app->make(Bar::class);
        $bar2 = $this->app->make(Bar::class);

        self::assertSame($foo, $bar1->foo);
        self::assertSame($bar1, $bar2);
    }

    public function testInterfaceToClass()
    {
        // $this->app->singleton(HelloService::class, HelloServiceIndonesia::class); // pake class
        // atau pake closure
        $this->app->singleton(HelloService::class, function ($app) {
            return new HelloServiceIndonesia();
        });

        $helloService = $this->app->make(HelloService::class);
        self::assertEquals('Halo Rivaldo', $helloService->hello("Rivaldo"));
    }
}
