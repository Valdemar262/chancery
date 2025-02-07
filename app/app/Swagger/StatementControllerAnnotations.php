<?php

namespace App\Swagger;

/**
 * @OA\Tag(
 *      name="Statement",
 *      description="API для управления заявками"
 *  )
 */

class StatementControllerAnnotations
{
    /**
     * @OA\Post(
     *     path="/api/createStatement",
     *     summary="Создать заявление",
     *     description="Создание нового заявления",
     *     tags={"Statements"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/StatementDTO")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Заявление создано",
     *         @OA\JsonContent(ref="#/components/schemas/Statement")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Некорректные данные"
     *     )
     * )
     */
    public static function createStatementAnnotation() {}

    /**
     * @OA\Get(
     *     path="/api/allStatements",
     *     summary="Получить все заявления",
     *     description="Возвращает список всех заявлений",
     *     tags={"Statements"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Список заявлений",
     *         @OA\JsonContent(type="array",
     *               @OA\Items(ref="#/components/schemas/AllStatementsDTO"))
     *      )
     * )
     */
    public static function getAllStatementsAnnotations() {}

    /**
     * @OA\Get(
     *     path="/api/showStatement/{id}",
     *     summary="Просмотр заявления",
     *     description="Получить информацию о конкретном заявлении",
     *     tags={"Statements"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Информация о заявлении",
     *         @OA\JsonContent(ref="#/components/schemas/StatementDTO")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Заявление не найдено"
     *     )
     * )
     */
    public static function showStatementAnnotation() {}

    /**
     * @OA\Put(
     *     path="/api/updateStatement",
     *     summary="Обновить заявление",
     *     description="Обновление существующего заявления",
     *     tags={"Statements"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/StatementDTO")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Заявление обновлено",
     *         @OA\JsonContent(ref="#/components/schemas/StatementDTO")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Некорректные данные"
     *     )
     * )
     */
    public static function updateStatementAnnotation() {}

    /**
     * @OA\Delete(
     *     path="/api/deleteStatement/{id}",
     *     summary="Удалить заявление",
     *     description="Удаление конкретного заявления",
     *     tags={"Statements"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *          response=200,
     *          description="Успешный запрос",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="message", type="string", example="Statement deleted successfully")
     *          ),
     *      ),
     * )
     */
    public static function deleteStatementAnnotation() {}
}
