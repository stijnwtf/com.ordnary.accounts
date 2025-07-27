# Test Script voor Developer Portal API

Dit is een eenvoudig test script om de Developer Portal API te testen.

## Stap 1: API Key instellen

Voeg een API key toe aan je `.env` bestand:

```env
DEVELOPER_PORTAL_API_KEY=test-api-key-123456789
```

## Stap 2: Test requests

### Test Client Aanmaken

```bash
curl -X POST https://accounts.numblio.com/api/v1/developer-portal/clients \
  -H "Content-Type: application/json" \
  -H "X-API-Key: dG9v-V5p8KX3!bX#ZyL2t3@Q9W!rMf6Jv^pC7xE$" \
  -d '{
    "name": "Test Applicatie",
    "description": "Een test applicatie",
    "developer_email": "developer@test.com",
    "developer_name": "Test Developer",
    "redirect_uris": [
      "https://testapp.com/auth/callback"
    ],
    "webhook_url": "https://testapp.com/webhooks/sso",
    "logout_url": "https://testapp.com/auth/logout"
  }'
```

### Test Client Data Ophalen

```bash
# Vervang CLIENT_ID met de client_id uit de vorige response
curl -X GET https://accounts.numblio.com/api/v1/developer-portal/clients/CLIENT_ID \
  -H "X-API-Key: dG9v-V5p8KX3!bX#ZyL2t3@Q9W!rMf6Jv^pC7xE$"
```

### Test Alle Clients Ophalen

```bash
curl -X GET http://localhost:8000/api/v1/developer-portal/clients \
  -H "X-API-Key: test-api-key-123456789"
```

### Test Client Bijwerken

```bash
# Vervang CLIENT_ID met een echte client_id
curl -X PUT http://localhost:8000/api/v1/developer-portal/clients/CLIENT_ID \
  -H "Content-Type: application/json" \
  -H "X-API-Key: test-api-key-123456789" \
  -d '{
    "name": "Bijgewerkte Test Applicatie",
    "redirect_uris": [
      "https://testapp.com/auth/callback",
      "https://testapp.com/auth/callback-new"
    ]
  }'
```

### Test Ongeldige API Key

```bash
curl -X GET http://localhost:8000/api/v1/developer-portal/clients \
  -H "X-API-Key: verkeerde-key"
```

Dit zou een 401 error moeten geven.

## Verwachte Responses

### Succesvolle Client Aanmaak (201)

```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "Test Applicatie",
    "client_id": "client_abc123...",
    "client_secret": "secret_xyz789...",
    "redirect_uris": ["https://testapp.com/auth/callback"],
    "webhook_url": "https://testapp.com/webhooks/sso",
    "logout_url": "https://testapp.com/auth/logout",
    "created_at": "2025-07-27T12:00:00Z"
  },
  "message": "Client created successfully"
}
```

### Unauthorized (401)

```json
{
  "success": false,
  "message": "Unauthorized. Invalid API key."
}
```

### Validation Error (422)

```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "name": ["The name field is required."]
  }
}
```
