# Implementation Plan

- [x] 1. Install Laravel Breeze and configure authentication
  - Install Laravel Breeze package for authentication scaffolding
  - Publish Breeze views and configure for session-based authentication
  - Modify registration form to include role and department fields
  - Update User model to include role, department, and profile_pic fields
  - _Requirements: 1.1, 1.2, 1.5, 1.6_

- [x] 2. Create database migrations for core tables
  - _Requirements: 3.1, 4.1, 5.1, 6.1, 8.1, 9.1_

- [x] 2.1 Modify users table migration to add role and department
  - Add role column with enum constraint (admin, advisor, student, committee)
  - Add department and profile_pic columns
  - _Requirements: 1.1, 2.1_

- [x] 2.2 Create categories table migration
  - Create migration with name and description columns
  - _Requirements: 9.1, 9.2_

- [x] 2.3 Create tags table migration
  - Create migration with name column
  - _Requirements: 9.3_

- [x] 2.4 Create projects table migration
  - Create migration with title_th, title_en, abstract, year, semester, status, category_id, created_by, advisor_id
  - Add foreign key constraints and indexes
  - Add status enum constraint (draft, submitted, approved, rejected, completed)
  - _Requirements: 3.1, 3.2_

- [x] 2.5 Create project_members table migration
  - Create migration with project_id, user_id, role_in_team
  - Add foreign key constraints
  - _Requirements: 4.1_

- [x] 2.6 Create project_files table migration
  - Create migration with project_id, file_name, file_type, file_path, uploaded_at
  - Add file_type enum constraint (proposal, report, presentation, code, other)
  - _Requirements: 5.1, 5.2_

- [x] 2.7 Create evaluations table migration
  - Create migration with project_id, evaluator_id, technical_score, design_score, documentation_score, presentation_score, total_score, comment
  - Add foreign key constraints and indexes
  - _Requirements: 6.1, 6.2_

- [x] 2.8 Create comments table migration
  - Create migration with project_id, user_id, content, created_at
  - Add foreign key constraints
  - _Requirements: 8.1, 8.2_

- [x] 2.9 Create project_tag pivot table migration
  - Create migration with project_id and tag_id
  - Add composite primary key and foreign key constraints
  - _Requirements: 9.3_

- [x] 2.10 Create activity_logs table migration
  - Create migration with user_id, action, detail, created_at
  - Add foreign key constraint
  - _Requirements: 14.1_

- [x] 3. Create Eloquent models with relationships
  - _Requirements: 3.1, 4.1, 5.1, 6.1, 8.1, 9.1_

- [x] 3.1 Update User model with relationships and helper methods
  - Add relationships: createdProjects, advisedProjects, projectMemberships, evaluations, comments, activityLogs
  - Add helper methods: isAdmin(), isAdvisor(), isStudent(), isCommittee()
  - _Requirements: 1.3_

- [x] 3.2 Create Project model with relationships and scopes
  - Add fillable fields and relationships: category, creator, advisor, members, files, evaluations, comments, tags
  - Add scopes: scopePublished, scopeByStatus, scopeByYear, scopeByCategory
  - Add helper methods: canBeEditedBy(), canBeDeletedBy(), averageScore()
  - _Requirements: 3.1, 3.3, 10.1, 10.3, 11.5_

- [x] 3.3 Create Category model with relationships
  - Add fillable fields and projects relationship
  - _Requirements: 9.1, 9.2_

- [x] 3.4 Create Tag model with relationships
  - Add fillable fields and projects many-to-many relationship
  - _Requirements: 9.3_

- [x] 3.5 Create ProjectMember model with relationships
  - Add fillable fields and relationships to project and user
  - _Requirements: 4.1, 4.2_

- [x] 3.6 Create ProjectFile model with relationships and helper methods
  - Add fillable fields and project relationship
  - Add helper methods: getDownloadUrl(), getFileSize()
  - _Requirements: 5.1, 5.2, 5.3_

