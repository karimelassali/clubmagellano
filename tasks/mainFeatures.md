# Coding Challenge — Laravel + Angular Senior

## Scenario

You are completing a platform that receives processing requests via API.

Each request:
- Belongs to a project
- Starts in a `pending` state
- Must be processed asynchronously using queues/jobs

The Angular frontend should allow users to:
- Authenticate
- Consult the list of requests
- Create a new request
- View request details
- Retry failed requests
- View dashboard statistics

---

# Existing Project Structure

## Backend Already Present

- [x] Main models
- [x] Basic migrations
- [x] Basic seeder
- [x] `POST /api/login`
- [x] `GET /api/me`
- [x] Main API routes declared
- [x] Job and service skeletons
- [x] Protect all required endpoints using authentication middleware

## Frontend Already Present

- [x] Basic Angular routing
- [x] Minimal login page
- [x] Minimal layout
- [x] Initial API services
- [x] Basic screens scaffolded

---

# Backend Tasks (API Endpoints)

## Projects

### GET `/api/projects`
- [x] Return only active projects
- [x] Use application cache with 5-minute TTL
- [x] Invalidate cache when projects change

---

## Processing Requests

### GET `/api/processing-requests`
- [x] Add pagination
- [ ] Add filters:
  - [x] status
  - [x] project_id
  - [x] reference
  - [ ] date_from (Pending: Fix fragile date range filtering bug)
  - [ ] date_to (Pending: Fix fragile date range filtering bug)
- [x] Add sorting:
  - [x] created_at
- [x] Include relationships:
  - [x] project
  - [x] creator

### POST `/api/processing-requests`
- [ ] Validate request input
- [ ] Validate `project_id`
  - [ ] Must exist
  - [ ] Must refer to active project
- [ ] Validate `reference`
  - [ ] Unique within project
- [ ] Validate `payload_json`
  - [ ] Contains `customer`
  - [ ] Contains non-empty `items` array
- [ ] Create request with `pending` status
- [ ] Dispatch processing job to queue

### GET `/api/processing-requests/{id}`
- [x] Include full data for the request, project, and creator
- [x] Use short-term cache only for `completed` requests (Fixed Bug 2)

### POST `/api/processing-requests/{id}/retry`
- [ ] Allowed only for `failed` requests
- [ ] Reset the request to `pending`
- [ ] Re-queue the job

---

## Dashboard

### GET `/api/dashboard/stats`
- [ ] Return Total requests
- [ ] Return Count by status
- [ ] Return Requests created today
- [ ] Return Average processing time for completed requests
- [ ] Use application cache with a 2-minute TTL

---

# Background Processing (Queue)

## ProcessProcessingRequestJob
- [ ] Set status to `processing`
- [ ] Process the payload
  - [ ] Calculate `items_count`
  - [ ] Calculate `total_amount`
  - [ ] Calculate `vat`
  - [ ] Calculate `grand_total`
- [ ] Produce a `result_json`
- [ ] Set `processed_at`
- [ ] In case of error, set to `failed` and populate `error_message`
- [ ] Enforce minimum rules: `items` not empty, `qty > 0`, `price >= 0`

---

# Frontend Tasks (Angular)

## Login
- [x] Use `POST /api/login`
- [x] Save the bearer token
- [x] Use `GET /api/me` to retrieve the authenticated user

## Request List
- [ ] Table with `id`, `reference`, `project`, `status`, `created_at`, `processed_at`
- [ ] Filter by status and project
- [ ] Manual refresh button

## Create Request
- [ ] Form with project dropdown, reference input, JSON payload textarea
- [ ] Submit to API
- [ ] Error handling

## Detail View
- [ ] Show original payload
- [ ] Show result
- [ ] Retry button if `failed`

## Dashboard
- [ ] Show summary statistics cards