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

- [ ] Main models
- [ ] Basic migrations
- [ ] Basic seeder
- [ ] `POST /api/login`
- [ ] `GET /api/me`
- [ ] Main API routes declared
- [ ] Job and service skeletons

---

## Frontend Already Present

- [ ] Basic Angular routing
- [ ] Minimal login page
- [ ] Minimal layout
- [ ] Initial API services
- [ ] Basic screens scaffolded

---

# Backend Tasks

## Authentication

- [ ] Protect all required endpoints using authentication middleware

---

# API Endpoints To Implement

## Projects

### GET `/api/projects`

### Requirements
- [ ] Return only active projects
- [ ] Use application cache with 5-minute TTL
- [ ] Invalidate cache when projects change

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