- [x] 3.7 Create Evaluation model with auto-calculation
  - Add fillable fields and relationships to project and evaluator
  - Implement boot method to auto-calculate total_score on save
  - _Requirements: 6.1, 6.2, 6.3_

- [x] 3.8 Create Comment model with relationships
  - Add fillable fields and relationships to project and user
  - _Requirements: 8.1, 8.2_

- [x] 3.9 Create ActivityLog model with relationships
  - Add fillable fields and user relationship
  - _Requirements: 14.1, 14.3_

- [x] 4. Create authorization policies and gates
  - _Requirements: 1.3, 3.3, 3.4, 6.5, 7.5_

- [x] 4.1 Create ProjectPolicy with authorization methods
  - Implement view, create, update, delete, approve, reject, submit methods
  - Ensure students can only edit/delete draft projects
  - Ensure only assigned advisors can approve/reject
  - _Requirements: 3.3, 3.4, 7.1, 7.2, 7.3, 7.5_

- [x] 4.2 Define Gates in AppServiceProvider
  - Define manage-users gate for admin-only access
  - Define evaluate-project gate for advisors and committee members
  - Define manage-categories gate for admin-only access
  - _Requirements: 1.4, 6.5, 9.1_

- [x] 5. Create database seeders for initial data
  - _Requirements: 1.1, 9.1, 9.3_

- [x] 5.1 Create UserSeeder with sample users
  - Create admin, advisor, student, and committee users
  - Hash passwords properly
  - _Requirements: 1.1_

- [x] 5.2 Create CategorySeeder with sample categories
  - Create common project categories (e.g., Web Development, Mobile App, AI/ML, IoT)
  - _Requirements: 9.1, 9.2_

- [x] 5.3 Create TagSeeder with sample tags
  - Create common tags (e.g., Laravel, React, Python, Machine Learning)
  - _Requirements: 9.3_

- [x] 6. Implement ProjectController with CRUD operations
  - _Requirements: 3.1, 3.2, 3.3, 3.4, 3.5, 10.1, 10.3, 10.4, 11.1_

- [x] 6.1 Implement index method with search and filters
  - Display paginated project list
  - Implement search by title, abstract, tags
  - Implement filters by category, year, semester, advisor, status
  - Use eager loading to prevent N+1 queries
  - _Requirements: 10.1, 10.2, 10.3, 10.5_

- [x] 6.2 Implement create and store methods
  - Show create form with category and advisor dropdowns
  - Validate input using Form Request
  - Set initial status to 'draft' and created_by to authenticated user
  - Handle tag associations
  - _Requirements: 3.1, 3.2_

- [x] 6.3 Implement show method for project details
  - Display complete project information with eager loaded relationships
  - Show team members, advisor, files, evaluations, comments
  - Check authorization for non-published projects
  - _Requirements: 11.1, 11.2, 11.3, 11.4, 11.5, 11.6_

- [x] 6.4 Implement edit and update methods
  - Check authorization using ProjectPolicy
  - Show edit form with current data
  - Validate and update project data
  - Handle tag associations
  - _Requirements: 3.3_

- [x] 6.5 Implement destroy method
  - Check authorization using ProjectPolicy
  - Delete project and associated records
  - Log activity
  - _Requirements: 3.4_

- [x] 6.6 Implement submit method for status transition
  - Change status from 'draft' to 'submitted'
  - Check authorization
  - Log activity
  - _Requirements: 3.5_

- [x] 6.7 Implement approve and reject methods
  - Check authorization using ProjectPolicy
  - Change status to 'approved' or 'rejected'
  - Log activity
  - _Requirements: 7.1, 7.2, 7.3, 7.4, 7.5_

- [x] 7. Create Form Request classes for validation
  - _Requirements: 3.1, 6.1, 8.2_

- [x] 7.1 Create StoreProjectRequest
  - Validate title_th, title_en, abstract, year, semester, category_id, advisor_id, tags
  - Add custom error messages
  - _Requirements: 3.1_

