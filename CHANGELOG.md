# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).
## [2.0.0] - 2026-04-28

### Features

- **Validation**: Added `CUIL` and `OTRO` as valid payer document types in `PayerRules`.
- **Search**: Added `gateway_transaction_id` to payment search data and updated local search endpoints.
- **Services**: Implemented a fluent builder pattern for payment services and improved dedicated service layers with custom Carbon date casting.
- **API**: Improved API error response handling and added `throw()` method to auth token requests for better failure handling.
- **Data Objects**: Added `toModel` methods for recursive DTO-to-Eloquent transformation and introduced `DataPaginatorTrait` and `DataMergerTrait`.
- **UI/UX**: Added pagination view support with a new Blade component.

### Fixed

- **Authentication**: Ensured auth token requests throw exceptions on failure.
- **Formatting**: Removed redundant whitespace and standardized code style across the SDK.

### Refactoring

- **Architecture**: Major restructuring of the SDK architecture, including a new API client, service layers, and standardized data transfer objects.
- **Namespacing**: Organized payment clients, requests, and services into `local` and `remote` namespaces for better modularity.
- **Endpoints**: Standardized API endpoints to include gateway IDs and improved response handling across all services.
- **Data Transformation**: Implemented a generic `DataModelTrait` for recursive mapping and simplified DTOs by removing database-specific fields.
- **Naming Conventions**: Updated internal classes and properties (e.g., `PaginationFromModel`) to follow camelCase conventions.

### Chore

- **Dependencies**: Updated `darkaonline/l5-swagger` and `spatie/laravel-data` to their latest compatible versions.
- **Configuration**: Updated configuration keys and improved SDK initialization logic.

## [1.0.5] - 2026-03-31

### Features

- **Payment Data**: Added `PaymentDetailData` to `PaymentData`.
- **Validation**: Allowed `'PAS'` as a valid payer document type in `PayerRules`.

## [1.0.4] - 2026-03-09

### Features

- **Services**: Created `ApiLocalPaymentService` and `ApiRemotePaymentService` to separate local and remote payment processing.
- **Search**: Added `page` and `per_page` properties to `PaymentSearchData` to support pagination.
- **Payment Data**: Made `id`, `client_id`, `preference_id`, and `payment_gateway_id` properties nullable in `PaymentData`.

### Refactoring

- **API Base Client**: Refactored `ApiPaymentService` to act as a base class, extracting local and remote methods to their respective subclasses. Changed HTTP methods to `protected` and added `makeUrlFromBody` utility.
- **Payment Builder**: Updated `ApiPaymentBuilder` to use `ApiLocalPaymentService` instead of `ApiPaymentService`.
- **Payment Model**: Standardize payment model attribute keys, generalize payment lookup methods, and add gateway transaction ID to payment search data.
- **Payment Data**: Changed properties format from `snake_case` to `camelCase` (`paymentCard`, `paymentIntention`, `paymentGateway`) and implemented a dynamic `parseDate` parser for `created_at` and `updated_at` to accept mixed formats.

## [1.0.3] - 2026-02-23

### Features

- **Payment Sync**: Added `sync` method to `ApiPaymentService` to allow synchronizing payment status using a unique field and value.
- **API Client**: Implemented `doPut` helper method in `ApiPaymentService` to support PUT requests.
- **Endpoints**: Added `payment/{uniqueField}/{value}/sync` endpoint definition.

## [1.0.2] - 2026-02-20

### Fixed

- **Payment Processing**: removed `strtolower` normalization for `external_reference` in `PaymentData` constructor to ensure case-sensitive references are preserved.

## [1.0.1] - 2025-12-18

### Features

- **Authentication & Security**:
  - Implemented full authentication logic against the Payment API.
  - Added automatic **token refresh handling**: if a request fails with `401 Unauthorized`, the system attempts to fetch a new token and retry the operation automatically.
  - Added configuration validation in the constructor to ensure `client_key`, `client_secret`, and `payment-gateway-url` are present before making requests.

- **Middleware**:
  - Added `CheckWebAccessPermission` to validate web access using the application key.
  - Added `RestrictToLocal` to restrict access to local environments.

- **Architecture Refactoring**:
  - Centralized all API endpoints into a class constant (`ENDPOINTS`) for easier maintenance and to avoid scattered hardcoded URLs.
  - Standardized `doGet` and `doPost` methods to handle headers, JSON parsing, and error handling consistently.

### Chore

- **Configuration**: Updated `uca-payments-sdk.php` configuration keys to include `client_key`, `client_secret`, and `token_ttl`.
- **Gitignore**: Expanded `.gitignore` to keep the repository clean from temporary and environment files.
