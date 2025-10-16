# 📚 Student Project Repository System (SPRS)

A comprehensive web-based system for managing student projects throughout their lifecycle, from creation to evaluation. Built with Laravel 12, this system provides role-based access control for students, advisors, committee members, and administrators.

[![Laravel](https://img.shields.io/badge/Laravel-12.x-red.svg)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-blue.svg)](https://php.net)
[![TailwindCSS](https://img.shields.io/badge/TailwindCSS-3.x-38B2AC.svg)](https://tailwindcss.com)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)

## 🌟 Features

### 👨‍🎓 For Students
- Create and manage projects with bilingual support (Thai/English)
- Add team members to projects
- Upload project files (proposals, reports, presentations, code)
- Submit projects for advisor review
- Track project status through workflow
- View evaluation scores and feedback
- Comment on projects

### 👨‍🏫 For Advisors
- Review assigned student projects
- Approve or reject submitted projects
- Evaluate projects with detailed scoring
- Provide feedback through comments
- View comprehensive project reports
- Track student progress

### 👔 For Committee Members
- Evaluate approved projects
- Provide independent scoring and feedback
- View project statistics and reports
- Access all approved/completed projects

### 👨‍💼 For Administrators
- Manage users (create, edit, delete)
- Manage categories and tags
- View system-wide statistics
- Access all projects and data
- Monitor system activity logs
- Generate comprehensive reports

## 🎯 Key Capabilities

- **Role-Based Access Control**: Four distinct roles with specific permissions
- **Project Workflow**: Draft → Submitted → Approved/Rejected → Completed
- **File Management**: Secure file upload and storage system
- **Evaluation System**: Multi-criteria scoring with automatic calculation
- **Search & Filter**: Advanced search with multiple filter options
- **Bilingual Support**: Thai and English interface and content
- **Activity Logging**: Comprehensive audit trail
- **Responsive Design**: Mobile-first approach with TailwindCSS
- **Flash Messages**: User-friendly feedback for all actions

## 🛠️ Technology Stack

- **Framework**: Laravel 12.x
- **PHP**: 8.2+
- **Database**: SQLite (easily switchable to MySQL/PostgreSQL)
- **Frontend**: Blade Templates + TailwindCSS + Alpine.js
- **Authentication**: Laravel Breeze
- **File Storage**: Local storage with symbolic links

## 📋 Requirements

- PHP >= 8.2
- Composer
- Node.js & NPM
- SQLite3 (or MySQL/PostgreSQL)

## 🚀 Installation

### 1. Clone the Repository

```bash
git clone <repository-url>
cd student-project-repository
```

### 2. Install Dependencies

```bash
# Install PHP dependencies
composer install

# Install Node dependencies
npm install
```

### 3. Environment Setup

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Database Setup

```bash
# Create SQLite database
touch database/database.sqlite

# Run migrations
php artisan migrate

# (Optional) Seed database with sample data
php artisan db:seed
```

### 5. Storage Setup

```bash
# Create symbolic link for file storage
php artisan storage:link
```

### 6. Build Assets

```bash
# Development
npm run dev

# Production
npm run build
```

### 7. Start Development Server

```bash
php artisan serve
```

Visit `http://localhost:8000` in your browser.

## 👥 Default User Roles

After seeding, you can login with these accounts:

| Role | Email | Password | Description |
|------|-------|----------|-------------|
| Admin | admin@example.com | password | Full system access |
| Advisor | advisor@example.com | password | Review and approve projects |
| Committee | committee@example.com | password | Evaluate projects |
| Student | student@example.com | password | Create and manage projects |

## 📁 Project Structure

```
student-project-repository/
├── app/
│   ├── Http/
│   │   ├── Controllers/      # Application controllers
│   │   ├── Middleware/       # Custom middleware
│   │   └── Requests/         # Form request validation
│   ├── Models/               # Eloquent models
│   ├── Policies/             # Authorization policies
│   └── Helpers/              # Helper classes
├── database/
│   ├── migrations/           # Database migrations
│   ├── seeders/              # Database seeders
│   └── database.sqlite       # SQLite database file
├── resources/
│   ├── views/                # Blade templates
│   │   ├── auth/            # Authentication views
│   │   ├── projects/        # Project views
│   │   ├── dashboard/       # Dashboard views
│   │   ├── admin/           # Admin views
│   │   └── components/      # Reusable components
│   ├── css/                 # CSS files
│   └── js/                  # JavaScript files
├── routes/
│   ├── web.php              # Web routes
│   └── auth.php             # Authentication routes
├── storage/
│   └── app/
│       └── public/          # File uploads
│           ├── profiles/    # Profile pictures
│           └── projects/    # Project files
└── public/                  # Public assets
```

## 🔐 User Roles & Permissions

### Student
- ✅ Create projects
- ✅ Edit own draft projects
- ✅ Delete own draft projects
- ✅ Submit projects for review
- ✅ Add team members
- ✅ Upload files
- ✅ Comment on projects
- ❌ Approve/reject projects
- ❌ Evaluate projects

### Advisor
- ✅ View assigned projects
- ✅ Edit assigned projects
- ✅ Approve/reject submitted projects
- ✅ Evaluate projects
- ✅ Comment on projects
- ✅ View reports
- ❌ Delete projects
- ❌ Manage users

### Committee
- ✅ View approved/completed projects
- ✅ Evaluate projects
- ✅ Comment on projects
- ✅ View reports
- ❌ Create/edit/delete projects
- ❌ Approve/reject projects

### Admin
- ✅ Full system access
- ✅ Manage users (CRUD)
- ✅ Manage categories and tags
- ✅ View all projects
- ✅ Delete any project
- ✅ View all reports
- ✅ Access activity logs

## 📊 Database Schema

### Core Tables

- **users** - User accounts and profiles
- **projects** - Main project information
- **categories** - Project categories
- **tags** - Project tags
- **project_members** - Team membership
- **project_files** - File attachments
- **evaluations** - Project evaluations
- **comments** - Project comments
- **activity_logs** - System activity tracking

### Relationships

```
User ──┬─── createdProjects (1:N)
       ├─── advisedProjects (1:N)
       ├─── projectMemberships (1:N)
       ├─── evaluations (1:N)
       └─── comments (1:N)

Project ──┬─── creator (N:1)
          ├─── advisor (N:1)
          ├─── category (N:1)
          ├─── members (1:N)
          ├─── files (1:N)
          ├─── evaluations (1:N)
          ├─── comments (1:N)
          └─── tags (N:M)
```

## 🔄 Project Workflow

```
┌─────────┐
│  Draft  │ ← Student creates project
└────┬────┘
     │ Student submits
     ▼
┌───────────┐
│ Submitted │ ← Awaiting advisor review
└─────┬─────┘
      │
      ├─── Advisor approves ──→ ┌──────────┐
      │                         │ Approved │ ← Committee evaluates
      │                         └──────────┘
      │
      └─── Advisor rejects ───→ ┌──────────┐
                                │ Rejected │ ← Student can revise
                                └──────────┘
```

## 🎨 UI Components

### Reusable Blade Components

- `<x-alert>` - Flash messages with auto-dismiss
- `<x-button>` - Styled buttons
- `<x-card>` - Card containers
- `<x-badge>` - Status badges
- `<x-form-group>` - Form input groups
- `<x-project-card>` - Project display cards
- `<x-file-list>` - File attachment lists
- `<x-evaluation-display>` - Evaluation scores
- `<x-comment-section>` - Comment threads

### TailwindCSS Styling

- Responsive design (mobile-first)
- Custom color scheme
- Consistent spacing and typography
- Smooth transitions and animations

## 📝 API Endpoints (Web Routes)

### Authentication
- `GET /login` - Login page
- `POST /login` - Process login
- `POST /logout` - Logout
- `GET /register` - Registration page
- `POST /register` - Process registration

### Projects
- `GET /projects` - List projects
- `GET /projects/create` - Create form
- `POST /projects` - Store project
- `GET /projects/{id}` - View project
- `GET /projects/{id}/edit` - Edit form
- `PUT /projects/{id}` - Update project
- `DELETE /projects/{id}` - Delete project
- `PATCH /projects/{id}/submit` - Submit project
- `PATCH /projects/{id}/approve` - Approve project
- `PATCH /projects/{id}/reject` - Reject project

### Files
- `POST /projects/{id}/files` - Upload file
- `DELETE /projects/{id}/files/{fileId}` - Delete file
- `GET /projects/{id}/files/{fileId}/download` - Download file

### Evaluations
- `GET /projects/{id}/evaluations/create` - Evaluation form
- `POST /projects/{id}/evaluations` - Store evaluation

### Comments
- `POST /projects/{id}/comments` - Add comment
- `DELETE /comments/{id}` - Delete comment

### Admin
- `GET /admin/users` - Manage users
- `GET /admin/categories` - Manage categories
- `GET /admin/tags` - Manage tags

### Reports
- `GET /reports` - View reports

## 🧪 Testing

```bash
# Run all tests
php artisan test

# Run specific test suite
php artisan test --testsuite=Feature

# Run with coverage
php artisan test --coverage
```

## 🔧 Configuration

### Environment Variables

Key variables in `.env`:

```env
APP_NAME="Student Project Repository"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=sqlite
DB_DATABASE=/absolute/path/to/database.sqlite

FILESYSTEM_DISK=local
```

### Customization

- **Logo**: Replace `resources/views/components/application-logo.blade.php`
- **Colors**: Modify `tailwind.config.js`
- **Languages**: Edit files in `resources/lang/`
- **Email**: Configure mail settings in `.env`

## 📈 Performance Optimization

### Production Deployment

```bash
# Cache configuration
php artisan config:cache

# Cache routes
php artisan route:cache

# Cache views
php artisan view:cache

# Optimize autoloader
composer install --optimize-autoloader --no-dev

# Build assets for production
npm run build
```

### Database Optimization

- Indexes on frequently queried columns
- Eager loading to prevent N+1 queries
- Pagination for large result sets
- Query caching for static data

## 🔒 Security Features

- CSRF protection on all forms
- Password hashing with bcrypt
- SQL injection prevention (Eloquent ORM)
- XSS prevention (Blade escaping)
- File upload validation
- Role-based authorization
- Activity logging
- Session security

## 🌐 Internationalization

The system supports both Thai and English:

- **Interface**: Switch language using language selector
- **Content**: Projects have separate Thai/English fields
- **Messages**: All UI messages are translatable

### Adding New Languages

1. Create language directory: `resources/lang/{locale}/`
2. Copy message files from `en/` or `th/`
3. Translate all strings
4. Add language option to navigation

## 📚 Documentation

- [Design Document](.kiro/specs/student-project-repository/design.md)
- [Requirements](.kiro/specs/student-project-repository/requirements.md)
- [Implementation Tasks](.kiro/specs/student-project-repository/tasks.md)
- [Flash Messages Implementation](.kiro/specs/student-project-repository/flash-messages-implementation.md)
- [Bilingual Implementation](.kiro/specs/student-project-repository/bilingual-implementation-summary.md)

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## 🐛 Bug Reports

If you discover a bug, please create an issue with:
- Clear description of the problem
- Steps to reproduce
- Expected vs actual behavior
- Screenshots (if applicable)
- Environment details (PHP version, OS, etc.)

## 📄 License

This project is open-sourced software licensed under the [MIT license](LICENSE).

## 👨‍💻 Authors

Developed as part of a student project management system initiative.

## 🙏 Acknowledgments

- Laravel Framework
- TailwindCSS
- Alpine.js
- Laravel Breeze
- All contributors and testers

## 📞 Support

For support and questions:
- Create an issue on GitHub
- Check existing documentation
- Review the design document

---

**Built with ❤️ using Laravel 12**
