import { useState, useEffect } from 'react';
import { zodResolver } from '@hookform/resolvers/zod';
import {
  AlertCircle,
  Eye,
  EyeOff,
  LoaderCircleIcon,
  Building2,
} from 'lucide-react';
import { useForm } from 'react-hook-form';
import { Link, router, usePage } from '@inertiajs/react';
import { Alert, AlertIcon, AlertTitle } from '@/components/ui/alert';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import {
  Form,
  FormControl,
  FormDescription,
  FormField,
  FormItem,
  FormLabel,
  FormMessage,
} from '@/components/ui/form';
import { Input } from '@/components/ui/input';
import { Separator } from '@/components/ui/separator';
import { getSignupSchema } from '../forms/signup-schema';
import { AuthLayout } from '@/components/layouts';

function SignUpPage() {
  const [passwordVisible, setPasswordVisible] = useState(false);
  const [confirmPasswordVisible, setConfirmPasswordVisible] = useState(false);
  const [isProcessing, setIsProcessing] = useState(false);
  const [error, setError] = useState(null);
  const { errors: serverErrors } = usePage().props;

  const form = useForm({
    resolver: zodResolver(getSignupSchema()),
    defaultValues: {
      firstName: '',
      lastName: '',
      domain: '',
      email: '',
      password: '',
      confirmPassword: '',
      terms: false,
    },
  });

  // Handle server-side validation errors
  useEffect(() => {
    if (serverErrors && Object.keys(serverErrors).length > 0) {
      Object.keys(serverErrors).forEach((key) => {
        const message = Array.isArray(serverErrors[key])
          ? serverErrors[key][0]
          : serverErrors[key];
        form.setError(key, { type: 'server', message });
      });
    }
  }, [serverErrors, form]);

  async function onSubmit(values) {
    setIsProcessing(true);
    setError(null);

    // Combine first name and last name with domain if provided
    const fullName = values.domain
      ? `${values.firstName} ${values.lastName} (${values.domain})`.trim()
      : `${values.firstName} ${values.lastName}`.trim();

    router.post('/register', {
      name: fullName,
      email: values.email.toLowerCase(),
      password: values.password,
      password_confirmation: values.confirmPassword,
      terms: values.terms ? 1 : 0,
    }, {
      onSuccess: () => {
        // Clear form on success
        form.reset();
        router.visit('/auth/signin');
      },
      onError: (errors) => {
        const errorMessage = errors.message || errors.email || 'An error occurred during registration. Please try again.';
        setError(errorMessage);
        setIsProcessing(false);
      },
      onFinish: () => {
        setIsProcessing(false);
      },
    });
  }

  return (
    <AuthLayout variant="classic" title="Sign Up - Vireo">
    <Form {...form}>
      <form
        onSubmit={form.handleSubmit(onSubmit)}
        className="block w-full space-y-5"
      >
        <div className="text-center space-y-1 pb-3">
          <h1 className="text-2xl font-semibold tracking-tight">Sign Up</h1>
          <p className="text-sm text-muted-foreground">
            Create your account to get started
          </p>
        </div>

        {error && (
          <Alert
            variant="destructive"
            appearance="light"
            onClose={() => setError(null)}
          >
            <AlertIcon>
              <AlertCircle />
            </AlertIcon>
            <AlertTitle>{error}</AlertTitle>
          </Alert>
        )}

        {/* User Details Section */}
        <div className="space-y-4">
          <div className="space-y-1">
            <h3 className="text-sm font-medium text-foreground">User Details</h3>
            <p className="text-xs text-muted-foreground">
              Provide your personal information
            </p>
          </div>

          <div className="grid grid-cols-2 gap-3">
            <FormField
              control={form.control}
              name="firstName"
              render={({ field }) => (
                <FormItem>
                  <FormLabel>First Name</FormLabel>
                  <FormControl>
                    <Input
                      placeholder="John"
                      autoComplete="given-name"
                      {...field}
                    />
                  </FormControl>
                  <FormMessage />
                </FormItem>
              )}
            />

            <FormField
              control={form.control}
              name="lastName"
              render={({ field }) => (
                <FormItem>
                  <FormLabel>Last Name</FormLabel>
                  <FormControl>
                    <Input
                      placeholder="Doe"
                      autoComplete="family-name"
                      {...field}
                    />
                  </FormControl>
                  <FormMessage />
                </FormItem>
              )}
            />
          </div>

          <FormField
            control={form.control}
            name="domain"
            render={({ field }) => (
              <FormItem>
                <FormLabel>
                  Company / Domain <span className="text-muted-foreground">(Optional)</span>
                </FormLabel>
                <FormControl>
                  <div className="relative">
                    <Building2 className="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground" />
                    <Input
                      placeholder="Acme Inc."
                      className="pl-9"
                      autoComplete="organization"
                      {...field}
                    />
                  </div>
                </FormControl>
                <FormDescription className="text-xs">
                  Your company or organization name
                </FormDescription>
                <FormMessage />
              </FormItem>
            )}
          />
        </div>

        <Separator className="my-4" />

        {/* Account Details Section */}
        <div className="space-y-4">
          <div className="space-y-1">
            <h3 className="text-sm font-medium text-foreground">Account Details</h3>
            <p className="text-xs text-muted-foreground">
              Set up your login credentials
            </p>
          </div>

          <FormField
            control={form.control}
            name="email"
            render={({ field }) => (
              <FormItem>
                <FormLabel>Email Address</FormLabel>
                <FormControl>
                  <Input
                    placeholder="john.doe@example.com"
                    type="email"
                    autoComplete="email"
                    {...field}
                  />
                </FormControl>
                <FormMessage />
              </FormItem>
            )}
          />

          <FormField
            control={form.control}
            name="password"
            render={({ field }) => (
              <FormItem>
                <FormLabel>Password</FormLabel>
                <div className="relative">
                  <Input
                    placeholder="••••••••"
                    type={passwordVisible ? 'text' : 'password'}
                    autoComplete="new-password"
                    {...field}
                  />

                  <Button
                    type="button"
                    variant="ghost"
                    mode="icon"
                    onClick={() => setPasswordVisible(!passwordVisible)}
                    className="absolute right-0 top-0 h-full px-3 py-2 hover:bg-transparent"
                  >
                    {passwordVisible ? (
                      <EyeOff className="h-4 w-4 text-muted-foreground" />
                    ) : (
                      <Eye className="h-4 w-4 text-muted-foreground" />
                    )}
                  </Button>
                </div>
                <FormDescription className="text-xs">
                  Must be 8+ characters with uppercase, lowercase, number, and special character
                </FormDescription>
                <FormMessage />
              </FormItem>
            )}
          />

          <FormField
            control={form.control}
            name="confirmPassword"
            render={({ field }) => (
              <FormItem>
                <FormLabel>Confirm Password</FormLabel>
                <div className="relative">
                  <Input
                    placeholder="••••••••"
                    type={confirmPasswordVisible ? 'text' : 'password'}
                    autoComplete="new-password"
                    {...field}
                  />

                  <Button
                    type="button"
                    variant="ghost"
                    mode="icon"
                    onClick={() =>
                      setConfirmPasswordVisible(!confirmPasswordVisible)
                    }
                    className="absolute right-0 top-0 h-full px-3 py-2 hover:bg-transparent"
                  >
                    {confirmPasswordVisible ? (
                      <EyeOff className="h-4 w-4 text-muted-foreground" />
                    ) : (
                      <Eye className="h-4 w-4 text-muted-foreground" />
                    )}
                  </Button>
                </div>
                <FormMessage />
              </FormItem>
            )}
          />
        </div>

        <Separator className="my-4" />

        <FormField
          control={form.control}
          name="terms"
          render={({ field }) => (
            <FormItem className="flex flex-row items-start space-x-0.5 space-y-0 rounded-md">
              <FormControl>
                <Checkbox
                  checked={field.value}
                  onCheckedChange={field.onChange}
                />
              </FormControl>
              <div className="space-y-1 leading-none">
                <FormLabel className="text-sm text-muted-foreground">
                  I agree to the{' '}
                  <Link
                    href="#"
                    className="text-sm font-semibold text-foreground hover:text-primary"
                  >
                    Terms and Conditions
                  </Link>
                  {' '}and{' '}
                  <Link
                    href="#"
                    className="text-sm font-semibold text-foreground hover:text-primary"
                  >
                    Privacy Policy
                  </Link>
                </FormLabel>
                <FormMessage />
              </div>
            </FormItem>
          )}
        />

        <Button type="submit" className="w-full" disabled={isProcessing}>
          {isProcessing ? (
            <span className="flex items-center gap-2">
              <LoaderCircleIcon className="h-4 w-4 animate-spin" /> Creating
              account...
            </span>
          ) : (
            'Create Account'
          )}
        </Button>

        <div className="text-center text-sm text-muted-foreground">
          Already have an account?{' '}
          <Link
            href="/auth/signin"
            className="text-sm font-semibold text-foreground hover:text-primary"
          >
            Sign In
          </Link>
        </div>
      </form>
    </Form>
  </AuthLayout>
  );
}

export default SignUpPage;
