<?php
return [
    'tenant_model' => App\Models\Tenant::class,
    'id_generator' => Stancl\Tenancy\UUIDGenerator::class,
    'tenant_artisan_search_fields' => ['id', 'name', 'email'],
    'identification_middleware' => [
        Stancl\Tenancy\Middleware\InitializeTenancyBySubdomain::class,
        // Add more middleware as needed
    ],
    // ...other config options as needed
];
