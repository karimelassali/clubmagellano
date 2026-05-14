# Coding challenge — Laravel + Angular Senior

## Scenario

You are completing a platform that receives processing requests via API.
Each request belongs to a project, is created in a `pending` state, and must then be processed in the background via a **queue**.

The Angular frontend should allow users to:
- Authenticate
- Consult the list of requests
- Create a new request
- View the details
- Retry a failed request
- View summary statistics

## What is already present in the test

### Backend
- Main models
- Basic migrations
- Basic seeder
- `POST /api/login` endpoint
- `GET /api/me` endpoint
- Main API routes already declared
- Job and services as incomplete skeletons

### Frontend
- Basic routing
- Minimal login
- Minimal layout
- Initial API services
- Basic screens to be completed

## What you need to implement

### Backend
Implement these authenticated endpoints:

- `GET /api/projects`
- `GET /api/processing-requests`
- `POST /api/processing-requests`
- `GET /api/processing-requests/{id}`
- `POST /api/processing-requests/{id}/retry`
- `GET /api/dashboard/stats`

### Backend Requirements

#### Projects
`GET /api/projects`
- Return only active projects
- Use application cache with a 5-minute TTL
- Invalidate the cache when necessary

#### Create processing request
`POST /api/processing-requests`
- Validate input
- `project_id` must exist and refer to an active project
- `reference` must be unique within the project
- `payload_json` must contain:
  - `customer`
  - `items` non-empty array
- Create the request with `pending` status
- Dispatch the processing job

Example payload:
```json
{
  "project_id": 1,
  "reference": "ACME-001",
  "payload_json": {
    "customer": "ACME",
    "items": [
      { "sku": "A1", "qty": 2, "price": 10 },
      { "sku": "B2", "qty": 1, "price": 30 }
    ]
  }
}
```

#### List processing requests
`GET /api/processing-requests`
- Pagination
- Filters: `status`, `project_id`, `reference`, `date_from`, `date_to`
- Sorting at least by `created_at`
- Include project and creator

#### Detail
`GET /api/processing-requests/{id}`
- Include full data for the request, project, and creator
- Use short-term cache only for `completed` requests

#### Retry
`POST /api/processing-requests/{id}/retry`
- Allowed only for `failed` requests
- Reset the request to `pending`
- Re-queue the job

#### Dashboard
`GET /api/dashboard/stats`
Return at least:
- Total requests
- Count by status
- Requests created today
- Average processing time for completed requests

Use application cache with a 2-minute TTL.

### Queue
The `ProcessProcessingRequestJob` job must:
- Set status to `processing`
- Process the payload
- Produce a `result_json`
- Set `processed_at`
- In case of error, set to `failed` and populate `error_message`

Expected result for the example above:
```json
{
  "customer": "ACME",
  "items_count": 2,
  "total_amount": 50,
  "vat": 11,
  "grand_total": 61
}
```

Minimum rules:
- `items` not empty
- `qty > 0`
- `price >= 0`

### Angular Frontend
Implement at least:

#### Login
- Use `POST /api/login`
- Save the bearer token
- Use `GET /api/me` to retrieve the authenticated user

#### Request List
- Table with `id`, `reference`, `project`, `status`, `created_at`, `processed_at`
- Filter by status and project
- Manual refresh

#### Create Request
- Form with project, reference, JSON payload
- Submit to API
- Error handling

#### Detail
- Show original payload
- Show result
- Retry button if `failed`

#### Dashboard
- Show summary statistics

## Constraints
- A refined UI is not required
- Pay attention to code structure and error handling
- You can add classes, services, tests, DTOs, actions, policies, resources, interceptors

## Delivery
Provide:
- Final repository or zip
- Startup instructions
- Brief architectural note
- Any tradeoffs and future improvements
