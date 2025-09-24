# Expense Tracker

A modern expense tracking application built with Laravel, Vue.js, Inertia.js, and TypeScript. Features automatic expense categorization, filtering, and visual analytics.

## Features

- ✅ **Expense Management**: Create, read, update, and delete expenses
- ✅ **Auto-categorization**: Automatic expense categorization based on keywords
- ✅ **Filtering**: Filter expenses by category, date, and date ranges
- ✅ **Visual Analytics**: Pie chart showing expenses by category
- ✅ **RESTful API**: Complete API endpoints for all operations
- ✅ **Responsive Design**: Mobile-friendly interface using Tailwind CSS
- ✅ **Type Safety**: Full TypeScript support on the frontend
- ✅ **Testing**: Comprehensive unit and feature tests

## Tech Stack

**Backend:**
- Laravel 10
- PostgreSQL
- PHP 8.1+

**Frontend:**
- Vue.js 3
- Inertia.js
- TypeScript
- Tailwind CSS
- Heroicons

**DevOps:**
- Docker & Docker Compose
- Vite for asset bundling

## Auto-categorization Rules

The application automatically categorizes expenses based on keywords in the description:

| Category | Keywords |
|----------|----------|
| **Transport** | Grab, Gojek, Uber, Taxi, Fuel, Gas Station |
| **Food & Drink** | Starbucks, McDonald's, KFC, Restaurant, Coffee, Food, Lunch, Dinner, Breakfast |
| **Shopping** | Shopee, Tokopedia, Mall, Store, Shopping |
| **Entertainment** | Netflix, Spotify, Cinema, Movie, Game |
| **Health** | Hospital, Doctor, Pharmacy, Medicine |
| **Others** | Default fallback for unmatched descriptions |

## Quick Start with Docker

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd expense-tracker
   ```

2. **Copy environment file**
   ```bash
   cp .env.example .env
   ```

3. **Start the application**
   ```bash
   docker-compose up -d --build
   ```

4. **Install dependencies and setup database**
   ```bash
   # Install PHP dependencies
   docker-compose exec app composer install
   
   # Generate application key
   docker-compose exec app php artisan key:generate
   
   # Run migrations
   docker-compose exec app php artisan migrate
   
   # Seed database with sample data
   docker-compose exec app php artisan db:seed --class=ExpenseSeeder
   ```

5. **Install Node dependencies and build assets**
   ```bash
   # Install Node dependencies
   npm install
   
   # Build assets for development
   npm run dev
   
   # Or build for production
   npm run build
   ```

6. **Access the application**
   - Web interface: http://localhost:8080
   - API: http://localhost:8080/api

## Local Development Setup

### Prerequisites
- PHP 8.1+
- Composer
- Node.js 18+
- PostgreSQL 15+

### Installation

1. **Install PHP dependencies**
   ```bash
   composer install
   ```

2. **Install Node dependencies**
   ```bash
   npm install
   ```

3. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database setup**
   ```bash
   # Create database
   createdb expense_tracker
   
   # Run migrations
   php artisan migrate
   
   # Seed with sample data
   php artisan db:seed --class=ExpenseSeeder
   ```

5. **Start development servers**
   ```bash
   # Laravel development server
   php artisan serve
   
   # Vite development server (in another terminal)
   npm run dev
   ```

## API Endpoints

### Expenses
- `GET /api/expenses` - List all expenses with optional filters
- `POST /api/expenses` - Create a new expense
- `GET /api/expenses/{id}` - Get a specific expense
- `PUT /api/expenses/{id}` - Update an expense
- `DELETE /api/expenses/{id}` - Delete an expense
- `GET /api/expenses-stats` - Get expense statistics

### Query Parameters
- `category` - Filter by category
- `date` - Filter by specific date (YYYY-MM-DD)
- `start_date` - Filter by start date
- `end_date` - Filter by end date

### Example API Usage

```bash
# Create an expense
curl -X POST http://localhost:8000/api/expenses \
  -H "Content-Type: application/json" \
  -d '{
    "amount": 25.50,
    "description": "Grab ride to office",
    "date": "2025-01-15"
  }'

