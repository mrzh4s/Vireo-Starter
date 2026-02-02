import { z } from 'zod';

export const getSignupSchema = () => {
  return z
    .object({
      // User Details
      firstName: z
        .string()
        .min(1, { message: 'First name is required.' })
        .max(50, { message: 'First name must be less than 50 characters.' })
        .regex(/^[a-zA-Z\s'-]+$/, {
          message: 'First name can only contain letters, spaces, hyphens, and apostrophes.',
        }),
      lastName: z
        .string()
        .min(1, { message: 'Last name is required.' })
        .max(50, { message: 'Last name must be less than 50 characters.' })
        .regex(/^[a-zA-Z\s'-]+$/, {
          message: 'Last name can only contain letters, spaces, hyphens, and apostrophes.',
        }),
      domain: z
        .string()
        .max(100, { message: 'Company/Domain must be less than 100 characters.' })
        .optional()
        .or(z.literal('')),

      // Account Details
      email: z
        .string()
        .email({ message: 'Please enter a valid email address.' })
        .min(1, { message: 'Email is required.' })
        .max(255, { message: 'Email must be less than 255 characters.' })
        .toLowerCase(),
      password: z
        .string()
        .min(8, { message: 'Password must be at least 8 characters.' })
        .max(128, { message: 'Password must be less than 128 characters.' })
        .regex(/[A-Z]/, {
          message: 'Password must contain at least one uppercase letter.',
        })
        .regex(/[a-z]/, {
          message: 'Password must contain at least one lowercase letter.',
        })
        .regex(/[0-9]/, {
          message: 'Password must contain at least one number.',
        })
        .regex(/[^A-Za-z0-9]/, {
          message: 'Password must contain at least one special character.',
        }),
      confirmPassword: z
        .string()
        .min(1, { message: 'Please confirm your password.' }),

      // Terms
      terms: z
        .boolean()
        .refine((val) => val === true, {
          message: 'You must accept the terms and conditions.',
        }),
    })
    .refine((data) => data.password === data.confirmPassword, {
      message: "Passwords don't match",
      path: ['confirmPassword'],
    });
};
