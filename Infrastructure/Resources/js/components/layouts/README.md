# Layout Components

This directory contains all layout components for the Vireo application. Each layout serves a specific purpose and provides consistent structure across different pages.

## Available Layouts

### 1. AppLayout
**Purpose:** Main application layout with sidebar navigation and header
**Use Case:** Dashboard pages, main application views
**Features:**
- Responsive sidebar (collapsible on desktop, drawer on mobile)
- Header with logo and navigation
- Footer
- LayoutProvider context for sidebar state management

**Example:**
```jsx
import { AppLayout } from '@/components/layouts';

export default function Dashboard() {
  return (
    <AppLayout>
      <div className="container mx-auto px-4 py-8">
        {/* Your page content */}
      </div>
    </AppLayout>
  );
}
```

### 2. BlankLayout
**Purpose:** Minimal layout with no navigation or chrome
**Use Case:** Landing pages, error pages, standalone views
**Features:**
- Clean background
- Only includes Helmet for page title
- Full control over content

**Example:**
```jsx
import { BlankLayout } from '@/components/layouts';

export default function ErrorPage() {
  return (
    <BlankLayout title="404 - Page Not Found">
      <div className="min-h-screen flex items-center justify-center">
        {/* Your error page content */}
      </div>
    </BlankLayout>
  );
}
```

### 3. AuthLayout
**Purpose:** Flexible authentication layout with multiple variants
**Use Case:** Login, register, forgot password, reset password pages
**Features:**
- Three variants: simple, classic, branded
- Logo display option
- Card wrapper with consistent styling
- Background image support (classic & branded)

**Variants:**

**Simple** (default):
- Clean, centered layout
- Optional logo above form
- Muted background
- Card wrapper

**Classic**:
- Background image with dark mode support
- Logo at top
- Card wrapper
- Consistent with original ClassicLayout

**Branded**:
- Split-screen design
- Branded side with background image
- Form on opposite side
- Perfect for marketing-focused auth pages

**Props:**
- `title`: Page title (default: 'Vireo - Authentication')
- `variant`: 'simple' | 'classic' | 'branded' (default: 'simple')
- `showLogo`: Display logo (default: true)
- `showCard`: Wrap content in Card (default: true, simple variant only)

**Examples:**

Simple variant:
```jsx
import { AuthLayout } from '@/components/layouts';

export default function Login() {
  return (
    <AuthLayout title="Login - Vireo" variant="simple">
      <Card>
        <CardContent className="p-6">
          {/* Your login form */}
        </CardContent>
      </Card>
    </AuthLayout>
  );
}
```

Classic variant (with background):
```jsx
SignInPage.layout = (page) => (
  <AuthLayout variant="classic" title="Sign In - Vireo">
    {page}
  </AuthLayout>
);
```

Branded variant (split-screen):
```jsx
SignUpPage.layout = (page) => (
  <AuthLayout variant="branded" title="Sign Up - Vireo">
    {page}
  </AuthLayout>
);
```

### 4. FullWidthLayout
**Purpose:** Layout without sidebar but with header
**Use Case:** Reports, analytics dashboards, wide content views
**Features:**
- Mobile header (same as AppLayout)
- No sidebar on any screen size
- Footer included
- Full-width content area

**Example:**
```jsx
import { FullWidthLayout } from '@/components/layouts';

export default function Reports() {
  return (
    <FullWidthLayout title="Reports - Vireo">
      <div className="container-fluid px-6 py-8">
        {/* Your wide content */}
      </div>
    </FullWidthLayout>
  );
}
```

### 5. CenteredLayout
**Purpose:** Vertically and horizontally centered content
**Use Case:** Welcome pages, onboarding flows, success pages
**Features:**
- Centered content with configurable max-width
- Optional background color
- Responsive padding

**Props:**
- `title`: Page title (string)
- `maxWidth`: Max width class (default: 'max-w-4xl')
- `showBackground`: Show muted background (default: true)

**Example:**
```jsx
import { CenteredLayout } from '@/components/layouts';

export default function Welcome() {
  return (
    <CenteredLayout
      title="Welcome - Vireo"
      maxWidth="max-w-2xl"
      showBackground={true}
    >
      {/* Your centered content */}
    </CenteredLayout>
  );
}
```

## Layout Architecture

