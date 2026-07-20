# 🏗️ Architecture Decision Record: Wuwei Ecosystem

**Status:** Accepted
**Date:** July 2026
**Context:** Wuwei is a monolithic Symfony application (Current Stack: PHP, Doctrine ORM, MySQL, Twig) containing years of historical personal and business data. The objective is to evolve this Source of Truth (SoT) into a multi-face ecosystem supporting distinct products (Business O.S., Life O.S., Public Services) without halting current operations or executing a high-risk immediate database migration.

## Architectural Decisions

### 1. The Strangler Fig Pattern via API Integration
We will not rewrite Wuwei from scratch. We will gradually build out new domains as decoupled services or isolated API boundaries. Legacy Twig views will remain operational for internal admin use, while new user-facing features will be routed to modern external interfaces.

### 2. The Modular Monolith Pattern
We are transitioning the traditional MVC directory structure into Domain-Driven Design (DDD) Bounded Contexts within this repository (`src/Domain/Core`, `src/Domain/BusinessOS`, `src/Domain/LifeOS`).
* **Rules:** Cross-domain communication must occur via internal interfaces or the Symfony Event Dispatcher, never by direct Doctrine joins across domain boundaries.

### 3. Multi-Tenancy via Row-Level Isolation
We will implement multi-tenancy at the application level to prepare for future SaaS capabilities.
* **Implementation:** A `tenant_id` (UUID) will be attached to all root aggregate entities. 
* **Security:** We will utilize Doctrine SQL Filters to globally scope all queries by the authenticated user's `tenant_id`, guaranteeing data isolation using the existing shared database.

### 4. Headless Core Exposure (External Frontends)
Wuwei acts as a headless data engine for external "Faces" (e.g., React/TypeScript SPAs like WhatShouldIDoNow). We will expose specific, domain-driven REST/JSON API endpoints (e.g., `/api/v1/temporal-blocks`) to serve data securely across origins.

### 5. Internal Presentation Layer Modernization (Symfony UX)
The current internal frontend relies on standard, full-page-reload Twig templates. We will modernize this exclusively using **Symfony UX (Turbo & Stimulus)**.
* **Implementation:** We will wrap existing Twig forms and lists in `<turbo-frame>` tags and manage lightweight state with Stimulus.
* **Why:** This delivers a reactive, Single-Page Application (SPA) feel for our internal operations (Business O.S.) via HTML-over-the-wire, allowing us to build incredibly fast interfaces without the overhead of maintaining a separate JavaScript API client for internal tools.

### 6. Dependency Pruning: EasyAdmin Removal
EasyAdmin was previously integrated but never fully utilized, resulting in unused abstraction layers. We will completely remove the `EasyCorp/EasyAdminBundle` dependency.
* **Implementation:** We will construct our own custom, domain-specific CRUD controllers and Twig/Turbo views.
* **Why:** Simplicity and architectural control. "Magic" admin bundles create severe friction when implementing strict Domain-Driven Design and complex multi-tenancy rules. Controlling our own presentation logic ensures the UI strictly obeys our exact domain boundaries.