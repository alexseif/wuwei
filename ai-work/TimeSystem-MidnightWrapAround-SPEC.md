# Specification: TimeSystem Refactoring & API Endpoint

## 1. Objective
Refactor the `TimeSystemRepository` to resolve the midnight wrap-around issue (handling standard vs. overnight blocks) with eager loading. Implement a read-only endpoint `/api/v1/temporal-blocks` that accepts an optional `?time=` filter and returns flattened JSON blocks.

### Core Requirements
1. **Repository Refactoring**:
   - Implement `getActiveForTime(?string $timeString = null)` in `TimeSystemRepository`.
   - Correctly query both:
     - Standard blocks: `from_time <= to_time` where `$timeString` is between `from_time` and `to_time`.
     - Overnight blocks: `from_time > to_time` where `$timeString >= from_time` OR `$timeString <= to_time`.
   - Defaults to the current time `date('H:i:s')` if `$timeString` is null.
   - Eager load associated `tags` and `tagType` relations using a single query (using QueryBuilder `leftJoin` and `addSelect`).
   - Update `getCurrent()` in `TimeSystemRepository` to call `getActiveForTime()`.

2. **API Endpoint**:
   - Route: `GET /api/v1/temporal-blocks`.
   - Accept optional parameter `?time=`.
   - Validate `?time=` format (must match `H:i:s` or `H:i`). If invalid, return a `400 Bad Request`.
   - If no time parameter is passed, default to current time `date('H:i:s')`.
   - Group tags by their tag type name dynamically (e.g. `{"Organ": ["Gallbladder"], "Element": ["Wood"]}`).
   - Return HTTP `200 OK` with JSON array of blocks.

---

## 2. Project Commands
- **Run PHPUnit test suite**:
  ```bash
  vendor/bin/phpunit
  ```
- **Validate Doctrine schema**:
  ```bash
  php bin/console --env=test doctrine:schema:validate
  ```

---

## 3. Project Structure
The following files will be created or modified:

```
/var/www/wuwei/
├── src/
│   ├── Repository/
│   │   └── TimeSystemRepository.php (modified)
│   └── Controller/
│       └── Api/
│           └── TimeSystemController.php (created)
└── tests/
    ├── Repository/
    │   └── TimeSystemRepositoryTest.php (created)
    └── Controller/
        └── Api/
            └── TimeSystemControllerTest.php (created)
```

---

## 4. Code Style & Design Patterns
- **Language Level**: PHP 8.2 with strict types where appropriate.
- **ORM & QueryBuilder**:
  - Keep logic within `TimeSystemRepository` and use QueryBuilder.
  - Check that times are not null when executing matching query.
- **Manual Serialization**:
  - Keep serialization logic simple and self-contained in the controller using a map function or manual mapping loop to convert `TimeSystem` entities into the requested JSON shape. This avoids overhead and extra dependencies.

---

## 5. Testing Strategy
We will implement two test suites:

### A. Repository Integration Tests (`TimeSystemRepositoryTest`)
- Extend `Symfony\Bundle\FrameworkBundle\Test\KernelTestCase`.
- In `setUp()`, boot the kernel and grab the `EntityManager` and `TimeSystemRepository`.
- Persist test database records:
  - Standard block: `09:00:00` to `11:00:00` (e.g. Spleen block).
  - Overnight block: `23:00:00` to `01:00:00` (e.g. Gallbladder block).
  - Associated tags and tag types to verify eager loading.
- Test scenarios for `getActiveForTime()`:
  - Query time `10:00:00` -> should return the standard block.
  - Query time `23:30:00` -> should return the overnight block.
  - Query time `00:30:00` -> should return the overnight block.
  - Query time `12:00:00` -> should return empty array.
- Clean up all persisted test data in `tearDown()`.

### B. Controller Functional Tests (`TimeSystemControllerTest`)
- Extend `Symfony\Bundle\FrameworkBundle\Test\WebTestCase`.
- Perform requests:
  - `GET /api/v1/temporal-blocks` -> verify success response and JSON structure.
  - `GET /api/v1/temporal-blocks?time=10:00` -> verify success response.
  - `GET /api/v1/temporal-blocks?time=23:30:00` -> verify success response.
  - `GET /api/v1/temporal-blocks?time=invalid` -> verify `400 Bad Request` status and error message structure.

---

## 6. Boundaries
- Do not alter database schema/mappings of `TimeSystem`, `Tag`, or `TagType`.
- Do not use annotation/attribute serialization libraries (like JMS Serializer or Symfony Serializer metadata) to minimize configuration surface area.
- Ensure read-only nature of the endpoint.
- Strictly adhere to the `/build` workflow loop.
