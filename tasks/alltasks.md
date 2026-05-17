# 🗂️ Full Project TODO — Backend + Frontend
> Deadline: **after tomorrow**. Focus on 🔴 HIGH priority first.

---

## 🔧 BACKEND

### Cache
- [x] Wrap dashboard stats in `Cache::remember()`
- [x] Invalidate `dashboard_stats` cache in Job `finally` block
- [x] Invalidate cache also when a new request is created (`store`)
- [x] Invalidate cache also when a request is retried

### List / Detail / Retry
- [x] `index()` — paginated list with filters
- [x] `show()` — detail via `ProcessingRequestDetailService`
- [x] `retry()` — guard: only failed requests can be retried
- [x] `retry()` — reset `error_message`, `result_json`, `processed_at`
- [x] `retry()` — re-dispatch job after reset

### Concurrency Controls in Job 🔴
- [x] Use a semaphore or lock to prevent race conditions
- [x] Set status to `processing` atomically before invoking engine
- [x] Release lock after job completes or fails

### Tests
- [x] Test: unauthenticated request returns 401
- [x] Test: paginated list returns correct structure
- [x] Test: retry a failed request resets fields and dispatches job
- [x] Test: cannot retry a non-failed request returns 422
- [x] Test: `store` creates request and dispatches job
- [x] Test: `show` returns correct detail structure
- [x] Test: dashboard stats endpoint returns expected fields
- [x] Test: cache is invalidated after job completes

---

## 🖥️ FRONTEND

### Authentication
- [x] Improve login form validation
- [x] Add proper TypeScript typing to auth responses
- [x] Store authentication token/session securely (HttpOnly cookies) 🔴
- [x] Create authentication service
- [x] Handle login loading state
- [x] Handle login API errors
- [x] Redirect user after successful login
- [x] Redirect unauthenticated users to login page
- [x] Add logout functionality

### Route Protection
- [x] Create Angular auth guard (`CanActivate`)
- [x] Protect dashboard routes
- [x] Protect request detail routes
- [x] Protect request creation routes
- [x] Handle expired sessions/tokens
- [x] Redirect unauthorized users properly

### TypeScript & Code Quality 🔴
- [x] Create interfaces/models for API responses
- [x] Type API service responses
- [x] Improve component typing
- [x] Improve form typing
- [x] Refactor duplicated logic
- [x] Organize folder structure
- [x] Clean unused code/imports

### Dashboard
- [x] Improve dashboard UI/UX
- [x] Add loading states
- [x] Add error handling
- [x] Improve responsiveness 🔴
- [x] Add summary/statistics cards 🔴
- [x] Improve navigation experience

### Requests List
- [x] Add real backend pagination
- [x] Add next/previous page navigation
- [x] Add loading spinner
- [x] Add empty state UI
- [x] Add API error handling
- [x] Improve mobile responsiveness
- [x] Add request status badges
- [x] Improve table/list styling

### Request Detail Page
- [x] Add loading state 🔴
- [x] Add API error handling
- [x] Improve detail page UI
- [x] Add status visualization
- [x] Handle missing request IDs 🔴
- [x] Improve responsiveness

### Request Creation
- [x] Improve form validation 🔴
- [x] Add disabled submit button during loading 🔴
- [x] Add success feedback/toast
- [x] Add API error handling
- [x] Improve UX of form inputs
- [x] Improve form responsiveness

### Loading & Error Handling
- [x] Create reusable loading component/spinner
- [x] Create reusable error component
- [x] Handle global API errors
- [x] Handle network failures gracefully
- [x] Prevent duplicate requests (disabling buttons on submit)

### UX Improvements
- [x] Add toast notifications
- [x] Improve button states
- [x] Improve spacing/layout consistency
- [x] Improve responsive design
- [x] Improve navigation flow

### API Layer
- [x] Centralize API calls in services
- [x] Add request interceptors
- [x] Add auth token interceptor
- [x] Handle HTTP errors globally
- [x] Improve API structure

---

## ✅ Final Review
- [x] Check mobile responsiveness
- [x] Check route protection
- [x] Check loading/error handling everywhere
- [x] Remove console logs/debug code 🔴
- [x] Improve overall UI polish
- [x] Test complete user flow 🔴
- [x] Prepare clean Git commits 🔴
