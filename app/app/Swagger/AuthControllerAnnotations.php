<?php

namespace App\Swagger;

/**
 * @OA\Tag(
 *      name="Auth",
 *      description="API для управления авторизацией пользователей"
 *  )
 */

class AuthControllerAnnotations
{
    /**
     * @OA\Post(
     *     path="/api/register",
     *     summary="Регистрация нового пользователя",
     *     description="Создает нового пользователя в системе.",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "email", "password"},
     *             @OA\Property(property="name", type="string", description="Имя пользователя"),
     *             @OA\Property(property="email", type="string", description="Email пользователя"),
     *             @OA\Property(property="password", type="string", description="Пароль пользователя")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Успешная регистрация",
     *         @OA\JsonContent(ref="#/components/schemas/AuthResponseDTO")
     *     ),
     * )
     */
    public static function registerAnnotations() {}

    /**
     * @OA\Post(
     *     path="/api/login",
     *     summary="Авторизация пользователя",
     *     description="Авторизует пользователя, возвращает токен доступа.",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", description="Email пользователя"),
     *             @OA\Property(property="password", type="string", description="Пароль пользователя")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Успешная авторизация",
     *         @OA\JsonContent(ref="#/components/schemas/AuthResponseDTO")
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Ошибка авторизации",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthorized")
     *         )
     *     )
     * )
     */
    public static function loginAnnotations() {}

    /**
     * @OA\Get(
     *     path="/api/me",
     *     summary="Получить информацию о текущем пользователе",
     *     description="Возвращает информацию о текущем авторизованном пользователе.",
     *     tags={"Auth"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Информация о текущем пользователе",
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Ошибка авторизации",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthorized")
     *         )
     *     )
     * )
     */
    public static function meAnnotations() {}

    /**
     * @OA\Post(
     *     path="/api/refresh",
     *     summary="Обновление токена",
     *     description="Обновляет токен доступа для авторизованного пользователя.",
     *     tags={"Auth"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"refreshToken"},
     *             @OA\Property(property="refresh_token", type="string", description="Токен для обновления")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Токен успешно обновлен",
     *         @OA\JsonContent(ref="#/components/schemas/RefreshTokenResponseDTO")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Неверный токен обновления",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Invalid refresh token")
     *         )
     *     )
     * )
     */
    public static function refreshAnnotations() {}

    /**
     * @OA\Post(
     *     path="/api/logout",
     *     summary="Выход пользователя",
     *     description="Завершающий аутентификацию процесс для текущего пользователя.",
     *     tags={"Auth"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Успешный выход",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="User logged out successfully")
     *         )
     *     )
     * )
     */
    public static function logoutAnnotations() {}
}