- [x] 7.2 Create UpdateProjectRequest
  - Same validation as StoreProjectRequest
  - _Requirements: 3.3_

- [x] 7.3 Create StoreEvaluationRequest
  - Validate technical_score, design_score, documentation_score, presentation_score, comment
  - Ensure scores are numeric and within valid range
  - _Requirements: 6.1_

- [x] 8. Implement ProjectFileController for file management
  - _Requirements: 5.1, 5.2, 5.3, 5.4, 5.5_

- [x] 8.1 Implement store method for file upload
  - Validate file type and size
  - Generate unique filename
  - Store file in storage/app/public/projects/{project_id}/{file_type}/
  - Save file metadata to database
  - Log activity
  - _Requirements: 5.1, 5.2, 5.5_

- [x] 8.2 Implement destroy method for file deletion
  - Check authorization
  - Delete file from storage
  - Delete database record
  - Log activity
  - _Requirements: 5.4_

- [x] 8.3 Implement download method for file access
  - Check authorization
  - Return file download response
  - Log activity
  - _Requirements: 5.3_

- [x] 9. Implement ProjectMemberController for team management
  - _Requirements: 4.1, 4.2, 4.3, 4.4_

- [x] 9.1 Implement store method to add team members
  - Validate user_id and role_in_team
  - Check for duplicate members
  - Save team member association
  - _Requirements: 4.1, 4.4_

- [x] 9.2 Implement destroy method to remove team members
  - Check authorization (only project creator or admin)
  - Delete team member association
  - _Requirements: 4.3_

- [x] 10. Implement EvaluationController
  - _Requirements: 6.1, 6.2, 6.3, 6.4, 6.5_

- [x] 10.1 Implement create method to show evaluation form
  - Check authorization using evaluate-project gate
  - Display evaluation form with score fields
  - _Requirements: 6.1, 6.5_

- [x] 10.2 Implement store method to save evaluation
  - Validate scores using StoreEvaluationRequest
  - Save evaluation (total_score calculated automatically by model)
  - Log activity
  - _Requirements: 6.2, 6.3, 6.4_

- [x] 11. Implement CommentController
  - _Requirements: 8.1, 8.2, 8.3, 8.4, 8.5_

- [x] 11.1 Implement store method to add comments
  - Check authentication
  - Validate comment content
  - Save comment with user_id and timestamp
  - _Requirements: 8.2, 8.5_

- [x] 11.2 Implement destroy method to delete comments
  - Check authorization (admin only)
  - Delete comment
  - Log activity
  - _Requirements: 8.4_

- [x] 12. Implement DashboardController with role-specific views
  - _Requirements: 12.1, 12.2, 12.3, 12.4, 12.5_

- [x] 12.1 Implement index method with role-based logic
  - For students: show their projects with status indicators
  - For advisors: show assigned projects requiring action
  - For admins: show system statistics and recent activities
  - For committee: show projects available for evaluation
  - Provide quick action links
  - _Requirements: 12.1, 12.2, 12.3, 12.4, 12.5_

- [x] 13. Implement admin controllers for system management
  - _Requirements: 1.4, 9.1, 9.2, 9.4, 9.5_

- [x] 13.1 Create UserController for user management
  - Implement index to list all users
  - Implement create and store to add new users
  - Implement edit and update to modify users
  - Implement destroy to delete users
  - Check admin authorization using manage-users gate
  - _Requirements: 1.4_

- [x] 13.2 Create CategoryController for category management
  - Implement index to list all categories
  - Implement create and store to add new categories
  - Implement edit and update to modify categories
  - Implement destroy to delete categories with warning if in use
  - Check admin authorization using manage-categories gate
  - _Requirements: 9.1, 9.2, 9.4, 9.5_

- [x] 13.3 Create TagController for tag management
  - Implement index to list all tags
  - Implement store to add new tags
  - Implement destroy to delete tags
  - Check admin authorization
  - _Requirements: 9.3_

