# Design Document

## Overview

The Student Project Repository System (SPRS) is a Laravel 12-based web application that manages the complete lifecycle of student projects. The system uses SQLite as its database, Blade templates for views, and TailwindCSS for styling. The architecture follows Laravel's MVC pattern with additional layers for authorization (Gates/Policies) and service classes for complex business logic.

### Key Design Principles

- **Role-Based Access Control (RBAC)**: Four distinct roles (Admin, Advisor, Student, Committee) with specific permissions
- **Status-Driven Workflow**: Projects progress through states (draft → submitted → approved/rejected → completed)
- **File Storage**: Local file storage using Laravel's storage system with symbolic links
- **Session-Based Authentication**: Using Laravel Breeze for authentication scaffolding
- **Responsive Design**: Mobile-first approach using TailwindCSS
- **Bilingual Support**: Thai and English fields for project titles and content

## Architecture

### High-Level Architecture

```
┌─────────────────────────────────────────────────────────────┐
│                        Browser (Client)                      │
└────────────────────────┬────────────────────────────────────┘
                         │ HTTP/HTTPS
┌────────────────────────▼────────────────────────────────────┐
│                    Laravel Application                       │
│  ┌──────────────────────────────────────────────────────┐  │
│  │              Routes (web.php)                        │  │
│  └────────────┬─────────────────────────────────────────┘  │
│               │                                              │
│  ┌────────────▼─────────────────────────────────────────┐  │
│  │           Middleware Layer                           │  │
│  │  - Authentication (auth)                             │  │
│  │  - CSRF Protection                                   │  │
│  │  - Session Management                                │  │
│  └────────────┬─────────────────────────────────────────┘  │
│               │                                              │
│  ┌────────────▼─────────────────────────────────────────┐  │
│  │              Controllers                             │  │
│  │  - AuthController                                    │  │
│  │  - ProjectController                                 │  │
│  │  - EvaluationController                              │  │
│  │  - etc.                                              │  │
│  └────────┬──────────────────────┬──────────────────────┘  │
│           │                      │                          │
│  ┌────────▼────────┐    ┌───────▼──────────┐              │
│  │   Policies/     │    │   Service Layer  │              │
│  │   Gates         │    │   (Optional)     │              │
│  └────────┬────────┘    └───────┬──────────┘              │
│           │                      │                          │
│  ┌────────▼──────────────────────▼──────────────────────┐  │
│  │              Eloquent Models                         │  │
│  │  - User, Project, Evaluation, etc.                  │  │
│  └────────────────────┬─────────────────────────────────┘  │
│                       │                                     │
│  ┌────────────────────▼─────────────────────────────────┐  │
│  │              SQLite Database                         │  │
│  │  database/database.sqlite                            │  │
│  └──────────────────────────────────────────────────────┘  │
│                                                              │
│  ┌──────────────────────────────────────────────────────┐  │
│  │           File Storage (storage/app/public)          │  │
│  │  - Profile pictures                                  │  │
│  │  - Project files (proposals, reports, code)          │  │
│  └──────────────────────────────────────────────────────┘  │
└──────────────────────────────────────────────────────────────┘
```

### Request Flow

1. **User Request** → Routes → Middleware (auth, CSRF)
2. **Controller** → Validates input, checks authorization
3. **Model/Service** → Business logic, database operations
4. **Response** → Blade view rendering with data
5. **Client** → Rendered HTML with TailwindCSS styling

## Components and Interfaces

### 1. Authentication System

**Component**: Laravel Breeze
- Provides login, registration, password reset
- Session-based authentication
- CSRF protection enabled by default

**Key Files**:
- `routes/auth.php` - Authentication routes
- `app/Http/Controllers/Auth/*` - Auth controllers
- `resources/views/auth/*` - Login/register views

### 2. Authorization System

**Gates** (for simple checks):
```php
// app/Providers/AppServiceProvider.php
Gate::define('manage-users', function (User $user) {
    return $user->role === 'admin';
});

Gate::define('evaluate-project', function (User $user) {
    return in_array($user->role, ['advisor', 'committee']);
});
```

**Policies** (for model-specific authorization):
```php
// app/Policies/ProjectPolicy.php
class ProjectPolicy
{
    public function update(User $user, Project $project)
    {
        // Student can update only their own draft projects
        // Advisor can update projects they advise
        return ($user->id === $project->created_by && $project->status === 'draft')
            || ($user->id === $project->advisor_id && $user->role === 'advisor');
    }
    
    public function delete(User $user, Project $project)
    {
        // Only draft projects can be deleted by owner or admin
        return ($user->id === $project->created_by && $project->status === 'draft')
            || $user->role === 'admin';
    }
    
    public function approve(User $user, Project $project)
    {
        // Only assigned advisor can approve
        return $user->id === $project->advisor_id && $user->role === 'advisor';
    }
}
```