### Directory Structure
```
layouts/
├── AppLayout.jsx           # Main app layout with sidebar
├── BlankLayout.jsx         # Minimal layout
├── AuthLayout.jsx          # Centered auth layout
├── FullWidthLayout.jsx     # Full-width without sidebar
├── CenteredLayout.jsx      # Centered content layout
├── index.js                # Layout exports
├── components/             # Layout sub-components
│   ├── header.jsx
│   ├── sidebar.jsx
│   ├── footer.jsx
│   ├── main.jsx
│   └── ...
└── context/                # Layout context providers
    └── layout-provider.jsx
```

### Context Provider

The `LayoutProvider` manages sidebar state across the application:

```jsx
import { useLayout } from '@/components/layouts/context/layout-provider';

function MyComponent() {
  const { sidebarOpen, toggleSidebar, mobileMenuOpen, toggleMobileMenu } = useLayout();

  return (
    <button onClick={toggleSidebar}>
      Toggle Sidebar
    </button>
  );
}
```

## Choosing a Layout

| Page Type | Recommended Layout |
|-----------|-------------------|
| Dashboard | AppLayout |
| Settings | AppLayout |
| User Profile | AppLayout |
| Login/Register | AuthLayout |
| Landing Page | BlankLayout |
| Error Pages (404, 500) | BlankLayout or CenteredLayout |
| Reports/Analytics | FullWidthLayout or AppLayout |
| Onboarding | CenteredLayout |
| Success/Confirmation | CenteredLayout |

## Customization

All layouts accept a `title` prop for setting the page title via React Helmet. Some layouts accept additional props for customization.

### Common Props
- `title`: Sets the page title (default varies by layout)
- `children`: Page content (required)

### Layout-Specific Props
- **CenteredLayout:**
  - `maxWidth`: Tailwind max-width class
  - `showBackground`: Boolean for background color

## Best Practices

1. **Import from the index file:**
   ```jsx
   import { AppLayout, BlankLayout } from '@/components/layouts';
   ```

2. **Set meaningful titles:**
   ```jsx
   <AppLayout title="User Settings - Vireo">
   ```

3. **Use semantic HTML:**
   - Layouts provide the outer structure
   - Your content should use semantic elements (section, article, etc.)

4. **Maintain consistency:**
   - Use the same layout for similar page types
   - Don't mix layout patterns without reason

5. **Responsive considerations:**
   - AppLayout and FullWidthLayout handle mobile responsiveness
   - Test your content in all layouts on different screen sizes

## Migration Examples

### From Inline Layout to AppLayout

Before (inline layout):
```jsx
export default function Dashboard() {
  return (
    <div className="min-h-screen bg-background">
      <header>{/* Custom header */}</header>
      <main>{/* Content */}</main>
    </div>
  );
}
```

After (using AppLayout):
```jsx
import { AppLayout } from '@/components/layouts';

export default function Dashboard() {
  return (
    <AppLayout>
      <div className="container mx-auto px-4 py-8">
        {/* Content */}
      </div>
    </AppLayout>
  );
}
```

### From ClassicLayout to AuthLayout

The old `ClassicLayout` from `@/views/auth/layouts/classic` is now replaced by `AuthLayout` with `variant="classic"`.

Before:
```jsx
import { ClassicLayout } from '../layouts/classic';

function SignUpPage() {
  // ... your component
}

SignUpPage.layout = ClassicLayout;
export default SignUpPage;
```

After:
```jsx
import { AuthLayout } from '@/components/layouts';

function SignUpPage() {
  // ... your component
}

SignUpPage.layout = (page) => (
  <AuthLayout variant="classic" title="Sign Up - Vireo">
    {page}
  </AuthLayout>
);

export default SignUpPage;
```

### From BrandedLayout to AuthLayout

The old `BrandedLayout` from `@/views/auth/layouts/branded` is now replaced by `AuthLayout` with `variant="branded"`.

Before:
```jsx
import { BrandedLayout } from '../layouts/branded';

function SignInPage() {
  // ... your component
}

SignInPage.layout = BrandedLayout;
export default SignInPage;
```

After:
```jsx
import { AuthLayout } from '@/components/layouts';

function SignInPage() {
  // ... your component
}

SignInPage.layout = (page) => (
  <AuthLayout variant="branded" title="Sign In - Vireo">
    {page}
  </AuthLayout>
);

export default SignInPage;
```

## Deprecation Notice

The following layouts are deprecated and should be migrated to the new unified layout system:

- `@/views/auth/layouts/classic` → Use `AuthLayout` with `variant="classic"`
- `@/views/auth/layouts/branded` → Use `AuthLayout` with `variant="branded"`

These old layout files can be safely removed after migration.
