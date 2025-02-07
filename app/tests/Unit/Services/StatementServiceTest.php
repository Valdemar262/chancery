<?php

namespace Tests\Unit\Services;

use App\Data\AllStatementsDTO\AllStatementsDTO;
use App\Models\User;
use App\Services\FakerClient\FakerClient;
use Illuminate\Database\Eloquent\Collection;
use Tests\TestCase;
use App\Services\StatementService\StatementService;
use App\DataAdapters\StatementServiceDataAdapter\StatementServiceDataAdapter;
use App\Repositories\StatementRepository\StatementRepository;
use App\Data\StatementDTO\StatementDTO;
use App\Models\Statement;
use Mockery;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Enums\ErrorMessages;
use App\Enums\ResponseMessages;

class StatementServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->fakerClient = $this->app->make(FakerClient::class);

        $this->statementServiceDataAdapter = Mockery::mock(StatementServiceDataAdapter::class);
        $this->statementRepository = Mockery::mock(StatementRepository::class);

        $this->statementService = new StatementService(
            $this->statementServiceDataAdapter,
            $this->statementRepository
        );
    }

    public function generateStatementDTO(): StatementDTO
    {
        return new StatementDTO(
            id: $this->fakerClient->faker->randomNumber(),
            title: $this->fakerClient->faker->name(),
            user_id: User::factory()->create()->id,
            number: $this->fakerClient->faker->randomNumber(),
            date: $this->fakerClient->faker->date(),
        );
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_create_statement()
    {
        $statementDTO = new StatementDTO(
            id: $this->fakerClient->faker->randomNumber(),
            title: $this->fakerClient->faker->name(),
            user_id: User::factory()->create()->id,
            number: $this->fakerClient->faker->randomNumber(),
            date: $this->fakerClient->faker->date(),
        );

        $result = $this->statementService->createStatement($statementDTO);

        $this->assertInstanceOf(Statement::class, $result);
        $this->assertEquals($statementDTO->title, $result->title);
        $this->assertEquals($statementDTO->number, $result->number);
        $this->assertEquals($statementDTO->date, $result->date);
    }

    public function test_all_statements()
    {
        $statements = new Collection();

        $allStatementsDTO = new AllStatementsDTO($statements);

        $this->statementRepository
            ->shouldReceive('getAll')
            ->once()
            ->andReturn($statements);

        $this->statementServiceDataAdapter
            ->shouldReceive('createAllStatementsDTO')
            ->once()
            ->with($statements)
            ->andReturn($allStatementsDTO);

        $result = $this->statementService->allStatements();

        $this->assertInstanceOf(AllStatementsDTO::class, $result);
    }

    public function test_update_statement_success()
    {
        $updateStatementDTO = $this->generateStatementDTO();

        $statement = Statement::create([
            'title' => $this->fakerClient->faker->name(),
            'user_id' => User::factory()->create()->id,
            'number' => $this->fakerClient->faker->randomNumber(),
            'date' => $this->fakerClient->faker->date(),
        ]);

        $updatedStatementDTO = $this->generateStatementDTO();

        $this->statementRepository
            ->shouldReceive('findById')
            ->once()
            ->andReturn($statement);

        $this->statementServiceDataAdapter
            ->shouldReceive('createResponseStatementDTO')
            ->once()
            ->with($statement)
            ->andReturn($updatedStatementDTO);

        $result = $this->statementService->updateStatement($updateStatementDTO);

        $this->assertInstanceOf(StatementDTO::class, $result);
        $this->assertEquals($updatedStatementDTO->title, $result->title);
        $this->assertEquals($updatedStatementDTO->number, $result->number);
        $this->assertEquals($updatedStatementDTO->date, $result->date);
    }

    public function test_update_statement_not_found()
    {
        $updateStatementDTO = $this->generateStatementDTO();

        $this->statementRepository
            ->shouldReceive('findById')
            ->once()
            ->with($updateStatementDTO->id)
            ->andThrow(new ModelNotFoundException);

        $this->expectException(ModelNotFoundException::class);
        $this->expectExceptionMessage(ErrorMessages::STATEMENT_NOT_FOUND);

        $this->statementService->updateStatement($updateStatementDTO);
    }

    public function test_delete_statement_success()
    {
        $id = 1;

        $this->statementRepository
            ->shouldReceive('destroy')
            ->once()
            ->with($id)
            ->andReturn(true);

        $result = $this->statementService->deleteStatement($id);

        $this->assertEquals(ResponseMessages::DELETE_STATEMENT_SUCCESS, $result);
    }

    public function test_delete_statement_not_found()
    {
        $id = 1;

        $this->statementRepository
            ->shouldReceive('destroy')
            ->once()
            ->with($id)
            ->andReturn(false);

        $result = $this->statementService->deleteStatement($id);

        $this->assertEquals(ErrorMessages::STATEMENT_NOT_FOUND, $result);
    }
}
