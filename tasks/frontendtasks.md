# Angular Frontend Test - TODO List

## Authentication

- [ ] Improve login form validation
- [ ] Add proper TypeScript typing to auth responses
- [ ] Store authentication token/session securely
- [ ] Create authentication service
- [ ] Handle login loading state
- [ ] Handle login API errors
- [ ] Redirect user after successful login
- [ ] Redirect unauthenticated users to login page
- [ ] Add logout functionality

---

## Route Protection

- [ ] Create Angular auth guard (`CanActivate`)
- [ ] Protect dashboard routes
- [ ] Protect request detail routes
- [ ] Protect request creation routes
- [ ] Handle expired sessions/tokens
- [ ] Redirect unauthorized users properly

---

## TypeScript & Code Quality

- [ ] Remove all `any` types
- [ ] Create interfaces/models for API responses
- [ ] Type API service responses
- [ ] Improve component typing
- [ ] Improve form typing
- [ ] Refactor duplicated logic
- [ ] Organize folder structure
- [ ] Clean unused code/imports

---

## Dashboard

- [ ] Improve dashboard UI/UX
- [ ] Add loading states
- [ ] Add error handling
- [ ] Improve responsiveness
- [ ] Add summary/statistics cards
- [ ] Improve navigation experience

---

## Requests List

- [ ] Add real backend pagination
- [ ] Add next/previous page navigation
- [ ] Add page size selector
- [ ] Add loading spinner
- [ ] Add empty state UI
- [ ] Add API error handling
- [ ] Improve mobile responsiveness
- [ ] Add request status badges
- [ ] Improve table/list styling

---

## Request Detail Page

- [ ] Add loading state
- [ ] Add API error handling
- [ ] Improve detail page UI
- [ ] Add status visualization
- [ ] Handle missing request IDs
- [ ] Improve responsiveness

---

## Request Creation

- [ ] Improve form validation
- [ ] Add disabled submit button during loading
- [ ] Add success feedback/toast
- [ ] Add API error handling
- [ ] Improve UX of form inputs
- [ ] Improve form responsiveness
- [ ] Add typed request payload model

---

## Loading & Error Handling

- [ ] Create reusable loading component/spinner
- [ ] Create reusable error component
- [ ] Handle global API errors
- [ ] Handle network failures gracefully
- [ ] Prevent duplicate requests
- [ ] Add retry mechanisms where useful

---

## UX Improvements

- [ ] Add toast notifications
- [ ] Add skeleton loaders
- [ ] Improve button states
- [ ] Improve spacing/layout consistency
- [ ] Improve accessibility
- [ ] Improve responsive design
- [ ] Add confirmation dialogs where needed
- [ ] Improve navigation flow

---

## API Layer

- [ ] Centralize API calls in services
- [ ] Add typed HTTP responses
- [ ] Add request interceptors
- [ ] Add auth token interceptor
- [ ] Handle HTTP errors globally
- [ ] Improve API structure

---

## Optional Senior-Level Improvements

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

## Final Review

- [ ] Check mobile responsiveness
- [ ] Check route protection
- [ ] Check loading/error handling everywhere
- [ ] Check TypeScript strict mode compatibility
- [ ] Remove console logs/debug code
- [ ] Improve overall UI polish
- [ ] Test complete user flow
- [ ] Prepare clean Git commits