### 3. Controllers

#### ProjectController
**Responsibilities**:
- CRUD operations for projects
- Status transitions (submit, approve, reject)
- Authorization checks using policies
- Pagination and filtering

**Key Methods**:
- `index()` - List projects with filters
- `create()` - Show create form
- `store()` - Save new project
- `show($id)` - Display project details
- `edit($id)` - Show edit form
- `update($id)` - Update project
- `destroy($id)` - Delete project
- `submit($id)` - Change status to submitted
- `approve($id)` - Change status to approved (advisor only)
- `reject($id)` - Change status to rejected (advisor only)

#### EvaluationController
**Responsibilities**:
- Create and store evaluations
- Calculate total scores
- Display evaluations for projects

**Key Methods**:
- `create($projectId)` - Show evaluation form
- `store($projectId)` - Save evaluation with score calculation

#### ProjectFileController
**Responsibilities**:
- Upload files to storage
- Delete files
- Validate file types and sizes

**Key Methods**:
- `store($projectId)` - Upload file
- `destroy($projectId, $fileId)` - Delete file

#### CommentController
**Responsibilities**:
- Add comments to projects
- Delete inappropriate comments (admin)

**Key Methods**:
- `store($projectId)` - Add comment
- `destroy($commentId)` - Delete comment

#### DashboardController
**Responsibilities**:
- Display role-specific dashboards
- Show statistics and quick actions

**Key Methods**:
- `index()` - Show dashboard based on user role

#### ReportController
**Responsibilities**:
- Generate various reports
- Export to Excel/PDF

**Key Methods**:
- `index()` - Show reports page
- `projectsByYear()` - Projects grouped by year
- `projectsByCategory()` - Projects grouped by category
- `averageScores()` - Calculate average evaluation scores
- `advisorProjects()` - Projects per advisor

### 4. Models and Relationships

#### User Model
```php
class User extends Authenticatable
{
    protected $fillable = ['name', 'email', 'password', 'role', 'department', 'profile_pic'];
    
    // Relationships
    public function createdProjects() // Projects created by student
    public function advisedProjects() // Projects where user is advisor
    public function projectMemberships() // Team memberships
    public function evaluations() // Evaluations given
    public function comments() // Comments made
    public function activityLogs() // Activity logs
    
    // Helper methods
    public function isAdmin()
    public function isAdvisor()
    public function isStudent()
    public function isCommittee()
}
```

#### Project Model
```php
class Project extends Model
{
    protected $fillable = [
        'title_th', 'title_en', 'abstract', 'year', 'semester',
        'status', 'category_id', 'created_by', 'advisor_id'
    ];
    
    // Relationships
    public function category()
    public function creator() // User who created
    public function advisor() // Assigned advisor
    public function members() // Team members
    public function files() // Attached files
    public function evaluations() // Evaluations received
    public function comments() // Comments on project
    public function tags() // Many-to-many
    
    // Scopes
    public function scopePublished($query)
    public function scopeByStatus($query, $status)
    public function scopeByYear($query, $year)
    public function scopeByCategory($query, $categoryId)
    
    // Helper methods
    public function canBeEditedBy(User $user)
    public function canBeDeletedBy(User $user)
    public function averageScore()
}
```

#### ProjectMember Model
```php
class ProjectMember extends Model
{
    protected $fillable = ['project_id', 'user_id', 'role_in_team'];
    
    public function project()
    public function user()
}
```

#### ProjectFile Model
```php
class ProjectFile extends Model
{
    protected $fillable = ['project_id', 'file_name', 'file_type', 'file_path'];
    
    public function project()
    
    // Helper methods
    public function getDownloadUrl()
    public function getFileSize()
}
```

#### Evaluation Model
```php
class Evaluation extends Model
{
    protected $fillable = [
        'project_id', 'evaluator_id', 'technical_score',
        'design_score', 'documentation_score', 'presentation_score',
        'total_score', 'comment'
    ];
    
    public function project()
    public function evaluator() // User who evaluated
    
    // Automatically calculate total_score
    protected static function boot()
    {
        parent::boot();
        static::saving(function ($evaluation) {
            $evaluation->total_score = (
                $evaluation->technical_score +
                $evaluation->design_score +
                $evaluation->documentation_score +
                $evaluation->presentation_score
            ) / 4;
        });
    }
}
```

#### Comment Model
```php
class Comment extends Model
{
    protected $fillable = ['project_id', 'user_id', 'content'];
    
    public function project()
    public function user()
}
```

#### Category Model
```php
class Category extends Model
{
    protected $fillable = ['name', 'description'];
    
    public function projects()
}
```

#### Tag Model
```php
class Tag extends Model
{
    protected $fillable = ['name'];
    
    public function projects() // Many-to-many
}
```

