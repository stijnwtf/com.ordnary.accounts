# Numblio Single Sign-On (SSO)

## Overzicht
Numblio maakt gebruik van een Single Sign-On (SSO) proces om gebruikers eenvoudig en veilig te authenticeren via een centraal systeem. Dit document beschrijft de werking van het SSO-proces bij Numblio en hoe applicaties zoals Leerlokaal.com hiermee omgaan.

## SSO Werking

### 1. Navigeren naar de inlogpagina
De gebruiker bezoekt de website van een Numblio-product, zoals [Leerlokaal.com](https://leerlokaal.com), en klikt op de inlogknop.

### 2. Redirect naar Numblio Auth Service
Na het klikken op de inlogknop wordt de gebruiker doorgestuurd naar de Numblio Auth Service ([auth.numblio.com](https://auth.numblio.com)). De URL bevat specifieke queryparameters, zoals:

**Voorbeeld URL:**
```
https://auth.numblio.com/login?client_id=blabla&redirect_uri=https://leerlokaal.com/auth
```

### 3. Inloggen via de Numblio Auth Service
De gebruiker vult zijn inloggegevens in op de inlogpagina van Numblio Auth Service en authenticeert zich succesvol.

### 4. Redirect naar de applicatie na succesvolle login
Na een succesvolle login wordt de gebruiker doorgestuurd naar de opgegeven `redirect_uri`, inclusief een token.

**Voorbeeld URL:**
```
https://leerlokaal.com/auth?token=shhsjsjsj
```

### 5. Valideren van het token
Leerlokaal.com ontvangt het token en valideert het door een server-side API-aanroep naar de Numblio Auth Service.

### 6. Ingelogd op Leerlokaal.com
Indien de token geldig is:
- De gebruiker wordt ingelogd en krijgt toegang tot beveiligde inhoud.
- De gebruiker hoeft niet opnieuw in te loggen zolang de sessie geldig blijft.

## Contributie
Wil je bijdragen aan dit project? Voel je vrij om een issue aan te maken of een pull request in te dienen.

## Licentie
Dit project valt onder de [MIT Licentie](LICENSE).
