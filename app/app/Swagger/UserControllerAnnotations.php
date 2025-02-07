<?php

namespace App\Swagger;

/**
 * @OA\Info(
 *     title="Chancery API",
 *     version="1.0.0",
 *     description="API documentation for Chancery Application"
 * )
 * @OA\SecurityScheme(
 *      securityScheme="bearerAuth",
 *      type="http",
 *      scheme="bearer",
 *      bearerFormat="JWT",
 *      description="Введите ваш Bearer токен."
 *  )
 * @OA\Tag(
 *      name="Users",
 *      description="API для управления пользователями"
 *  )
 */

class UserControllerAnnotations
{
    /**
     * @OA\Get(
     *     path="/api/allUsers",
     *     summary="Получить всех пользователей",
     *     description="Возвращает список всех пользователей",
     *     tags={"Users"},
     *     @OA\Response(
     *         response=200,
     *         description="Успешный запрос",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/AllUsersDTO")
     *         ),
     *     ),
     *     security={{"bearerAuth": {}}}
     * )
     */
    public static function getAllUsersAnnotations() {}

    /**
     * @OA\Get(
     *     path="/api/showUser/{id}",
     *     summary="Показать пользователя",
     *     description="Возвращает информацию о пользователе по его ID",
     *     tags={"Users"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID пользователя",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Успешный запрос",
     *         @OA\JsonContent(
     *              type="array",
     *              @OA\Items(ref="#/components/schemas/UserOperationDTO"))
     *     ),
     *     security={{"bearerAuth": {}}},
     * )
     */
    public static function showUserAnnotation() {}

    /**
     * @OA\Put(
     *     path="/api/updateUser",
     *     summary="Обновить информацию о пользователе",
     *     description="Обновляет данные пользователя по его ID",
     *     tags={"Users"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID пользователя",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Успешный запрос",
     *         @OA\JsonContent(
     *               type="array",
     *               @OA\Items(ref="#/components/schemas/UserOperationDTO"))
     *      ),
     *     ),
     *     security={{"bearerAuth": {}}},
     * )
     */
    public static function updateUserAnnotation() {}

    /**
     * @OA\Delete(
     *     path="/api/deleteUser/{id}",
     *     summary="Удалить пользователя",
     *     description="Удаляет пользователя по его ID",
     *     tags={"Users"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID пользователя",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Успешный запрос",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="User deleted successfully")
     *         ),
     *     ),
     *     security={{"bearerAuth": {}}},
     * )
     */
    public static function deleteUserAnnotation() {}

    /**
     * @OA\Post(
     *     path="/api/assignRole/{id}",
     *     summary="Назначить роль пользователю",
     *     description="Назначает роль пользователю",
     *     tags={"Users"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID пользователя",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Успешный запрос",
     *         @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="message", type="string", example="Role assigned successfully")
     *          ),
     *     ),
     *     @OA\SecurityScheme(
     *          securityScheme="BearerAuth",
     *          type="http",
     *          scheme="bearer",
     *          bearerFormat="bearer",
     *      ),
     *     security={{"bearerAuth": {}}},
     * )
     */
    public static function assignUserAnnotation() {}

    /**
     * @OA\Delete(
     *     path="/api/removeRole/{id}",
     *     summary="Удалить роль у пользователя",
     *     description="Удаляет роль у пользователя",
     *     tags={"Users"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID пользователя",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Успешный запрос",
     *         @OA\JsonContent(
     *               type="object",
     *               @OA\Property(property="message", type="string", example="Role assigned successfully")
     *           ),
     *     ),
     *     security={{"bearerAuth": {}}},
     * )
     */
    public static function removeRoleAnnotation() {}
}
