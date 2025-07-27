# Developer Portal API

Deze API is specifiek voor developers.numblio.com om clients aan te maken en beheren in de SSO.

## Authenticatie

Alle endpoints vereisen een API key in de header:

```
X-API-Key: jouw-api-key-hier
```

Of als Bearer token:

```
Authorization: Bearer jouw-api-key-hier
```

## Base URL

```
https://jouw-sso-domain.com/api/v1/developer-portal
```

## Endpoints

### 1. Client Aanmaken

**POST** `/clients`

Maakt een nieuwe client aan na goedkeuring in developers.numblio.com.

**Request Body:**
```json
{
  "name": "Mijn Applicatie",
  "description": "Beschrijving van de applicatie",
  "developer_email": "developer@example.com",
  "developer_name": "Jan Ontwikkelaar",
  "redirect_uris": [
    "https://mijnapp.com/auth/callback",
    "https://mijnapp.com/auth/dev-callback"
  ],
  "webhook_url": "https://mijnapp.com/webhooks/sso",
  "logout_url": "https://mijnapp.com/auth/logout"
}
```

**Verplichte velden:**
- `name` - Unieke naam voor de applicatie
- `developer_email` - Email van de developer
- `redirect_uris` - Array met minimaal 1 redirect URI

**Response:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "Mijn Applicatie",
    "client_id": "client_abc123...",
    "client_secret": "secret_xyz789...",
    "redirect_uris": [
      "https://mijnapp.com/auth/callback",
      "https://mijnapp.com/auth/dev-callback"
    ],
    "webhook_url": "https://mijnapp.com/webhooks/sso",
    "logout_url": "https://mijnapp.com/auth/logout",
    "created_at": "2025-07-27T12:00:00Z"
  },
  "message": "Client created successfully"
}
```

### 2. Client Data Ophalen

**GET** `/clients/{client_id}`

Haalt gedetailleerde informatie op van een specifieke client.

**Response:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "Mijn Applicatie",
    "client_id": "client_abc123...",
    "redirect_uris": [
      "https://mijnapp.com/auth/callback"
    ],
    "webhook_url": "https://mijnapp.com/webhooks/sso",
    "logout_url": "https://mijnapp.com/auth/logout",
    "created_at": "2025-07-27T12:00:00Z",
    "updated_at": "2025-07-27T12:00:00Z",
    "statistics": {
      "total_sessions": 150,
      "active_sessions": 12,
      "unique_users": 45,
      "recent_sessions_30_days": 89,
      "last_used": "2025-07-27T11:45:00Z"
    }
  },
  "message": "Client data retrieved successfully"
}
```

### 3. Meerdere Clients Ophalen

**GET** `/clients`

Haalt data op van meerdere clients.

**Query Parameters:**
- `client_ids[]` (optioneel) - Array van client IDs om te filteren

**Voorbeelden:**
```
GET /clients
GET /clients?client_ids[]=client_123&client_ids[]=client_456
```

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "Mijn Applicatie",
      "client_id": "client_abc123...",
      "redirect_uris": ["https://mijnapp.com/auth/callback"],
      "webhook_url": "https://mijnapp.com/webhooks/sso",
      "logout_url": "https://mijnapp.com/auth/logout",
      "created_at": "2025-07-27T12:00:00Z",
      "updated_at": "2025-07-27T12:00:00Z",
      "statistics": {
        "total_sessions": 150,
        "active_sessions": 12,
        "unique_users": 45
      }
    }
  ],
  "message": "Clients data retrieved successfully"
}
```

### 4. Client Bijwerken

**PUT** `/clients/{client_id}`

Werkt een bestaande client bij.

**Request Body (alle velden optioneel):**
```json
{
  "name": "Nieuwe Naam",
  "redirect_uris": [
    "https://mijnapp.com/auth/new-callback"
  ],
  "webhook_url": "https://mijnapp.com/webhooks/new-sso",
  "logout_url": "https://mijnapp.com/auth/new-logout"
}
```

**Response:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "Nieuwe Naam",
    "client_id": "client_abc123...",
    "redirect_uris": [
      "https://mijnapp.com/auth/new-callback"
    ],
    "webhook_url": "https://mijnapp.com/webhooks/new-sso",
    "logout_url": "https://mijnapp.com/auth/new-logout",
    "updated_at": "2025-07-27T13:00:00Z"
  },
  "message": "Client updated successfully"
}
```

## Error Responses

Alle endpoints kunnen de volgende error responses geven:

**401 Unauthorized:**
```json
{
  "success": false,
  "message": "Unauthorized. Invalid API key."
}
```

**404 Not Found:**
```json
{
  "success": false,
  "message": "Client not found"
}
```

**422 Validation Error:**
```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "name": ["The name field is required."],
    "redirect_uris": ["At least one redirect URI is required."]
  }
}
```

**500 Server Error:**
```json
{
  "success": false,
  "message": "Failed to create client",
  "error": "Internal server error"
}
```

## Configuratie

Voeg de volgende environment variabele toe aan je `.env` bestand:

```env
DEVELOPER_PORTAL_API_KEY=jouw-geheime-api-key-hier
```

Genereer een sterke, willekeurige API key voor productie gebruik.

## Voorbeeld PHP Code voor developers.numblio.com

```php
<?php

class SSOClient {
    private $apiKey;
    private $baseUrl;
    
    public function __construct($apiKey, $baseUrl) {
        $this->apiKey = $apiKey;
        $this->baseUrl = rtrim($baseUrl, '/');
    }
    
    public function createClient($data) {
        return $this->makeRequest('POST', '/clients', $data);
    }
    
    public function getClient($clientId) {
        return $this->makeRequest('GET', "/clients/{$clientId}");
    }
    
    public function updateClient($clientId, $data) {
        return $this->makeRequest('PUT', "/clients/{$clientId}", $data);
    }
    
    private function makeRequest($method, $endpoint, $data = null) {
        $url = $this->baseUrl . '/api/v1/developer-portal' . $endpoint;
        
        $options = [
            'http' => [
                'method' => $method,
                'header' => [
                    'Content-Type: application/json',
                    'X-API-Key: ' . $this->apiKey
                ]
            ]
        ];
        
        if ($data) {
            $options['http']['content'] = json_encode($data);
        }
        
        $context = stream_context_create($options);
        $response = file_get_contents($url, false, $context);
        
        return json_decode($response, true);
    }
}

// Gebruik
$sso = new SSOClient('jouw-api-key', 'https://sso.numblio.com');

// Client aanmaken
$newClient = $sso->createClient([
    'name' => 'Mijn App',
    'developer_email' => 'dev@example.com',
    'redirect_uris' => ['https://app.com/callback']
]);

// Client data ophalen
$clientData = $sso->getClient('client_abc123');
```
