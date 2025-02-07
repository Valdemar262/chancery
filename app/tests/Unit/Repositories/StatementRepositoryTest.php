<?php

namespace Tests\Unit\Repositories;

use App\Repositories\StatementRepository\StatementRepository;
use Tests\TestCase;
use App\Models\Statement;
use Illuminate\Database\Eloquent\Collection;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

class StatementRepositoryTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    protected function setUp(): void
    {
        parent::setUp();

        $this->statementMock = Mockery::mock(Statement::class);

        $this->statementRepository = new StatementRepository();

        $this->app->instance(Statement::class, $this->statementMock);
    }

    public function test_findById()
    {
         $statement = Statement::factory()->create();

        $this->app->instance(Statement::class, $this->statementMock);

        $result = $this->statementRepository->findById($statement->id);

        $this->assertInstanceOf(Statement::class, $result);
        $this->assertEquals($statement->id, $result->id);
        $this->assertEquals($statement->title, $result->title);
    }

    public function test_getAll()
    {
        Statement::factory()->count(3)->create();

        $statements = $this->statementRepository->getAll();

        $this->assertInstanceOf(Collection::class, $statements);
        $this->assertCount(6, $statements);
    }

    public function test_destroy()
    {
        $statement = Statement::factory()->create();

        $deletedRows = $this->statementRepository->destroy($statement->id);
        $foundStatement = Statement::find($statement->id);

        $this->assertEquals(1, $deletedRows);
        $this->assertNull($foundStatement);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
