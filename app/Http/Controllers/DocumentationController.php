<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Http\JsonResponse;

class DocumentationController extends Controller
{
    public function index(): JsonResponse
    {
        $documentation = [
            'openapi' => '3.0.0',
            'info' => [
                'title' => 'Task Management System API',
                'version' => '1.0.0',
                'description' => 'Complete task management API with authentication, tasks, and comments'
            ],
            'servers' => [
                [
                    'url' => 'http://127.0.0.1:8000',
                    'description' => 'Development server'
                ]
            ],
            'security' => [
                ['bearerAuth' => []]
            ],
            'paths' => [
                '/api/register' => [
                    'post' => [
                        'tags' => ['Authentication'],
                        'summary' => 'Register a new user',
                        'security' => [],
                        'requestBody' => [
                            'required' => true,
                            'content' => [
                                'application/json' => [
                                    'schema' => [
                                        'type' => 'object',
                                        'required' => ['name', 'email', 'password'],
                                        'properties' => [
                                            'name' => ['type' => 'string', 'example' => 'Test User'],
                                            'email' => ['type' => 'string', 'format' => 'email', 'example' => 'test@example.com'],
                                            'password' => ['type' => 'string', 'format' => 'password', 'example' => 'Password123!'],
                                            'password_confirmation' => ['type' => 'string', 'format' => 'password', 'example' => 'Password123!']
                                        ]
                                    ]
                                ]
                            ]
                        ],
                        'responses' => [
                            '201' => [
                                'description' => 'User registered successfully',
                                'content' => [
                                    'application/json' => [
                                        'schema' => [
                                            'type' => 'object',
                                            'properties' => [
                                                'message' => ['type' => 'string', 'example' => 'User registered successfully'],
                                                'user' => [
                                                    'type' => 'object',
                                                    'properties' => [
                                                        'id' => ['type' => 'integer', 'example' => 1],
                                                        'name' => ['type' => 'string', 'example' => 'Test User'],
                                                        'email' => ['type' => 'string', 'example' => 'test@example.com']
                                                    ]
                                                ],
                                                'access_token' => ['type' => 'string', 'example' => '1|eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...'],
                                                'token_type' => ['type' => 'string', 'example' => 'Bearer']
                                            ]
                                        ]
                                    ]
                                ]
                            ],
                            '422' => ['description' => 'Validation error'],
                            '500' => ['description' => 'Server error']
                        ]
                    ]
                ],
                '/api/login' => [
                    'post' => [
                        'tags' => ['Authentication'],
                        'summary' => 'Login user',
                        'security' => [],
                        'requestBody' => [
                            'required' => true,
                            'content' => [
                                'application/json' => [
                                    'schema' => [
                                        'type' => 'object',
                                        'required' => ['email', 'password'],
                                        'properties' => [
                                            'email' => ['type' => 'string', 'format' => 'email', 'example' => 'admin@example.com'],
                                            'password' => ['type' => 'string', 'format' => 'password', 'example' => 'Password123!']
                                        ]
                                    ]
                                ]
                            ]
                        ],
                        'responses' => [
                            '200' => [
                                'description' => 'Login successful',
                                'content' => [
                                    'application/json' => [
                                        'schema' => [
                                            'type' => 'object',
                                            'properties' => [
                                                'message' => ['type' => 'string', 'example' => 'Login successful'],
                                                'user' => [
                                                    'type' => 'object',
                                                    'properties' => [
                                                        'id' => ['type' => 'integer', 'example' => 1],
                                                        'name' => ['type' => 'string', 'example' => 'Admin User'],
                                                        'email' => ['type' => 'string', 'example' => 'admin@example.com']
                                                    ]
                                                ],
                                                'access_token' => ['type' => 'string', 'example' => '1|eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...'],
                                                'token_type' => ['type' => 'string', 'example' => 'Bearer']
                                            ]
                                        ]
                                    ]
                                ]
                            ],
                            '401' => ['description' => 'Invalid credentials'],
                            '422' => ['description' => 'Validation error'],
                            '500' => ['description' => 'Server error']
                        ]
                    ]
                ],
                '/api/user' => [
                    'get' => [
                        'tags' => ['Authentication'],
                        'summary' => 'Get authenticated user',
                        'responses' => [
                            '200' => [
                                'description' => 'User information',
                                'content' => [
                                    'application/json' => [
                                        'schema' => [
                                            'type' => 'object',
                                            'properties' => [
                                                'user' => [
                                                    'type' => 'object',
                                                    'properties' => [
                                                        'id' => ['type' => 'integer', 'example' => 1],
                                                        'name' => ['type' => 'string', 'example' => 'Admin User'],
                                                        'email' => ['type' => 'string', 'example' => 'admin@example.com']
                                                    ]
                                                ]
                                            ]
                                        ]
                                    ]
                                ]
                            ],
                            '401' => ['description' => 'Unauthenticated']
                        ]
                    ]
                ],
                '/api/logout' => [
                    'post' => [
                        'tags' => ['Authentication'],
                        'summary' => 'Logout user',
                        'responses' => [
                            '200' => [
                                'description' => 'Successfully logged out',
                                'content' => [
                                    'application/json' => [
                                        'schema' => [
                                            'type' => 'object',
                                            'properties' => [
                                                'message' => ['type' => 'string', 'example' => 'Successfully logged out']
                                            ]
                                        ]
                                    ]
                                ]
                            ],
                            '401' => ['description' => 'Unauthenticated']
                        ]
                    ]
                ],
                '/api/tasks' => [
                    'get' => [
                        'tags' => ['Tasks'],
                        'summary' => 'Get all tasks',
                        'responses' => [
                            '200' => [
                                'description' => 'List of tasks',
                                'content' => [
                                    'application/json' => [
                                        'schema' => [
                                            'type' => 'array',
                                            'items' => [
                                                'type' => 'object',
                                                'properties' => [
                                                    'id' => ['type' => 'integer', 'example' => 1],
                                                    'title' => ['type' => 'string', 'example' => 'Implement user authentication system'],
                                                    'description' => ['type' => 'string', 'example' => 'This is a test task created via API'],
                                                    'status' => ['type' => 'string', 'example' => 'pending'],
                                                    'user_id' => ['type' => 'integer', 'example' => 1],
                                                    'assigned_to' => ['type' => 'integer', 'example' => 1],
                                                    'created_at' => ['type' => 'string', 'format' => 'date-time'],
                                                    'updated_at' => ['type' => 'string', 'format' => 'date-time']
                                                ]
                                            ]
                                        ]
                                    ]
                                ]
                            ],
                            '401' => ['description' => 'Unauthenticated']
                        ]
                    ],
                    'post' => [
                        'tags' => ['Tasks'],
                        'summary' => 'Create a new task',
                        'requestBody' => [
                            'required' => true,
                            'content' => [
                                'application/json' => [
                                    'schema' => [
                                        'type' => 'object',
                                        'required' => ['title', 'description'],
                                        'properties' => [
                                            'title' => ['type' => 'string', 'example' => 'API Test Task'],
                                            'description' => ['type' => 'string', 'example' => 'This is a test task created via API'],
                                            'status' => ['type' => 'string', 'enum' => ['pending', 'in-progress', 'completed'], 'example' => 'pending'],
                                            'assigned_to' => ['type' => 'integer', 'example' => 1],
                                            'due_date' => ['type' => 'date', 'example' => '2025-12-31']
                                        ]
                                    ]
                                ]
                            ]
                        ],
                        'responses' => [
                            '201' => [
                                'description' => 'Task created successfully',
                                'content' => [
                                    'application/json' => [
                                        'schema' => [
                                            'type' => 'object',
                                            'properties' => [
                                                'id' => ['type' => 'integer', 'example' => 1],
                                                'title' => ['type' => 'string', 'example' => 'API Test Task'],
                                                'description' => ['type' => 'string', 'example' => 'This is a test task created via API'],
                                                'status' => ['type' => 'string', 'example' => 'pending'],
                                                'user_id' => ['type' => 'integer', 'example' => 1],
                                                'assigned_to' => ['type' => 'integer', 'example' => 1],
                                                'created_at' => ['type' => 'string', 'format' => 'date-time'],
                                                'updated_at' => ['type' => 'string', 'format' => 'date-time']
                                            ]
                                        ]
                                    ]
                                ]
                            ],
                            '401' => ['description' => 'Unauthenticated'],
                            '422' => ['description' => 'Validation error']
                        ]
                    ]
                ],
                '/api/tasks/{id}' => [
                    'get' => [
                        'tags' => ['Tasks'],
                        'summary' => 'Get a specific task',
                        'parameters' => [
                            [
                                'name' => 'id',
                                'in' => 'path',
                                'required' => true,
                                'description' => 'Task ID',
                                'schema' => ['type' => 'integer', 'example' => 1]
                            ]
                        ],
                        'responses' => [
                            '200' => [
                                'description' => 'Task details',
                                'content' => [
                                    'application/json' => [
                                        'schema' => [
                                            'type' => 'object',
                                            'properties' => [
                                                'id' => ['type' => 'integer', 'example' => 1],
                                                'title' => ['type' => 'string', 'example' => 'Implement user authentication system'],
                                                'description' => ['type' => 'string', 'example' => 'This is a test task created via API'],
                                                'status' => ['type' => 'string', 'example' => 'pending'],
                                                'user_id' => ['type' => 'integer', 'example' => 1],
                                                'assigned_to' => ['type' => 'integer', 'example' => 1],
                                                'created_at' => ['type' => 'string', 'format' => 'date-time'],
                                                'updated_at' => ['type' => 'string', 'format' => 'date-time']
                                            ]
                                        ]
                                    ]
                                ]
                            ],
                            '401' => ['description' => 'Unauthenticated'],
                            '404' => ['description' => 'Task not found']
                        ]
                    ],
                    'put' => [
                        'tags' => ['Tasks'],
                        'summary' => 'Update a task',
                        'parameters' => [
                            [
                                'name' => 'id',
                                'in' => 'path',
                                'required' => true,
                                'description' => 'Task ID',
                                'schema' => ['type' => 'integer', 'example' => 1]
                            ]
                        ],
                        'requestBody' => [
                            'required' => true,
                            'content' => [
                                'application/json' => [
                                    'schema' => [
                                        'type' => 'object',
                                        'properties' => [
                                            'title' => ['type' => 'string', 'example' => 'This is a test task created via API'],
                                            'description' => ['type' => 'string', 'example' => 'This is a test task created via API'],
                                            'status' => ['type' => 'string', 'enum' => ['pending', 'in-progress', 'completed'], 'example' => 'in-progress'],
                                            'assigned_to' => ['type' => 'integer', 'example' => 1],
                                            'due_date' => ['type' => 'date', 'example' => '2025-12-31']
                                        ]
                                    ]
                                ]
                            ]
                        ],
                        'responses' => [
                            '200' => [
                                'description' => 'Task updated successfully',
                                'content' => [
                                    'application/json' => [
                                        'schema' => [
                                            'type' => 'object',
                                            'properties' => [
                                                'message' => ['type' => 'string', 'example' => 'Task updated successfully']
                                            ]
                                        ]
                                    ]
                                ]
                            ],
                            '401' => ['description' => 'Unauthenticated'],
                            '403' => ['description' => 'Unauthorized - Only task owner can update'],
                            '404' => ['description' => 'Task not found'],
                            '422' => ['description' => 'Validation error']
                        ]
                    ],
                    'delete' => [
                        'tags' => ['Tasks'],
                        'summary' => 'Delete a task',
                        'parameters' => [
                            [
                                'name' => 'id',
                                'in' => 'path',
                                'required' => true,
                                'description' => 'Task ID',
                                'schema' => ['type' => 'integer', 'example' => 1]
                            ]
                        ],
                        'responses' => [
                            '200' => [
                                'description' => 'Task deleted successfully',
                                'content' => [
                                    'application/json' => [
                                        'schema' => [
                                            'type' => 'object',
                                            'properties' => [
                                                'message' => ['type' => 'string', 'example' => 'Task deleted successfully']
                                            ]
                                        ]
                                    ]
                                ]
                            ],
                            '401' => ['description' => 'Unauthenticated'],
                            '403' => ['description' => 'Unauthorized - Only task owner can delete'],
                            '404' => ['description' => 'Task not found']
                        ]
                    ]
                ],
                '/api/my-tasks' => [
                    'get' => [
                        'tags' => ['Tasks'],
                        'summary' => 'Get tasks created by authenticated user',
                        'responses' => [
                            '200' => [
                                'description' => 'List of user\'s tasks',
                                'content' => [
                                    'application/json' => [
                                        'schema' => [
                                            'type' => 'array',
                                            'items' => [
                                                'type' => 'object',
                                                'properties' => [
                                                    'id' => ['type' => 'integer', 'example' => 1],
                                                    'title' => ['type' => 'string', 'example' => 'Review code quality and performance'],
                                                    'description' => ['type' => 'string', 'example' => 'This is a test task created via API'],
                                                    'status' => ['type' => 'string', 'example' => 'pending'],
                                                    'user_id' => ['type' => 'integer', 'example' => 1],
                                                    'assigned_to' => ['type' => 'integer', 'example' => 1],
                                                    'created_at' => ['type' => 'string', 'format' => 'date-time'],
                                                    'updated_at' => ['type' => 'string', 'format' => 'date-time']
                                                ]
                                            ]
                                        ]
                                    ]
                                ]
                            ],
                            '401' => ['description' => 'Unauthenticated']
                        ]
                    ]
                ],
                '/api/assigned-tasks' => [
                    'get' => [
                        'tags' => ['Tasks'],
                        'summary' => 'Get tasks assigned to authenticated user',
                        'responses' => [
                            '200' => [
                                'description' => 'List of assigned tasks',
                                'content' => [
                                    'application/json' => [
                                        'schema' => [
                                            'type' => 'array',
                                            'items' => [
                                                'type' => 'object',
                                                'properties' => [
                                                    'id' => ['type' => 'integer', 'example' => 1],
                                                    'title' => ['type' => 'string', 'example' => 'Fix critical security vulnerabilities'],
                                                    'description' => ['type' => 'string', 'example' => 'This is a test task created via API'],
                                                    'status' => ['type' => 'string', 'example' => 'pending'],
                                                    'user_id' => ['type' => 'integer', 'example' => 1],
                                                    'assigned_to' => ['type' => 'integer', 'example' => 1],
                                                    'created_at' => ['type' => 'string', 'format' => 'date-time'],
                                                    'updated_at' => ['type' => 'string', 'format' => 'date-time']
                                                ]
                                            ]
                                        ]
                                    ]
                                ]
                            ],
                            '401' => ['description' => 'Unauthenticated']
                        ]
                    ]
                ],
                '/api/tasks/{task}/comments' => [
                    'get' => [
                        'tags' => ['Comments'],
                        'summary' => 'Get comments for a task',
                        'parameters' => [
                            [
                                'name' => 'task',
                                'in' => 'path',
                                'required' => true,
                                'description' => 'Task ID',
                                'schema' => ['type' => 'integer', 'example' => 1]
                            ]
                        ],
                        'responses' => [
                            '200' => [
                                'description' => 'List of comments',
                                'content' => [
                                    'application/json' => [
                                        'schema' => [
                                            'type' => 'array',
                                            'items' => [
                                                'type' => 'object',
                                                'properties' => [
                                                    'id' => ['type' => 'integer', 'example' => 1],
                                                    'content' => ['type' => 'string', 'example' => 'This is a test comment via API.'],
                                                    'task_id' => ['type' => 'integer', 'example' => 1],
                                                    'user_id' => ['type' => 'integer', 'example' => 1],
                                                    'created_at' => ['type' => 'string', 'format' => 'date-time'],
                                                    'updated_at' => ['type' => 'string', 'format' => 'date-time'],
                                                    'user' => [
                                                        'type' => 'object',
                                                        'properties' => [
                                                            'id' => ['type' => 'integer', 'example' => 1],
                                                            'name' => ['type' => 'string', 'example' => 'Admin User'],
                                                            'email' => ['type' => 'string', 'example' => 'admin@example.com']
                                                        ]
                                                    ]
                                                ]
                                            ]
                                        ]
                                    ]
                                ]
                            ],
                            '401' => ['description' => 'Unauthenticated'],
                            '404' => ['description' => 'Task not found']
                        ]
                    ],
                    'post' => [
                        'tags' => ['Comments'],
                        'summary' => 'Add a comment to a task',
                        'parameters' => [
                            [
                                'name' => 'task',
                                'in' => 'path',
                                'required' => true,
                                'description' => 'Task ID',
                                'schema' => ['type' => 'integer', 'example' => 1]
                            ]
                        ],
                        'requestBody' => [
                            'required' => true,
                            'content' => [
                                'application/json' => [
                                    'schema' => [
                                        'type' => 'object',
                                        'required' => ['content'],
                                        'properties' => [
                                            'content' => ['type' => 'string', 'example' => 'This is a test comment via API.']
                                        ]
                                    ]
                                ]
                            ]
                        ],
                        'responses' => [
                            '201' => [
                                'description' => 'Comment created successfully',
                                'content' => [
                                    'application/json' => [
                                        'schema' => [
                                            'type' => 'object',
                                            'properties' => [
                                                'id' => ['type' => 'integer', 'example' => 1],
                                                'content' => ['type' => 'string', 'example' => 'This is a test comment via API.'],
                                                'task_id' => ['type' => 'integer', 'example' => 1],
                                                'user_id' => ['type' => 'integer', 'example' => 1],
                                                'created_at' => ['type' => 'string', 'format' => 'date-time'],
                                                'updated_at' => ['type' => 'string', 'format' => 'date-time']
                                            ]
                                        ]
                                    ]
                                ]
                            ],
                            '401' => ['description' => 'Unauthenticated'],
                            '404' => ['description' => 'Task not found'],
                            '422' => ['description' => 'Validation error']
                        ]
                    ]
                ],
                '/api/tasks/{task}/comments/{comment}' => [
                    'put' => [
                        'tags' => ['Comments'],
                        'summary' => 'Update a comment',
                        'parameters' => [
                            [
                                'name' => 'task',
                                'in' => 'path',
                                'required' => true,
                                'description' => 'Task ID',
                                'schema' => ['type' => 'integer', 'example' => 1]
                            ],
                            [
                                'name' => 'comment',
                                'in' => 'path',
                                'required' => true,
                                'description' => 'Comment ID',
                                'schema' => ['type' => 'integer', 'example' => 1]
                            ]
                        ],
                        'requestBody' => [
                            'required' => true,
                            'content' => [
                                'application/json' => [
                                    'schema' => [
                                        'type' => 'object',
                                        'required' => ['content'],
                                        'properties' => [
                                            'content' => ['type' => 'string', 'example' => 'This is a test comment via API.']
                                        ]
                                    ]
                                ]
                            ]
                        ],
                        'responses' => [
                            '200' => [
                                'description' => 'Comment updated successfully',
                                'content' => [
                                    'application/json' => [
                                        'schema' => [
                                            'type' => 'object',
                                            'properties' => [
                                                'message' => ['type' => 'string', 'example' => 'Comment updated successfully']
                                            ]
                                        ]
                                    ]
                                ]
                            ],
                            '401' => ['description' => 'Unauthenticated'],
                            '403' => ['description' => 'Unauthorized - Only comment owner can update'],
                            '404' => ['description' => 'Comment not found'],
                            '422' => ['description' => 'Validation error']
                        ]
                    ],
                    'delete' => [
                        'tags' => ['Comments'],
                        'summary' => 'Delete a comment',
                        'parameters' => [
                            [
                                'name' => 'task',
                                'in' => 'path',
                                'required' => true,
                                'description' => 'Task ID',
                                'schema' => ['type' => 'integer', 'example' => 1]
                            ],
                            [
                                'name' => 'comment',
                                'in' => 'path',
                                'required' => true,
                                'description' => 'Comment ID',
                                'schema' => ['type' => 'integer', 'example' => 1]
                            ]
                        ],
                        'responses' => [
                            '200' => [
                                'description' => 'Comment deleted successfully',
                                'content' => [
                                    'application/json' => [
                                        'schema' => [
                                            'type' => 'object',
                                            'properties' => [
                                                'message' => ['type' => 'string', 'example' => 'Comment deleted successfully']
                                            ]
                                        ]
                                    ]
                                ]
                            ],
                            '401' => ['description' => 'Unauthenticated'],
                            '403' => ['description' => 'Unauthorized - Only comment owner can delete'],
                            '404' => ['description' => 'Comment not found']
                        ]
                    ]
                ]
            ],
            'components' => [
                'securitySchemes' => [
                    'bearerAuth' => [
                        'type' => 'http',
                        'scheme' => 'bearer',
                        'bearerFormat' => 'JWT'
                    ]
                ]
            ],
            'tags' => [
                [
                    'name' => 'Authentication',
                    'description' => 'User authentication endpoints'
                ],
                [
                    'name' => 'Tasks',
                    'description' => 'Task management endpoints'
                ],
                [
                    'name' => 'Comments',
                    'description' => 'Comment management endpoints'
                ]
            ]
        ];

        return response()->json($documentation);
    }
}
