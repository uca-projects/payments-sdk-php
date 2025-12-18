# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.0.1] - 2025-12-18

### Features

- **Authentication & Security**:

  - Implemented full authentication logic against the Payment API.
  - Added automatic **token refresh handling**: if a request fails with `401 Unauthorized`, the system attempts to fetch a new token and retry the operation automatically.
  - Added configuration validation in the constructor to ensure `client_key`, `client_secret`, and `payment-gateway-url` are present before making requests.

- **Architecture Refactoring**:
  - Centralized all API endpoints into a class constant (`ENDPOINTS`) for easier maintenance and to avoid scattered hardcoded URLs.
  - Standardized `doGet` and `doPost` methods to handle headers, JSON parsing, and error handling consistently.

### Chore

- **Configuration**: Updated `uca-payments-sdk.php` configuration keys to include `client_key`, `client_secret`, and `token_ttl`.
- **Gitignore**: Expanded `.gitignore` to keep the repository clean from temporary and environment files.
