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

- [ x] Main models
- [x] Basic migrations
- [ x] Basic seeder
- [ x] `POST /api/login`
- [ x] `GET /api/me`
- [ x] Main API routes declared
- [ x] Job and service skeletons

---

## Frontend Already Present

- [x] Basic Angular routing
- [x] Minimal login page
- [x] Minimal layout
- [x] Initial API services
- [x] Basic screens scaffolded

---

# Backend Tasks

## Authentication

- [x] Protect all required endpoints using authentication middleware

---

# API Endpoints To Implement

## Projects

### GET `/api/projects`

### Requirements
- [ x] Return only active projects
- [ x] Use application cache with 5-minute TTL
- [ x] Invalidate cache when projects change

---

## Processing Requests

### GET `/api/processing-requests`

### Requirements
- [ ] Add pagination
- [ ] Add filters:
  - [ ] status
  - [ ] project_id
  - [ ] reference
  - [ ] date_from
  - [ ] date_to
- [ ] Add sorting:
  - [ ] created_at
- [ ] Include relationships:
  - [ ] project
  - [ ] creator

---

### POST `/api/processing-requests`

### Requirements
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

### Example Payload

```json
{
  "project_id": 1,
  "reference": "ACME-001",
  "payload_json": {
    "customer": "ACME",
    "items": [
      {
        "sku": "A1",
        "qty": 2,
        "price": 10
      },
      {
        "sku": "B2",
        "qty": 1,
        "price": 30
      }
    ]
  }
}