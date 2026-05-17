# 🗂️ Full Project TODO — Backend + Frontend
> Deadline: **after tomorrow**. Focus on 🔴 HIGH priority first.

---

## 🔧 BACKEND

### Cache
- [x] Wrap dashboard stats in `Cache::remember()`
- [x] Invalidate `dashboard_stats` cache in Job `finally` block
- [ ] Invalidate cache also when a new request is created (`store`)
- [ ] Invalidate cache also when a request is retried

### List / Detail / Retry
- [x] `index()` — paginated list with filters
- [x] `show()` — detail via `ProcessingRequestDetailService`
- [x] `retry()` — guard: only failed requests can be retried
- [x] `retry()` — reset `error_message`, `result_json`, `processed_at`
- [x] `retry()` — re-dispatch job after reset

### Concurrency Controls in Job 🔴
- [ ] Add atomic lock (`Cache::lock()`) to prevent double processing
- [ ] Set status to `processing` atomically before invoking engine
- [ ] Release lock after job completes or fails

### Tests
- [x] Test: unauthenticated request returns 401
- [x] Test: paginated list returns correct structure
- [x] Test: retry a failed request resets fields and dispatches job
- [x] Test: cannot retry a non-failed request returns 422
- [ ] Test: `store` creates request and dispatches job
- [ ] Test: `show` returns correct detail structure
- [ ] Test: dashboard stats endpoint returns expected fields
- [ ] Test: cache is invalidated after job completes

---

## 🖥️ FRONTEND

### Authentication
- [x] Improve login form validation
- [x] Add proper TypeScript typing to auth responses
- [ x] Store authentication token/session securely (HttpOnly cookies) 🔴
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
- [ ] Remove all `any` types
- [ ] Create interfaces/models for API responses
- [ ] Type API service responses
- [ ] Improve component typing
- [ ] Improve form typing
- [ ] Refactor duplicated logic
- [ ] Organize folder structure
- [ ] Clean unused code/imports

### Dashboard
- [x] Improve dashboard UI/UX
- [x] Add loading states
- [x] Add error handling
- [x] Improve responsiveness 🔴
- [x] Add summary/statistics cards 🔴
- [ ] Improve navigation experience

### Requests List
- [ ] Add real backend pagination
- [ ] Add next/previous page navigation
- [ ] Add page size selector
- [x] Add loading spinner
- [x] Add empty state UI
- [x] Add API error handling
- [ ] Improve mobile responsiveness
- [x] Add request status badges
- [ ] Improve table/list styling

### Request Detail Page
- [ ] Add loading state 🔴
- [x] Add API error handling
- [ ] Improve detail page UI
- [ ] Add status visualization
- [ ] Handle missing request IDs 🔴
- [ ] Improve responsiveness

### Request Creation
- [ ] Improve form validation 🔴
- [ ] Add disabled submit button during loading 🔴
- [x] Add success feedback/toast
- [x] Add API error handling
- [ ] Improve UX of form inputs
- [ ] Improve form responsiveness
- [ ] Add typed request payload model

### Loading & Error Handling
- [x] Create reusable loading component/spinner
- [x] Create reusable error component
- [x] Handle global API errors
- [x] Handle network failures gracefully
- [ ] Prevent duplicate requests
- [ ] Add retry mechanisms where useful

### UX Improvements
- [x] Add toast notifications
- [ ] Add skeleton loaders
- [ ] Improve button states
- [ ] Improve spacing/layout consistency
- [ ] Improve accessibility
- [ ] Improve responsive design
- [ ] Add confirmation dialogs where needed
- [ ] Improve navigation flow

### API Layer
- [ ] Centralize API calls in services
- [ ] Add typed HTTP responses
- [x] Add request interceptors
- [x] Add auth token interceptor
- [x] Handle HTTP errors globally
- [ ] Improve API structure

### Optional Senior-Level Improvements
- [ ] Add state management if needed
- [ ] Add reusable shared components
- [ ] Add environment configuration
- [ ] Add unit tests
- [ ] Add integration tests
- [ ] Add dark mode support
- [ ] Improve performance optimization
- [ ] Add caching strategies
- [ ] Add reusable UI system/components

---

## ✅ Final Review
- [ ] Check mobile responsiveness
- [ ] Check route protection
- [ ] Check loading/error handling everywhere
- [ ] Check TypeScript strict mode compatibility
- [ ] Remove console logs/debug code 🔴
- [ ] Improve overall UI polish
- [ ] Test complete user flow 🔴
- [ ] Prepare clean Git commits 🔴
