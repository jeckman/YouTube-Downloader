<?php

/*
 * PHP script for downloading videos from youtube
 * Copyright (C) 2012-2018  John Eckman
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, see <http://www.gnu.org/licenses/>.
 */

namespace YoutubeDownloader\Tests\Unit\Container;

use Psr\Container\ContainerInterface;
use YoutubeDownloader\Container\Container;
use YoutubeDownloader\Container\ContainerException;
use YoutubeDownloader\Container\NotFoundException;
use YoutubeDownloader\Container\SimpleContainer;
use YoutubeDownloader\Tests\Fixture\TestCase;
use YoutubeDownloader\Tests\Fixture\Container\Psr11ContainerAdapter;

class SimpleContainerTest extends TestCase
{
    /**
     * @test SimpleContainer implements Container
     */
    public function implementsContainer()
    {
        $container = new SimpleContainer();

        $this->assertInstanceOf(Container::class, $container);
    }

    /**
     * @test SimpleContainer is compatible with Psr\Container\ContainerInterface
     */
    public function isPsr11Compatible()
    {
        $container = new SimpleContainer();

        $adapter = new Psr11ContainerAdapter($container);

        $this->assertInstanceOf(ContainerInterface::class, $adapter);
        $this->assertInstanceOf(Container::class, $adapter);
    }

    /**
     * @test set(), has() and get()
     * @dataProvider GetterSetterProvider
     *
     * @param mixed $id
     * @param mixed $value
     */
    public function testSetterAndGetterThrowsExceptionWithoutClosure($id, $value)
    {
        $container = new SimpleContainer();

        $this->expectException(ContainerException::class);
        $this->expectExceptionMessage('Second argument ($value) must be a Closure or a string as alias to an existing entry.');

        $container->set($id, $value);
    }

    /**
     * @test set(), has() and get()
     * @dataProvider GetterSetterProvider
     *
     * @param mixed $id
     * @param mixed $value
     */
    public function testSetterAndGetter($id, $value)
    {
        $container = new SimpleContainer();

        $closure = function ($c) use ($value) {
            return $value;
        };

        $container->set($id, $closure);

        $this->assertTrue($container->has($id));
        $this->assertSame($value, $container->get($id));
    }

    /**
     * @test set(), has() and get()
     * @dataProvider GetterSetterProvider
     *
     * @param mixed $id
     * @param mixed $value
     */
    public function testSetterAndGetterAlias($id, $value)
    {
        $container = new SimpleContainer();

        $closure = function ($c) use ($value) {
            return $value;
        };

        $container->set($id, $closure);
        $container->set($id . '-alias', $id);

        $this->assertTrue($container->has($id . '-alias'));
        $this->assertSame($value, $container->get($id . '-alias'));
    }

    /**
     * GetterSetterProvider
     */
    public function GetterSetterProvider()
    {
        return [
            ['null', null],
            ['true', true],
            ['false', false],
            ['int', 123456789],
            ['float', 1234.56789],
            ['string', 'string'],
            ['array', ['array']],
            ['object', new \stdClass],
        ];
    }

    /**
     * @test SimpleContainer throws NotFoundException
     */
    public function getThrowsNotFoundException()
    {
        $container = new SimpleContainer();

        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage('Entry "foo" don\'t exists in the container');

        $container->get('foo');
    }
}