#### ActivityLog Model
```php
class ActivityLog extends Model
{
    protected $fillable = ['user_id', 'action', 'detail'];
    
    public function user()
}
```

## Data Models

### Database Schema

The system uses SQLite with the following tables:

1. **users** - User accounts and profiles
2. **categories** - Project categories
3. **projects** - Main project information
4. **project_members** - Team membership
5. **project_files** - File attachments
6. **evaluations** - Project evaluations
7. **comments** - Project comments
8. **tags** - Available tags
9. **project_tag** - Many-to-many pivot table
10. **activity_logs** - System activity tracking

### Migration Strategy

Migrations will be created in this order to respect foreign key constraints:

1. Create users table (Laravel default, modify for roles)
2. Create categories table
3. Create tags table
4. Create projects table (references users, categories)
5. Create project_members table (references projects, users)
6. Create project_files table (references projects)
7. Create evaluations table (references projects, users)
8. Create comments table (references projects, users)
9. Create project_tag pivot table (references projects, tags)
10. Create activity_logs table (references users)

### Indexes

For performance optimization:
- `projects.status` - Frequently filtered
- `projects.year` - Frequently filtered
- `projects.category_id` - Foreign key and filter
- `projects.advisor_id` - Foreign key and filter
- `projects.created_by` - Foreign key
- `evaluations.project_id` - Foreign key
- `comments.project_id` - Foreign key
- `project_tag.project_id` and `project_tag.tag_id` - Composite index

## Error Handling

### Validation

**Form Requests** for complex validation:
```php
// app/Http/Requests/StoreProjectRequest.php
class StoreProjectRequest extends FormRequest
{
    public function rules()
    {
        return [
            'title_th' => 'required|string|max:255',
            'title_en' => 'required|string|max:255',
            'abstract' => 'required|string',
            'year' => 'required|integer|min:2000|max:2100',
            'semester' => 'required|in:1,2,3',
            'category_id' => 'required|exists:categories,id',
            'advisor_id' => 'required|exists:users,id',
            'tags' => 'array',
            'tags.*' => 'exists:tags,id'
        ];
    }
}
```

### Exception Handling

**Custom Exceptions**:
- `UnauthorizedActionException` - When user attempts unauthorized action
- `InvalidProjectStatusException` - When status transition is invalid
- `FileUploadException` - When file upload fails

**Global Exception Handler** (bootstrap/app.php):
```php
->withExceptions(function (Exceptions $exceptions): void {
    $exceptions->render(function (UnauthorizedActionException $e, Request $request) {
        return response()->view('errors.unauthorized', [], 403);
    });
})
```

### User-Friendly Error Messages

- Validation errors displayed inline with form fields
- Flash messages for success/error operations
- Custom error pages (403, 404, 500)
- Logging of critical errors to `storage/logs/laravel.log`

## Testing Strategy

### Unit Tests

**Model Tests**:
- Test relationships are correctly defined
- Test model methods (e.g., `Project::averageScore()`)
- Test scopes work correctly
- Test automatic calculations (e.g., Evaluation total_score)

**Policy Tests**:
- Test authorization logic for each policy method
- Test edge cases (e.g., advisor trying to approve non-assigned project)

### Feature Tests

**Authentication Tests**:
- Test registration, login, logout flows
- Test password reset functionality
- Test role-based access to routes

**Project Workflow Tests**:
- Test complete project lifecycle (create → submit → approve → complete)
- Test file upload and deletion
- Test team member management
- Test status transitions

**Evaluation Tests**:
- Test evaluation creation
- Test score calculation
- Test authorization (only advisors/committee can evaluate)

**Search and Filter Tests**:
- Test search functionality
- Test filters (category, year, status)
- Test pagination

### Browser Tests (Optional)

Using Laravel Dusk for end-to-end testing:
- Test complete user journeys
- Test JavaScript interactions
- Test responsive design

### Test Database

Use in-memory SQLite for faster tests:
```php
// phpunit.xml
<env name="DB_CONNECTION" value="sqlite"/>
<env name="DB_DATABASE" value=":memory:"/>
```

## View Layer Design

### Layout Structure

**Master Layout** (`resources/views/layouts/app.blade.php`):
- Navigation bar (role-specific menu items)
- Flash message display area
- Main content area
- Footer

**Navigation Items by Role**:
- **Guest**: Home, Login, Register
- **Student**: Dashboard, My Projects, Browse Projects, Profile
- **Advisor**: Dashboard, Assigned Projects, Browse Projects, Profile
- **Committee**: Dashboard, Browse Projects, Evaluate, Profile
- **Admin**: Dashboard, Users, Categories, Tags, Reports, Projects

### Key Views

