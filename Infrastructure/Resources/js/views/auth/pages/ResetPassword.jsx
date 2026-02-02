import { useState } from 'react';
import { zodResolver } from '@hookform/resolvers/zod';
import { AlertCircle, Check, LoaderCircleIcon, MoveLeft } from 'lucide-react';
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
import { getResetRequestSchema } from '../forms/reset-password-schema';
import { AuthLayout } from '@/components/layouts';

function ResetPasswordPage() {
  const [isProcessing, setIsProcessing] = useState(false);
  const [error, setError] = useState(null);
  const [successMessage, setSuccessMessage] = useState(null);

  const form = useForm({
    resolver: zodResolver(getResetRequestSchema()),
    defaultValues: {
      email: '',
    },
  });

  async function onSubmit(values) {
    setIsProcessing(true);
    setError(null);
    setSuccessMessage(null);

    router.post('/forgot-password', {
      email: values.email,
    }, {
      onSuccess: () => {
        setSuccessMessage(
          `Password reset link sent to ${values.email}! Please check your inbox and spam folder.`
        );
        form.reset();
        setIsProcessing(false);
      },
      onError: (errors) => {
        setError(errors.message || 'An error occurred. Please try again.');
        setIsProcessing(false);
      },
      onFinish: () => {
        setIsProcessing(false);
      },
    });
  }

  return (
    <AuthLayout variant="classic" title="Reset Password - Vireo">
    <div className="max-w-md mx-auto">
      <Form {...form}>
        <form onSubmit={form.handleSubmit(onSubmit)} className="space-y-5">
          <div className="text-center space-y-2">
            <h1 className="text-2xl font-bold tracking-tight">
              Reset Password
            </h1>
            <p className="text-sm text-muted-foreground">
              Enter your email to receive a password reset link
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

          <div className="space-y-5">
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

            <Button type="submit" className="w-full" disabled={isProcessing}>
              {isProcessing ? (
                <span className="flex items-center gap-2">
                  <LoaderCircleIcon className="h-4 w-4 animate-spin" /> Sending
                  Link...
                </span>
              ) : (
                'Send Reset Link'
              )}
            </Button>
          </div>

          <div className="text-center text-sm">
            <Link
              href="/auth/signin"
              className="inline-flex items-center gap-2 text-sm font-semibold text-accent-foreground hover:underline hover:underline-offset-2"
            >
              <MoveLeft className="size-3.5 opacity-70" /> Back to Sign In
            </Link>
          </div>
        </form>
      </Form>
    </div>
      </AuthLayout>
  );
}

export default ResetPasswordPage;