- [x] 14. Implement ReportController for analytics
  - _Requirements: 13.1, 13.2, 13.3, 13.4, 13.5_

- [x] 14.1 Implement index method to show reports page
  - Display various report options
  - Check authorization (admin or advisor)
  - _Requirements: 13.1, 13.5_

- [x] 14.2 Implement projectsByYear method
  - Query and group projects by year
  - Display count per year
  - _Requirements: 13.1_

- [x] 14.3 Implement projectsByCategory method
  - Query and group projects by category
  - Display count per category
  - _Requirements: 13.1_

- [x] 14.4 Implement averageScores method
  - Calculate average evaluation scores across all projects
  - Display by category or year
  - _Requirements: 13.2_

- [x] 14.5 Implement advisorProjects method
  - Count projects per advisor
  - Display advisor workload
  - _Requirements: 13.3_

- [x] 15. Create ProfileController for user profile management
  - _Requirements: 2.1, 2.2, 2.3, 2.4_

- [x] 15.1 Implement edit method to show profile form
  - Display current user information
  - _Requirements: 2.1_

- [x] 15.2 Implement update method to save profile changes
  - Validate input
  - Handle profile picture upload
  - Save changes to database
  - _Requirements: 2.2, 2.3, 2.4_

- [x] 16. Create activity logging helper
  - _Requirements: 14.1, 14.2, 14.3, 14.4_

- [x] 16.1 Create ActivityLogger helper class
  - Implement static method to log activities
  - Accept user_id, action, and detail parameters
  - Save to activity_logs table
  - _Requirements: 14.1, 14.3_

- [x] 16.2 Integrate logging into controllers
  - Add logging calls to create, update, delete, approve, reject actions
  - Log authentication events
  - _Requirements: 14.1, 14.4_

- [x] 17. Create Blade layout and components
  - _Requirements: All UI-related requirements_

- [x] 17.1 Create master layout (app.blade.php)
  - Include navigation bar with role-specific menu items
  - Include flash message display area
  - Include main content area and footer
  - Integrate TailwindCSS
  - _Requirements: 1.2, 12.4_

- [x] 17.2 Create reusable Blade components
  - Create alert component for flash messages
  - Create card component for content containers
  - Create button component with consistent styling
  - Create input and select components for forms
  - Create project-card component for project listings
  - Create file-list component for file attachments
  - Create evaluation-display component for scores
  - _Requirements: Various UI requirements_

- [x] 18. Create authentication views
  - _Requirements: 1.1, 1.2, 1.6_

- [x] 18.1 Customize Breeze login view
  - Style with TailwindCSS
  - Add bilingual support
  - _Requirements: 1.2_

- [x] 18.2 Customize Breeze registration view
  - Add role and department fields
  - Style with TailwindCSS
  - Add bilingual support
  - _Requirements: 1.1_

- [x] 18.3 Customize password reset views
  - Style with TailwindCSS
  - Add bilingual support
  - _Requirements: 1.2_

- [x] 19. Create dashboard views
  - _Requirements: 12.1, 12.2, 12.3, 12.4, 12.5_

- [x] 19.1 Create dashboard index view with role-specific sections
  - Create student dashboard section showing their projects
  - Create advisor dashboard section showing assigned projects
  - Create admin dashboard section showing statistics
  - Create committee dashboard section showing evaluation opportunities
  - Add quick action buttons
  - _Requirements: 12.1, 12.2, 12.3, 12.4, 12.5_

- [x] 20. Create project views
  - _Requirements: 3.1, 3.3, 10.1, 10.2, 10.3, 10.4, 11.1, 11.2, 11.3, 11.4, 11.5_

- [x] 20.1 Create projects index view with search and filters
  - Display paginated project grid/list
  - Add search form for title, abstract, tags
  - Add filter dropdowns for category, year, semester, advisor, status
  - Display project cards with key information
  - _Requirements: 10.1, 10.2, 10.3, 10.4, 10.5_

