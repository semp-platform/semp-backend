# SEMP — Electoral Management & Nomination Platform

SEMP is a Laravel-based electoral management platform designed to support
candidate nomination, electoral administration, departmental review workflows,
candidate documentation, party primary monitoring, payments, and public
electoral information.

The project is currently under active development and program testing.

## Overview

SEMP provides a structured workflow for managing electoral processes from
candidate nomination and document submission through departmental review and
eventual public-facing electoral information.

The application separates responsibilities across different user roles and
departments, with workflow history and controlled transitions between stages.

## Key Capabilities

### Candidate & Nomination Management

- Candidate registration and candidate information management
- Political party nomination workflows
- Nomination batches
- Candidate withdrawals and replacements
- Supporting evidence and review information
- Electoral location/reference data

### Candidate Documents

- Candidate document uploads
- Configurable document types
- Document review workflows
- Document verification/review requests
- Controlled document access

### Departmental Workflow

The application supports departmental processing involving areas such as:

- ICT
- Electoral/Political Monitoring (EPM)
- Legal
- Finance
- Commissioner-level review

Nomination workflow history is maintained to provide an audit trail of
departmental processing.

### Party Primary Monitoring

SEMP includes functionality for:

- Party primary notices
- Primary event management
- Monitor assignments
- Primary event monitoring reports
- Monitoring report attachments
- Commissioner review of monitoring reports

### Elections & Results

The system includes functionality for:

- Elections
- Election types
- Positions
- Election locations
- Result entry and imports
- Election results
- Public result views
- Result analysis

### Finance

The application includes:

- Nomination batch payments
- Payment records
- Receipt generation
- Financial reports

### Public Information

SEMP includes a public-facing area for:

- Elections
- Candidates
- Election results
- Notices
- Announcements
- Publications and other public content

## Technology Stack

- PHP 8.3+
- Laravel 13
- Laravel Sanctum
- Laravel Blade
- Vite
- Spatie Laravel Permission
- PHPUnit
- DomPDF
- PhpSpreadsheet
- PHPWord

## Architecture

The application follows Laravel's application structure with dedicated
controllers, models, services, form requests, API resources, policies,
notifications, migrations, seeders, and feature tests.

Business logic is separated into service classes where appropriate, including
services for:

- Candidates
- Elections
- Nominations
- Candidate documents
- Candidate withdrawals/replacements
- Payments
- Political parties
- Reference/location data
- Workflow processing

The application also exposes API endpoints alongside its web-based
departmental interfaces.

## Workflow & Auditability

A central design goal of SEMP is to make departmental processing explicit and
traceable.

Nomination workflow history records movement through departmental stages,
while role and permission controls determine which users can perform specific
operations.

This provides a foundation for controlled electoral administration rather
than treating the application as a simple CRUD system.

## Testing Status

The project is currently undergoing program testing.

Automated tests are maintained under the `tests/` directory, including feature
tests covering areas such as primary monitoring and assignment workflows.

Additional testing and production-readiness work is ongoing.

## Local Development

### Requirements

- PHP 8.3+
- Composer
- Node.js and npm
- A supported database configured through Laravel's environment settings

### Installation

Clone the repository and install the dependencies:

```bash
composer install
npm install
