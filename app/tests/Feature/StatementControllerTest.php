<?php

namespace Tests\Feature;

use App\Data\AllStatementsDTO\AllStatementsDTO;
use App\Data\StatementDTO\StatementDTO;
use App\DataAdapters\StatementServiceDataAdapter\StatementServiceDataAdapter;
use App\Http\Controllers\StatementController\StatementController;
use App\Models\Statement;
use App\Models\User;
use App\Services\FakerClient\FakerClient;
use App\Services\StatementService\StatementService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Mockery;
use Tests\TestCase;

class StatementControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->fakerClient = $this->app->make(FakerClient::class);

        $this->statementService = Mockery::mock(StatementService::class);
        $this->statementServiceDataAdapter = Mockery::mock(StatementServiceDataAdapter::class);

        $this->statementController = new StatementController(
            $this->statementService,
            $this->statementServiceDataAdapter
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

    public function test_createStatement()
    {
        $request = Request::create('/statements', 'POST');

        $statementDTO = $this->generateStatementDTO();
        $statement = new Statement();

        $this->statementServiceDataAdapter
            ->shouldReceive('createStatementDTOByRequest')
            ->once()
            ->with($request)
            ->andReturn($statementDTO);

        $this->statementService
            ->shouldReceive('createStatement')
            ->once()
            ->with($statementDTO)
            ->andReturn($statement);

        $response = $this->statementController->createStatement($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function test_getAllStatements()
    {
        $statements = new AllStatementsDTO(
            allStatements: new Collection()
        );

        $this->statementService
            ->shouldReceive('allStatements')
            ->once()
            ->andReturn($statements);

        $response = $this->statementController->getAllStatements();

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function test_showStatement()
    {
        $statement = new Statement();
        $responseDTO = $this->generateStatementDTO();

        $this->statementServiceDataAdapter
            ->shouldReceive('createResponseStatementDTO')
            ->once()
            ->with($statement)
            ->andReturn($responseDTO);

        $response = $this->statementController->showStatement($statement);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function test_updateStatement()
    {
        $request = Request::create('/statements', 'PUT');

        $updateStatementDTO = $this->generateStatementDTO();
        $statement = $this->generateStatementDTO();

        $this->statementServiceDataAdapter
            ->shouldReceive('createUpdateStatementDTOByRequest')
            ->once()
            ->with($request)
            ->andReturn($updateStatementDTO);

        $this->statementService
            ->shouldReceive('updateStatement')
            ->once()
            ->with($updateStatementDTO)
            ->andReturn($statement);

        $response = $this->statementController->updateStatement($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function test_deleteStatement()
    {
        $id = 1;

        $this->statementService
            ->shouldReceive('deleteStatement')
            ->once()
            ->with($id)
            ->andReturn(true);

        $response = $this->statementController->deleteStatement($id);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
