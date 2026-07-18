# Topic Name: TimeSystem
# Issue Name: MidnightWrapAround

## Todo List

- [x] **Phase 1: Repository Refactoring**
  - [x] Implement `getActiveForTime()` and update `getCurrent()` in `src/Repository/TimeSystemRepository.php` <!-- id: 0 -->
  - [x] Create `tests/Repository/TimeSystemRepositoryTest.php` with setup, teardown, and test cases for standard/overnight blocks <!-- id: 1 -->
  - [x] Run PHPUnit to verify repository tests pass <!-- id: 2 -->

- [ ] **Phase 2: API Endpoint Implementation**
  - [ ] Create `src/Controller/Api/TimeSystemController.php` with `GET /api/v1/temporal-blocks` and `time` query validation/dynamic grouping <!-- id: 3 -->
  - [ ] Create `tests/Controller/Api/TimeSystemControllerTest.php` verifying HTTP response status and structure <!-- id: 4 -->
  - [ ] Run complete PHPUnit test suite to verify all tests pass <!-- id: 5 -->