1. **Authentication Views**
   - `auth/login.blade.php`
   - `auth/register.blade.php`
   - `auth/forgot-password.blade.php`

2. **Dashboard Views**
   - `dashboard/index.blade.php` (role-specific content)

3. **Project Views**
   - `projects/index.blade.php` (list with search/filter)
   - `projects/create.blade.php` (create form)
   - `projects/edit.blade.php` (edit form)
   - `projects/show.blade.php` (detail page with files, evaluations, comments)

4. **Evaluation Views**
   - `evaluations/create.blade.php` (evaluation form)

5. **Report Views**
   - `reports/index.blade.php` (various reports)

6. **Admin Views**
   - `admin/users/index.blade.php`
   - `admin/categories/index.blade.php`
   - `admin/tags/index.blade.php`

### Component Strategy

**Blade Components** for reusable UI elements:
- `<x-alert>` - Flash messages
- `<x-card>` - Card container
- `<x-button>` - Styled buttons
- `<x-input>` - Form inputs
- `<x-select>` - Dropdowns
- `<x-project-card>` - Project display card
- `<x-file-list>` - File attachment list
- `<x-evaluation-display>` - Evaluation scores display

### TailwindCSS Integration

- Use Tailwind utility classes for styling
- Custom color scheme in `tailwind.config.js`
- Responsive breakpoints: mobile-first approach
- Dark mode support (optional)

## File Storage

### Storage Structure

```
storage/
  app/
    public/
      profiles/          # User profile pictures
      projects/          # Project files
        {project_id}/
          proposals/
          reports/
          presentations/
          code/
          other/
```

### File Upload Process

1. Validate file (type, size)
2. Generate unique filename
3. Store in appropriate directory
4. Save metadata to database
5. Return success/error response

### File Download

- Use Laravel's `Storage::download()` method
- Check authorization before allowing download
- Log download activity

### File Deletion

- Delete from storage
- Remove database record
- Log deletion activity

## Security Considerations

### Authentication & Authorization

- Passwords hashed using bcrypt
- CSRF protection on all forms
- Session-based authentication
- Role-based access control using Gates and Policies
- Middleware protection on routes

### File Upload Security

- Validate file types (whitelist approach)
- Limit file sizes
- Store files outside public directory
- Sanitize filenames
- Scan for malware (optional, using ClamAV)

### SQL Injection Prevention

- Use Eloquent ORM (parameterized queries)
- Validate all user inputs
- Use Form Requests for validation

### XSS Prevention

- Blade automatically escapes output (`{{ }}`)
- Use `{!! !!}` only for trusted content
- Sanitize user-generated content

### Activity Logging

- Log all critical actions (create, update, delete, approve)
- Log authentication events
- Store IP addresses for security auditing

## Performance Optimization

### Database Optimization

- Use eager loading to prevent N+1 queries
- Add indexes on frequently queried columns
- Use pagination for large result sets
- Cache frequently accessed data (categories, tags)

### Caching Strategy

```php
// Cache categories for 1 hour
$categories = Cache::remember('categories', 3600, function () {
    return Category::all();
});
```

### File Storage

- Use symbolic link for public access
- Consider CDN for production (optional)
- Implement lazy loading for images

### Query Optimization

```php
// Eager load relationships
$projects = Project::with(['creator', 'advisor', 'category', 'tags'])
    ->paginate(20);
```

## Deployment Considerations

### Environment Configuration

- Use `.env` file for environment-specific settings
- Set `APP_ENV=production` for production
- Enable caching in production (`php artisan config:cache`)

### Database Setup

- SQLite file must be writable
- Backup strategy for `database.sqlite`
- Consider migration to MySQL/PostgreSQL for large-scale deployment

### File Permissions

- `storage/` and `bootstrap/cache/` must be writable
- Create symbolic link: `php artisan storage:link`

### Optimization Commands

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

## Internationalization (i18n)

### Language Files

```
resources/
  lang/
    en/
      messages.php
      validation.php
    th/
      messages.php
      validation.php
```

### Usage in Views

```blade
{{ __('messages.welcome') }}
```

### Database Fields

- Store bilingual content in separate fields (title_th, title_en)
- Display based on user preference or app locale

## Future Extensibility

### API Support

- Add API routes in `routes/api.php`
- Use Laravel Sanctum for API authentication
- Return JSON responses for API endpoints

### Real-time Notifications

- Integrate Laravel Echo and Pusher
- Real-time updates for project approvals
- Real-time comments

### Advanced Search

- Integrate Laravel Scout for full-text search
- Use Meilisearch or Algolia backend

### Export Functionality

- Use Laravel Excel for Excel exports
- Use DomPDF or Snappy for PDF generation

### GitHub Integration

- OAuth integration for GitHub
- Automatic repository linking
- Code analysis integration