- [x] 20.2 Create projects create view
  - Create form with title_th, title_en, abstract, year, semester fields
  - Add category dropdown and advisor dropdown
  - Add tag multi-select
  - Add validation error display
  - _Requirements: 3.1_

- [x] 20.3 Create projects edit view
  - Similar to create view but pre-filled with current data
  - Show only if user has permission
  - _Requirements: 3.3_

- [x] 20.4 Create projects show view for detail page
  - Display complete project information (title, abstract, year, semester, category, tags)
  - Display team members section with roles
  - Display advisor information
  - Display file attachments with download links
  - Display evaluations with scores and comments
  - Display comments section with add comment form
  - Add action buttons (edit, delete, submit, approve, reject) based on permissions
  - _Requirements: 11.1, 11.2, 11.3, 11.4, 11.5, 11.6_

- [x] 21. Create file management views
  - _Requirements: 5.1, 5.3, 5.4_

- [x] 21.1 Create file upload form component
  - Add to project show page
  - Include file type dropdown and file input
  - Display validation errors
  - _Requirements: 5.1_

- [x] 21.2 Create file list display component
  - Show all project files grouped by type
  - Add download and delete buttons
  - Show file metadata (name, size, upload date)
  - _Requirements: 5.3, 5.4_

- [x] 22. Create team member management views
  - _Requirements: 4.1, 4.2, 4.3_

- [x] 22.1 Create team member form component
  - Add to project show/edit page
  - Include user dropdown and role selection
  - Display current team members with remove option
  - _Requirements: 4.1, 4.2, 4.3_

- [x] 23. Create evaluation views
  - _Requirements: 6.1, 6.4_

- [x] 23.1 Create evaluation form view
  - Display score input fields for technical, design, documentation, presentation
  - Add comment textarea
  - Show validation errors
  - Display calculated total score preview
  - _Requirements: 6.1_

- [x] 23.2 Create evaluation display component
  - Show all evaluations for a project
  - Display evaluator name, scores, total score, and comments
  - Display average score across all evaluations
  - _Requirements: 6.4_

- [x] 24. Create comment views
  - _Requirements: 8.1, 8.2, 8.3, 8.4_

- [x] 24.1 Create comment section component
  - Display all comments with user name and timestamp
  - Add comment form for authenticated users
  - Add delete button for admins
  - _Requirements: 8.1, 8.2, 8.3, 8.4, 8.5_

- [x] 25. Create admin views for system management
  - _Requirements: 1.4, 9.1, 9.2, 9.4_

- [x] 25.1 Create user management views
  - Create index view listing all users
  - Create create/edit forms for user management
  - Add role and department fields
  - _Requirements: 1.4_

- [x] 25.2 Create category management views
  - Create index view listing all categories
  - Create create/edit forms for categories
  - Add delete confirmation with warning if in use
  - _Requirements: 9.1, 9.2, 9.4, 9.5_

- [x] 25.3 Create tag management views
  - Create index view listing all tags
  - Create simple add tag form
  - Add delete option
  - _Requirements: 9.3_

- [x] 26. Create report views
  - _Requirements: 13.1, 13.2, 13.3, 13.4_

- [x] 26.1 Create reports index view
  - Display projects by year chart/table
  - Display projects by category chart/table
  - Display average scores by category
  - Display projects per advisor table
  - Add export buttons for Excel/PDF
  - _Requirements: 13.1, 13.2, 13.3, 13.4_

- [x] 27. Create profile management views
  - _Requirements: 2.1, 2.2, 2.3_

- [x] 27.1 Create profile edit view
  - Display current profile information
  - Add form fields for name, department, profile picture
  - Show profile picture preview
  - Add validation error display
  - _Requirements: 2.1, 2.2, 2.3, 2.4_

- [x] 28. Create error pages
  - _Requirements: Various error handling requirements_

