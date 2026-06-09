<?php

declare(strict_types=1);

namespace Tests\Unit\Strategies;

use Mockery;
use Tests\TestCase;
use App\Enums\StatusTransitionType;
use App\Services\StatementService\Strategies\StatusTransitionStrategyResolver;
use App\Services\StatementService\Strategies\Contracts\StatusTransitionStrategyInterface;
use Illuminate\Contracts\Container\Container;
use InvalidArgumentException;

class StatusTransitionStrategyResolverTest extends TestCase
{
    public function test_resolve_returns_strategy_when_binding_exists(): void
    {
        $container = Mockery::mock(Container::class);
        $strategy = Mockery::mock(StatusTransitionStrategyInterface::class);

        $container
            ->shouldReceive('bound')
            ->once()
            ->with('status_transition.submit')
            ->andReturn(true);

        $container
            ->shouldReceive('make')
            ->once()
            ->with('status_transition.submit')
            ->andReturn($strategy);

        $resolver = new StatusTransitionStrategyResolver($container);
        $result = $resolver->resolve(StatusTransitionType::SUBMIT);

        $this->assertSame($strategy, $result);
    }

    public function test_resolve_throws_exception_when_binding_does_not_exist(): void
    {
        $container = Mockery::mock(Container::class);

        $container
            ->shouldReceive('bound')
            ->once()
            ->with('status_transition.approve')
            ->andReturn(false);

        $container->shouldNotReceive('make');

        $resolver = new StatusTransitionStrategyResolver($container);
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('No strategy registered for: approve');

        $resolver->resolve(StatusTransitionType::APPROVE);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