# Get expenses filtered by category
curl "http://localhost:8000/api/expenses?category=Transport"

# Get expenses for a specific date
curl "http://localhost:8000/api/expenses?date=2025-01-15"
```

## Testing

Run the test suite:

```bash
# Run all tests
php artisan test

# Run specific test files
php artisan test tests/Feature/ExpenseTest.php
php artisan test tests/Unit/ExpenseModelTest.php

# Run tests with coverage
php artisan test --coverage
```

### Test Coverage

- **Feature Tests**: API endpoints, validation, filtering
- **Unit Tests**: Model methods, auto-categorization logic
- **Integration Tests**: Database interactions, business logic

## Project Structure

```
expense-tracker/
├── app/
│   ├── Http/Controllers/
│   │   └── ExpenseController.php
│   └── Models/
│       └── Expense.php
├── database/
│   ├── factories/
│   │   └── ExpenseFactory.php
│   ├── migrations/
│   │   └── *_create_expenses_table.php
│   └── seeders/
│       └── ExpenseSeeder.php
├── resources/
│   ├── css/
│   │   └── app.css
│   └── js/
│       ├── Layouts/
│       │   └── AppLayout.vue
│       ├── Pages/
│       │   └── Expenses/
│       │       ├── Index.vue
│       │       ├── Create.vue
│       │       └── Edit.vue
│       ├── app.ts
│       └── bootstrap.js
├── routes/
│   ├── api.php
│   └── web.php
├── tests/
│   ├── Feature/
│   │   └── ExpenseTest.php
│   └── Unit/
│       └── ExpenseModelTest.php
├── docker-compose.yml
├── Dockerfile
└── README.md
```

## Environment Variables

Key environment variables in `.env`:

```bash
APP_NAME="Expense Tracker"
APP_URL=http://localhost:8000

DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_DATABASE=expense_tracker
DB_USERNAME=postgres
DB_PASSWORD=password
```

## Known Limitations

1. **Single User**: Currently designed for single-user use, no authentication system
2. **Basic Charts**: Simple canvas-based pie chart, could be enhanced with Chart.js or similar
3. **No Bulk Operations**: No support for bulk import/export of expenses
4. **Limited Categories**: Fixed set of categories, no custom category creation
5. **No Receipts**: No ability to attach receipt images or files
6. **No Budgeting**: No budget tracking or spending limits
7. **No Recurring Expenses**: No support for recurring/scheduled expenses

## Future Improvements

Given more time, I would add:

### Short Term
- **Enhanced Charting**: Replace canvas charts with Chart.js for better interactivity
- **Bulk Operations**: CSV import/export functionality
- **Receipt Uploads**: File attachment support for receipts
- **Search**: Full-text search across expense descriptions
- **Better Mobile UX**: More mobile-optimized interface

### Medium Term
- **User Authentication**: Multi-user support with Laravel Sanctum
- **Custom Categories**: Allow users to create and manage custom categories
- **Budgeting System**: Monthly/yearly budget tracking with alerts
- **Recurring Expenses**: Support for recurring/scheduled expenses
- **Advanced Analytics**: Trends, comparisons, and detailed reporting

### Long Term
- **Mobile App**: React Native or Flutter mobile application
- **Integrations**: Bank account/credit card API integrations
- **AI Categorization**: Machine learning for better auto-categorization
- **Multi-currency**: Support for multiple currencies with conversion
- **Team Features**: Shared expenses for teams or families

## Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## License

This project is open-sourced software licensed under the [MIT license](LICENSE).

## Support

If you encounter any issues or have questions, please:
1. Check the existing issues on GitHub
2. Create a new issue with detailed information
3. Include steps to reproduce any bugs

---

**Happy expense tracking! 💰📊**