- [x] 28.1 Create custom error views
  - Create 403 unauthorized page
  - Create 404 not found page
  - Create 500 server error page
  - Style with TailwindCSS
  - _Requirements: Error handling_

- [x] 29. Define web routes
  - _Requirements: All routing requirements_

- [x] 29.1 Define public routes
  - Home route to projects index
  - Authentication routes (login, register, logout)
  - _Requirements: 1.1, 1.2, 1.6, 15.1_

- [x] 29.2 Define authenticated routes
  - Dashboard route
  - Profile routes
  - Project routes (resource routes plus custom actions)
  - File upload/download/delete routes
  - Team member routes
  - Evaluation routes
  - Comment routes
  - _Requirements: Various authenticated features_

- [x] 29.3 Define admin routes with middleware
  - User management routes
  - Category management routes
  - Tag management routes
  - Report routes
  - Activity log routes
  - _Requirements: 1.4, 9.1, 13.1, 14.2_

- [x] 30. Configure file storage
  - _Requirements: 2.3, 5.2_

- [x] 30.1 Create storage directory structure
  - Create profiles directory for profile pictures
  - Create projects directory for project files
  - Run php artisan storage:link command
  - _Requirements: 2.3, 5.2_

- [x] 30.2 Configure filesystem settings
  - Update config/filesystems.php if needed
  - Set proper permissions
  - _Requirements: 5.2_

- [x] 31. Implement middleware for role-based access
  - _Requirements: 1.3, 1.4_

- [x] 31.1 Create CheckRole middleware
  - Implement handle method to check user role
  - Return 403 if unauthorized
  - _Requirements: 1.3_

- [x] 31.2 Register middleware in bootstrap/app.php
  - Add middleware alias
  - Apply to admin routes
  - _Requirements: 1.3, 1.4_

- [x] 32. Add bilingual support
  - _Requirements: Various bilingual requirements_

- [x] 32.1 Create language files
  - Create resources/lang/en/messages.php
  - Create resources/lang/th/messages.php
  - Add common translations
  - _Requirements: Bilingual support_

- [x] 32.2 Update views to use translations
  - Replace hardcoded text with __() helper
  - Add language switcher to navigation
  - _Requirements: Bilingual support_

- [x] 33. Run migrations and seed database
  - _Requirements: All database requirements_

- [x] 33.1 Run migrations
  - Execute php artisan migrate
  - Verify all tables created correctly
  - _Requirements: All database requirements_

- [x] 33.2 Run seeders
  - Execute php artisan db:seed
  - Verify sample data created
  - _Requirements: 1.1, 9.1, 9.3_

- [x] 34. Configure TailwindCSS
  - _Requirements: All styling requirements_

- [x] 34.1 Update tailwind.config.js
  - Configure custom colors and theme
  - Add custom utilities if needed
  - _Requirements: Styling_

- [x] 34.2 Build CSS assets
  - Run npm install
  - Run npm run build
  - Verify styles applied correctly
  - _Requirements: Styling_

- [x] 35. Implement guest access restrictions
  - _Requirements: 15.1, 15.2, 15.3, 15.4_

- [x] 35.1 Update ProjectController index for guest access
  - Show only completed/published projects to guests
  - Show all projects to authenticated users based on role
  - _Requirements: 15.1, 15.4_

- [x] 35.2 Update ProjectController show for guest access
  - Allow guests to view published projects
  - Hide evaluation details from guests
  - Hide comment form from guests
  - _Requirements: 15.2, 15.3_

- [x] 36. Add flash messages for user feedback
  - _Requirements: Various user feedback requirements_

- [x] 36.1 Implement flash messages in controllers
  - Add success messages for create, update, delete actions
  - Add error messages for failed operations
  - _Requirements: User feedback_

- [x] 36.2 Display flash messages in layout
  - Use alert component to display messages
  - Style with TailwindCSS (success, error, warning, info)
  - _Requirements: User feedback_
