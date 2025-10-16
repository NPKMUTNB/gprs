# Requirements Document

## Introduction

The Student Project Repository System (SPRS) is a web application designed to record, search, evaluate, and publish student project work. The system supports multiple user roles including students, advisors, committee members, and administrators. Built with Laravel 12 and SQLite, it provides a comprehensive platform for managing the entire lifecycle of student projects from creation through evaluation and publication.

## Requirements

### Requirement 1: User Authentication and Authorization

**User Story:** As a user, I want to register, login, and access features based on my role, so that I can perform actions appropriate to my responsibilities in the system.

#### Acceptance Criteria

1. WHEN a new user visits the registration page THEN the system SHALL allow them to create an account with name, email, password, role, and department
2. WHEN a user submits valid login credentials THEN the system SHALL authenticate them and redirect to their dashboard
3. WHEN a user attempts to access a protected resource THEN the system SHALL verify their role and permissions before granting access
4. WHEN an admin views the user management page THEN the system SHALL display all users with options to add, edit, delete, and modify roles
5. IF a user is not authenticated THEN the system SHALL redirect them to the login page when accessing protected routes
6. WHEN a user logs out THEN the system SHALL terminate their session and redirect to the home page

### Requirement 2: User Profile Management

**User Story:** As a user, I want to manage my profile information, so that my details are accurate and up-to-date in the system.

#### Acceptance Criteria

1. WHEN a user accesses their profile page THEN the system SHALL display their current name, student ID, department, and profile picture
2. WHEN a user updates their profile information THEN the system SHALL validate and save the changes
3. WHEN a user uploads a profile picture THEN the system SHALL store it in the storage directory and update their profile
4. IF a user provides invalid data THEN the system SHALL display appropriate validation error messages

### Requirement 3: Project Creation and Management

**User Story:** As a student, I want to create and manage my project, so that I can document my work and submit it for evaluation.

#### Acceptance Criteria

1. WHEN a student creates a new project THEN the system SHALL require title (Thai/English), abstract, year, semester, category, and advisor
2. WHEN a student saves a project THEN the system SHALL set the initial status to "draft"
3. WHEN a student edits their project THEN the system SHALL only allow editing if the status is "draft" or they have appropriate permissions
4. WHEN a student deletes a project THEN the system SHALL only allow deletion if the status is "draft"
5. WHEN a student submits a project THEN the system SHALL change the status from "draft" to "submitted"
6. IF a project is in "submitted" status THEN the system SHALL prevent the student from editing it without advisor approval

### Requirement 4: Team Member Management

**User Story:** As a project team leader, I want to add team members to my project, so that all contributors are properly credited.

#### Acceptance Criteria

1. WHEN a team leader adds a member THEN the system SHALL record the member's user ID and role (leader/member)
2. WHEN viewing a project THEN the system SHALL display all team members with their roles
3. WHEN a team leader removes a member THEN the system SHALL delete the member association from the project
4. IF a user is already a member THEN the system SHALL prevent duplicate additions

### Requirement 5: Project File Management

**User Story:** As a student, I want to upload multiple files to my project, so that I can provide complete documentation including proposals, reports, presentations, and source code.

#### Acceptance Criteria

1. WHEN a student uploads a file THEN the system SHALL require file type selection (proposal, report, presentation, code, other)
2. WHEN a file is uploaded THEN the system SHALL store it in the storage directory and record metadata in the database
3. WHEN viewing a project THEN the system SHALL display all attached files with download links
4. WHEN a student deletes a file THEN the system SHALL remove it from storage and database
5. IF a file upload fails THEN the system SHALL display an appropriate error message

### Requirement 6: Project Evaluation

**User Story:** As an advisor or committee member, I want to evaluate student projects, so that I can provide feedback and scores based on defined criteria.

#### Acceptance Criteria

1. WHEN an evaluator accesses the evaluation form THEN the system SHALL display fields for technical, design, documentation, and presentation scores
2. WHEN an evaluator submits scores THEN the system SHALL calculate the total score as (technical + design + documentation + presentation) ÷ 4
3. WHEN an evaluator provides a comment THEN the system SHALL store it with the evaluation
4. WHEN viewing a project THEN the system SHALL display all evaluations with scores and comments
5. IF a user is not an advisor or committee member THEN the system SHALL prevent them from evaluating projects

### Requirement 7: Project Approval Workflow

**User Story:** As an advisor, I want to approve or reject submitted projects, so that I can control which projects are published.

#### Acceptance Criteria

1. WHEN an advisor views submitted projects THEN the system SHALL display projects assigned to them with approve/reject options
2. WHEN an advisor approves a project THEN the system SHALL change the status from "submitted" to "approved"
3. WHEN an advisor rejects a project THEN the system SHALL change the status to "rejected" and allow the student to edit
4. WHEN a project is approved THEN the system SHALL allow it to be marked as "completed" for public viewing
5. IF a user is not the assigned advisor THEN the system SHALL prevent them from approving/rejecting the project

