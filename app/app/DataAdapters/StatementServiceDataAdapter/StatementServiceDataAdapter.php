<?php

namespace App\DataAdapters\StatementServiceDataAdapter;

use App\Data\AllStatementsDTO\AllStatementsDTO;
use App\Data\StatementDTO\StatementDTO;
use app\Enums\StatementStatus;
use App\Models\Statement;
use Illuminate\Database\Eloquent\Collection;
use Symfony\Component\HttpFoundation\Request;

class StatementServiceDataAdapter
{
    public function createStatementDTOByRequest(Request $request): StatementDTO
    {
        return $this->createStatementDTO(
            title: $request->get('title'),
            user_id: $request->get('user_id'),
            number: $request->get('number'),
            date: $request->get('date'),
        );
    }

    public function createStatementDTO(
        string  $title,
        string  $user_id,
        int     $number,
        ?string $date,
    ): StatementDTO {
        return StatementDTO::validateAndCreate([
            'title' => $title,
            'user_id' => $user_id,
            'number' => $number,
            'date' => $date,
            'status' => StatementStatus::DRAFT->value,
        ]);
    }

    public function createResponseStatementDTO(Statement $statement): StatementDTO
    {
        return StatementDTO::validateAndCreate([
            'id'          => $statement->id,
            'title'       => $statement->title,
            'user_id'     => $statement->user_id,
            'number'      => $statement->number,
            'date'        => $statement->date,
            'status'      => $statement->status->value,
            'resource_id' => $statement->resource_id,
            'approved_by' => $statement->approved_by,
        ]);
    }

    public function createUpdateStatementDTOByRequest(Request $request): StatementDTO
    {
        return $this->createUpdateStatementDTO(
            id: $request->get('id'),
            title: $request->get('title'),
            user_id: $request->get('user_id'),
            number: $request->get('number'),
            date: $request->get('date'),
        );
    }

    public function createUpdateStatementDTO(
        int    $id,
        string $title,
        int    $user_id,
        string $number,
        string $date,
    ): StatementDTO {
        return StatementDTO::validateAndCreate([
            'id' => $id,
            'title' => $title,
            'user_id' => $user_id,
            'number' => $number,
            'date' => $date,
        ]);
    }

    /**
     * @param Collection<int, Statement> $statements
     */
    public function createAllStatementsDTO(Collection $statements): AllStatementsDTO
    {
        return AllStatementsDTO::validateAndCreate([
            'allStatements' => $statements,
        ]);
    }

    public function createForSubmitStatementDTO(StatementDTO $statementDTO): StatementDTO
    {
        return StatementDTO::validateAndCreate([
            'id'          => $statementDTO->id,
            'title'       => $statementDTO->title,
            'user_id'     => $statementDTO->user_id,
            'number'      => $statementDTO->number,
            'date'        => $statementDTO->date,
            'status'      => StatementStatus::SUBMITTED->value,
            'resource_id' => $statementDTO->resource_id,
            'approved_by' => $statementDTO->approved_by,
        ]);
    }
}
