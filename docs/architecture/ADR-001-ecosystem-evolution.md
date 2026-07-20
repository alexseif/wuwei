Status: Accepted
Date: July 2026
Context: The current system (Wuwei) is a monolithic Symfony application containing years of historical data. The objective is to evolve this Source of Truth (SoT) into a multi-face ecosystem supporting distinct products (Business O.S., Life O.S., Public Services like WhatShouldIDoNow). We must achieve this without halting current operations or executing a high-risk immediate database migration.

Architectural Decisions
1. The Strangler Fig Pattern via API Integration
We will not rewrite Wuwei from scratch. Instead, we will gradually build out new domains as decoupled services or isolated API boundaries. Legacy Twig views will remain operational for internal admin use, while new user-facing features will be routed to modern interfaces.

2. Decoupled Presentation Layer (React + TypeScript)
All new external "Faces" (e.g., WhatShouldIDoNow, future Client Portals) will be built as independent Single Page Applications (SPAs) using React and TypeScript.

Why: Enforces strict separation of concerns, provides a superior, reactive user experience, and allows different frontends to scale or be discarded independently of the backend SoT.

3. Headless Core Exposure
The Wuwei backend will act as a headless data engine for the new frontends. We will expose specific, domain-driven REST/JSON API endpoints (e.g., /api/v1/temporal-alignment) to serve data to the React applications.

Consequences
Positive: We can rapidly prototype modern tools (like WhatShouldIDoNow) without untangling the entire legacy UI.

Trade-offs: We must manage Cross-Origin Resource Sharing (CORS) and eventually implement token-based authentication (JWT or Sanctum-style) for secure API communication between the React apps and the Symfony backend.