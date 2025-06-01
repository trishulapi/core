<?php


$openapi = [
    'openapi' => '3.0.0',
    'info' => [
        'title' => 'My Full CRUD API',
        'version' => '1.0.0',
        'description' => 'Demonstrates GET, POST, PUT, DELETE in OpenAPI using raw PHP',
    ],
    'paths' => [
        '/items' => [
            'get' => [
                'summary' => 'List all items',
                'responses' => [
                    '200' => [
                        'description' => 'List of items',
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    'type' => 'array',
                                    'items' => ['type' => 'object']
                                ]
                            ]
                        ]
                    ]
                ]
            ],
            'post' => [
                'summary' => 'Create a new item',
                'requestBody' => [
                    'required' => true,
                    'content' => [
                        'application/json' => [
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'name' => ['type' => 'string'],
                                    'price' => ['type' => 'number']
                                ],
                                'required' => ['name', 'price']
                            ]
                        ]
                    ]
                ],
                'responses' => [
                    '201' => ['description' => 'Item created']
                ]
            ]
        ],
        '/items/{id}' => [
            'parameters' => [
                [
                    'name' => 'id',
                    'in' => 'path',
                    'required' => true,
                    'schema' => ['type' => 'integer']
                ]
            ],
            'get' => [
                'summary' => 'Get item by ID',
                'responses' => [
                    '200' => ['description' => 'Item found'],
                    '404' => ['description' => 'Item not found']
                ]
            ],
            'put' => [
                'summary' => 'Update item by ID',
                'requestBody' => [
                    'required' => true,
                    'content' => [
                        'application/json' => [
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'name' => ['type' => 'string'],
                                    'price' => ['type' => 'number']
                                ]
                            ]
                        ]
                    ]
                ],
                'responses' => [
                    '200' => ['description' => 'Item updated'],
                    '404' => ['description' => 'Item not found']
                ]
            ],
            'delete' => [
                'summary' => 'Delete item by ID',
                'responses' => [
                    '204' => ['description' => 'Item deleted'],
                    '404' => ['description' => 'Item not found']
                ]
            ]
        ]
    ]
];

// Output JSON
file_put_contents(__DIR__ . '/docs/openapi.json', json_encode($openapi, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

echo "✅ openapi.json generated successfully.\n";
