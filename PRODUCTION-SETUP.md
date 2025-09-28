# Gem Shop Management System - Production Setup

This document provides instructions for setting up the Gem Shop Management System for production use by removing all test data and creating proper administrative users.

## 🚀 Quick Setup

### Option 1: Using PowerShell (Recommended)
```powershell
.\setup-production.ps1
```

### Option 2: Using Batch File
```cmd
setup-production.bat
```

### Option 3: Using Artisan Command
```bash
php artisan db:seed --class=ProductionDataSeeder
```

## 📋 What This Setup Does

### ✅ Data Cleanup
- Removes all test data from all tables
- Clears transaction history
- Removes sample items, customers, suppliers
- Cleans up test users and related data

### 👥 Administrative Users Created
- **System Administrator**
  - Email: `admin@gemshop.com`
  - Password: `Admin@2024!`
  - Role: Admin (Full system access)

- **Operations Manager**
  - Email: `manager@gemshop.com`
  - Password: `Manager@2024!`
  - Role: Manager (Limited administrative access)

### 💱 System Configuration
- Sets up essential currencies (LKR, USD, EUR)
- Configures LKR as base currency
- Sets appropriate exchange rates

## 🔐 Security Recommendations

### ⚠️ IMPORTANT: Change Default Passwords
After first login, immediately change the default passwords for both admin users:

1. Log in with the provided credentials
2. Navigate to User Management
3. Edit each user profile
4. Set strong, unique passwords

### 🔒 Password Requirements
- Minimum 8 characters
- Mix of uppercase, lowercase, numbers, and symbols
- Avoid common words or patterns
- Use unique passwords for each account

## 🗂️ Database Tables Cleaned

The following tables are completely cleared during setup:
- `transaction_items`
- `stock_movements`
- `customer_returns`
- `supplier_returns`
- `sales_orders`
- `invoices`
- `grns`
- `purchase_orders`
- `items`
- `customers`
- `suppliers`
- `sales_executives`
- `craftsmen`
- `tour_guides`
- `banks`
- `users`

## 🎯 Post-Setup Tasks

1. **Change Default Passwords** (Critical)
2. **Configure System Settings**
   - Update company information
   - Set tax rates
   - Configure payment terms
3. **Add Real Data**
   - Create actual suppliers
   - Add real customers
   - Input inventory items
4. **Set User Permissions**
   - Configure role-based access
   - Assign appropriate permissions
5. **Test System Functions**
   - Verify all modules work correctly
   - Test data entry and reporting

## 🛠️ Troubleshooting

### If Setup Fails
1. Check database connection
2. Ensure all migrations are run: `php artisan migrate`
3. Verify file permissions
4. Check Laravel logs in `storage/logs/`

### If Users Cannot Login
1. Verify user accounts exist in database
2. Check password hashing
3. Clear application cache: `php artisan cache:clear`

## 📞 Support

For technical support or questions about the production setup:
- Check the application logs
- Review the Laravel documentation
- Contact your system administrator

---

**⚠️ WARNING**: This setup will permanently delete all existing data. Make sure to backup your database before running if you have important data you want to preserve.
