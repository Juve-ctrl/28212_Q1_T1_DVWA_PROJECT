# API Security — Remediation and Retest Report

**Student ID:** 28212  
**Application:** Hardened Damn Vulnerable Web Application (DVWA)  
**Environment:** Local Docker-based hardened environment  
**Application URL:** http://127.0.0.1:4280/  
**API documentation:** /vulnerabilities/api/openapi.yml  
**Date:** 11 November 2026

## 1. Scope

This report documents the API security changes made to the hardened DVWA environment and the verification results obtained.

The vulnerable baseline was preserved separately. Changes were applied to the hardened workspace.

## 2. Implemented protections

### 2.1 Sensitive user information
- Updated the user API serialization so that the returned user representation contains only the intended fields: ID, name and level.
- Password hashes are not included in the API user representation.

### 2.2 Mass assignment
- Restricted user creation and update input to permitted fields.
- The update validator accepts a name field and rejects unexpected fields such as level.
- New users receive a server-controlled default level rather than a level supplied by the request.

### 2.3 Command injection
- Validated the connectivity-check target before execution.
- Replaced shell-interpreted command execution with an argument-array invocation through proc_open.
- Enabled bypass_shell to avoid passing user input through a shell command string.
- Invalid target input is rejected.

### 2.4 API token secrets
- Replaced hardcoded access-token and refresh-token signing secrets in Login.php with environment-based configuration.
- Added validation requiring each secret to contain 64 hexadecimal characters.
- Configured the hardened Compose environment to require both secrets.
- Stored the local hardened environment file with restrictive file permissions.

### 2.5 Bearer-token handling
- Updated the order controller to obtain the Bearer token from the HTTP Authorization header and validate it before processing the protected order request.
- Configured Apache to pass the Authorization header through to the application.

## 3. Retest evidence

| Test | Observed result | Assessment |
|---|---|---|
| User API response | User representation returned without password-hash fields | Passed |
| Mass-assignment attempt | HTTP 422 — Invalid input when an update included an unauthorized level field | Passed |
| Command-injection attempt | HTTP 400 — Invalid target for `127.0.0.1; id` | Passed |
| OAuth login | HTTP 200; access_token, refresh_token, token_type and expires_in returned | Passed |
| Protected order endpoint | HTTP 200 when called with a valid Bearer access token | Passed |

## 4. Source syntax verification

PHP syntax checks passed in the rebuilt hardened container for:

- User.php
- UserController.php
- HealthController.php
- OrderController.php
- Login.php

The hardened application was rebuilt and recreated after the changes.

## 5. Evidence screenshots

- SS_H35_API_Security_Password_Hashes_Not_Exposed.png
- SS_H36_API_Security_Mass_Assignment_Rejected.png
- SS_H37_API_Security_Command_Injection_Rejected.png
- SS_H38_API_Security_OAuth_Bearer_Token_Accepted.png

Screenshots must not expose access tokens, refresh tokens or secret values.

## 6. Limitations and remaining considerations

- The successful tests demonstrate the specific API behaviours tested; they do not prove that every API route is free of vulnerabilities.
- The demonstration OAuth client credentials used by the existing DVWA login controller are separate from the token-signing secrets moved to environment configuration. They have not been independently replaced in this remediation.
- The default password behaviour for API-created demonstration users should be reviewed before any use outside this isolated lab.
- DVWA is intentionally vulnerable software. The hardened copy is for coursework demonstration and must not be treated as a production application.
- Keep the environment isolated and do not expose it to the public internet.

## 7. Conclusion

The documented API security changes were deployed to the hardened DVWA environment. The five PHP files passed syntax checks, and the recorded retests confirmed rejection of the tested mass-assignment and command-injection payloads, omission of password hashes from the user representation, successful OAuth login, and successful Bearer-token access to the order endpoint.

These results apply to the tested endpoints and payloads only.
