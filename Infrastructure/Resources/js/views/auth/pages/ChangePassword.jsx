import { useState } from 'react';
import { zodResolver } from '@hookform/resolvers/zod';
import {
  AlertCircle,
  Check,
  Eye,
  EyeOff,
  LoaderCircleIcon,
} from 'lucide-react';
import { useForm } from 'react-hook-form';
import { Link, router } from '@inertiajs/react';
import { Alert, AlertIcon, AlertTitle } from '@/components/ui/alert';
import { Button } from '@/components/ui/button';
import {
  Form,
  FormControl,
  FormField,
  FormItem,
  FormLabel,
  FormMessage,
} from '@/components/ui/form';
import { Input } from '@/components/ui/input';
import { getNewPasswordSchema } from '../forms/reset-password-schema';
import { AuthLayout } from '@/components/layouts';

function ChangePasswordPage({ token }) {
  const [passwordVisible, setPasswordVisible] = useState(false);
  const [confirmPasswordVisible, setConfirmPasswordVisible] = useState(false);
  const [isProcessing, setIsProcessing] = useState(false);
  const [error, setError] = useState(null);
  const [successMessage, setSuccessMessage] = useState(null);

  const form = useForm({
    resolver: zodResolver(getNewPasswordSchema()),
    defaultValues: {
      email: '',
      password: '',
      confirmPassword: '',
    },
  });

  async function onSubmit(values) {
    setIsProcessing(true);
    setError(null);
    setSuccessMessage(null);

    router.post('/reset-password', {
      email: values.email,
      token: token || '',
      password: values.password,
      password_confirmation: values.confirmPassword,
    }, {
      onSuccess: () => {
        setSuccessMessage('Password reset successfully! Redirecting to login...');
        setTimeout(() => {
          router.visit('/auth/signin');
        }, 2000);
      },
      onError: (errors) => {
        setError(errors.message || 'An error occurred. Please try again or request a new reset link.');
        setIsProcessing(false);
      },
      onFinish: () => {
        setIsProcessing(false);
      },
    });
  }

  if (!token) {
    return (
      <AuthLayout variant="classic" title="Change Password - Vireo">
      <div className="max-w-md mx-auto space-y-5">
        <div className="text-center space-y-2">
          <h1 className="text-2xl font-bold tracking-tight">Reset Password</h1>
          <p className="text-sm text-muted-foreground">
            You need a valid reset link to change your password
          </p>
        </div>

        <div className="bg-muted/50 p-4 rounded-lg border border-border">
          <h3 className="font-medium mb-2">How to reset your password:</h3>
          <ol className="list-decimal ms-4 text-sm space-y-1 text-muted-foreground">
            <li>Request a password reset link via email</li>
            <li>Check your email inbox and spam folder</li>
            <li>Click the reset link in the email you receive</li>
            <li>Create a new password on the page that opens</li>
          </ol>
        </div>

        <Button asChild className="w-full">
          <Link href="/auth/reset-password">Request a Reset Link</Link>
        </Button>

        <div className="text-center text-sm">
          <span className="text-muted-foreground">Remember your password?</span>{' '}
          <Link href="/auth/signin" className="text-primary hover:underline">
            Sign In
          </Link>
        </div>
      </div>
      </AuthLayout>
    );
  }

  return (
    <AuthLayout variant="classic" title="Change Password - Vireo">
    <div className="max-w-md mx-auto">
      <Form {...form}>
        <form onSubmit={form.handleSubmit(onSubmit)} className="space-y-6">
          <div className="text-center space-y-2">
            <h1 className="text-2xl font-bold tracking-tight">
              Set New Password
            </h1>
            <p className="text-muted-foreground">
              Create a strong password for your account
            </p>
          </div>

          {error && (
            <Alert variant="destructive">
              <AlertIcon>
                <AlertCircle className="h-4 w-4" />
              </AlertIcon>
              <AlertTitle>{error}</AlertTitle>
            </Alert>
          )}

          {successMessage && (
            <Alert>
              <AlertIcon>
                <Check className="h-4 w-4 text-green-500" />
              </AlertIcon>
              <AlertTitle>{successMessage}</AlertTitle>
            </Alert>
          )}

          <div className="space-y-4">
            <FormField
              control={form.control}
              name="email"
              render={({ field }) => (
                <FormItem>
                  <FormLabel>Email</FormLabel>
                  <FormControl>
                    <Input
                      placeholder="your.email@example.com"
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
                  <FormLabel>New Password</FormLabel>
                  <div className="relative">
                    <Input
                      placeholder="Create a strong password"
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
                        <EyeOff className="h-4 w-4" />
                      ) : (
                        <Eye className="h-4 w-4" />
                      )}
                    </Button>
                  </div>
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
                      placeholder="Verify your password"
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
                        <EyeOff className="h-4 w-4" />
                      ) : (
                        <Eye className="h-4 w-4" />
                      )}
                    </Button>
                  </div>
                  <FormMessage />
                </FormItem>
              )}
            />
          </div>

          <Button type="submit" className="w-full" disabled={isProcessing}>
            {isProcessing ? (
              <span className="flex items-center gap-2">
                <LoaderCircleIcon className="h-4 w-4 animate-spin" /> Updating Password...
              </span>
            ) : (
              'Reset Password'
            )}
          </Button>

          <div className="text-center text-sm">
            <Link href="/auth/signin" className="text-primary hover:underline">
              Back to Sign In
            </Link>
          </div>
        </form>
      </Form>
    </div>
    </AuthLayout>
  );
}

export default ChangePasswordPage;
