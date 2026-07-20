# Wu Wei Roadmap

## Feature Roadmaps
- [Login/Sign up Feature](docs/login_signup_roadmap.md)
- [Objective Management Feature](docs/objective_management_roadmap.md)
- [AI Integration Feature](docs/ai_integration_roadmap.md)
- [Notifications Feature](docs/notifications_roadmap.md)
- [Task Management Feature](docs/task_management_roadmap.md)

## Phase 0: Continuous Integration (CI) Setup
- [ ] Configure CI tools (e.g., Jenkins, Travis CI)
- [ ] Define build and deployment pipelines
- [ ] Automate code quality checks (e.g., linting, static analysis)
- [ ] Enable automatic testing and reporting
- [ ] Implement deployment to development/staging environment

## Phase 1: Foundation
- [x] Set up Symfony project structure and dependencies
- [x] Implement user authentication and authorization
- [x] Design and create database schema
- [x] Create basic CRUD functionality for objectives

## Phase 2: Habit Building
- [ ] Implement habit tracking functionality
- [ ] Allow users to create and manage habits
- [ ] Send reminders and notifications for habit completion
- [ ] Provide visualizations for habit progress

## Phase 3: Cultural Methodologies Integration
- [ ] Integrate Chinese Clock methodology
- [ ] Integrate Ayurvedic Dosha Clock methodology
- [ ] Integrate Egyptian Temporal Beliefs methodology

## Phase 4: Notifications and Enhancements
- [ ] Implement browser and email notifications
- [ ] Enhance user experience and UI/UX design
- [ ] Refine performance and security measures
- [ ] Conduct thorough testing and bug fixes

## Phase 5: Documentation and Deployment
- [ ] Create comprehensive documentation for installation and usage
- [ ] Prepare the project for deployment to a production environment
- [ ] Deploy the app to a live server
- [ ] Continuously update and maintain documentation

## Phase 6: Ecosystem Decoupling & Headless API (Strangler Fig)
*Implementing isolated API boundaries to serve decoupled React/TypeScript "Faces".*

- [x] **Task 1: Core Temporal Retrieval API** - [x] Fix midnight wrap-around logic in `TimeSystemRepository`.
  - [x] Expose `GET /api/v1/temporal-blocks` endpoint accepting dynamic timezone queries.
  - [x] Serialize output to clean JSON for decoupled frontend consumption.
- [ ] **Task 2: Contextual Enrichment (Database Architecture Pending)**
  - [ ] *Requires Architectural Decision:* Determine whether to expand the existing EAV NAV system (mapping 'Pitta' to 'High Focus') or build a new holistic bridging table.
  - [ ] Design the schema to map ancient terminology to actionable modern work categories.
  - [ ] Update API payloads to include secondary contextual strings based on the new schema.
- [ ] **Task 3: Temporal Ecosystem Expansion**
  - [ ] Design seeder/admin interface to ingest macro-rhythms (Days, Months) from markdown files.
  - [ ] Expand the API endpoint to dynamically loop through and return *all* active `TimeSystem` overlapping blocks.

## Acknowledgements
- [Symfony](https://symfony.com) - The PHP framework used for development.
- [Chinese Zodiac](https://en.wikipedia.org/wiki/Chinese_zodiac) - The basis for the Chinese clock methodology.
- [Ayurveda](https://en.wikipedia.org/wiki/Ayurveda) - The traditional Indian system of medicine, including the dosha clock concept.
- [Ancient Egyptian Culture](https://en.wikipedia.org/wiki/Ancient_Egypt) - The inspiration for incorporating Egyptian temporal beliefs into the app.
