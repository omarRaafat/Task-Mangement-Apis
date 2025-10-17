<?php

/**
 * @OA\Info(
 *     title="Task Management System API",
 *     version="1.0.0",
 *     description="Complete task management API with authentication, tasks, and comments"
 * )
 * 
 * @OA\Server(
 *     url="http://127.0.0.1:8000",
 *     description="Development server"
 * )
 * 
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )
 * 
 * @OA\Tag(
 *     name="Authentication",
 *     description="User authentication endpoints"
 * )
 * 
 * @OA\Tag(
 *     name="Tasks",
 *     description="Task management endpoints"
 * )
 * 
 * @OA\Tag(
 *     name="Comments",
 *     description="Comment management endpoints"
 * )
 */

/**
 * @OA\PathItem(
 *     path="/api/register"
 * )
 * @OA\Post(
 *     path="/api/register",
 *     summary="Register a new user",
 *     tags={"Authentication"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"name","email","password"},
 *             @OA\Property(property="name", type="string", example="John Doe"),
 *             @OA\Property(property="email", type="string", format="email", example="john@example.com"),
 *             @OA\Property(property="password", type="string", format="password", example="password123")
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="User registered successfully"
 *     )
 * )
 */
function register() {}

/**
 * @OA\PathItem(
 *     path="/api/login"
 * )
 * @OA\Post(
 *     path="/api/login",
 *     summary="Login user",
 *     tags={"Authentication"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"email","password"},
 *             @OA\Property(property="email", type="string", format="email", example="john@example.com"),
 *             @OA\Property(property="password", type="string", format="password", example="password123")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Login successful"
 *     )
 * )
 */
function login() {}

/**
 * @OA\PathItem(
 *     path="/api/tasks"
 * )
 * @OA\Get(
 *     path="/api/tasks",
 *     summary="Get all tasks",
 *     tags={"Tasks"},
 *     security={{"bearerAuth":{}}},
 *     @OA\Response(
 *         response=200,
 *         description="List of tasks"
 *     )
 * )
 */
function getTasks() {}

/**
 * @OA\Post(
 *     path="/api/tasks",
 *     summary="Create a new task",
 *     tags={"Tasks"},
 *     security={{"bearerAuth":{}}},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"title","description"},
 *             @OA\Property(property="title", type="string", example="New Task"),
 *             @OA\Property(property="description", type="string", example="Task description")
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Task created successfully"
 *     )
 * )
 */
function createTask() {}