### Requirement 8: Comment and Feedback System

**User Story:** As an authenticated user, I want to comment on projects, so that I can provide feedback and engage in discussions.

#### Acceptance Criteria

1. WHEN a user views a project detail page THEN the system SHALL display all existing comments
2. WHEN an authenticated user submits a comment THEN the system SHALL save it with their user ID and timestamp
3. WHEN viewing comments THEN the system SHALL display the commenter's name and timestamp
4. WHEN an admin views a comment THEN the system SHALL provide an option to delete inappropriate comments
5. IF a user is not authenticated THEN the system SHALL hide the comment form

### Requirement 9: Category and Tag Management

**User Story:** As an administrator, I want to manage categories and tags, so that projects can be properly organized and searchable.

#### Acceptance Criteria

1. WHEN an admin accesses category management THEN the system SHALL display all categories with add, edit, and delete options
2. WHEN an admin creates a category THEN the system SHALL require a name and optional description
3. WHEN a student creates a project THEN the system SHALL allow them to select one category and multiple tags
4. WHEN an admin deletes a category THEN the system SHALL handle projects associated with that category appropriately
5. IF a category is in use THEN the system SHALL warn before deletion

### Requirement 10: Project Search and Browse

**User Story:** As any user, I want to search and filter projects, so that I can find relevant projects easily.

#### Acceptance Criteria

1. WHEN a user accesses the project list THEN the system SHALL display all published projects with pagination
2. WHEN a user enters search terms THEN the system SHALL filter projects by title, abstract, or tags
3. WHEN a user selects filters THEN the system SHALL filter by category, year, semester, advisor, or status
4. WHEN a user clicks on a project THEN the system SHALL display the detailed project page with all information
5. IF no projects match the search THEN the system SHALL display an appropriate message

### Requirement 11: Project Detail View

**User Story:** As any user, I want to view complete project details, so that I can understand the project thoroughly.

#### Acceptance Criteria

1. WHEN viewing a project detail page THEN the system SHALL display title, abstract, year, semester, category, and tags
2. WHEN viewing a project detail page THEN the system SHALL display all team members with their roles
3. WHEN viewing a project detail page THEN the system SHALL display the assigned advisor
4. WHEN viewing a project detail page THEN the system SHALL display all attached files with download links
5. WHEN viewing a project detail page THEN the system SHALL display all evaluations and comments
6. IF a project is not published THEN the system SHALL only show it to authorized users

### Requirement 12: Dashboard and Activity Overview

**User Story:** As an authenticated user, I want to see a personalized dashboard, so that I can quickly access relevant information and actions.

#### Acceptance Criteria

1. WHEN a student accesses the dashboard THEN the system SHALL display their projects with status indicators
2. WHEN an advisor accesses the dashboard THEN the system SHALL display projects assigned to them requiring action
3. WHEN an admin accesses the dashboard THEN the system SHALL display system statistics and recent activities
4. WHEN viewing the dashboard THEN the system SHALL provide quick links to common actions
5. IF there are pending notifications THEN the system SHALL display them on the dashboard

### Requirement 13: Reports and Analytics

**User Story:** As an administrator or advisor, I want to view reports and statistics, so that I can analyze project trends and performance.

#### Acceptance Criteria

1. WHEN accessing the reports page THEN the system SHALL display project count by year and category
2. WHEN viewing reports THEN the system SHALL calculate and display average project scores
3. WHEN viewing reports THEN the system SHALL show project count per advisor
4. WHEN generating a report THEN the system SHALL provide options to download as Excel or PDF
5. IF insufficient data exists THEN the system SHALL display an appropriate message

### Requirement 14: Activity Logging

**User Story:** As an administrator, I want to track user activities, so that I can monitor system usage and troubleshoot issues.

#### Acceptance Criteria

1. WHEN a user performs a significant action THEN the system SHALL log the action with user ID, action type, and timestamp
2. WHEN an admin views activity logs THEN the system SHALL display recent activities with filtering options
3. WHEN viewing logs THEN the system SHALL include details about the action performed
4. IF a security-related action occurs THEN the system SHALL ensure it is logged

### Requirement 15: Public Access and Guest Viewing

**User Story:** As a guest user, I want to browse published projects, so that I can view student work without requiring an account.

#### Acceptance Criteria

1. WHEN a guest visits the home page THEN the system SHALL display all completed/published projects
2. WHEN a guest views a project THEN the system SHALL show all public information except evaluation details
3. WHEN a guest attempts to comment or evaluate THEN the system SHALL prompt them to login
4. IF a project is not published THEN the system SHALL hide it from guest users
