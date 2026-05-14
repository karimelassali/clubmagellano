# 🚀 Pending Features Checklist

This list tracks the remaining tasks to complete the **Senior API Challenge**.

## 🛠️ Backend (API)

### Processing Requests
- [ ] **POST `/api/processing-requests`** (Creation)
    - [ ] Validate `project_id` (must exist and be active).
    - [ ] Validate `reference` (must be unique within the same project).
    - [ ] Validate `payload_json` (must contain `customer` and non-empty `items`).
    - [ ] Initialize with `pending` status.
    - [ ] Dispatch `ProcessProcessingRequestJob`.
- [ ] **POST `/api/processing-requests/{id}/retry`** (Retry Logic)
    - [ ] Allow only for `failed` requests.
    - [ ] Reset status to `pending`.
    - [ ] Re-queue the job.

### Dashboard
- [ ] **GET `/api/dashboard/stats`** (Statistics)
    - [ ] Calculate **Total requests**.
    - [ ] Calculate **Count by status**.
    - [ ] Calculate **Requests created today**.
    - [ ] Calculate **Average processing time** for completed requests.
    - [ ] Implement **2-minute TTL cache**.

### Fixes & Improvements
- [ ] **BUG Intenzionale 1**: Fix the fragile date range filtering in `ProcessingRequestQueryService`.

---

## ⚙️ Background Processing (Queue)

- [ ] **ProcessProcessingRequestJob** Logic:
    - [ ] Set status to `processing`.
    - [ ] Calculate `items_count`.
    - [ ] Calculate `total_amount` (sum of price * qty).
    - [ ] Calculate `vat` (22% of total_amount).
    - [ ] Calculate `grand_total` (total_amount + vat).
    - [ ] Update `processed_at`.
    - [ ] Handle errors: Set status to `failed` and store `error_message`.

---

## 🎨 Frontend (Angular)

### Request Management
- [ ] **Request List Table**:
    - [ ] Show `id`, `reference`, `project`, `status`, `created_at`, `processed_at`.
    - [ ] Add filters for **Status** and **Project**.
    - [ ] Implement a **Manual Refresh** button.
- [ ] **Create Request Form**:
    - [ ] Project dropdown selection.
    - [ ] Reference input.
    - [ ] JSON Payload editor/textarea.
- [ ] **Detail Page**:
    - [ ] Display formatted "Original Payload".
    - [ ] Display formatted "Result JSON".
    - [ ] Add the "Retry" button (visible only if status is `failed`).

### Dashboard
- [ ] **Stats Widgets**:
    - [ ] Display cards for Total, Pending, Processing, Completed, and Failed requests.
    - [ ] Display "Avg Processing Time" indicator.
