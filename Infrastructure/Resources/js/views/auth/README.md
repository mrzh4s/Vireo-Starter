# Authentication Module

This directory contains all authentication-related components and forms.

## Sign Up Form Structure

The Sign Up form has been organized into logical sections for better UX and clarity:

### Form Sections

#### 1. User Details
Personal information about the user:
- **First Name** (required) - Letters, spaces, hyphens, and apostrophes only
- **Last Name** (required) - Letters, spaces, hyphens, and apostrophes only
- **Company/Domain** (optional) - Organization or company name

#### 2. Account Details
Login credentials:
- **Email Address** (required) - Valid email format, max 255 characters
- **Password** (required) - Minimum requirements:
  - At least 8 characters
  - At least one uppercase letter
  - At least one lowercase letter
  - At least one number
  - At least one special character
- **Confirm Password** (required) - Must match the password

#### 3. Terms & Conditions
- **Terms Acceptance** (required) - User must accept terms and privacy policy

### Backend Integration

The form submits the following data to `/register`:

```javascript
{
  name: "John Doe (Acme Inc.)",  // Combined: firstName + lastName + (domain if provided)
  email: "john.doe@example.com",  // Lowercased automatically
  password: "SecureP@ss123",
  password_confirmation: "SecureP@ss123",
  terms: 1  // Boolean converted to 1/0
}
```

### Features

✅ **Server-Side Validation Integration**
- Automatically displays backend validation errors
- Maps server errors to form fields
- Real-time error feedback

✅ **Enhanced User Experience**
- Organized sections with headers and descriptions
- Password visibility toggles
- Responsive grid layout for name fields
- Auto-complete attributes for browser autofill
- Clear password requirements displayed

✅ **Improved Validation**
- Strong password requirements (uppercase, lowercase, number, special char)
- Name validation (only letters, spaces, hyphens, apostrophes)
- Email normalization (auto-lowercase)
- Domain/company field for business users

✅ **Better Error Handling**
- Display server errors in form fields
- Global error alert for general issues
- Form reset on successful registration
- Proper loading states

### Usage Example

```jsx
import SignUpPage from '@/views/auth/pages/SignUp';

// The component uses AuthLayout with 'classic' variant
SignUpPage.layout = (page) => (
  <AuthLayout variant="classic" title="Sign Up - Vireo">
    {page}
  </AuthLayout>
);
```

### Validation Schema

Located in `/views/auth/forms/signup-schema.js`

- Uses Zod for client-side validation
- Matches backend validation rules
- Provides detailed error messages
- Password confirmation validation
- Optional domain field

### Backend Expected Fields

From `RegisterController.php`:

| Field | Type | Rules |
|-------|------|-------|
| name | string | required, min:2, max:255 |
| email | string | required, email, max:255 |
| password | string | required, min:8 |
| password_confirmation | string | required, same:password |
| terms | boolean | required |

### Data Flow

1. User fills out the form with sections:
   - User Details (firstName, lastName, domain)
   - Account Details (email, password, confirmPassword)
   - Terms acceptance

2. On submit:
   - Client-side validation via Zod
   - Combine firstName + lastName (+ domain if provided) → name
   - Email converted to lowercase
   - Terms converted to 1/0

3. POST to `/register`:
   ```
   {
     name: "John Doe (Acme Inc.)",
     email: "john.doe@example.com",
     password: "SecureP@ss123",
     password_confirmation: "SecureP@ss123",
     terms: 1
   }
   ```

4. Backend validates and creates user

5. On success: Redirect to `/auth/signin`

6. On error: Display errors in form fields or alert

## Other Auth Pages

- **SignIn** - Login with email/password
- **ResetPassword** - Request password reset link
- **ChangePassword** - Set new password with token
- **Dashboard** - Post-login user dashboard

All auth pages use the unified `AuthLayout` with variants:
- `classic` - Background image style
- `branded` - Split-screen with branding
- `simple` - Clean centered style
